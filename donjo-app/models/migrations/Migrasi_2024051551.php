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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\UserGrup;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024051551 extends MY_model
{
    public function up()
    {
        return $this->migrasi_data(true);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        $hasil = $hasil && $this->migrasi_2024050851($hasil);
        $hasil = $hasil && $this->migrasi_2024051251($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024050851($hasil)
    {
        // karena data awal belum diubah, maka perlu diubah
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'wilayah-administratif', 'url' => 'wilayah/clear'],
            ['url' => 'wilayah']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'calon-pemilih', 'url' => 'dpt/clear'],
            ['url' => 'dpt']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'data-suplemen', 'url' => 'suplemen/clear'],
            ['url' => 'suplemen']
        );
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'data-suplemen', 'url' => 'suplemen/clear'],
            ['url' => 'suplemen']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'modul', 'url' => 'modul/clear'],
            ['url' => 'modul']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'widget', 'url' => 'web_widget/clear'],
            ['url' => 'web_widget']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pengunjung', 'url' => 'pengunjung/clear'],
            ['url' => 'pengunjung']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'klasifikasi-surat', 'url' => 'klasifikasi/clear'],
            ['url' => 'klasifikasi']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'qr-code', 'url' => 'setting/qrcode/clear'],
            ['url' => 'qr_code']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pengaturan-grup', 'url' => 'grup/clear'],
            ['url' => 'grup']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'artikel', 'url' => 'web/clear'],
            ['url' => 'web']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'buku-ktp-dan-kk', 'url' => 'bumindes_penduduk_ktpkk/clear'],
            ['url' => 'bumindes_penduduk_ktpkk']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'buku-rekapitulasi-jumlah-penduduk', 'url' => 'bumindes_penduduk_rekapitulasi/clear'],
            ['url' => 'bumindes_penduduk_rekapitulasi']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'penduduk', 'url' => 'penduduk/clear'],
            ['url' => 'penduduk']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'keluarga', 'url' => 'keluarga/clear'],
            ['url' => 'keluarga']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'surat-keluar', 'url' => 'surat_keluar/clear'],
            ['url' => 'surat_keluar']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'surat-masuk', 'url' => 'surat_masuk/clear'],
            ['url' => 'surat_masuk']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'informasi-publik', 'url' => 'dokumen/clear'],
            ['url' => 'dokumen']
        );
    }

    protected function migrasi_2024051251($hasil)
    {
        UserGrup::where('slug', null)->get()->each(static function ($user) {
            $user->update([
                'slug' => unique_slug('user_grup', $user->nama),
            ]);
        });

        return $hasil;
    }
}
