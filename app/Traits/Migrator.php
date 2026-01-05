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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Traits;

use App\Actions\Modul\UpsertModul;
use App\Models\Modul;
use App\Models\SettingAplikasi;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait Migrator
{
    /**
     * Menjalankan migrasi Laravel secara manual.
     *
     * @param array|string $migrationFiles Daftar file migrasi yang akan dijalankan
     * @param string       $method         Metode yang akan dijalankan: 'up' atau 'down'
     */
    public function runMigration($migrationFiles, $method = 'up'): string
    {
        $directoryTable = base_path('app/database/migrations');

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

    /**
     * Tambah indeks ke tabel.
     *
     * @param string $tabel Nama tabel
     * @param string $kolom Nama kolom
     * @param string $index Tipe indeks (UNIQUE, INDEX, dll)
     * @param bool   $multi Apakah indeks multi kolom
     *
     * @return bool
     */
    public function tambahIndeks($tabel, $kolom, $index = 'UNIQUE', $multi = false)
    {
        if ($index == 'UNIQUE') {
            // Handle multiple columns properly
            $groupByColumns = is_array($kolom) ? $kolom : explode(',', str_replace(' ', '', $kolom));

            $duplikat = DB::table($tabel)
                ->selectRaw($kolom . ', count(*) as jumlah')
                ->groupBy($groupByColumns)
                ->havingRaw('count(*) > 1')
                ->exists();

            if ($duplikat) {
                session_error('--> Silakan Cek <a href="' . site_url('info_sistem') . '">Info Sistem > Log</a>.');
                log_message('error', "Data kolom {$kolom} pada tabel {$tabel} ada yang duplikat dan perlu diperbaiki sebelum migrasi dilanjutkan.");

                return false;
            }
        }

        $unique_name = preg_replace('/[^a-zA-Z0-9_-]+/i', '', $kolom);
        if (! $this->cek_indeks($tabel, $unique_name)) {
            if ($multi == true && $index == 'UNIQUE') {
                return DB::statement("ALTER TABLE `{$tabel}` ADD UNIQUE INDEX `{$unique_name}` ({$kolom})");
            }

            return DB::statement("ALTER TABLE {$tabel} ADD {$index} {$kolom} (`{$kolom}`)");
        }

        return true;
    }

    /**
     * Cek apakah indeks sudah ada di tabel.
     *
     * @param string $tabel Nama tabel
     * @param string $kolom Nama kolom indeks
     *
     * @return bool
     */
    public function cek_indeks($tabel, $kolom)
    {
        $db = DB::getDatabaseName();

        return DB::table('INFORMATION_SCHEMA.STATISTICS')
            ->where('table_schema', $db)
            ->where('table_name', $tabel)
            ->where('index_name', $kolom)
            ->exists();
    }

    /**
     * Ubah modul setting menu.
     *
     * @param mixed $where Kondisi where
     * @param array $modul Data modul untuk update
     *
     * @return bool
     */
    public function ubah_modul($where, array $modul)
    {
        $query = DB::table('setting_modul');

        if (is_array($where)) {
            $query->where($where);
        } else {
            $query->where('id', $where);
        }

        $query->update($modul);

        cache()->flush();

        return true;
    }

    /**
     * Tambah setting aplikasi (legacy method untuk kompatibilitas).
     *
     * @param array $setting   Data setting
     * @param int   $config_id Config ID
     *
     * @return bool
     */
    public function tambah_setting($setting, $config_id = null)
    {
        $setting['config_id'] = $config_id ?? identitas('id');

        return $this->createSetting($setting);
    }

    /**
     * Tambah surat TinyMCE.
     *
     * @param array $data      Data surat
     * @param int   $config_id Config ID
     *
     * @return bool
     */
    public function tambah_surat_tinymce($data, $config_id = null)
    {
        $config_id ??= identitas('id');
        $data['url_surat']    = 'surat-' . url_title($data['nama'], '-', true);
        $data['jenis']        = 1; // FormatSurat::TINYMCE_SISTEM
        $data['syarat_surat'] = json_encode($data['syarat_surat'], JSON_THROW_ON_ERROR);
        $data['created_by']   = auth()->id ?? 1;
        $data['updated_by']   = auth()->id ?? 1;
        $data['config_id']    = $config_id;

        if (is_array($data['form_isian'])) {
            $data['form_isian'] = json_encode($data['form_isian'], JSON_THROW_ON_ERROR);
        }

        if (is_array($data['kode_isian'])) {
            $data['kode_isian'] = json_encode($data['kode_isian'], JSON_THROW_ON_ERROR);
        }

        // Tambah data baru dan update (hanya kolom template) jika ada sudah ada
        $cek_surat = DB::table('tweb_surat_format')->where('config_id', $config_id)->where('url_surat', $data['url_surat']);

        if ($cek_surat->exists()) {
            $cek_surat->update(['template' => $data['template']]);
        } else {
            DB::table('tweb_surat_format')->insert($data);
        }

        return true;
    }

    /**
     * Cek primary key pada tabel.
     *
     * @param string $tabel Nama tabel
     * @param array  $kolom Kolom primary key
     *
     * @return bool
     */
    public function cek_primary_key($tabel, $kolom = [])
    {
        $schemaManager = DB::connection()->getDoctrineSchemaManager();
        $indexes       = $schemaManager->listTableIndexes($tabel);

        foreach ($indexes as $index) {
            if ($index->isPrimary() && $index->getColumns() == $kolom) {
                return true;
            }
        }

        return false;
    }

    /**
     * Hapus FOREIGN KEY (legacy method untuk kompatibilitas).
     *
     * @param string $tabel           Nama tabel referensi
     * @param string $nama_constraint Nama constraint
     * @param string $drop            Nama tabel yang akan di-drop foreign key-nya
     *
     * @return bool
     */
    public function hapus_foreign_key($tabel, $nama_constraint, $drop)
    {
        $query = DB::table('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('REFERENCED_TABLE_NAME', $tabel)
            ->where('CONSTRAINT_NAME', $nama_constraint)
            ->first();

        if ($query) {
            try {
                DB::statement("ALTER TABLE {$drop} DROP FOREIGN KEY {$nama_constraint}");
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return true;
        }

        return true;
    }

    /**
     * Check and fix table structure
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function checkAndFixTable($tableName)
    {
        $table = DB::table($tableName)->first();
        if ($table) {
            $kolom_id = DB::select("SHOW COLUMNS FROM {$tableName} WHERE Field = 'id' AND Extra = 'auto_increment'");
            $pk       = DB::select("SHOW INDEX FROM {$tableName} WHERE Key_name = 'PRIMARY'");

            if (! $kolom_id || ! $pk) {
                DB::statement("ALTER TABLE {$tableName} ADD PRIMARY KEY (id)");
                DB::statement("ALTER TABLE {$tableName} MODIFY id INT AUTO_INCREMENT");
            }
        }

        return true;
    }

    /**
     * Tambah atau perbarui data ke tabel setting_modul.
     *
     * @return void
     */
    protected function createModul(array $data)
    {
        (new UpsertModul())->handle($data);

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
     * Tambah atau perbarui data ke tabel setting_aplikasi.
     *
     * @return bool
     */
    protected function createSetting(array $data)
    {
        $setting = new SettingAplikasi();
        $setting = $setting->withoutGlobalScope('config_id');

        $data['config_id'] ??= identitas('id');

        $forCreate = ['judul', 'keterangan', 'jenis', 'option', 'attribute', 'kategori', 'urut'];

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
     * Jalankan migrasi.
     */
    private function runMigrations(string $directoryTable, string $action = 'up'): void
    {
        if (! is_dir($directoryTable)) {
            logger()->info("Folder migrations tidak ditemukan: {$directoryTable}");

            return;
        }

        $migrations = File::files($directoryTable);

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

            logger()->info("Migrasi {$action} {$migrate->getFilename()} berhasil dijalankan.");
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
        $dirOld           = "{$modulesDirectory}/{$name}/Database/Migrations";
        $dirNew           = "{$modulesDirectory}/{$name}/database/migrations";

        if (is_dir($dirOld)) {
            $directoryTable = $dirOld;
        } elseif (is_dir($dirNew)) {
            $directoryTable = $dirNew;
        } else {
            Log::info("Folder migrations tidak ditemukan: {$dirOld} dan {$dirNew}");

            return;
        }

        $migrations = File::files($directoryTable);

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
}
