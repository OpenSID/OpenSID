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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024052771 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024050271($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024050272($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024051571($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024052151($hasil, $id);
        }

        $hasil = $hasil && $this->migrasi_2024051251($hasil);
        $hasil = $hasil && $this->migrasi_2024051252($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024051251($hasil)
    {
        DB::table('analisis_master')->where('jenis', 1)->update(['jenis' => 2]);

        return $hasil;
    }

    protected function migrasi_2024051252($hasil)
    {
        DB::table('tweb_penduduk_umur')->where('nama', 'Di Atas 75 Tahun')->update(['nama' => '75 Tahun ke Atas']);

        return $hasil;
    }

    protected function migrasi_2024052151($hasil, $id)
    {
        $media_sosial = DB::table('media_sosial')
            ->where('config_id', $id)
            ->pluck('nama')->map(static fn ($item) => Str::slug($item))->toArray();

        $setting = DB::table('setting_aplikasi')
            ->where('config_id', $id)
            ->where('key', 'media_sosial_pemerintah_desa')
            ->first();

        $value  = json_decode($setting->value, true);
        $option = json_decode($setting->option, true);

        if (count($value) > count($media_sosial) || count($option) > count($media_sosial)) {
            $value  = array_values(array_filter(array_unique($value), static fn ($item) => in_array($item, $media_sosial)));
            $option = array_filter(array_unique($option, SORT_REGULAR), static fn ($item) => in_array($item['id'], $media_sosial));

            DB::table('setting_aplikasi')
                ->where('config_id', $id)
                ->where('key', 'media_sosial_pemerintah_desa')
                ->update([
                    'value'  => json_encode($value),
                    'option' => json_encode($option),
                ]);
        }

        return $hasil;
    }

    protected function migrasi_2024050272($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Icon Pembangunan Peta',
            'key'        => 'icon_pembangunan_peta',
            'value'      => 'construction.png',
            'keterangan' => 'Icon penanda Lokasi Pembangunan yang ditampilkan pada Peta',
            'jenis'      => 'select-simbol',
            'option'     => json_encode(['model' => 'App\\Models\\Simbol', 'value' => 'simbol', 'label' => 'simbol']),
            'attribute'  => 'class="required"',
            'kategori'   => 'pembangunan',
        ], $id);
    }

    protected function migrasi_2024050271($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Jumlah Gambar Galeri',
            'key'        => 'jumlah_gambar_galeri',
            'value'      => 4,
            'keterangan' => 'Jumlah gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'input-number',
            'attribute'  => 'min="1" max="50" step="1"',
            'kategori'   => 'galeri',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Urutan Gambar Galeri',
            'key'        => 'urutan_gambar_galeri',
            'value'      => 'acak',
            'keterangan' => 'Urutan gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'option',
            'option'     => json_encode([
                'asc'  => 'A - Z',
                'desc' => 'Z - A',
                'acak' => 'Acak',
            ]),
            'kategori' => 'galeri',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Jumlah Pengajuan Produk Oleh Warga',
            'key'        => 'jumlah_pengajuan_produk',
            'value'      => 3,
            'keterangan' => 'Jumlah pengajuan produk perhari oleh warga melalui layanan mandiri',
            'jenis'      => 'input-number',
            'attribute'  => 'min="1" max="50" step="1"',
            'kategori'   => 'lapak',
        ], $id);
    }

    protected function migrasi_2024051571($hasil, $id)
    {
        $option = json_encode([
            '1' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan',
            '2' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama',
            '3' => 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar',
            '4' => 'Nomor berurutan untuk masing-masing klasifikasi surat yang sama',
        ]);
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Penomoran Surat',
            'key'        => 'penomoran_surat',
            'value'      => '2',
            'keterangan' => 'Penomoran surat mulai dari satu (1) setiap tahun',
            'jenis'      => 'option',
            'option'     => $option,
            'kategori'   => 'sistem',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Penomoran Surat Dinas',
            'key'        => 'penomoran_surat_dinas',
            'value'      => '2',
            'keterangan' => 'Penomoran surat dinas mulai dari satu (1) setiap tahun',
            'jenis'      => 'option',
            'option'     => $option,
            'kategori'   => 'format_surat_dinas',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Panjang Nomor Surat Dinas',
            'key'        => 'panjang_nomor_surat_dinas',
            'value'      => '3',
            'keterangan' => "Nomor akan diisi '0' di sebelah kiri, kalau perlu",
            'jenis'      => 'text',
            'attribute'  => 'class="int"',
            'kategori'   => 'format_surat_dinas',
        ], $id);
    }
}
