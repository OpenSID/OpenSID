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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2202 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2201');
        $hasil = $hasil && $this->migrasi_2022011251($hasil);
        $hasil = $hasil && $this->migrasi_2022011371($hasil);

        return $hasil && $this->migrasi_2022012471($hasil);
    }

    protected function migrasi_2022011251($hasil)
    {
        $hasil = $hasil && $this->keuangan_ta_pencairan($hasil);
        $hasil = $hasil && $this->keuangan_ta_spjpot($hasil);
        $hasil = $hasil && $this->keuangan_ta_spj_bukti($hasil);
        $hasil = $hasil && $this->keuangan_ta_sppbukti($hasil);

        return $hasil && $this->keuangan_ta_spppot($hasil);
    }

    protected function keuangan_ta_pencairan($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('No_Ref', 'keuangan_ta_pencairan')) {
            $fields['No_Ref'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_pencairan')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_pencairan')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_pencairan', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_spjpot($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Billing_Pajak', 'keuangan_ta_spjpot')) {
            $fields['Billing_Pajak'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spjpot', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_spj_bukti($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Kd_Bank', 'keuangan_ta_spj_bukti')) {
            $fields['Kd_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Ref_Bayar', 'keuangan_ta_spj_bukti')) {
            $fields['Ref_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_spj_bukti')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_spj_bukti')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spj_bukti', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_sppbukti($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Kd_Bank', 'keuangan_ta_sppbukti')) {
            $fields['Kd_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Ref_Bayar', 'keuangan_ta_sppbukti')) {
            $fields['Ref_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_sppbukti')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_sppbukti')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_sppbukti', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_spppot($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Billing_Pajak', 'keuangan_ta_spppot')) {
            $fields['Billing_Pajak'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spppot', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022011371($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'tampilan_anjungan_audio',
            'value'      => 0,
            'keterangan' => 'Apakah audio diaktifkan atau tidak saat video diputar',
            'jenis'      => 'boolean',
            'kategori'   => 'setting_mandiri',
        ]);
    }

    protected function migrasi_2022012471($hasil)
    {
        if ($this->db->field_exists('bagan_warna', 'tweb_desa_pamong')) {
            $fields = [
                'bagan_warna' => [
                    'type'       => 'varchar',
                    'constraint' => 20,
                    'null'       => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('tweb_desa_pamong', $fields);
        }

        return $hasil;
    }
}
