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

use App\Traits\Migrator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

defined('BASEPATH') || exit('No direct script access allowed');

class Plugin extends Admin_Controller
{
    use Migrator;

    public $modul_ini       = 'pengaturan';
    public $sub_modul_ini   = 'modul';
    public $aliasController = 'modul';
    private int|string $modulesDirectory;

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

    /**
     * @return mixed[]
     */
    private function paketTerpasang(): array
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
        [$name, $url, $version] = explode('___', (string) $this->request['pasang']);
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
        redirect('plugin');
    }

    /**
     * Fungsi untuk memasang paket
     */
    private function pasangPaket(string $name, string $url)
    {
        try {
            $zipFilePath     = $this->modulesDirectory . $name . '.zip';
            $extractedDir    = $this->modulesDirectory . $name;
            $tmpExtractedDir = $this->modulesDirectory;

            if (File::exists($extractedDir . '/modules.json')) {
                return redirect_with('error', "Paket {$name} sudah ada", 'plugin');
            }

            if (file_put_contents($zipFilePath, file_get_contents($url)) === false) {
                return redirect_with('error', "Gagal mengunduh paket dari {$url}", 'plugin');
            }

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath) !== true) {
                return redirect_with('error', "Gagal membuka file ZIP: {$zipFilePath}", 'plugin');
            }

            $subfolder = rtrim($zip->getNameIndex(0), '/');
            $sourceDir = $tmpExtractedDir . $subfolder;
            $zip->extractTo($tmpExtractedDir);
            $zip->close();

            if (File::exists($extractedDir)) {
                File::deleteDirectory($extractedDir);
            }

            if (! File::exists($sourceDir)) {
                return redirect_with('error', "Direktori sumber tidak ditemukan: {$sourceDir}", 'plugin');
            }

            if (! File::move($sourceDir, $extractedDir)) {
                return redirect_with('error', "Gagal memindahkan direktori dari {$sourceDir} ke {$extractedDir}", 'plugin');
            }

            $this->jalankanMigrasiModule($name, 'up');
            set_session('success', "Paket tambahan {$name} berhasil diinstall, silakan aktifkan paket tersebut");
            unlink($zipFilePath);
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
            $this->jalankanMigrasiModule($name, 'down');
            forceRemoveDir($this->modulesDirectory . $name);
            set_session('success', 'Paket ' . $name . ' berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            set_session('error', 'Paket ' . $name . ' gagal dihapus (' . $e->getMessage() . ')');
        }
        redirect('plugin/installed');
    }
}
