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

namespace Modules\BukuTamu\Providers;

use App\Services\Acak\RegistriPembersih;
use App\Services\FolderDesaCleaner\RegistriModul;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\BukuTamu\Acak\BukuTamuSanitizer;
use Modules\BukuTamu\Events\TamuSubmitted;
use Modules\BukuTamu\FolderDesa\OrphanBukuTamuRule;
use Modules\BukuTamu\Listeners\SendTamuNotification;

class BukuTamuServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'BukuTamu';

    /**
     * @var string
     */
    protected $moduleNameLower = 'bukutamu';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerListeners();
        $this->registerKategoriNotifikasi();
        $this->registerSanitizerAcak();
        $this->registerAturanFolderDesa();
    }

    /**
     * Daftarkan listener event BukuTamu ke dispatcher (menggantikan entri statis
     * core EventServiceProvider yang dihapus saat ekstraksi add-on).
     */
    protected function registerListeners(): void
    {
        Event::listen(TamuSubmitted::class, SendTamuNotification::class);
    }

    /**
     * Sumbang kategori notifikasi 'buku_tamu' ke config core (dulu di-seed
     * `config/notifications.php`; kini dimiliki add-on). Tanpa modul, kategori
     * tak muncul di filter notifikasi.
     */
    protected function registerKategoriNotifikasi(): void
    {
        $kategori = config('notifications.categories', []);

        // Sisipkan lewat merge config (bukan NotificationService::registerCategory)
        // karena getCategories() hanya memuat config bila $categories masih kosong —
        // register saat boot justru menekan pemuatan kategori inti.
        $kategori['buku_tamu'] = [
            'slug'  => 'buku_tamu',
            'label' => 'Buku Tamu',
            'icon'  => 'fa-book',
            'color' => '#27ae60',
            'route' => 'buku_tamu',
            'modul' => 'data-tamu',
            'query' => 'status=0',
        ];

        config(['notifications.categories' => $kategori]);
    }

    /**
     * Daftarkan Sanitizer Acak BukuTamu ke registry inti (tabel `buku_tamu`
     * ber-PII). Tanpa modul, inti tak mengacak tabel ini.
     */
    protected function registerSanitizerAcak(): void
    {
        if (! $this->app->bound(RegistriPembersih::class)) {
            return;
        }

        $this->app->make(RegistriPembersih::class)->daftarkan(new BukuTamuSanitizer());
    }

    /**
     * Daftarkan aturan pembersih folder desa + folder upload dilindungi ke
     * registry inti (foto tamu yatim di `desa/upload/buku_tamu`). Tanpa modul,
     * inti tak mengenal folder ini.
     */
    protected function registerAturanFolderDesa(): void
    {
        if (! $this->app->bound(RegistriModul::class)) {
            return;
        }

        $registri = $this->app->make(RegistriModul::class);
        $registri->daftarkanAturan(static fn (string $basePath): OrphanBukuTamuRule => new OrphanBukuTamuRule($basePath));
        $registri->daftarkanFolderUpload('buku_tamu');
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
