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

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    protected $signature   = 'opensid:setup';
    protected $description = 'Setup OpenSID environment dan konfigurasi.';

    public function handle(): void
    {
        $this->setConfigItems();
    }

    /**
     * Mengatur item konfigurasi.
     */
    protected function setConfigItems(): void
    {
        $db     = '$db';
        $config = '$config';

        $configContent = <<<EOS
            <?php

            {$config}['user_admin'] = 0;
            EOS;

        $databaseContent = <<<EOS
            <?php

            {$db}['default']['hostname'] = getenv('DB_HOST');
            {$db}['default']['username'] = getenv('DB_USERNAME');
            {$db}['default']['password'] = getenv('DB_PASSWORD') ?: '';
            {$db}['default']['port']     = (int) (getenv('DB_PORT') ?: 3306);
            {$db}['default']['database'] =  getenv('DB_DATABASE');
            {$db}['default']['dbcollat'] = 'utf8mb4_general_ci';
            {$db}['default']['stricton'] = true;
            {$db}['default']['options'] = [
                // PDO::ATTR_EMULATE_PREPARES => true,
            ];
            EOS;

        $configDir = base_path('desa/config');

        if (! is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }

        file_put_contents($configDir . '/config.php', $configContent);
        file_put_contents($configDir . '/database.php', $databaseContent);

        $this->info("Berhasil menulis config.php dan database.php ke {$configDir}");
    }
}
