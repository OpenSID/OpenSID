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

namespace App\Services\Theme;

use ZipArchive;

/**
 * Ekstraksi + validasi ZIP tema hasil unduhan bursa (Layanan) ke `desa/themes/`.
 *
 * Diekstrak dari `Theme::extractAndValidateTheme()` (controller CI3,
 * `donjo-app/controllers/Theme.php`) supaya bisa diuji langsung tanpa boot CI3 —
 * method aslinya tak pernah menyentuh `$this`, jadi pemindahan ini murni
 * relokasi, bukan perubahan perilaku (mirror premium#6790).
 *
 * Catatan: berbeda dari versi Premium, di sini TIDAK ada pemanggilan
 * validate_zip_entries() (pengecekan path traversal) -- fungsi itu belum ada
 * di Umum sama sekali (gap keamanan pra-ada, terpisah dari perbaikan ini,
 * tidak ditambahkan di sini supaya tidak diam-diam memperluas cakupan).
 */
class ThemeZipExtractor
{
    /**
     * @param array{full_path: string} $upload
     *
     * @return array{status: bool, data: string}
     */
    public function extract(array $upload): array
    {
        $zip = new ZipArchive();

        if ($zip->open($upload['full_path']) !== true) {
            unlink($upload['full_path']);

            return [
                'status' => false,
                'data'   => 'Tema tidak valid',
            ];
        }

        $lokasi_ekstrak = FCPATH . 'desa/themes/';
        $subfolder      = $zip->getNameIndex(0);
        $namaTema       = substr($subfolder, 0, -1);
        $lokasi_tema    = $lokasi_ekstrak . $namaTema;

        // premium#6790: ZipArchive::extractTo() menimpa file-per-file, bukan
        // mengganti seluruh folder — file yang DIHAPUS pengembang tema antara dua
        // versi akan tertinggal (stale) bila diekstrak langsung ke folder lama.
        // Diekstrak dulu ke folder staging sementara & divalidasi di sana; folder
        // tema LAMA baru dihapus & diganti setelah hasil ekstraksi TERBUKTI valid —
        // supaya ZIP yang korup/tidak lengkap tidak menghapus tema yang sedang
        // berjalan (jalur ini khusus Tema Pro dari bursa, satu-satunya pemanggil
        // method ini; tidak ada jalur unggah manual yang memakainya).
        $staging = $lokasi_ekstrak . '_staging_' . uniqid('', true) . '/';
        mkdir($staging, 0755, true);
        $zip->extractTo($staging);
        $zip->close();

        $lokasi_staging_tema = $staging . $namaTema;

        if (! file_exists($lokasi_staging_tema . '/resources/views/template.blade.php')) {
            delete_files($staging, true);
            rmdir($staging);

            return [
                'status' => false,
                'data'   => 'Tema tidak valid',
            ];
        }

        // Gerbang kompatibilitas core (premium#7026): tolak ZIP tema yang
        // min_core/max_core-nya (theme.json) mengecualikan versi core ini
        // SEBELUM tema lama diganti. Dilewati pada ENVIRONMENT=development.
        if (($pesan = KompatibilitasCoreTema::periksa($lokasi_staging_tema)) !== null) {
            delete_files($staging, true);
            rmdir($staging);

            return [
                'status' => false,
                'data'   => $pesan,
            ];
        }

        if (is_dir($lokasi_tema)) {
            delete_files($lokasi_tema, true);
            rmdir($lokasi_tema);
        }

        rename($lokasi_staging_tema, $lokasi_tema);
        delete_files($staging, true);
        rmdir($staging);

        theme_scan();

        return [
            'status' => true,
            'data'   => 'Berhasil Unggah Tema',
        ];
    }
}
