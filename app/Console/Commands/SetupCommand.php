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

namespace App\Console\Commands;

use CI_Controller;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
    protected $signature   = 'opensid:setup';
    protected $description = 'Setup OpenSID environment dan konfigurasi.';
    protected CI_Controller $ci;

    public function __construct()
    {
        parent::__construct();

        $this->ci = app()->make('ci');
    }

    public function handle()
    {
        $this->setConfigItems();
    }

    /**
     * Mengatur item konfigurasi.
     */
    protected function setConfigItems()
    {
        $this->ci->load->config('installer');
        $this->ci->load->helper('file');

        $db     = '$db';
        $config = '$config';

        $this->ci->config->set_item(
            'config',
            <<<EOS
                <?php

                {$config}['base_url']       = getenv('PLAYWRIGHT_BASE_URL');
                {$config}['demo_mode']      = true;
                {$config}['user_admin']     = 0;
                {$config}['server_layanan'] = getenv('SERVER_LAYANAN');
                {$config}['token_layanan']  = getenv('TOKEN_LAYANAN');
                EOS
        );

        $this->ci->config->set_item(
            'database',
            <<<EOS
                <?php

                {$db}['default']['hostname'] = getenv('DB_HOST');
                {$db}['default']['username'] = getenv('DB_USERNAME');
                {$db}['default']['password'] = 'secret';
                {$db}['default']['port']     = 3306;
                {$db}['default']['database'] =  getenv('DB_DATABASE');
                {$db}['default']['dbcollat'] = 'utf8mb4_general_ci';
                {$db}['default']['stricton'] = true;
                {$db}['default']['options'] = [
                    // PDO::ATTR_EMULATE_PREPARES => true,
                ];
                EOS
        );

        write_file(LOKASI_CONFIG_DESA . 'config.php', config_item('config'), 'wb');
        write_file(LOKASI_CONFIG_DESA . 'database.php', config_item('database'), 'wb');
    }
}
