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

use App\Services\Module\ModuleManager;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

defined('BASEPATH') || exit('No direct script access allowed');

class Plugin extends Admin_Controller
{
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
        $market = $this->marketplace();
        $data   = [
            'content'         => 'admin.plugin.paket_tersedia',
            'act_tab'         => 1,
            'url_marketplace' => $market['url'],
            'paket_terpasang' => json_encode($this->paketTerpasang()),
            'token_layanan'   => $market['token'],
            'klien_terpasang' => app(ModuleManager::class)->klienLanggananTerpasang(),
        ];

        view('admin.plugin.index', $data);
    }

    public function installed(): void
    {
        $market = $this->marketplace();

        // Tab Paket Terpasang hanya menampilkan add-on bursa paket yang TERPASANG
        // (module.json `marketplace:true`). Modul OSS inti (Analisis/Kehadiran/
        // Lapak) dikecualikan. Daftar digerakkan pemindaian folder (bukan
        // respons Layanan) agar add-on terpasang tetap tampil walau Layanan mati.
        $terpasangMarket = app(ModuleManager::class)->installedMarketplace();

        // Add-on yang hak-pakainya bisa DIVERIFIKASI sumber aktif. Mode lokal:
        // yang terdaftar di bursa paket. Mode Layanan: dibiarkan kosong —
        // klien menentukan dari respons Layanan (paket yang dikembalikan =
        // langganan aktif). Add-on terpasang di luar daftar ini → "belum
        // terverifikasi" (mis. BukuTamu/DTSEN terpasang tapi belum didaftarkan).
        $terverifikasi = [];
        $belumVerif    = [];

        $data = [
            'content'               => 'admin.plugin.paket_terinstall',
            'act_tab'               => 2,
            'url_marketplace'       => $market['url'],
            'paket_terpasang'       => $terpasangMarket !== [] ? json_encode($terpasangMarket) : null,
            'paket_tersedia_sumber' => json_encode(array_values($terverifikasi)),
            'paket_belum_verif'     => json_encode($belumVerif),
            'paket_bawaan'          => json_encode(app(ModuleManager::class)->nonRemovable()),
            'token_layanan'         => $market['token'],
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
            'url_marketplace' => config('bursa.url_penyedia') . '/api/v1/modules',
            'token_layanan'   => token_bursa(),
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
            'token_layanan' => token_bursa(),
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
            $url      = config('bursa.url_penyedia') . '/api/v1/pemesanan';
            $response = Http::withToken(token_bursa())
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
        try {
            $parts = explode('___', (string) $this->request['pasang']);

            if (count($parts) < 3) {
                throw new RuntimeException('Parameter paket tidak valid.');
            }

            [$name, $url, $version] = $parts;

            if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
                throw new RuntimeException('Nama paket mengandung karakter tidak diizinkan.');
            }

            // Ambil ZIP dari sumber terikat (Layanan di produksi, repo lokal di
            // pengembangan), ekstrak, tegakkan min_core, lalu migrasi — seluruhnya
            // di ModuleManager (add-on-agnostik).
            $isInstalasiAwal = app(ModuleManager::class)->installFromSource($name, $url);

            if ($isInstalasiAwal) {
                app(ModuleManager::class)->reportInstall($name, $version);
            }

            set_session('success', "Paket tambahan {$name} berhasil diinstall, silakan aktifkan paket tersebut");

            return redirect('plugin');
        } catch (RuntimeException $e) {
            log_message('error', 'Gagal memasang paket: ' . $e->getMessage());

            return redirect_with('error', $e->getMessage(), 'plugin');
        } catch (Exception $e) {
            log_message('error', 'Gagal memasang paket (unexpected): ' . $e->getMessage());

            return redirect_with('error', 'Terjadi kesalahan tidak terduga saat memasang paket.', 'plugin');
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

            // Validasi: cegah penghapusan paket non-removable (bawaan) — sifat
            // dibaca dari module.json, bukan konstanta MODUL_BAWAAN.
            if (! app(ModuleManager::class)->isRemovable($name)) {
                set_session('error', 'Paket bawaan tidak dapat dihapus');
                redirect('plugin/installed');
            }

            app(ModuleManager::class)->uninstall($name, true);

            set_session('success', 'Paket ' . $name . ' berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            set_session('error', 'Paket ' . $name . ' gagal dihapus (' . $e->getMessage() . ')');
        }
        redirect('plugin/installed');
    }

    /**
     * Simpan token Layanan untuk pertama kali (sebelum modul klien terpasang).
     * Menyimpan token memicu SettingAplikasiObserver → bootstrap → pasang Pelanggan.
     */
    public function simpanToken(): void
    {
        isCan('u');

        $token = trim((string) $this->input->post('token_layanan'));

        if (empty($token)) {
            redirect_with('error', 'Token tidak boleh kosong.', 'plugin');

            return;
        }

        (new \App\Repositories\SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);

        redirect_with('success', 'Token tersimpan. Modul Layanan dipasang otomatis — muat ulang halaman bila belum muncul.', 'plugin');
    }

    /**
     * Coba ulang bootstrap Layanan — berguna bila Layanan tidak dapat dihubungi
     * saat token pertama kali disimpan sehingga modul klien belum terpasang.
     */
    public function bootstrapUlang(): void
    {
        isCan('u');

        $token = token_bursa();

        if (empty($token)) {
            redirect_with('error', 'Token Layanan belum diisi. Masukkan token di Pengaturan Aplikasi terlebih dahulu.', 'plugin');

            return;
        }

        $dipasang = app(ModuleManager::class)->jalankanBootstrap($token);

        if ($dipasang !== []) {
            redirect_with('success', 'Berhasil memasang: ' . implode(', ', $dipasang) . '.', 'plugin');

            return;
        }

        if (! app(ModuleManager::class)->klienLanggananTerpasang()) {
            redirect_with('error', 'Layanan tidak dapat dihubungi atau token tidak valid. Periksa koneksi internet server dan keabsahan token, lalu coba lagi.', 'plugin');

            return;
        }

        redirect_with('success', 'Semua modul bootstrap sudah terpasang.', 'plugin');
    }

    /**
     * @return array{lokal: bool, url: string, token: string}
     */
    private function marketplace(): array
    {
        return [
            'lokal' => false,
            'url'   => config('bursa.url_penyedia') . '/api/v1/modules',
            'token' => token_bursa(),
        ];
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
}
