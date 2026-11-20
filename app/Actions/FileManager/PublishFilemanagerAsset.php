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

namespace App\Actions\FileManager;

use Illuminate\Support\Facades\File;

class PublishFilemanagerAsset
{
    /**
     * Publikasikan aset filemanager (bekas RFM) ke folder public/vendor/filemanager.
     *
     * Meniru pola PublishThemeAsset / PublishModuleAsset: satu Action yang
     * dipanggil otomatis dari Migrasi_required saat pembaruan, dari boot
     * AppServiceProvider sebagai jaring pengaman, dan lewat command
     * opensid:publish-filemanager secara manual.
     *
     * Bedanya, sumber aset ada di dalam paket vendor (bukan folder tema/
     * modul milik aplikasi) dan hanya ada satu tujuan, jadi penyalinannya
     * langsung — setara `vendor:publish --tag=filemanager-assets` yang
     * didaftarkan OpenSID\LaravelFilemanager\LaravelFilemanagerServiceProvider.
     *
     * @param bool $force Salin ulang walau aset sudah lengkap.
     */
    public function handle(bool $force = false): void
    {
        if (! $force && $this->sudahLengkap()) {
            return;
        }

        $source = $this->sourcePath();

        if (! File::isDirectory($source)) {
            // Paket belum ter-install (mis. sebelum composer install selesai) —
            // biarkan pemanggil berikutnya yang mencoba lagi.
            return;
        }

        File::ensureDirectoryExists(dirname($this->targetPath()));
        File::copyDirectory($source, $this->targetPath());
    }

    /**
     * Aset dianggap lengkap bila folder tujuan berisi berkas inti CSS & JS,
     * bukan sekadar ada — publish yang gagal separuh bisa menyisakan folder
     * berisi hanya img/ico/favicon.ico sehingga cek `File::exists` lolos
     * padahal CSS/JS-nya hilang.
     */
    public function sudahLengkap(): bool
    {
        $target = $this->targetPath();

        return File::isFile($target . '/css/style.css')
            && File::isFile($target . '/js/plugins.js');
    }

    /**
     * Folder sumber di dalam paket — sejajar dengan tag publish
     * "filemanager-assets" milik service provider paket.
     */
    protected function sourcePath(): string
    {
        return base_path('vendor/opensid/laravel-filemanager/resources/dist');
    }

    /**
     * Folder tujuan, mengikuti config('filemanager.assets_path') yang juga
     * dipakai service provider paket & helper filemanager_asset().
     */
    protected function targetPath(): string
    {
        return base_path(config('filemanager.assets_path', 'public/vendor/filemanager'));
    }
}
