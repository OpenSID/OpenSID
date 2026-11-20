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

namespace App\Actions\Theme;

use App\Models\Theme;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class PublishThemeAsset
{
    /**
     * Publikasikan aset tema ke folder public/themes/{slug}.
     *
     * Aset yang dipublikasikan hanyalah tema aktif — folder hasil publish
     * milik tema lain dihapus agar hanya aset tema aktif yang tersaji
     * langsung dari public/.
     */
    public function handle(Theme $theme): void
    {
        $tag = "theme:{$theme->slug}";

        $this->registerPublishable($theme, $tag);

        Artisan::call('vendor:publish', ['--tag' => $tag, '--force' => true]);

        $this->cleanup($theme->slug);
    }

    /**
     * Daftarkan aset tema sebagai publishable Laravel (vendor:publish --tag).
     *
     * Aset yang disalin adalah isi folder {tema}/assets, sehingga hasilnya
     * di public/themes/{slug}/ langsung memetakan 1:1 ke pemanggilan
     * theme_asset('images/head/x.webp') tanpa segmen tambahan.
     */
    protected function registerPublishable(Theme $theme, string $tag): void
    {
        ServiceProvider::$publishGroups[$tag] = [
            base_path($theme->asset_path) => public_path("themes/{$theme->slug}"),
        ];
    }

    /**
     * Hapus folder hasil publish milik tema lain agar hanya tema aktif
     * yang asetnya tersedia langsung dari public/.
     */
    protected function cleanup(string $activeSlug): void
    {
        foreach (glob(public_path('themes/*'), GLOB_ONLYDIR) ?: [] as $dir) {
            if (basename($dir) !== $activeSlug) {
                File::deleteDirectory($dir);
            }
        }
    }
}
