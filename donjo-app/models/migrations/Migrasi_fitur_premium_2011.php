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

class Migrasi_fitur_premium_2011 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2010');

        // Tambah kolom warna di tabel config
        if (! $this->db->field_exists('warna', 'config')) {
            $fields = [
                'warna' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('config', $fields);
        }

        // Tambah kolom warna di tabel tweb_
        if (! $this->db->field_exists('warna', 'tweb_wil_clusterdesa')) {
            $fields = [
                'warna' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('tweb_wil_clusterdesa', $fields);
        }

        // Hapus widget layanan mandiri
        $hasil = $hasil && $this->db->delete('widget', ['isi' => 'layanan_mandiri.php']);

        // Tambah pencatatan anjungan
        $hasil = $hasil && $this->tambah_modul([
            'id'         => '312',
            'modul'      => 'Anjungan',
            'url'        => 'anjungan',
            'aktif'      => '1',
            'ikon'       => 'fa-desktop',
            'urut'       => '4',
            'level'      => '2',
            'parent'     => '14',
            'hidden'     => '0',
            'ikon_kecil' => '',
        ]);

        // Tabel anjungan
        if (! $this->db->table_exists('anjungan')) {
            $query = "
            CREATE TABLE IF NOT EXISTS anjungan (
                id int(11) NOT NULL AUTO_INCREMENT,
                ip_address varchar(100) NOT NULL,
                keterangan varchar(300) DEFAULT NULL,
                status tinyint(1) NOT NULL DEFAULT '1',
                created_by int(11) NOT NULL,
                updated_by int(11) NOT NULL,
                created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            )";
            $hasil = $hasil && $this->db->query($query);
        }
        // Update view supaya kolom baru ikut masuk
        $hasil = $hasil && $this->db->query('DROP VIEW penduduk_hidup');
        $hasil = $hasil && $this->db->query('CREATE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1');

        // komentar.email boleh null
        $field = [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('komentar', $field);
    }
}