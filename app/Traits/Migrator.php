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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
        if (! isset($data['urut'])) {
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

        // Simpan atau perbarui data setting
        $setting->upsert($data, ['config_id', 'key'], ['judul', 'keterangan', 'jenis', 'option', 'attribute', 'kategori']);

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
}
