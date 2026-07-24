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

namespace Modules\Pelanggan\Providers;

use App\Services\Database\SetelanDipertahankan;
use App\Services\Kapabilitas\GerbangFitur;
use App\Services\Kapabilitas\PerbaruiLangganan;
use App\Services\Kapabilitas\SumberStatusFitur;
use App\Services\Mandiri\TokenPerangkatMandiri;
use App\Services\Pengumuman\SumberPengumuman;
use App\Services\Penjaga\PenjagaPermintaan;
use App\Services\Telemetri\PelaporVersi;
use App\Services\Theme\BursaTema;
use App\Services\Theme\SumberTemaBursa;
use Illuminate\Support\ServiceProvider;
use Modules\Pelanggan\Services\BursaTemaLayanan;
use Modules\Pelanggan\Services\CekService;
use Modules\Pelanggan\Services\PelangganService;

class PelangganServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'Pelanggan';

    /**
     * @var string
     */
    protected $moduleNameLower = 'pelanggan';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerSeams();
    }

    /**
     * Daftarkan lapisan langganan (Pelanggan) ke seam netral core.
     *
     * Core memanggil seam; seam memanggil kembali kelas Pelanggan di modul ini —
     * sehingga core OSS tak memuat pengetahuan langganan/Layanan apa pun. Tanpa
     * modul terpasang, seam memakai default null-object-nya (lapisan berbayar mati).
     */
    protected function registerSeams(): void
    {
        // Penjaga akses request/migrasi ← CekService::validasi()/validasiVersi().
        $this->app->make(PenjagaPermintaan::class)->daftarkan(
            static fn (bool $migration, bool $install): bool => $migration
                ? (new CekService())->validasiVersi($install)
                : (new CekService())->validasi()
        );

        // Gerbang entitlement "premium" ← CekService::validasiAkses().
        $this->app->make(GerbangFitur::class)->daftarkan(
            'premium',
            static fn (): bool => (new CekService())->validasiAkses()
        );

        // Banner status langganan & masa percobaan ← PelangganService. Tiap notice
        // diberi penanda `jenis` agar core dapat memilah slotnya (header/beranda).
        $notice = $this->app->make(SumberPengumuman::class);
        $notice->daftarkan(static function () {
            $status = PelangganService::statusLangganan();

            return $status === null ? null : $status + ['jenis' => 'langganan'];
        });
        $notice->daftarkan(static function () {
            $status = PelangganService::statusPercobaan();

            return $status === null ? null : $status + ['jenis' => 'percobaan'];
        });

        // Penyegar cache status langganan ← PelangganService::perbaruiLangganan().
        $this->app->make(PerbaruiLangganan::class)->register(
            static fn () => PelangganService::perbaruiLangganan()
        );

        // Predikat "perlu perbarui langganan" ← Admin_Controller (cache status +
        // token berlangganan). Core tak lagi membaca cache/token ini langsung.
        $this->app->make(PerbaruiLangganan::class)->registerPredikat(static function (): bool {
            $info = app('ci')->cache->file->get_metadata('status_langganan');

            return empty($info)
                || (strtotime('+30 day', $info['mtime']) < time())
                || ($info == false && setting('layanan_opendesa_token') != null);
        });

        // Sumber data pemesanan tema premium ← PelangganService::apiPelangganPemesanan().
        $this->app->make(SumberTemaBursa::class)->setelPenyedia(
            static fn () => PelangganService::apiPelangganPemesanan()
        );

        // Bursa tema (katalog/verifikasi-pesanan/unduh) ← klien langganan tema.
        // Dipindah dari core Theme controller agar core OSS tak memuat token/host
        // langganan; core hanya memanggil seam BursaTema.
        $bursaTema   = $this->app->make(BursaTema::class);
        $layananTema = new BursaTemaLayanan();
        $bursaTema->setDaftar(static fn (?string $kategori): array => $layananTema->daftar($kategori));
        $bursaTema->setValidasiPesanan(static fn (string $attribute, string $nama): ?string => $layananTema->validasiPesanan($attribute, $nama));
        $bursaTema->setUnduh(static fn (string $url): ?string => $layananTema->unduh($url));

        // Sumber status keaktifan fitur (mis. 'anjungan') dari data langganan ←
        // PelangganService::apiPelangganPemesanan(). Dipakai antar-modul (mis.
        // AnjunganEntitlement) agar modul lain tak memanggil klien Layanan langsung.
        $this->app->make(SumberStatusFitur::class)->setelPenyedia(static function (string $feature): bool {
            $status = PelangganService::apiPelangganPemesanan();

            return is_object($status)
                && isset($status->body->tanggal_berlangganan->{$feature})
                && $status->body->tanggal_berlangganan->{$feature} === 'aktif';
        });

        // Pemeriksa token perangkat Layanan Mandiri ← AuthenticatedSessionController.
        $this->app->make(TokenPerangkatMandiri::class)->register(
            static fn (?string $token): bool => $token == setting('layanan_opendesa_token')
        );

        // Setting langganan yang dipertahankan saat restore DB ← Database::restore()
        // (dulu hardcode 'layanan_opendesa_token' bila "hapus token" tak dicentang).
        $this->app->make(SetelanDipertahankan::class)->daftar(
            static fn (): array => request()->input('hapus_token') === 'N'
                ? ['layanan_opendesa_token']
                : []
        );

        // Pelapor versi terpasang ← helper kirim_versi_opensid (dipindah dari core).
        $this->app->make(PelaporVersi::class)->register(static function (string $kodeDesa): void {
            if (config_item('demo_mode') || empty($kodeDesa) || ENVIRONMENT !== 'production') {
                return;
            }

            $ci = get_instance();
            $ci->load->driver('cache');

            $versi = AmbilVersi();

            if ($versi == $ci->cache->file->get('versi_app_cache')) {
                return;
            }

            try {
                $client = new \GuzzleHttp\Client();
                $client->post(config('bursa.url_penyedia') . '/api/v1/pelanggan/catat-versi', [
                    'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                    'form_params' => [
                        'kode_desa' => kode_wilayah($kodeDesa),
                        'versi'     => $versi,
                    ],
                ])->getBody();
                $ci->cache->file->save('versi_app_cache', $versi);
            } catch (\Exception $e) {
                log_message('error', $e);
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $sourcePath = FCPATH . 'Modules' . DIRECTORY_SEPARATOR . $this->moduleName . DIRECTORY_SEPARATOR . 'Views';

        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            $this->moduleNameLower
        );
    }
}
