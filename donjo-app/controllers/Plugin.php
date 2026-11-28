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

    public function pendaftaran()
    {
        if (config_item('demo_mode')) {
            $msg = 'Tidak dapat melakukan pendaftaran paket pada mode demo.';

            return redirect_with('error', $msg);
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

    public function pemesanan()
    {
        if (config_item('demo_mode')) {
            $msg = 'Tidak dapat melakukan pendaftaran paket pada mode demo.';

            return redirect_with('error', $msg);
        }

        $data = [
            'content'       => 'admin.plugin.pemesanan',
            'act_tab'       => 4,
            'token_layanan' => setting('layanan_opendesa_token'),
        ];

        view('admin.plugin.index', $data);
    }

    public function pendaftaranStore()
    {
        if (config_item('demo_mode')) {
            $msg = 'Tidak dapat melakukan pendaftaran paket pada mode demo.';

            return redirect_with('error', $msg);
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
                return redirect_with('error', 'Nama berkas terlalu panjang. Maksimal 80 karakter diperbolehkan.');
            }

            // Validasi file tidak mengandung kode PHP
            if ($adaLampiran && isPHP($file['tmp_name'], $file['name'])) {
                return redirect_with('error', 'Jenis file ini tidak diperbolehkan.');
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

                return redirect_with('success', $message, 'plugin/pemesanan');
            }
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

                return redirect_with('error', $errorMessage, 'plugin/pendaftaran');

        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return redirect_with('error', 'Terjadi kesalahan saat memproses data.', 'plugin/pendaftaran');
        }
    }

    public function pasang()
    {
        try {
            $serverLayanan = (string) config_item('server_layanan');
            $serverHost    = parse_url($serverLayanan, PHP_URL_HOST);

            if (empty($serverHost)) {
                throw new RuntimeException('Konfigurasi server layanan tidak valid.');
            }

            $parts = explode('___', (string) $this->request['pasang']);

            if (count($parts) < 3) {
                throw new RuntimeException('Parameter paket tidak valid.');
            }

            [$name, $url, $version] = $parts;

            if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
                throw new RuntimeException('Nama paket mengandung karakter tidak diizinkan.');
            }

            $this->validasiUrlPaket($url, $serverHost);

            $isInstalasiAwal = $this->instalasiDenganBackup($name, $url);

            if ($isInstalasiAwal) {
                $this->laporkanInstalasiModul($name, $version);
            }

            return redirect('plugin');
        } catch (RuntimeException $e) {
            log_message('error', 'Gagal memasang paket: ' . $e->getMessage());

            return redirect_with('error', $e->getMessage(), 'plugin');
        } catch (Exception $e) {
            log_message('error', 'Gagal memasang paket (unexpected): ' . $e->getMessage());

            return redirect_with('error', 'Terjadi kesalahan tidak terduga saat memasang paket.', 'plugin');
        }
    }

    public function hapus()
    {
        try {
            $name = $this->request['name'];
            if (empty($name)) {
                set_session('error', 'Nama paket tidak boleh kosong');

                return redirect('plugin/installed');
            }

            // Validasi: Cegah penghapusan paket bawaan
            if (in_array($name, MODUL_BAWAAN)) {
                set_session('error', 'Paket bawaan tidak dapat dihapus');

                return redirect('plugin/installed');
            }

            $this->jalankanMigrasiModule($name, 'down');
            forceRemoveDir($this->modulesDirectory . $name);
            set_session('success', 'Paket ' . $name . ' berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            set_session('error', 'Paket ' . $name . ' gagal dihapus (' . $e->getMessage() . ')');
        }

        return redirect('plugin/installed');
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
     * Backup modul lama (jika ada), pasang modul baru, restore jika gagal.
     * Mengembalikan true jika ini instalasi pertama kali, false jika update.
     *
     * @throws RuntimeException|Throwable
     */
    private function instalasiDenganBackup(string $name, string $url): bool
    {
        $modulDir  = $this->modulesDirectory . $name;
        $backupDir = $modulDir . '_backup_' . time();
        $adaBackup = File::exists($modulDir);

        if ($adaBackup && ! File::move($modulDir, $backupDir)) {
            throw new RuntimeException("Gagal membuat backup modul {$name} sebelum update.");
        }

        try {
            $this->pasangPaket($name, $url);
        } catch (Throwable $e) {
            if ($adaBackup && File::exists($backupDir)) {
                File::move($backupDir, $modulDir);
                log_message('error', "instalasiDenganBackup: modul {$name} gagal, modul lama berhasil di-restore.");
            }

            throw $e;
        }

        if ($adaBackup && File::exists($backupDir)) {
            File::deleteDirectory($backupDir);
        }

        return ! $adaBackup;
    }

    /**
     * Validasi URL paket: wajib HTTPS dan host harus sama dengan server layanan.
     *
     * @throws RuntimeException
     */
    private function validasiUrlPaket(string $url, string $serverHost): void
    {
        if (parse_url($url, PHP_URL_SCHEME) !== 'https') {
            throw new RuntimeException('URL harus menggunakan HTTPS');
        }

        if (parse_url($url, PHP_URL_HOST) !== $serverHost) {
            throw new RuntimeException("Domain URL harus sama dengan {$serverHost}");
        }
    }

    /**
     * Unduh, ekstrak, dan pasang paket dari URL yang diberikan.
     *
     * @throws RuntimeException
     */
    private function pasangPaket(string $name, string $url): void
    {
        $zipFilePath  = $this->modulesDirectory . $name . '.zip';
        $extractedDir = $this->modulesDirectory . $name;

        if (File::exists($extractedDir . '/modules.json')) {
            throw new RuntimeException("Paket {$name} sudah terpasang");
        }

        $this->unduhZip($name, $url, $zipFilePath);

        try {
            $this->ekstrakZip($name, $zipFilePath, $extractedDir);
        } finally {
            if (file_exists($zipFilePath)) {
                @unlink($zipFilePath);
            }
        }

        $this->jalankanMigrasiModule($name, 'up');
        set_session('success', "Paket tambahan {$name} berhasil diinstall, silakan aktifkan paket tersebut");
    }

    /**
     * Unduh file ZIP dari server layanan menggunakan Guzzle/Http (stream langsung ke file).
     *
     * @throws RuntimeException
     */
    private function unduhZip(string $name, string $url, string $zipFilePath): void
    {
        $token    = (string) setting('layanan_opendesa_token');
        $response = Http::withToken($token)
            ->withOptions(['sink' => $zipFilePath, 'timeout' => 120])
            ->get($url);

        $httpStatus = $response->status();

        if (! $response->successful()) {
            @unlink($zipFilePath);
            log_message('error', "unduhZip: gagal mengunduh paket {$name} dari {$url} | HTTP {$httpStatus}");

            throw new RuntimeException("Gagal mengunduh paket {$name}. Status server: HTTP {$httpStatus}");
        }
    }

    /**
     * Ekstrak ZIP dan pindahkan ke direktori modul yang benar.
     *
     * @throws RuntimeException
     */
    private function ekstrakZip(string $name, string $zipFilePath, string $extractedDir): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipFilePath) !== true) {
            log_message('error', "ekstrakZip: gagal membuka ZIP paket {$name}: {$zipFilePath}");

            throw new RuntimeException("Gagal membuka file ZIP paket {$name}. File unduhan mungkin tidak valid.");
        }

        $subfolder = rtrim($zip->getNameIndex(0), '/');
        $sourceDir = $this->modulesDirectory . $subfolder;
        $zip->extractTo($this->modulesDirectory);
        $zip->close();

        if (File::exists($extractedDir)) {
            File::deleteDirectory($extractedDir);
        }

        if (! File::exists($sourceDir)) {
            log_message('error', "ekstrakZip: direktori sumber tidak ditemukan setelah ekstrak paket {$name}: {$sourceDir}");

            throw new RuntimeException("Gagal mengekstrak paket {$name}: direktori sumber tidak ditemukan.");
        }

        if (! File::move($sourceDir, $extractedDir)) {
            log_message('error', "ekstrakZip: gagal memindahkan direktori paket {$name} dari {$sourceDir} ke {$extractedDir}");

            throw new RuntimeException("Gagal memindahkan direktori paket {$name}.");
        }
    }

    /**
     * Hit API server layanan untuk mencatat instalasi modul baru.
     * Kegagalan tidak menghentikan alur utama, hanya dicatat di log.
     */
    private function laporkanInstalasiModul(string $name, string $version): void
    {
        try {
            $token    = (string) setting('layanan_opendesa_token');
            $response = Http::withToken($token)
                ->post(config_item('server_layanan') . '/api/v1/modules/install', [
                    'module_name'   => $name,
                    'version'       => $version,
                    'domain'        => request()->getSchemeAndHttpHost(),
                    'tanggal_waktu' => date('Y-m-d H:i:s'),
                ]);

            log_message('notice', "laporkanInstalasiModul {$name}: " . $response->body());
        } catch (Exception $e) {
            log_message('error', "laporkanInstalasiModul {$name}: " . $e->getMessage());
        }
    }
}
