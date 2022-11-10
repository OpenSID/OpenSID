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

class Migrasi_fitur_premium_2212 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2211');
        $hasil = $hasil && $this->migrasi_2022110171($hasil);
        $hasil = $hasil && $this->migrasi_2022110771($hasil);
        $hasil = $hasil && $this->migrasi_2022110951($hasil);

        return $hasil && true;
    }

    protected function migrasi_2022110171($hasil)
    {
        if (! $this->db->field_exists('premium', 'migrasi')) {
            $fields = [
                'premium' => [
                    'type' => 'text',
                    'null' => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('migrasi', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022110771($hasil)
    {
        // Buat ulang view keluarga_aktif
        return $hasil && $this->db->query('CREATE OR REPLACE VIEW keluarga_aktif AS SELECT k.* FROM tweb_keluarga k LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id WHERE p.status_dasar = 1');
    }

    protected function migrasi_2022110951($hasil)
    {
        if (! $this->db->field_exists('satuan_waktu', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', [
                'satuan_waktu' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => '3',
                    'after'      => 'waktu',
                    'comment'    => '1 = Hari, 2 = Minggu, 3 = Bulan, 4 = Tahun',
                ],
            ]);
        }

        return $hasil;
    }
}
