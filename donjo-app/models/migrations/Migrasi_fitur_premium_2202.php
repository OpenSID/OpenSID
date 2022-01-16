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
        $hasil =  $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2201');

        return $hasil && $this->migrasi_2022010971($hasil);
    }

    protected function migrasi_2022010971($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'            => 306,
            'modul'         => 'Arsip Desa',
            'url'           => 'bumindes_arsip',
            'aktif'         => 1,
            'ikon'          => 'fa-archive',
            'urut'          => 5,
            'level'         => 2,
            'hidden'        => 0,
            'ikon_kecil'    => 'fa fa-archive',
            'parent'        => 301,
        ]);
        
        $list_tabel = ['surat_masuk', 'surat_keluar', 'dokumen', 'log_surat'];
        foreach($list_tabel as $tabel)
        {
            if (! $this->db->field_exists('lokasi_arsip', $tabel)) {
                $hasil = $hasil && $this->dbforge->add_column($tabel, ['lokasi_arsip' => ['type' => 'VARCHAR', 'constraint' => '150', 'default' => '']]);
            }
        }

        // Perbaharui view dokumen_hidup
        $hasil = $hasil && $this->db->query('DROP VIEW dokumen_hidup');
        
        return $hasil && $this->db->query('CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1');
    }
}
