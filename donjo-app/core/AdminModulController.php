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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

abstract class AdminModulController extends Admin_Controller
{
    public $moduleDirectory;
    public $moduleName;

    public function __construct()
    {
        parent::__construct();
        $this->moduleDirectory = $this->getModuleDirectory();
        $this->moduleName      = $this->loadModuleJson()['name'];
        $this->activate();
        $this->loadHelper();
        $this->loadConfig();
    }

    protected function getModuleDirectory()
    {
        $reflection = new ReflectionClass(static::class);

        return substr(dirname($reflection->getFileName()), 0, -1 * strlen('/Http/Controllers'));
    }

    private function loadHelper(): void
    {
        foreach (glob($this->moduleDirectory . '/Helpers/*.php') as $file) {
            require_once $file;
        }
    }

    private function loadConfig(): void
    {
        foreach (glob($this->moduleDirectory . '/Config/*.php') as $file) {
            $this->mergeConfigFrom($file, substr(basename($file), 0, -4));
        }
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = app()->make('config');

        $config->set($key, array_merge(
            require $path,
            $config->get($key, [])
        ));
    }

    protected function loadModuleJson()
    {
        $path = $this->moduleDirectory . '/module.json';
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }

    protected function activate()
    {
        if ((config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO)) || cache('siappakai') === true) {
            return true;
        }

        if (! in_array($this->moduleName, cache('modul_aktif') ?? [])) {
            set_session('error', 'Paket ' . $this->moduleName . ' belum bisa digunakan karena belum diaktivasi.');

            redirect('plugin');
        }
    }
}
