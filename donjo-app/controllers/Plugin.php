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

use App\Traits\Migrator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

defined('BASEPATH') || exit('No direct script access allowed');

class Plugin extends Admin_Controller
{
    use Migrator;

    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'paket-tambahan';
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
            'content'           => 'admin.plugin.paket_terinstall',
            'act_tab'           => 2,
            'url_marketplace'   => config_item('server_layanan') . '/api/v1/modules',
            'paket_terpasang'   => $terpasang ? json_encode(array_keys($terpasang)) : null,
            'paket_bawaan'      => json_encode(MODUL_BAWAAN),
            'token_layanan'     => setting('layanan_opendesa_token'),
            'default_thumbnail' => URL::signedRoute('storage.desa', [
                'path'        => 'images/404-image-not-found.jpg',
                'default'     => 'images/404-image-not-found.jpg',
                'defaultDisk' => 'assets',
            ]),
        ];

        view('admin.plugin.index', $data);
    }

    public function pendaftaran(): void
    {
        if (config_item('demo_mode')) {
            $msg = 'Tidak dapat melakukan pendaftaran paket pada mode demo.';
            redirect_with('error', $msg);
        }

        $data = [
            'content'         => 'admin.plugin.pendaftaran',
            'act_tab'         => 3,
            'url_marketplace' => config_item('server_layanan') . '/api/v1/modules',
            'token_layanan'   => setting('layanan_opendesa_token'),
            'form_action'     => site_url('plugin/pendaftaran/store'),
        ];

        view('admin.plugin.index', $data);
    }

    public function pemesanan(): void
    {
        if (config_item('demo_mode')) {
            $msg = 'Tidak dapat melakukan pendaftaran paket pada mode demo.';
            redirect_with('error', $msg);
        }

        $data = [
            'content'       => 'admin.plugin.pemesanan',
            'act_tab'       => 4,
            'token_layanan' => setting('layanan_opendesa_token'),
        ];

        view('admin.plugin.index', $data);
    }

    public function pendaftaranStore(): void
    {
        if (config_item('demo_mode')) {
            $msg = 'Tidak dapat melakukan pendaftaran paket pada mode demo.';
            redirect_with('error', $msg);
        }

        try {
            isCan('u');

            // Ambil semua input POST
            $data = $this->input->post(null);

            // Validasi input
            $this->validasi($data);

            $file        = $_FILES['bukti'] ?? null;
            $adaLampiran = ! empty($file['name']);

            // Validasi panjang nama file
            if ($adaLampiran && (strlen($file['name']) + 20) >= 100) {
                redirect_with('error', 'Nama berkas terlalu panjang. Maksimal 80 karakter diperbolehkan.');
            }

            // Validasi file tidak mengandung kode PHP
            if ($adaLampiran && isPHP($file['tmp_name'], $file['name'])) {
                redirect_with('error', 'Jenis file ini tidak diperbolehkan.');
            }

            // Siapkan multipart data
            $multipartData = [
                ['name' => 'module_name', 'contents' => $data['module_name']],
                ['name' => 'tanggal_pembayaran', 'contents' => $data['tanggal_pembayaran']],
                ['name' => 'tanggal_nota', 'contents' => $data['tanggal_nota']],
                ['name' => 'tujuan', 'contents' => $data['tujuan']],
                ['name' => 'keterangan', 'contents' => $data['keterangan']],
            ];

            // Tambahkan bukti jika ada
            if ($adaLampiran) {
                $multipartData[] = [
                    'name'     => 'bukti',
                    'contents' => fopen($file['tmp_name'], 'rb'),
                    'filename' => $file['name'],
                ];
            }

            // Kirim ke API
            $url      = config_item('server_layanan') . '/api/v1/pemesanan';
            $response = Http::withToken(setting('layanan_opendesa_token'))
                ->asMultipart()
                ->post($url, $multipartData);

            // Cek hasil respon
            if ($response->successful()) {
                $json    = $response->json();
                $message = 'Data berhasil dikirim.';

                if (isset($json['messages']['0'], $json['messages']['faktur'])  ) {
                    $message = $json['messages']['0'] . $json['messages']['faktur'];
                }

                log_message('notice', 'Sukses: ' . $message);
                redirect_with('success', $message, 'plugin/pemesanan');
            } else {
                // Ambil pesan dari API jika ada
                $errorMessage = 'Gagal mengirim data ke layanan.';

                $json = $response->json();
                if (isset($json['messages']['error'])) {
                    $errorContent = $json['messages']['error'];

                    // Jika error berupa array
                    if (is_array($errorContent)) {
                        $errorMessage = implode(', ', $errorContent);
                    } else {
                        $errorMessage = $errorContent;
                    }
                }

                log_message('error', 'Gagal: ' . $response->status() . ' - ' . $errorMessage);
                redirect_with('error', $errorMessage, 'plugin/pendaftaran');
            }

        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Terjadi kesalahan saat memproses data.', 'plugin/pendaftaran');
        }
    }

    public function pasang()
    {
        $serverLayanan = config_item('server_layanan');
        $serverHost    = parse_url($serverLayanan, PHP_URL_HOST);
        $domain        = request()->getSchemeAndHttpHost();
        $tanggal_waktu = date('Y-m-d H:i:s');

        [$name, $url, $version] = explode('___', (string) $this->request['pasang']);
        $pasangBaru             = true;

        // Validasi URL
        $urlScheme = parse_url($url, PHP_URL_SCHEME);
        $urlHost   = parse_url($url, PHP_URL_HOST);

        if ($urlScheme !== 'https') {
            return redirect_with('error', 'URL harus menggunakan HTTPS', 'plugin');
        }

        if ($urlHost !== $serverHost) {
            return redirect_with('error', "Domain URL harus sama dengan {$serverHost}", 'plugin');
        }

        // Hanya set pasangBaru = false jika modul sudah ada
        if (File::exists($this->modulesDirectory . $name)) {
            forceRemoveDir($this->modulesDirectory . $name);
            $pasangBaru = false;
        }

        $this->pasangPaket($name, $url);

        if ($pasangBaru) {
            try {
                // hit ke url install module untuk update total yang terinstall dengan versi tertentu
                $urlHitModule = config_item('server_layanan') . '/api/v1/modules/install';
                $token        = setting('layanan_opendesa_token');
                $response     = Http::withToken($token)->post($urlHitModule, ['module_name' => $name, 'version' => $version, 'domain' => $domain, 'tanggal_waktu' => $tanggal_waktu]);
                log_message('error', $response->body());
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
        redirect('plugin');
    }

    public function hapus(): void
    {
        try {
            $name = $this->request['name'];
            if (empty($name)) {
                set_session('error', 'Nama paket tidak boleh kosong');
                redirect('plugin/installed');
            }

            // Validasi: Cegah penghapusan paket bawaan
            if (in_array($name, MODUL_BAWAAN)) {
                set_session('error', 'Paket bawaan tidak dapat dihapus');
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

    private function validasi(array &$data): void
    {
        $data['module_name'] = strip_tags((string) $data['module_name']);
        $data['keterangan']  = strip_tags((string) $data['keterangan']);
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
}
