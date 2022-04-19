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

class Migrasi_fitur_premium_2205 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2204');
        $hasil = $hasil && $this->pengaturanStatusDesa($hasil);
        $hasil = $hasil && $this->pengaturanDataLengkap($hasil);
        $hasil = $hasil && $this->ubahKolomNama($hasil);

        return $hasil && $this->pantauWarga($hasil);
    }

    protected function pengaturanStatusDesa($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'tahun_idm',
            'value'      => date('Y'),
            'keterangan' => 'Default tahun IDM saat pertamakali dibuka',
            'kategori'   => 'status desa',
        ]);
    }

    protected function pengaturanDataLengkap($hasil)
    {
        return $hasil && $this->db
            ->where_in('key', ['tgl_data_lengkap', 'tgl_data_lengkap_aktif'])
            ->update('setting_aplikasi', ['kategori' => 'data_lengkap']);
    }

    protected function ubahKolomNama($hasil)
    {
        if ($this->db->field_exists('nama', 'analisis_klasifikasi')) {
            $fields = [
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('analisis_klasifikasi', $fields);
        }

        return $hasil;
    }

    protected function pantauWarga($hasil)
    {
        if (! $this->db->field_exists('pantau', 'covid19_pemudik')) {
            $fields = [
                'pantau' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 1,
                    'after'      => 'id_terdata',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('covid19_pemudik', $fields);
        }

        return $hasil;
    }
}
