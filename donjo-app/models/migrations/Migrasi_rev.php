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

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        // }

        $hasil = $hasil && $this->migrasi_2024072751($hasil);
        $hasil = $hasil && $this->migrasi_2024072752($hasil);
        $hasil = $hasil && $this->migrasi_2024072753($hasil);
        $hasil = $hasil && $this->migrasi_2024072754($hasil);
        $hasil = $hasil && $this->migrasi_2024072755($hasil);
        $hasil = $hasil && $this->migrasi_2024072756($hasil);
        $hasil = $hasil && $this->migrasi_2024072651($hasil);

        return $hasil && $this->migrasi_2024072951($hasil);
    }

    protected function migrasi_2024072651($hasil)
    {
        return DB::table('gambar_gallery')
            ->where('parrent', 0)
            ->where('tipe', 0)
            ->update(['tipe' => 1]);
    }

    protected function migrasi_2024072751($hasil)
    {
        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_lapak_web')
            ->update([
                'jenis'     => 'select-boolean',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'icon_lapak_peta')
            ->update([
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'jumlah_pengajuan_produk')
            ->update([
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'jumlah_produk_perhalaman')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'banyak_foto_tiap_produk')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 5,
                    'step'  => 1,
                ]),
            ]);

        return $hasil;
    }

    protected function migrasi_2024072752($hasil)
    {
        DB::table('setting_aplikasi')
            ->where('key', 'ukuran_lebar_bagan')
            ->update([
                'jenis'     => 'select-array',
                'attribute' => [
                    'class' => 'required',
                ],
                'option' => json_encode([
                    '800'  => '800',
                    '1200' => '1200',
                    '1400' => '1400',
                ]),
                'keterangan' => 'Ukuran Lebar Bagan',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'media_sosial_pemerintah_desa')
            ->update([
                'jenis'     => 'select-multiple-array',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'sebutan_pemerintah_desa')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        return $hasil;
    }

    protected function migrasi_2024072753($hasil)
    {
        DB::table('setting_aplikasi')
            ->where('key', 'jumlah_gambar_galeri')
            ->update([
                'attribute' => [
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'urutan_gambar_galeri')
            ->update([
                'jenis'     => 'select-array',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        return $hasil;
    }

    protected function migrasi_2024072754($hasil)
    {
        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_kehadiran')
            ->update([
                'jenis'     => 'select-boolean',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'ip_adress_kehadiran')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class'       => 'ip_address',
                    'placeholder' => '127.0.0.1',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'mac_adress_kehadiran')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class'       => 'mac_address',
                    'placeholder' => '00:1B:44:11:3A:B7',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'id_pengunjung_kehadiran')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => [
                    'class'       => 'alfanumerik',
                    'placeholder' => 'ad02c373c2a8745d108aff863712fe92',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'rentang_waktu_kehadiran')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 0,
                    'max'         => 3600,
                    'step'        => 1,
                    'placeholder' => '10',
                ],
            ]);

        return $hasil;
    }

    protected function migrasi_2024072755($hasil)
    {
        DB::table('setting_aplikasi')
            ->where('key', 'rentang_waktu_notifikasi_rilis')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 0,
                    'max'         => 365,
                    'step'        => 1,
                    'placeholder' => '7',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'kode_desa_bps')
            ->update([
                'jenis'     => 'input-text',
                'kategori'  => 'Status SDGs',
                'attribute' => [
                    'class'       => 'required',
                    'max-length'  => 15,
                    'placeholder' => '3312110003',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tgl_data_lengkap_aktif')
            ->update([
                'jenis'     => 'select-boolean',
                'kategori'  => 'Data Lengkap',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'pembangunan')
            ->update([
                'kategori' => 'Pembangunan',
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'beranda')
            ->update([
                'kategori' => 'Beranda',
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'lapak')
            ->update([
                'kategori' => 'Lapak',
            ]);

        DB::table('setting_aplikasi')
            ->where('kategori', 'kehadiran')
            ->update([
                'kategori' => 'Kehadiran',
            ]);

        return $hasil;
    }

    protected function migrasi_2024072756($hasil)
    {
        DB::table('setting_aplikasi')
            ->where('kategori', 'peta')
            ->update([
                'kategori' => 'Peta',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'mapbox_key')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'mapbox_key')
            ->update([
                'jenis'     => 'input-text',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'min_zoom_peta')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 1,
                    'max'         => 50,
                    'step'        => 1,
                    'placeholder' => '1',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'max_zoom_peta')
            ->update([
                'jenis'     => 'input-number',
                'attribute' => [
                    'class'       => 'required',
                    'min'         => 1,
                    'max'         => 30,
                    'step'        => 1,
                    'placeholder' => '30',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tampilkan_tombol_peta')
            ->update([
                'jenis'     => 'select-multiple-array',
                'attribute' => null,
                'option'    => json_encode([
                    [
                        'id'   => 'Statistik Penduduk',
                        'nama' => 'Statistik Penduduk',
                    ],
                    [
                        'id'   => 'Statistik Bantuan',
                        'nama' => 'Statistik Bantuan',
                    ],
                    [
                        'id'   => 'Aparatur Desa',
                        'nama' => 'Aparatur Desa',
                    ],
                    [
                        'id'   => 'Kepala Wilayah',
                        'nama' => 'Kepala Wilayah',
                    ],
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tampil_luas_peta')
            ->update([
                'jenis'     => 'select-boolean',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'jenis_peta')
            ->update([
                'jenis'     => 'select-array',
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        DB::table('setting_aplikasi')
            ->whereIn('key', ['default_tampil_peta_wilayah', 'default_tampil_peta_infrastruktur'])
            ->update([
                'jenis'     => 'select-multiple-array',
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('jenis', 'select-simbol')
            ->update([
                'attribute' => [
                    'class' => 'required',
                ],
            ]);

        return $hasil;
    }

    protected function migrasi_2024072951($hasil)
    {
        return $hasil && $this->dbforge->modify_column('log_surat', [
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }
}
