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

use Illuminate\Support\Facades\Http;

defined('BASEPATH') || exit('No direct script access allowed');

class Plugin extends Admin_Controller
{
    public $modul_ini       = 'pengaturan';
    public $sub_modul_ini   = 'modul';
    public $aliasController = 'modul';
    private $modulesDirectory;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
    }

    public function index(): void
    {
        $data = [
            'content'         => 'admin.plugin.paket_tersedia',
            'act_tab'         => 1,
            'url_marketplace' => config_item('server_layanan') . '/api/v1/modules',
            'paket_terpasang' => json_encode($this->paketTerpasang()),
            'token_layanan'   => setting('layanan_opendesa_token'),
        ];

        view('admin.plugin.index', $data);
    }

    public function installed(): void
    {
        $terpasang = $this->paketTerpasang();
        $data      = [
            'content'         => 'admin.plugin.paket_terinstall',
            'act_tab'         => 2,
            'url_marketplace' => config_item('server_layanan') . '/api/v1/modules',
            'paket_terpasang' => $terpasang ? json_encode(array_keys($terpasang)) : null,
            'token_layanan'   => setting('layanan_opendesa_token'),
        ];

        view('admin.plugin.index', $data);
    }

    private function paketTerpasang()
    {
        $terpasang         = [];
        $moduleDirectories = glob($this->modulesDirectory . '*', GLOB_ONLYDIR);

        foreach ($moduleDirectories as $moduleDirectory) {
            if (file_exists($moduleDirectory . '/module.json')) {
                $metaJson                              = file_get_contents($moduleDirectory . '/module.json');
                $terpasang[basename($moduleDirectory)] = json_decode($metaJson, 1);
            }
        }

        return $terpasang;
    }

    public function pasang(): void
    {
        [$name, $url, $version] = explode('___', $this->request['pasang']);
        $pasangBaru             = true;
        if ($version !== '' && $version !== '0') {
            forceRemoveDir($this->modulesDirectory . $name);
            $pasangBaru = false;
        }
        $this->pasangPaket($name, $url);

        if ($pasangBaru) {
            try {
                // hit ke url install module untuk update total yang terinstall
                $urlHitModule = config_item('server_layanan') . '/api/v1/modules/install';
                $token        = setting('layanan_opendesa_token');
                $response     = Http::withToken($token)->post($urlHitModule, ['module_name' => $name]);
                log_message('error', $response->body());
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
        // reset cache views_blade karena di MY_Controller diset cache rememberForever
        cache()->forget('views_blade');
        redirect('plugin');
    }

    private function pasangPaket(string $name, string $url): void
    {
        try {
            // Destination path for the downloaded ZIP file
            $zipFilePath     = $this->modulesDirectory . $name . '.zip';
            $extractedDir    = $this->modulesDirectory . $name;
            $tmpExtractedDir = $this->modulesDirectory;
            if (file_exists($extractedDir . '/modules.json')) {
                set_session('error', 'Paket ' . $name . ' sudah ada');
                redirect('plugin');
            }

            // Download the ZIP file
            file_put_contents($zipFilePath, file_get_contents($url));
            // Extract the ZIP file
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath) == true) {
                $subfolder = $zip->getNameIndex(0);
                $zip->extractTo($tmpExtractedDir);
                $zip->close();
                rename($tmpExtractedDir . substr($subfolder, 0, -1), $extractedDir);
                // jalankan migrasi dari paket
                $this->jalankanMigrasi($name, 'up');
                set_session('success', 'Paket tambahan ' . $name . ' berhasil diinstall, silakan aktifkan paket tersebut');
                // Optional: Remove the downloaded ZIP file
                unlink($zipFilePath);
                // reset cache views_blade karena di MY_Controller diset cache rememberForever
                cache()->forget('views_blade');
            } else {
                set_session('error', 'Gagal download paket ' . $url . ' atau gagal ekstract ke folder ' . $extractedDir);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            set_session('error', $e->getMessage());
        }
    }

    public function hapus(): void
    {
        try {
            $name = $this->request['name'];
            if (empty($name)) {
                set_session('error', 'Nama paket tidak boleh kosong');
                redirect('plugin/installed');
            }
            $this->jalankanMigrasi($name, 'down');
            forceRemoveDir($this->modulesDirectory . $name);
            set_session('success', 'Paket ' . $name . ' berhasil dihapus');
            // reset cache views_blade karena di MY_Controller diset cache rememberForever
            cache()->forget('views_blade');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            set_session('error', 'Paket ' . $name . ' gagal dihapus (' . $e->getMessage() . ')');
        }
        redirect('plugin/installed');
    }

    private function jalankanMigrasi($name, string $action = 'up'): void
    {
        $this->load->helper('directory');
        $directoryTable = $this->modulesDirectory . $name . '/Database/Migrations';
        $migrations     = directory_map($directoryTable, 1);
        if ($action == 'up') {
            usort($migrations, static fn ($a, $b): int => strcmp($a, $b));
        }

        foreach ($migrations as $migrate) {
            $migrateFile = require $directoryTable . DIRECTORY_SEPARATOR . $migrate;

            switch($action) {
                case 'down':
                    $migrateFile->down();
                    break;

                default:
                    $migrateFile->up();
            }
        }
    }

    public function dev($name, $action): void
    {
        if (ENVIRONMENT !== 'development') {
            show_error('Hanya bisa dijalankan di development');
        }

        if (! is_dir($this->modulesDirectory . $name)) {
            show_error('Modul ' . $name . ' tidak ditemukan');
        }

        $this->jalankanMigrasi($name, $action ?? 'up');

        redirect_with('success', 'Migrasi Modul ' . $name . ' berhasil dijalankan');
    }
}
