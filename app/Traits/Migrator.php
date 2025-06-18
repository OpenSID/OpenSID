<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Traits;

use App\Enums\StatusEnum;
use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\SettingAplikasi;
use App\Models\UserGrup;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait Migrator
{
    /**
     * Tambah atau perbarui data ke tabel setting_modul.
     *
     * @return void
     */
    protected function createModul(array $data)
    {
        $modul = new Modul();
        $modul = $modul->withoutGlobalScope('config_id');

        $data['config_id'] ??= identitas('id');
        $data['ikon_kecil'] ??= $data['ikon'];

        // Tetapkan nilai urut jika belum disediakan
        if (Schema::hasColumn('setting_modul', 'urut') && ! isset($data['urut'])) {
            $data['urut'] = $data['parent'] == Modul::PARENT
                ? $modul->max('urut') + 1
                : $modul->where('parent', $data['parent'])->max('urut') + 1;
        }

        if (! isset($data['slug'])) {
            $data['slug'] = Str::slug($data['modul']);
        }

        if (! isset($data['aktif'])) {
            $data['aktif'] = StatusEnum::YA;
        }

        if (! isset($data['hidden'])) {
            $data['hidden'] = 0;
        }

        if (isset($data['parent_slug'])) {
            $parent         = $modul->where('config_id', $data['config_id'])->where('slug', $data['parent_slug'])->first();
            $data['parent'] = $parent ? $parent->id : Modul::PARENT;
            unset($data['parent_slug']);
        }

        // Simpan atau perbarui data modul
        $modul->upsert($data, ['config_id', 'slug'], ['url', 'level', 'hidden', 'ikon_kecil', 'parent']);

        // Create Hak Akses Administator
        $this->createHakAkses([
            'config_id' => $data['config_id'],
            'id_grup'   => UserGrup::withoutConfigId($data['config_id'])->where('slug', UserGrup::ADMINISTRATOR)->value('id'),
            'id_modul'  => Modul::withoutConfigId($data['config_id'])->where('slug', $data['slug'])->first()->id,
            'akses'     => GrupAkses::HAPUS,
        ]);

        cache()->flush();
    }

    /**
     * Tambah atau perbarui beberapa data ke tabel setting_modul.
     *
     * @return void
     */
    protected function createModuls(array $data)
    {
        foreach ($data as $modul) {
            $this->createModul($modul);
        }
    }

    /**
     * Ubah atau hapus modul lama dari tabel setting_modul.
     *
     * @param string $slug  Slug modul yang akan diubah atau dihapus.
     * @param array  $where Kondisi pencarian modul yang akan diubah.
     * @param array  $data  Data untuk update jika modul tidak ditemukan.
     *
     * @return void
     */
    protected function updateOrDeleteModul(string $slug, array $where, array $data)
    {
        $query = is_array(reset($where)) ? Modul::whereIn(key($where), reset($where)) : Modul::where($where);

        if (Modul::where('slug', $slug)->exists()) {
            $query->delete();
        } else {
            $query->update($data);
        }

        cache()->flush();
    }

    /**
     * Hapus data dari tabel modul.
     *
     * @return void
     */
    protected function deleteModul(array $where)
    {
        $modul = new Modul();
        $modul = $modul->withoutGlobalScope('config_id');

        $data['config_id'] ??= identitas('id');
        $modul = $modul->where($where)->first();

        if ($modul) {
            // Hapus modul anak jika ini adalah parent
            if ($modul->parent == Modul::PARENT) {
                $modul->whereParent($modul->id)->delete();
            }

            // Hapus modul itu sendiri
            $modul->delete();
        }

        cache()->flush();
    }

    /**
     * Jalankan migrasi modul.
     */
    private function jalankanMigrasiModule(string $name, string $action = 'up'): void
    {
        Log::info("Migrasi Module {$name}");

        $modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
        $directoryTable   = $modulesDirectory . '/' . $name . '/Database/Migrations';
        $migrations       = File::files($directoryTable);

        if ($action === 'up') {
            usort($migrations, static fn ($a, $b): int => strcmp($a->getFilename(), $b->getFilename()));
        } else {
            usort($migrations, static fn ($a, $b): int => strcmp($b->getFilename(), $a->getFilename()));
        }

        foreach ($migrations as $migrate) {
            $migrateFile = require $migrate->getPathname();

            match ($action) {
                'down'  => $migrateFile->down(),
                default => $migrateFile->up(),
            };

            Log::info("Migrasi {$action} {$migrate->getFilename()} berhasil dijalankan.");
        }

        cache()->flush();
    }

    /**
     * Tambah atau perbarui data ke tabel setting_aplikasi.
     *
     * @return bool
     */
    protected function createSetting(array $data)
    {
        $setting = new SettingAplikasi();
        $setting = $setting->withoutGlobalScope('config_id');

        $data['config_id'] ??= identitas('id');

        $forCreate = ['judul', 'keterangan', 'jenis', 'option', 'attribute', 'kategori'];

        if (Schema::hasColumn('setting_aplikasi', 'urut')) {
            $forCreate[] = 'urut';
        }

        $setting->upsert($data, ['config_id', 'key'], $forCreate);

        $setting->flushQueryCache();

        return true;
    }

    /**
     * Tambah atau perbarui beberapa data ke tabel setting_aplikasi.
     *
     * @return bool
     */
    protected function createSettings(array $data)
    {
        foreach ($data as $setting) {
            $this->createSetting($setting);
        }

        return true;
    }

    /**
     * Ubah dan hapus key lama dari tabel setting_aplikasi.
     *
     * @return bool
     */
    protected function changeSettingKey(string $oldKey, array $data)
    {
        $valueSetting = optional(SettingAplikasi::where('key', $oldKey)->first())->value;
        SettingAplikasi::where('key', $oldKey)->delete();

        $data['value'] = $valueSetting ?? $data['value'];

        return $this->createSetting($data);
    }

    /**
     * Hapus data dari tabel setting_aplikasi
     *
     * @return void
     */
    protected function deleteSetting(array $where)
    {
        $setting = new SettingAplikasi();
        $setting = $setting->withoutGlobalScope('config_id');

        $data['config_id'] ??= identitas('id');

        $setting->where($where)->delete();

        $setting->flushQueryCache();

        return true;
    }

    /**
     * Tambah atau perbarui data ke tabel grup_akses.
     *
     * @return void
     */
    protected function createHakAkses(array $data)
    {
        $akses = new GrupAkses();
        $akses = $akses->withoutGlobalScope('config_id');

        $data['config_id'] ??= identitas('id');

        $akses->upsert($data, ['config_id', 'id_grup', 'id_modul'], ['akses']);
    }

    /**
     * Menjalankan migrasi Laravel secara manual.
     *
     * @param array|string $migrationFiles Daftar file migrasi yang akan dijalankan
     * @param string       $method         Metode yang akan dijalankan: 'up' atau 'down'
     */
    public function runMigration($migrationFiles, $method = 'up'): string
    {
        $directoryTable = APPPATH . 'models/migrations/struktur_tabel';

        if (! is_array($migrationFiles)) {
            $migrationFiles = [$migrationFiles];
        }

        foreach ($migrationFiles as $file) {
            $migrateFile = require $directoryTable . DIRECTORY_SEPARATOR . $file . '.php';
            $migrateFile->{$method}();
        }

        return true;
    }

    /**
     * Menambahkan foreign key ke tabel tertentu jika belum ada.
     *
     * @param string $constraintName      Nama constraint foreign key.
     * @param string $targetTable         Nama tabel yang akan ditambahkan foreign key.
     * @param string $targetForeignKeyCol Nama kolom foreign key di tabel tujuan (target table).
     * @param string $referencedTable     Nama tabel referensi.
     * @param string $referencedColumn    Nama kolom referensi di tabel referensi.
     * @param bool   $setForeignToNull    Jika true, data asing diubah menjadi null sebelum menambahkan foreign key.
     * @param bool   $isForeignRequired   Jika true, kolom foreign key harus NOT NULL.
     * @param string $onDeleteAction      Aksi ON DELETE (default: CASCADE).
     * @param string $onUpdateAction      Aksi ON UPDATE (default: CASCADE).
     *
     * @return bool True jika foreign key berhasil ditambahkan atau sudah ada.
     */
    public function tambahForeignKey(
        string $constraintName,
        string $targetTable,
        string $targetForeignKeyCol,
        string $referencedTable,
        string $referencedColumn,
        bool $setForeignToNull = false,
        bool $isForeignRequired = false,
        string $onDeleteAction = 'CASCADE',
        string $onUpdateAction = 'CASCADE'
    ) {
        $databaseName = DB::getDatabaseName();
        $success      = true;

        $hasForeignKey = DB::table('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $databaseName)
            ->where('TABLE_NAME', $targetTable)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->where('REFERENCED_TABLE_NAME', $referencedTable)
            ->exists();

        if ($hasForeignKey) {
            return $success;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::statement("ALTER TABLE `{$referencedTable}` MODIFY COLUMN `{$referencedColumn}` INT(11) NOT NULL AUTO_INCREMENT");

        if (! $isForeignRequired) {
            DB::statement("ALTER TABLE `{$targetTable}` MODIFY COLUMN `{$targetForeignKeyCol}` INT(11) NULL");
        }

        $cekEngine = DB::table('information_schema.tables')
            ->whereIn('TABLE_NAME', [$targetTable, $referencedTable])
            ->where('TABLE_SCHEMA', $databaseName)
            ->where('ENGINE', '!=', 'InnoDB')
            ->get();

        if ($cekEngine->isNotEmpty()) {
            foreach ($cekEngine as $table) {
                DB::statement("ALTER TABLE `{$table->TABLE_NAME}` ENGINE = InnoDB");
            }
        }

        $invalidForeignData = DB::table($targetTable)
            ->whereNotNull($targetForeignKeyCol)
            ->whereNotIn($targetForeignKeyCol, static function ($query) use ($referencedTable, $referencedColumn) {
                $query->select($referencedColumn)->from($referencedTable);
            })
            ->exists();

        if ($invalidForeignData) {
            log_message('notice', "Ada data pada kolom {$targetForeignKeyCol} tabel {$targetTable} yang tidak ditemukan di tabel {$referencedTable} kolom {$referencedColumn}");

            if ($setForeignToNull) {
                DB::table($targetTable)
                    ->whereNotIn($targetForeignKeyCol, static function ($query) use ($referencedTable, $referencedColumn) {
                        $query->select($referencedColumn)->from($referencedTable);
                    })
                    ->orWhere($targetForeignKeyCol, 0)
                    ->update([$targetForeignKeyCol => null]);
            }
        }

        if (! $invalidForeignData || $setForeignToNull) {
            try {
                $onDeleteAction = strtoupper($onDeleteAction);
                $onUpdateAction = strtoupper($onUpdateAction);

                $sql = <<<SQL
                    ALTER TABLE `{$targetTable}` ADD CONSTRAINT `{$constraintName}`
                        FOREIGN KEY (`{$targetForeignKeyCol}`) REFERENCES `{$referencedTable}` (`{$referencedColumn}`)
                        ON DELETE {$onDeleteAction} ON UPDATE {$onUpdateAction}
                    SQL;

                DB::statement($sql);
            } catch (Exception $e) {
                Log::error("Gagal menambahkan foreign key {$constraintName}: " . $e->getMessage());
                $success = false;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        return $success;
    }

    /**
     * Hapus foreign key dari tabel tertentu jika ada.
     *
     * @param string $namaConstraint Nama constraint foreign key.
     * @param string $tabel          Nama tabel yang akan dihapus foreign key.
     * @param string $relasiTable    Nama tabel referensi.
     */
    public function hapusForeignKey($namaConstraint, $tabel, $relasiTable)
    {
        $exists = DB::table('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $tabel)
            ->where('CONSTRAINT_NAME', $namaConstraint)
            ->where('REFERENCED_TABLE_NAME', $relasiTable)
            ->exists();

        if ($exists) {
            Schema::table($tabel, static function (Blueprint $table) use ($namaConstraint) {
                $table->dropForeign($namaConstraint);
            });
        }
    }

    /**
     * Reset foreign key menjadi cascade pada tabel tertentu.
     *
     * @param string $table            Nama tabel yang akan direset foreign key-nya.
     * @param string $column           Nama kolom foreign key yang akan direset.
     * @param string $referencesTable  Nama tabel referensi yang akan digunakan.
     * @param string $referencesColumn Nama kolom referensi yang akan digunakan (default: 'id').
     *
     * @return void
     */
    public function resetForeignKey(string $table, string $column, string $foreignKey, string $referencesTable, string $referencesColumn = 'id')
    {
        if ($this->foreignKeyExists($table, $foreignKey)) {
            Schema::table($table, static function (Blueprint $table) use ($column, $foreignKey, $referencesTable, $referencesColumn) {
                $table->dropForeign($foreignKey);

                $table->foreign($column, $foreignKey)
                    ->references($referencesColumn)->on($referencesTable)
                    ->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Cek apakah foreign key sudah ada di tabel tertentu.
     *
     * @param string $table      Nama tabel yang akan diperiksa.
     * @param string $foreignKey Nama foreign key yang akan diperiksa.
     *
     * @return bool True jika foreign key ada, false jika tidak ada.
     */
    public function foreignKeyExists(string $table, string $foreignKey): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $foreignKey)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }
}
