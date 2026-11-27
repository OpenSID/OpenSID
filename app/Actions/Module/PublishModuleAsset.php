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

namespace App\Actions\Module;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class PublishModuleAsset
{
    /**
     * Publikasikan aset modul ke folder public/modules/{slug}.
     *
     * Berbeda dari tema, beberapa modul dapat aktif bersamaan sehingga
     * tidak ada folder milik modul lain yang perlu dibersihkan di sini —
     * folder publish modul yang dihapus hanya dibersihkan lewat cleanup().
     */
    public function handle(string $moduleName): void
    {
        $sourcePath = base_path("Modules/{$moduleName}/Views/assets");

        // Tidak semua modul punya aset (mis. modul murni backend), jadi
        // lewati publish jika tidak ada apa pun yang perlu disalin.
        if (! is_dir($sourcePath)) {
            return;
        }

        $slug = strtolower($moduleName);
        $tag  = "module:{$slug}";

        $this->registerPublishable($sourcePath, $slug, $tag);

        Artisan::call('vendor:publish', ['--tag' => $tag, '--force' => true]);
    }

    /**
     * Daftarkan aset modul sebagai publishable Laravel (vendor:publish --tag).
     *
     * Aset yang disalin adalah isi folder {modul}/Views/assets, sehingga hasilnya
     * di public/modules/{slug}/ langsung memetakan 1:1 ke pemanggilan
     * module_asset('nama', 'images/x.webp') tanpa segmen tambahan.
     */
    protected function registerPublishable(string $sourcePath, string $slug, string $tag): void
    {
        ServiceProvider::$publishGroups[$tag] = [
            $sourcePath => public_path("modules/{$slug}"),
        ];
    }

    /**
     * Hapus folder hasil publish milik modul yang dihapus/dinonaktifkan.
     */
    public function cleanup(string $moduleName): void
    {
        File::deleteDirectory(public_path('modules/' . strtolower($moduleName)));
    }
}
