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

class Migrasi_1911_ke_1912 extends CI_model
{
    public function up()
    {
        $this->load->model('migrations/migrasi_default_value');
        $this->migrasi_default_value->up();

        // Perbaiki form admin data keuangan
        $this->db->where('isi', 'keuangan.php')->update('widget', ['form_admin' => 'keuangan/impor_data']);
        // Buat kolom tweb_rtm.no_kk menjadi unique
        $fields          = [];
        $fields['no_kk'] = [
            'type'       => 'VARCHAR',
            'constraint' => 30,
            'null'       => false,
            'unique'     => true,
        ];
        $this->dbforge->modify_column('tweb_rtm', $fields);
        // Buat tabel untuk mencatat riwayat ekspor data
        if (! $this->db->table_exists('log_ekspor')) {
            $query = "
			CREATE TABLE IF NOT EXISTS `log_ekspor` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`tgl_ekspor` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`kode_ekspor` varchar(100) NOT NULL,
				`semua` int(1) NOT NULL DEFAULT '1',
				`dari_tgl` date DEFAULT NULL,
				`total` int NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)";
            $this->db->query($query);
        }
        // Aktifkan submodul informasi publik
        $modul_nonmenu = [
            'id'         => '96',
            'modul'      => 'Informasi Publik',
            'url'        => 'informasi_publik',
            'aktif'      => '1',
            'ikon'       => '',
            'urut'       => '0',
            'level'      => '0',
            'parent'     => '52',
            'hidden'     => '2',
            'ikon_kecil' => '',
        ];
        $sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . ' ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)';
        $this->db->query($sql);
        // Perbaiki nilai default kolom untuk sql_mode STRICT_TRANS_TABLE
        $this->dbforge->modify_column('inbox', 'ReceivingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('inventaris_asset', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('inventaris_gedung', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('inventaris_jalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('inventaris_kontruksi', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('inventaris_peralatan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('inventaris_tanah', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('outbox', 'InsertIntoDB TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('outbox', 'SendingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('outbox', 'SendingTimeOut TIMESTAMP NULL DEFAULT NULL');
        $this->dbforge->modify_column('sentitems', 'InsertIntoDB TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('sentitems', 'SendingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('teks_berjalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('mutasi_inventaris_asset', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('mutasi_inventaris_gedung', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('mutasi_inventaris_jalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('mutasi_inventaris_peralatan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('mutasi_inventaris_tanah', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->modify_column('program_peserta', 'kartu_nik VARCHAR(30) NULL DEFAULT NULL');
        $this->dbforge->modify_column('program_peserta', 'kartu_peserta VARCHAR(100) NULL DEFAULT NULL');
        $this->dbforge->modify_column('lokasi', 'lat VARCHAR(30) NULL DEFAULT NULL');
        $this->dbforge->modify_column('lokasi', 'lng VARCHAR(30) NULL DEFAULT NULL');
        $this->dbforge->modify_column('lokasi', 'foto VARCHAR(100) NULL DEFAULT NULL');
        $this->dbforge->modify_column('lokasi', 'id_cluster INT(11) NULL DEFAULT NULL');
        $this->dbforge->modify_column('polygon', 'simbol VARCHAR(50) NULL DEFAULT NULL');
        $this->dbforge->modify_column('garis', 'path TEXT NULL');
        $this->dbforge->modify_column('garis', 'foto VARCHAR(100) NULL DEFAULT NULL');
        $this->dbforge->modify_column('garis', 'desk TEXT NULL');
        $this->dbforge->modify_column('garis', 'id_cluster INT(11) NULL DEFAULT NULL');

        // Pindahkan submenu Informasi Publik ke menu Sekretariat
        $this->db->where('id', '52')->update('setting_modul', ['parent' => 15, 'urut' => 4]);
        // Pindahkan kolom untuk kategori informasi publik
        if (! $this->db->field_exists('kategori_info_publik', 'dokumen')) {
            $fields = [
                'kategori_info_publik' => [
                    'type'       => 'TINYINT',
                    'constraint' => '4',
                    'null'       => true,
                    'default'    => null,
                ],
            ];
            $this->dbforge->add_column('dokumen', $fields);
            // Pindahkan isi kolom sebelumnya
            $dokumen = $this->db->select('id, attr')->get('dokumen')->result_array();

            if ($dokumen) {
                foreach ($dokumen as $dok) {
                    $attr = json_decode($dok['attr'], true);
                    $kat  = $attr['kategori_publik'];
                    unset($attr['kategori_publik']);
                    $this->db->where('id', $dok['id'])
                        ->update('dokumen', ['kategori_info_publik' => $kat, 'attr' => json_encode($attr)]);
                }
            }
        }
        // Isi kategori_info_publik untuk semua dokumen SK Kades dan Perdes sebagai 'Informasi Setiap Saat'
        $this->db->where('kategori_info_publik IS NULL')
            ->where('kategori IN (2,3)')
            ->update('dokumen', ['kategori_info_publik' => '3']);
        // Perbesar nilai klasifikasi melebihi 999.99
        $this->dbforge->modify_column('analisis_klasifikasi', 'minval double(7,2) NOT NULL');
        $this->dbforge->modify_column('analisis_klasifikasi', 'maxval double(7,2) NOT NULL');
        // Catat perubahan pada dokumen dan terapkan soft-delete
        if (! $this->db->field_exists('updated_at', 'dokumen')) {
            $this->dbforge->add_column('dokumen', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $fields = [
                'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ],
            ];
            $this->dbforge->add_column('dokumen', $fields);
        }
        if (! $this->db->table_exists('dokumen_hidup')) {
            $this->db->query('CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1');
        }
        // Sesuaikan tabel config dengan sql_mode STRICT_TRANS_TABLES
        $this->dbforge->modify_column('config', 'logo varchar(100) NULL DEFAULT NULL');
        $this->dbforge->modify_column('config', 'lat varchar(20) NULL DEFAULT NULL');
        $this->dbforge->modify_column('config', 'lng varchar(20) NULL DEFAULT NULL');
        $this->dbforge->modify_column('config', 'zoom tinyint(4) NULL DEFAULT NULL');
        $this->dbforge->modify_column('config', 'map_tipe varchar(20) NULL DEFAULT NULL');
        $this->dbforge->modify_column('config', 'path text NULL');
        if ($this->db->field_exists('g_analytic', 'config')) {
            $this->dbforge->drop_column('config', 'g_analytic');
        }
        // Sesuaikan impor analisis dengan sql_mode STRICT_TRANS_TABLES
        $this->dbforge->modify_column('analisis_master', 'id_kelompok int(11) NULL DEFAULT NULL');
        $this->dbforge->modify_column('analisis_master', 'id_child smallint(4) NULL DEFAULT NULL');
        $this->dbforge->modify_column('analisis_master', 'format_impor tinyint(2) NULL DEFAULT NULL');
        $this->dbforge->modify_column('analisis_kategori_indikator', 'kategori_kode varchar(3) NULL DEFAULT NULL');
        $this->dbforge->modify_column('analisis_kategori_indikator', 'id int(11) NOT NULL AUTO_INCREMENT');
        $this->dbforge->modify_column('analisis_indikator', 'id_kategori int(4) NOT NULL');
    }
}
