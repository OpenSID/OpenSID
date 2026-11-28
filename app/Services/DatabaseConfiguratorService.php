<?php

declare(strict_types=1);

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

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Service untuk konfigurasi database.
 *
 * Menggunakan CI3 config system (sama dengan Installer)
 * karena folder_desa() membaca dari config_item('database').
 *
 * Bisa digunakan oleh:
 * - Installer (Install controller)
 * - DesaBaruCommand
 * - Koneksi_database controller
 */
class DatabaseConfiguratorService
{
    /**
     * Generate konten database.php dari parameter.
     */
    protected function generateDatabaseContent(string $host, int $port, string $database, string $username, string $password): string
    {
        $db = '$db';

        return <<<EOS
            <?php
            // -------------------------------------------------------------------------
            //
            // Letakkan username, password dan database sebetulnya di file ini.
            // File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
            // -------------------------------------------------------------------------

            // Data Konfigurasi MySQL yang disesuaikan

            {$db}['default']['hostname'] = '{$host}';
            {$db}['default']['username'] = '{$username}';
            {$db}['default']['password'] = '{$password}';
            {$db}['default']['port']     = {$port};
            {$db}['default']['database'] = '{$database}';
            {$db}['default']['dbcollat'] = 'utf8mb4_general_ci';

            /*
            | Untuk setting koneksi database 'Strict Mode'
            | Sesuaikan dengan ketentuan hosting
            */
            {$db}['default']['stricton'] = true;

            /*
            | Konfigurasi options digunakan untuk menyisipkan opsi tambahan
            | saat mengatur koneksi ke database.
            */
            {$db}['default']['options'] = [
                // PDO::ATTR_EMULATE_PREPARES => true,
            ];
            EOS;
    }

    /**
     * Update konfigurasi database di CI3 config dan Laravel config.
     *
     * Menggunakan CI3's config system (sama dengan Installer):
     * - CI3 config untuk config_item('database') dan folder_desa()
     * - Laravel config untuk DB::reconnect()
     */
    public function updateConfig(string $host, int $port, string $database, string $username, string $password): void
    {
        // 1. Set CI3 config (untuk config_item() dan folder_desa())
        $CI = &get_instance();
        $CI->config->set_item('database', $this->generateDatabaseContent($host, $port, $database, $username, $password));

        // 2. Set Laravel config (untuk DB::reconnect())
        // Update connection 'default' karena DB::reconnect('default') menggunakannya
        config()->set('database.connections.default', [
            'driver'    => 'mysql',
            'host'      => $host,
            'port'      => $port,
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);
    }

    /**
     * Test koneksi database.
     *
     * @throws \Exception Jika koneksi gagal
     */
    public function testConnection(): bool
    {
        DB::purge('default');
        DB::reconnect('default');
        DB::connection()->getPdo();

        return true;
    }

    /**
     * Setup lengkap: update config dan test koneksi.
     *
     * File database.php akan ditulis oleh folder_desa()
     * menggunakan config_item('database') yang sudah di-update.
     */
    public function setup(string $host, int $port, string $database, string $username, string $password): bool
    {
        // 1. Update CI3 config
        $this->updateConfig($host, $port, $database, $username, $password);

        // 2. Test koneksi
        return $this->testConnection();
    }
}
