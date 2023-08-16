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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2309 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2308', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        //     $hasil = $hasil && $this->migrasi_xxxxxxxxxx($hasil, $id);
        // }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_23080851($hasil);
        $hasil = $hasil && $this->migrasi_23081451($hasil);
        $hasil = $hasil && $this->migrasi_23081452($hasil);
        $hasil = $hasil && $this->migrasi_23081551($hasil);

        return $hasil && $this->migrasi_23081651($hasil);
    }

    protected function migrasi_23080851($hasil)
    {
        $periksa = ['setting_aplikasi', 'setting_modul', 'user_grup', 'user', 'grup_akses', 'media_sosial', 'kehadiran_jam_kerja', 'ref_jabatan', 'klasifikasi_surat', 'anjungan_menu', 'gis_simbol', 'ref_syarat_surat', 'widget', 'tweb_surat_format', 'tweb_penduduk_umur', 'notifikasi', 'analisis_indikator', 'analisis_kategori_indikator', 'analisis_master', 'analisis_parameter', 'analisis_periode'];

        foreach ($periksa as $tabel) {
            if ($this->db->where('config_id', null)->get($tabel)->num_rows() > 0) {
                $hasil = $hasil && $this->db->where('config_id', null)->delete($tabel);
            }
        }

        return $hasil;
    }

    protected function migrasi_23081452($hasil)
    {
        return $hasil && $this->dbforge->modify_column('pembangunan', [
            'manfaat' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'lokasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    protected function migrasi_23081451($hasil)
    {
        DB::table('produk')->whereNull('potongan')->update(['potongan' => '0']);

        $this->db->query('ALTER TABLE produk MODIFY COLUMN potongan INTEGER(11) NOT NULL Default 0');

        return $hasil;
    }

    protected function migrasi_23081551($hasil)
    {
        $this->db
            ->where('key', 'rentang_waktu_kehadiran')
            ->group_start()
            ->where('value is null')
            ->or_where("value = ''")
            ->group_end()
            ->update('setting_aplikasi', ['value' => 10]);

        return $hasil;
    }

    protected function migrasi_23081651($hasil)
    {
        return $hasil && $this->db->where('key', 'warna_tema')->where('kategori !=', 'openkab')->update('setting_aplikasi', ['kategori' => 'openkab']);
    }
}
