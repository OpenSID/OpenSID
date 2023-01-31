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

class Database_model extends MY_Model
{
    private $user   = 1;
    private $engine = 'InnoDB';

    // define versi opensid dan script migrasi yang harus dijalankan
    private $versionMigrate = [
        '2.4'     => ['migrate' => 'migrasi_24_ke_25', 'nextVersion' => '2.5'],
        'pra-2.5' => ['migrate' => 'migrasi_24_ke_25', 'nextVersion' => '2.5'],
        '2.5'     => ['migrate' => 'migrasi_25_ke_26', 'nextVersion' => '2.6'],
        '2.6'     => ['migrate' => 'migrasi_26_ke_27', 'nextVersion' => '2.7'],
        '2.7'     => ['migrate' => 'migrasi_27_ke_28', 'nextVersion' => '2.8'],
        '2.8'     => ['migrate' => 'migrasi_28_ke_29', 'nextVersion' => '2.9'],
        '2.9'     => ['migrate' => 'migrasi_29_ke_210', 'nextVersion' => '2.10'],
        '2.10'    => ['migrate' => 'migrasi_210_ke_211', 'nextVersion' => '2.11'],
        '2.11'    => ['migrate' => 'migrasi_211_ke_1806', 'nextVersion' => '18.06'],
        '2.12'    => ['migrate' => 'migrasi_211_ke_1806', 'nextVersion' => '18.06'],
        '18.06'   => ['migrate' => 'migrasi_1806_ke_1807', 'nextVersion' => '18.08'],
        '18.07'   => ['migrate' => 'migrasi_1806_ke_1807', 'nextVersion' => '18.08'],
        '18.08'   => ['migrate' => 'migrasi_1808_ke_1809', 'nextVersion' => '18.09'],
        '18.09'   => ['migrate' => 'migrasi_1809_ke_1810', 'nextVersion' => '18.10'],
        '18.10'   => ['migrate' => 'migrasi_1810_ke_1811', 'nextVersion' => '18.11'],
        '18.11'   => ['migrate' => 'migrasi_1811_ke_1812', 'nextVersion' => '18.12'],
        '18.12'   => ['migrate' => 'migrasi_1812_ke_1901', 'nextVersion' => '19.01'],
        '19.01'   => ['migrate' => 'migrasi_1901_ke_1902', 'nextVersion' => '19.02'],
        '19.02'   => ['migrate' => 'nop', 'nextVersion' => '19.03'],
        '19.03'   => ['migrate' => 'migrasi_1903_ke_1904', 'nextVersion' => '19.04'],
        '19.04'   => ['migrate' => 'migrasi_1904_ke_1905', 'nextVersion' => '19.05'],
        '19.05'   => ['migrate' => 'migrasi_1905_ke_1906', 'nextVersion' => '19.06'],
        '19.06'   => ['migrate' => 'migrasi_1906_ke_1907', 'nextVersion' => '19.07'],
        '19.07'   => ['migrate' => 'migrasi_1907_ke_1908', 'nextVersion' => '19.08'],
        '19.08'   => ['migrate' => 'migrasi_1908_ke_1909', 'nextVersion' => '19.09'],
        '19.09'   => ['migrate' => 'migrasi_1909_ke_1910', 'nextVersion' => '19.10'],
        '19.10'   => ['migrate' => 'migrasi_1910_ke_1911', 'nextVersion' => '19.11'],
        '19.11'   => ['migrate' => 'migrasi_1911_ke_1912', 'nextVersion' => '19.12'],
        '19.12'   => ['migrate' => 'migrasi_1912_ke_2001', 'nextVersion' => '20.01'],
        '20.01'   => ['migrate' => 'migrasi_2001_ke_2002', 'nextVersion' => '20.02'],
        '20.02'   => ['migrate' => 'migrasi_2002_ke_2003', 'nextVersion' => '20.03'],
        '20.03'   => ['migrate' => 'migrasi_2003_ke_2004', 'nextVersion' => '20.04'],
        '20.04'   => ['migrate' => 'migrasi_2004_ke_2005', 'nextVersion' => '20.05'],
        '20.05'   => ['migrate' => 'migrasi_2005_ke_2006', 'nextVersion' => '20.06'],
        '20.06'   => ['migrate' => 'migrasi_2006_ke_2007', 'nextVersion' => '20.07'],
        '20.07'   => ['migrate' => 'migrasi_2007_ke_2008', 'nextVersion' => '20.08'],
        '20.08'   => ['migrate' => 'migrasi_2008_ke_2009', 'nextVersion' => '20.09'],
        '20.09'   => ['migrate' => 'migrasi_2009_ke_2010', 'nextVersion' => '20.10'],
        '20.10'   => ['migrate' => 'migrasi_2010_ke_2011', 'nextVersion' => '20.11'],
        '20.11'   => ['migrate' => 'migrasi_2011_ke_2012', 'nextVersion' => '20.12'],
        '20.12'   => ['migrate' => 'migrasi_2012_ke_2101', 'nextVersion' => '21.01'],
        '21.01'   => ['migrate' => 'migrasi_2101_ke_2102', 'nextVersion' => '21.02'],
        '21.02'   => ['migrate' => 'migrasi_2102_ke_2103', 'nextVersion' => '21.03'],
        '21.03'   => ['migrate' => 'migrasi_2103_ke_2104', 'nextVersion' => '21.04'],
        '21.04'   => ['migrate' => 'migrasi_2104_ke_2105', 'nextVersion' => '21.05'],
        '21.05'   => ['migrate' => 'migrasi_2105_ke_2106', 'nextVersion' => '21.06'],
        '21.06'   => ['migrate' => 'migrasi_2106_ke_2107', 'nextVersion' => '21.07'],
        '21.07'   => ['migrate' => 'migrasi_2107_ke_2108', 'nextVersion' => '21.08'],
        '21.08'   => ['migrate' => 'migrasi_2108_ke_2109', 'nextVersion' => '21.09'],
        '21.09'   => ['migrate' => 'migrasi_2109_ke_2110', 'nextVersion' => '21.10'],
        '21.10'   => ['migrate' => 'migrasi_2110_ke_2111', 'nextVersion' => '21.11'],
        '21.11'   => ['migrate' => 'migrasi_2111_ke_2112', 'nextVersion' => '21.12'],
        '21.12'   => ['migrate' => 'migrasi_2112_ke_2201', 'nextVersion' => '22.01'],
        '22.01'   => ['migrate' => 'migrasi_2201_ke_2202', 'nextVersion' => '22.02'],
        '22.02'   => ['migrate' => 'migrasi_2202_ke_2203', 'nextVersion' => '22.03'],
        '22.03'   => ['migrate' => 'migrasi_2203_ke_2204', 'nextVersion' => '22.04'],
        '22.04'   => ['migrate' => 'migrasi_2204_ke_2205', 'nextVersion' => '22.05'],
        '22.05'   => ['migrate' => 'migrasi_2205_ke_2206', 'nextVersion' => '22.06'],
        '22.06'   => ['migrate' => 'migrasi_2206_ke_2207', 'nextVersion' => '22.07'],
        '22.07'   => ['migrate' => 'migrasi_2207_ke_2208', 'nextVersion' => '22.08'],
        '22.08'   => ['migrate' => 'migrasi_2208_ke_2209', 'nextVersion' => '22.09'],
        '22.09'   => ['migrate' => 'migrasi_2209_ke_2210', 'nextVersion' => '22.10'],
        '22.10'   => ['migrate' => 'migrasi_2210_ke_2211', 'nextVersion' => '22.11'],
        '22.11'   => ['migrate' => 'migrasi_2211_ke_2212', 'nextVersion' => '22.12'],
        '22.12'   => ['migrate' => 'migrasi_2212_ke_2301', 'nextVersion' => '23.01'],
        '23.01'   => ['migrate' => 'migrasi_2301_ke_2302', 'nextVersion' => '23.02'],
        '23.02'   => ['migrate' => 'migrasi_2302_ke_2303', 'nextVersion' => null],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->dbutil();
        if (!$this->dbutil->database_exists($this->db->database)) {
            return;
        }

        $this->cek_engine_db();
        $this->load->dbforge();
        $this->user = $this->session_user ?: 1;
    }

    private function cek_engine_db()
    {
        $db_debug           = $this->db->db_debug;
        $this->db->db_debug = false; //disable debugging for queries

        $query = $this->db->query("SELECT `engine` FROM INFORMATION_SCHEMA.TABLES WHERE table_schema= '" . $this->db->database . "' AND table_name = 'user'");
        $error = $this->db->error();
        if ($error['code'] != 0) {
            $this->engine = $query->row()->engine;
        }

        $this->db->db_debug = $db_debug; //restore setting
    }

    private function reset_setting_aplikasi()
    {
        $this->db->truncate('setting_aplikasi');
        $query = "
			INSERT INTO setting_aplikasi (`id`, `key`, `value`, `keterangan`, `jenis`,`kategori`) VALUES
			(1, 'sebutan_kabupaten','kabupaten','Pengganti sebutan wilayah kabupaten','',''),
			(2, 'sebutan_kabupaten_singkat','kab.','Pengganti sebutan singkatan wilayah kabupaten','',''),
			(3, 'sebutan_kecamatan','kecamatan','Pengganti sebutan wilayah kecamatan','',''),
			(4, 'sebutan_kecamatan_singkat','kec.','Pengganti sebutan singkatan wilayah kecamatan','',''),
			(5, 'sebutan_desa','desa','Pengganti sebutan wilayah desa','',''),
			(6, 'sebutan_dusun','dusun','Pengganti sebutan wilayah dusun','',''),
			(7, 'sebutan_camat','camat','Pengganti sebutan jabatan camat','',''),
			(8, 'website_title','Website Resmi','Judul tab browser modul web','','web'),
			(9, 'login_title','OpenSID', 'Judul tab browser halaman login modul administrasi','',''),
			(10, 'admin_title','Sistem Informasi Desa','Judul tab browser modul administrasi','',''),
			(11, 'web_theme', 'default','Tema penampilan modul web','','web'),
			(12, 'offline_mode',FALSE,'Apakah modul web akan ditampilkan atau tidak','boolean',''),
			(13, 'enable_track',TRUE,'Apakah akan mengirimkan data statistik ke tracker','boolean',''),
			(14, 'dev_tracker','','Host untuk tracker pada development','','development'),
			(15, 'nomor_terakhir_semua_surat', FALSE,'Gunakan nomor surat terakhir untuk seluruh surat tidak per jenis surat','boolean',''),
			(16, 'google_key','','Google API Key untuk Google Maps','','web'),
			(17, 'libreoffice_path','','Path tempat instal libreoffice di server SID','','')
		";
        $this->db->query($query);
    }

    public function migrasi_db_cri()
    {
        // Tunggu restore selesai sebelum migrasi
        if (isset($this->session->sedang_restore) && $this->session->sedang_restore == 1) {
            return;
        }

        $_SESSION['daftar_migrasi'] = []; // Catat migrasi yg sdh dijalankan, supaya tidak diulang

        $_SESSION['success'] = 1;
        $versi               = $this->getCurrentVersion();
        $nextVersion         = $versi;
        $versionMigrate      = $this->versionMigrate;
        if (isset($versionMigrate[$versi])) {
            while (!empty($nextVersion) && !empty($versionMigrate[$nextVersion]['migrate'])) {
                $migrate     = $versionMigrate[$nextVersion]['migrate'];
                $nextVersion = $versionMigrate[$nextVersion]['nextVersion'];
                if (method_exists($this, $migrate)) {
                    log_message('error', 'Jalankan ' . $migrate);
                    call_user_func(__NAMESPACE__ . '\\Database_model::' . $migrate);
                } else {
                    $this->jalankan_migrasi($migrate);
                }
            }
        } else {
            $this->_migrasi_db_cri();
        }

        // Jalankan migrasi layanan
        $this->jalankan_migrasi('migrasi_layanan');
        $this->db->where('id', 13)->update('setting_aplikasi', ['value' => true]);
        // Lengkapi folder desa
        folder_desa();
        /*
         * Update current_version di db.
         * 'pasca-<versi>' atau '<versi>-pasca disimpan sebagai '<versi>'
         */
        $newVersion = [
            'value' => currentVersion(),
        ];
        $this->db->where(['key' => 'current_version'])->update('setting_aplikasi', $newVersion);
        $this->catat_versi_database();
        $this->load->model('track_model');
        $this->track_model->kirim_data();
    }

    private function catat_versi_database()
    {
        // Catat migrasi ini telah dilakukan
        $sudah = $this->db->where('versi_database', VERSI_DATABASE)
            ->get('migrasi')->num_rows();
        if (!$sudah) {
            $this->db->insert('migrasi', ['versi_database' => VERSI_DATABASE]);
        }
    }

    private function getCurrentVersion()
    {
        // Untuk kasus tabel setting_aplikasi belum ada
        if (!$this->db->table_exists('setting_aplikasi')) {
            return null;
        }
        $result  = null;
        $_result = $this->db->where(['key' => 'current_version'])->get('setting_aplikasi')->row();
        if (!empty($_result)) {
            $result = $_result->value;
        }

        return $result;
    }

    private function versi_database_terbaru()
    {
        $sudah = false;
        if ($this->db->table_exists('migrasi')) {
            $sudah = $this->db->where('versi_database', VERSI_DATABASE)
                ->get('migrasi')->num_rows();
        }

        return $sudah;
    }

    // Cek apakah migrasi perlu dijalankan
    public function cek_migrasi($install = false)
    {
        if ($install) {

            // Paksa menjalankan migrasi kalau belum
            // Migrasi direkam di tabel migrasi
            if (! $this->versi_database_terbaru()) {
                // Ulangi migrasi terakhir
                $terakhir                                                                                  = key(array_slice($this->versionMigrate, -1, 1, true));
                $sebelumnya                                                                                = key(array_slice($this->versionMigrate, -2, 1, true));
                $this->versionMigrate[$terakhir]['migrate'] ?: $this->versionMigrate[$terakhir]['migrate'] = $this->versionMigrate[$sebelumnya]['migrate'];

                $this->migrasi_db_cri();

                // Kirim versi aplikasi ke layanan setelah migrasi selesai
                $this->kirimVersi();
            }
        }

        $this->jalankan_migrasi('migrasi_layanan');
    }

    // Migrasi dengan fuction
    private function _migrasi_db_cri()
    {
        $this->migrasi_cri_lama();
        $this->migrasi_03_ke_04();
        $this->migrasi_08_ke_081();
        $this->migrasi_082_ke_09();
        $this->migrasi_092_ke_010();
        $this->migrasi_010_ke_10();
        $this->migrasi_10_ke_11();
        $this->migrasi_111_ke_12();
        $this->migrasi_124_ke_13();
        $this->migrasi_13_ke_14();
        $this->migrasi_14_ke_15();
        $this->migrasi_15_ke_16();
        $this->migrasi_16_ke_17();
        $this->migrasi_17_ke_18();
        $this->migrasi_18_ke_19();
        $this->migrasi_19_ke_110();
        $this->migrasi_110_ke_111();
        $this->migrasi_111_ke_112();
        $this->migrasi_112_ke_113();
        $this->migrasi_113_ke_114();
        $this->migrasi_114_ke_115();
        $this->migrasi_115_ke_116();
        $this->migrasi_116_ke_117();
        $this->migrasi_117_ke_20();
        $this->migrasi_20_ke_21();
        $this->migrasi_21_ke_22();
        $this->migrasi_22_ke_23();
        $this->migrasi_23_ke_24();
        $this->migrasi_24_ke_25();
        $this->migrasi_25_ke_26();
        $this->migrasi_26_ke_27();
        $this->migrasi_27_ke_28();
        $this->migrasi_28_ke_29();
        $this->migrasi_29_ke_210();
        $this->migrasi_210_ke_211();
        $this->migrasi_211_ke_1806();
        $this->migrasi_1806_ke_1807();
        $this->migrasi_1808_ke_1809();
        $this->migrasi_1809_ke_1810();
        $this->migrasi_1810_ke_1811();
        $this->migrasi_1811_ke_1812();
        $this->migrasi_1812_ke_1901();
        $this->migrasi_1901_ke_1902();
        $this->migrasi_1903_ke_1904();
        $this->migrasi_1904_ke_1905();
        $this->migrasi_1905_ke_1906();
    }

    private function migrasi_1905_ke_1906()
    {
        // Tambah kolom waktu update dan user pengupdate
        if (!$this->db->field_exists('created_at', 'tweb_penduduk')) {
            // Tambah kolom
            $this->dbforge->add_field('created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $fields               = [];
            $fields['created_by'] = [
                'type'       => 'int',
                'constraint' => 11,
                'null'       => false,
            ];
            $this->dbforge->add_column('tweb_penduduk', $fields);
        }
        if (!$this->db->field_exists('updated_at', 'tweb_penduduk')) {
            // Tambah kolom
            $this->dbforge->add_field('updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
        }
        $fields               = [];
        $fields['updated_by'] = [
            'type'       => 'int',
            'constraint' => 11,
            'null'       => true,
            'default'    => null,
        ];
        if (!$this->db->field_exists('updated_by', 'tweb_penduduk')) {
            $this->dbforge->add_column('tweb_penduduk', $fields);
        } else {
            $this->dbforge->modify_column('tweb_penduduk', $fields);
        }

        // Tambah menu teks berjalan
        $data = [
            'id'         => '64',
            'modul'      => 'Teks Berjalan',
            'url'        => 'teks_berjalan',
            'aktif'      => '1',
            'ikon'       => 'fa-ellipsis-h',
            'urut'       => '9',
            'level'      => '2',
            'parent'     => '13',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-ellipsis-h',
        ];
        $sql = $this->db->insert_string('setting_modul', $data) . ' ON DUPLICATE KEY UPDATE url = VALUES(url), ikon = VALUES(ikon), ikon_kecil = VALUES(ikon_kecil)';
        $this->db->query($sql);

        if (!$this->db->table_exists('teks_berjalan')) {
            $query = "
			CREATE TABLE `teks_berjalan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`teks` text,
				`urut` int(5),
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11),
				`status` int(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);

            $setting_teks_berjalan = $this->db->select('id, value')->where('key', 'isi_teks_berjalan')->get('setting_aplikasi')->row();
            if ($setting_teks_berjalan) {
                // ambil teks, tulis ke tabel teks_berjalan
                // hapus setting
                $isi_teks = $setting_teks_berjalan->value;
                $data     = [
                    'teks'       => $isi_teks,
                    'created_by' => $this->user,
                ];
                $this->db->insert('teks_berjalan', $data);
                $this->db->where('key', 'isi_teks_berjalan')->delete('setting_aplikasi');
            } else {
                // ambil teks dari artikel, tulis ke tabel teks_berjalan
                // hapus artikel
                $id_kategori = $this->db->select('id')->where('kategori', 'teks_berjalan')->limit(1)->get('kategori')->row()->id;
                if ($id_kategori) {
                    // Ambil teks dari artikel
                    $teks = $this->db->select('a.isi, a.enabled')
                        ->from('artikel a')
                        ->join('kategori k', 'a.id_kategori = k.id', 'left')
                        ->where('k.kategori', 'teks_berjalan')
                        ->get()->result_array();

                    foreach ($teks as $data) {
                        $isi_teks = strip_tags($data['isi']);
                        $isi      = [
                            'teks'       => $isi_teks,
                            'status'     => $data['enabled'],
                            'created_by' => $this->user,
                        ];
                        $this->db->insert('teks_berjalan', $isi);
                    }
                    // Hapus artikel dan kategori teks berjalan
                    $this->db->where('id_kategori', $id_kategori)->delete('artikel');
                    $this->db->where('kategori', 'teks_berjalan')->delete('kategori');
                }
            }
        }
        // Tambah tautan pada teks berjalan
        if (!$this->db->field_exists('tautan', 'teks_berjalan')) {
            // Tambah kolom
            $fields           = [];
            $fields['tautan'] = [
                'type'       => 'varchar',
                'constraint' => 150,
            ];
            $fields['judul_tautan'] = [
                'type'       => 'varchar',
                'constraint' => 150,
            ];
            $this->dbforge->add_column('teks_berjalan', $fields);
        }

        // Hapus menu SID dan Donasi
        $this->db->where('id', 16)->delete('setting_modul');
        $this->db->where('id', 19)->delete('setting_modul');

        $fields            = $this->db->field_data('tweb_penduduk');
        $lookup            = array_column($fields, null, 'name');   // re-index by 'name'
        $field_berat_lahir = $lookup['berat_lahir'];
        if (strtolower($field_berat_lahir->type) == 'varchar') {
            // Ubah berat lahir dari kg menjadi gram
            $list_penduduk = $this->db->select('id, berat_lahir')->get('tweb_penduduk')->result_array();

            foreach ($list_penduduk as $penduduk) {
                // Kolom berat_lahir tersimpan sebagai varchar
                $berat_lahir = (float) str_replace(',', '.', preg_replace('/[^0-9,\.]/', '', $penduduk['berat_lahir']));
                if ($berat_lahir < 100.0) {
                    $berat_lahir = (int) ($berat_lahir * 1000.0);
                    $this->db->where('id', $penduduk['id'])->update('tweb_penduduk', ['berat_lahir' => $berat_lahir]);
                }
            }
            // Ganti kolom berat_lahir menjadi bilangan
            $this->dbforge->modify_column('tweb_penduduk', ['berat_lahir' => ['type' => 'SMALLINT']]);
        }
        // Di tweb_penduduk ubah kelahiran_anak_ke supaya default NULL
        $this->dbforge->modify_column('tweb_penduduk', ['kelahiran_anak_ke' => ['type' => 'TINYINT', 'constraint' => 2, 'default' => null]]);

        // Ubah kolom tweb_penduduk supaya boleh null
        $fields           = [];
        $fields['ktp_el'] = [
            'type'       => 'TINYINT',
            'constraint' => 4,
            'null'       => true,
            'default'    => null,
        ];
        $fields['status_rekam'] = [
            'type'       => 'TINYINT',
            'constraint' => 4,
            'null'       => true,
            'default'    => null,
        ];
        $fields['tempat_dilahirkan'] = [
            'type'       => 'TINYINT',
            'constraint' => 2,
            'null'       => true,
            'default'    => null,
        ];
        $fields['jenis_kelahiran'] = [
            'type'       => 'TINYINT',
            'constraint' => 2,
            'null'       => true,
            'default'    => null,
        ];
        $fields['penolong_kelahiran'] = [
            'type'       => 'TINYINT',
            'constraint' => 2,
            'null'       => true,
            'default'    => null,
        ];
        $fields['panjang_lahir'] = [
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
            'default'    => null,
        ];
        $fields['sakit_menahun_id'] = [
            'type'       => 'INT',
            'constraint' => 11,
            'null'       => true,
            'default'    => null,
        ];
        $this->dbforge->modify_column('tweb_penduduk', $fields);
    }

    private function migrasi_1904_ke_1905()
    {
        // Tambah kolom penduduk
        if (!$this->db->field_exists('tag_id_card', 'tweb_penduduk')) {
            // Tambah kolom
            $fields                = [];
            $fields['tag_id_card'] = [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'default'    => null,
            ];
            $this->dbforge->add_column('tweb_penduduk', $fields);
        }
        // Tambah form admin aparatur desa
        $this->db->where('isi', 'aparatur_desa.php')->update('widget', ['form_admin' => 'web_widget/admin/aparatur_desa']);
        // Konversi data suplemen terdata ke id
        $jml = $this->db->select('count(id) as jml')
            ->where('id_terdata <>', '0')
            ->where('char_length(id_terdata) <> 16')
            ->get('suplemen_terdata')
            ->row()->jml;
        if ($jml == 0) {
            $terdata = $this->db->select('s.id as s_id, s.id_terdata, s.sasaran,
	  		(case when s.sasaran = 1 then p.id else k.id end) as id')
                ->from('suplemen_terdata s')
                ->join('tweb_keluarga k', 'k.no_kk = s.id_terdata', 'left')
                ->join('tweb_penduduk p', 'p.nik = s.id_terdata', 'left')
                ->get()
                ->result_array();

            foreach ($terdata as $data) {
                $this->db
                    ->where('id', $data['s_id'])
                    ->update('suplemen_terdata', ['id_terdata' => $data['id']]);
            }
        }

        $this->db->where('id', 62)->update('setting_modul', ['url' => 'gis/clear', 'aktif' => '1']);
        // Tambah surat keterangan penghasilan orangtua
        $data = [
            'nama'       => 'Keterangan Penghasilan Orangtua',
            'url_surat'  => 'surat_ket_penghasilan_orangtua',
            'kode_surat' => 'S-42',
            'jenis'      => 1,
        ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);
    }

    private function migrasi_1903_ke_1904()
    {
        $this->db->where('id', 59)->update('setting_modul', ['url' => 'dokumen_sekretariat/clear/2', 'aktif' => '1']);
        $this->db->where('id', 60)->update('setting_modul', ['url' => 'dokumen_sekretariat/clear/3', 'aktif' => '1']);
        // Tambah tabel agenda
        $tb = 'agenda';
        if (!$this->db->table_exists($tb)) {
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'id_artikel' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'tgl_agenda' => [
                    'type' => 'timestamp',
                ],
                'koordinator_kegiatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'lokasi_kegiatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($tb, false, ['ENGINE' => $this->engine]);
            $this->dbforge->add_column(
                'agenda',
                ['CONSTRAINT `id_artikel_fk` FOREIGN KEY (`id_artikel`) REFERENCES `artikel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE']
            );
        }
        // Pindahkan tgl_agenda kalau sudah sempat membuatnya
        if ($this->db->field_exists('tgl_agenda', 'artikel')) {
            $data = $this->db->select('id, tgl_agenda')->where('id_kategori', AGENDA)
                ->get('artikel')
                ->result_array();
            if (count($data)) {
                $artikel_agenda = [];

                foreach ($data as $agenda) {
                    $artikel_agenda[] = ['id_artikel' => $agenda['id'], 'tgl_agenda' => $agenda['tgl_agenda']];
                }
                $this->db->insert_batch('agenda', $artikel_agenda);
            }
            $this->dbforge->drop_column('artikel', 'tgl_agenda');
        }
        // Tambah tombol media sosial whatsapp
        $query = "
			INSERT INTO media_sosial (id, gambar, link, nama, enabled) VALUES ('6', 'wa.png', '', 'WhatsApp', '1')
			ON DUPLICATE KEY UPDATE
				gambar = VALUES(gambar),
				nama = VALUES(nama)";
        $this->db->query($query);
        // Tambahkan setting aplikasi untuk mengubah warna tema komponen Admin
        $query = $this->db->select('1')->where('key', 'warna_tema_admin')->get('setting_aplikasi');
        if (!$query->result()) {
            $data = [
                'key'        => 'warna_tema_admin',
                'value'      => $query->value ?? 'skin-purple',
                'jenis'      => 'option-value',
                'keterangan' => 'Warna dasar tema komponen Admin',
            ];
            $this->db->insert('setting_aplikasi', $data);
            $setting_id = $this->db->insert_id();
            $this->db->insert_batch(
                'setting_aplikasi_options',
                [
                    ['id_setting' => $setting_id, 'value' => 'skin-blue'],
                    ['id_setting' => $setting_id, 'value' => 'skin-blue-light'],
                    ['id_setting' => $setting_id, 'value' => 'skin-yellow'],
                    ['id_setting' => $setting_id, 'value' => 'skin-yellow-light'],
                    ['id_setting' => $setting_id, 'value' => 'skin-green'],
                    ['id_setting' => $setting_id, 'value' => 'skin-green-light'],
                    ['id_setting' => $setting_id, 'value' => 'skin-purple'],
                    ['id_setting' => $setting_id, 'value' => 'skin-purple-light'],
                    ['id_setting' => $setting_id, 'value' => 'skin-red'],
                    ['id_setting' => $setting_id, 'value' => 'skin-red-light'],
                    ['id_setting' => $setting_id, 'value' => 'skin-black'],
                    ['id_setting' => $setting_id, 'value' => 'skin-black-light'],
                ]
            );
        }
    }

    private function nop()
    {
        // Migrasi kosong
    }

    private function migrasi_1901_ke_1902()
    {
        // Ubah judul status hubungan dalam keluarga
        $this->db->where('id', 9)->update('tweb_penduduk_hubungan', ['nama' => 'FAMILI']);
        // Perpanjang nomor surat di surat masuk dan keluar
        $this->dbforge->modify_column('surat_masuk', ['nomor_surat' => ['name' => 'nomor_surat', 'type' => 'VARCHAR',  'constraint' => 35]]);
        $this->dbforge->modify_column('surat_keluar', ['nomor_surat' => ['name' => 'nomor_surat', 'type' => 'VARCHAR',  'constraint' => 35]]);
        // Tambah setting program bantuan yg ditampilkan di dashboard
        $query = $this->db->select('1')->where('key', 'dashboard_program_bantuan')->get('setting_aplikasi');
        $query->result() || $this->db->insert('setting_aplikasi', ['key' => 'dashboard_program_bantuan', 'value' => '1	', 'jenis' => 'int', 'keterangan' => 'ID program bantuan yang ditampilkan di dashboard', 'kategori' => 'dashboard']);
        // Tambah setting panjang nomor surat
        $query = $this->db->select('1')->where('key', 'panjang_nomor_surat')->get('setting_aplikasi');
        $query->result() || $this->db->insert('setting_aplikasi', ['key' => 'panjang_nomor_surat', 'value' => '', 'jenis' => 'int', 'keterangan' => "Nomor akan diisi '0' di sebelah kiri, kalau perlu", 'kategori' => 'surat']);
        // Tambah rincian pindah di log_penduduk
        $tb_option = 'ref_pindah';
        if (!$this->db->table_exists($tb_option)) {
            $this->dbforge->add_field([
                'id' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($tb_option, false, ['ENGINE' => $this->engine]);
            $this->db->insert_batch(
                $tb_option,
                [
                    ['id' => 1, 'nama' => 'Pindah keluar Desa/Kelurahan'],
                    ['id' => 2, 'nama' => 'Pindah keluar Kecamatan'],
                    ['id' => 3, 'nama' => 'Pindah keluar Kabupaten/Kota'],
                    ['id' => 4, 'nama' => 'Pindah keluar Provinsi'],
                ]
            );
        }
        if (!$this->db->field_exists('ref_pindah', 'log_penduduk')) {
            // Tambah kolom
            $fields               = [];
            $fields['ref_pindah'] = [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'default'    => 1,
            ];
            $this->dbforge->add_column('log_penduduk', $fields);
            $this->dbforge->add_column(
                'log_penduduk',
                ['CONSTRAINT `id_ref_pindah` FOREIGN KEY (`ref_pindah`) REFERENCES `ref_pindah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE']
            );
        }
    }

    private function migrasi_1812_ke_1901()
    {
        // Tambah status dasar 'Tidak Valid'
        $data = [
            'id'   => 9,
            'nama' => 'TIDAK VALID',
        ];
        $sql = $this->db->insert_string('tweb_status_dasar', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				id = VALUES(id),
				nama = VALUES(nama)';
        $this->db->query($sql);
        // Tambah kolom tweb_desa_pamong
        if (!$this->db->field_exists('no_hp', 'komentar')) {
            // Tambah kolom
            $fields          = [];
            $fields['no_hp'] = [
                'type'       => 'varchar',
                'constraint' => 15,
                'default'    => null,
            ];
            $this->dbforge->add_column('komentar', $fields);
        }

        // Tambah kolom tweb_desa_pamong
        if (!$this->db->field_exists('pamong_pangkat', 'tweb_desa_pamong')) {
            // Tambah kolom
            $fields                = [];
            $fields['pamong_niap'] = [
                'type'       => 'varchar',
                'constraint' => 20,
                'default'    => null,
            ];
            $fields['pamong_pangkat'] = [
                'type'       => 'varchar',
                'constraint' => 20,
                'default'    => null,
            ];
            $fields['pamong_nohenti'] = [
                'type'       => 'varchar',
                'constraint' => 20,
                'default'    => null,
            ];
            $fields['pamong_tglhenti'] = [
                'type'    => 'date',
                'default' => null,
            ];
            $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }

        // Urut tabel tweb_desa_pamong
        if (!$this->db->field_exists('urut', 'tweb_desa_pamong')) {
            // Tambah kolom
            $fields         = [];
            $fields['urut'] = [
                'type'       => 'int',
                'constraint' => 5,
            ];
            $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }
        $this->db->where('id', 18)->update('setting_modul', ['url' => 'pengurus/clear', 'aktif' => '1']);
        $this->db->where('id', 48)->update('setting_modul', ['url' => 'web_widget/clear', 'aktif' => '1']);
    }

    private function migrasi_1811_ke_1812()
    {
        // Ubah struktur tabel tweb_desa_pamong
        if (!$this->db->field_exists('id_pend', 'tweb_desa_pamong')) {
            // Tambah kolom
            $fields            = [];
            $fields['id_pend'] = [
                'type'       => 'int',
                'constraint' => 11,
            ];
            $fields['pamong_tempatlahir'] = [
                'type'       => 'varchar',
                'constraint' => 100,
                'default'    => null,
            ];
            $fields['pamong_tanggallahir'] = [
                'type'    => 'date',
                'default' => null,
            ];
            $fields['pamong_sex'] = [
                'type'       => 'tinyint',
                'constraint' => 4,
                'default'    => null,
            ];
            $fields['pamong_pendidikan'] = [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => null,
            ];
            $fields['pamong_agama'] = [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => null,
            ];
            $fields['pamong_nosk'] = [
                'type'       => 'varchar',
                'constraint' => 20,
                'default'    => null,
            ];
            $fields['pamong_tglsk'] = [
                'type'    => 'date',
                'default' => null,
            ];
            $fields['pamong_masajab'] = [
                'type'       => 'varchar',
                'constraint' => 120,
                'default'    => null,
            ];
            $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }

        // Pada tweb_keluarga kosongkan nik_kepala kalau tdk ada penduduk dgn kk_level=1 dan id=nik_kepala untuk keluarga itu
        $kk_kosong = $this->db->select('k.id')
            ->where('p.id is NULL')
            ->from('tweb_keluarga k')
            ->join('tweb_penduduk p', 'p.id = k.nik_kepala and p.kk_level = 1', 'left')
            ->get()->result_array();

        foreach ($kk_kosong as $kk) {
            $this->db->where('id', $kk['id'])->update('tweb_keluarga', ['nik_kepala' => null]);
        }

        // Tambah surat keterangan domisili
        $data = [
            'nama'       => 'Keterangan Domisili',
            'url_surat'  => 'surat_ket_domisili',
            'kode_surat' => 'S-41',
            'jenis'      => 1,
        ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);

        $query = $this->db->select('1')->where('key', 'web_artikel_per_page')->get('setting_aplikasi');
        $query->result() || $this->db->insert('setting_aplikasi', ['key' => 'web_artikel_per_page', 'value' => 8, 'jenis' => 'int', 'keterangan' => 'Jumlah artikel dalam satu halaman', 'kategori' => 'web_theme']);

        $this->db->where('id', 42)->update('setting_modul', ['url' => 'modul/clear', 'aktif' => '1']);

        // tambah setting penomoran_surat
        if ($this->setting->penomoran_surat == null) {
            $setting = $this->db->select('value')
                ->where('key', 'nomor_terakhir_semua_surat')
                ->get('setting_aplikasi')
                ->row();
            $this->db->insert(
                'setting_aplikasi',
                [
                    'key'        => 'penomoran_surat',
                    'value'      => $setting->value ?: 2,
                    'jenis'      => 'option',
                    'keterangan' => 'Penomoran surat mulai dari satu (1) setiap tahun',
                ]
            );
            // Hapus setting nomor_terakhir_semua_surat
            $this->db->where('key', 'nomor_terakhir_semua_surat')->delete('setting_aplikasi');
        }

        $tb_option = 'setting_aplikasi_options';
        if (!$this->db->table_exists($tb_option)) {
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'id_setting' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => false,
                ],
                'value' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 512,
                ],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($tb_option, false, ['ENGINE' => $this->engine]);
            $this->dbforge->add_column(
                $tb_option,
                ['CONSTRAINT `id_setting_fk` FOREIGN KEY (`id_setting`) REFERENCES `setting_aplikasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE']
            );
        }

        $set = $this->db->select('s.id,o.id oid')
            ->where('key', 'penomoran_surat')
            ->join("{$tb_option} o", 's.id=o.id_setting', 'LEFT')
            ->get('setting_aplikasi s')
            ->row();
        if (!$set->oid) {
            $this->db->insert_batch(
                $tb_option,
                [
                    ['id' => 1, 'id_setting' => $set->id, 'value' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan'],
                    ['id' => 2, 'id_setting' => $set->id, 'value' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama'],
                    ['id' => 3, 'id_setting' => $set->id, 'value' => 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar'],
                ]
            );
        }
    }

    private function migrasi_1810_ke_1811()
    {
        // Ubah url untuk Admin Web > Artikel, Admin Web > Dokumen, Admin Web > Menu,
        // Admin Web > Komentar
        $this->db->where('id', 47)->update('setting_modul', ['url' => 'web/clear', 'aktif' => '1']);
        $this->db->where('id', 52)->update('setting_modul', ['url' => 'dokumen/clear', 'aktif' => '1']);
        $this->db->where('id', 50)->update('setting_modul', ['url' => 'komentar/clear', 'aktif' => '1']);
        $this->db->where('id', 49)->update('setting_modul', ['url' => 'menu/clear', 'aktif' => '1']);
        $this->db->where('id', 20)->update('setting_modul', ['url' => 'sid_core/clear', 'aktif' => '1']);
        // Ubah nama kolom 'nik' menjadi 'id_pend' dan hanya gunakan untuk pemilik desa
        if ($this->db->field_exists('nik', 'data_persil')) {
            $data = $this->db->select('d.*, d.nik as nama_pemilik, p.id as id_pend')
                ->from('data_persil d')
                ->join('tweb_penduduk p', 'p.nik = d.nik', 'left')
                ->get()->result_array();

            foreach ($data as $persil) {
                $tulis = [];
                // Kalau pemilik luar pindahkan isi kolom 'nik' sebagai nama pemilik luar
                if ($persil['jenis_pemilik'] == 2 && empty($persil['pemilik_luar'])) {
                    $tulis['pemilik_luar'] = $persil['nama_pemilik'];
                    $tulis['nik']          = null;
                } else { // Untuk pemilik desa ganti menjadi id penduduk
                    $tulis['nik'] = $persil['id_pend'];
                }
                $this->db->where('id', $persil['id'])->update('data_persil', $tulis);
            }
            // Tambahkan relational constraint
            $this->dbforge->modify_column(
                'data_persil',
                ['nik' => ['name' => 'id_pend',    'type' => 'int', 'constraint' => 11]]
            );
            $this->db->query('ALTER TABLE `data_persil` ADD INDEX `id_pend` (`id_pend`)');
            $this->dbforge->add_column('data_persil', [
                'CONSTRAINT `persil_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
            ]);
        }
        // Hapus kolom tweb_penduduk_mandiri.nik
        if ($this->db->field_exists('nik', 'tweb_penduduk_mandiri')) {
            $this->dbforge->drop_column('tweb_penduduk_mandiri', 'nik');
        }
        //menambahkan constraint kolom tabel
        $sql = "SELECT *
	    FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
	    WHERE CONSTRAINT_NAME = 'id_pend_fk'
			AND TABLE_NAME = 'tweb_penduduk_mandiri'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            $this->dbforge->add_column('tweb_penduduk_mandiri', [
                'CONSTRAINT `id_pend_fk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
            ]);
        }

        // Tambah perubahan database di sini
        // Tambah setting tombol_cetak_surat
        $setting = $this->db->where('key', 'tombol_cetak_surat')->get('setting_aplikasi')->row()->id;
        if (!$setting) {
            $this->db->insert('setting_aplikasi', ['key' => 'tombol_cetak_surat', 'value' => false, 'jenis' => 'boolean', 'keterangan' => 'Tampilkan tombol cetak langsung di form surat']);
        }
    }

    private function migrasi_1809_ke_1810()
    {
        // Tambah tabel surat_keluar
        //Perbaiki url untuk modul Surat Keluar
        $this->db->where('id', 58)->update('setting_modul', ['url' => 'surat_keluar/clear', 'aktif' => '1']);
        if (!$this->db->table_exists('surat_keluar')) {
            $query = '
				CREATE TABLE `surat_keluar` (
					`id` int NOT NULL AUTO_INCREMENT,
					`nomor_urut` smallint(5),
					`nomor_surat` varchar(20),
					`kode_surat` varchar(10),
					`tanggal_surat` date NOT NULL,
					`tanggal_catat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`tujuan` varchar(100),
					`isi_singkat` varchar(200),
					`berkas_scan` varchar(100),
					PRIMARY KEY  (`id`)
				);
			';
            $this->db->query($query);
        }

        // Tambah klasifikasi surat
        if (!$this->db->table_exists('klasifikasi_surat')) {
            $data = [
                'id'         => '63',
                'modul'      => 'Klasfikasi Surat',
                'url'        => 'klasifikasi/clear',
                'aktif'      => '1',
                'ikon'       => 'fa-code',
                'urut'       => '10',
                'level'      => '2',
                'parent'     => '15',
                'hidden'     => '0',
                'ikon_kecil' => 'fa-code',
            ];
            $sql = $this->db->insert_string('setting_modul', $data) . ' ON DUPLICATE KEY UPDATE url=VALUES(url)';
            $this->db->query($sql);

            $query = "
			CREATE TABLE IF NOT EXISTS `klasifikasi_surat` (
			  `id` int(4) NOT NULL AUTO_INCREMENT,
			  `kode` varchar(50) NOT NULL,
			  `nama` varchar(250) NOT NULL,
			  `uraian` mediumtext NOT NULL,
				`enabled` int(2) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`)
			)";
            $this->db->query($query);
            // Impor klasifikasi dari berkas csv
            $this->load->model('klasifikasi_model');
            $this->klasifikasi_model->impor(FCPATH . 'assets/import/klasifikasi_surat.csv');
        }

        //Perbaiki url untuk modul Surat Masuk dan Arsip Layanan
        $this->db->where('url', 'surat_masuk')->update('setting_modul', ['url' => 'surat_masuk/clear']);
        $this->db->where('url', 'keluar')->update('setting_modul', ['url' => 'keluar/clear']);
        //Perbaiki ikon untuk modul Sekretariat
        $this->db->where('url', 'sekretariat')->update('setting_modul', ['ikon' => 'fa-archive']);
        // Buat view untuk penduduk hidup -- untuk memudahkan query
        if (!$this->db->table_exists('penduduk_hidup')) {
            $this->db->query('CREATE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1');
        }
        // update jenis pekerjaan PETANI/PERKEBUNAN ke 'PETANI/PEKEBUN'
        // sesuai dengan issue https://github.com/OpenSID/OpenSID/issues/999
        if ($this->db->table_exists('tweb_penduduk_pekerjaan')) {
            $this->db->where('nama', 'PETANI/PERKEBUNAN')->update(
                'tweb_penduduk_pekerjaan',
                ['nama' => 'PETANI/PEKEBUN']
            );
        }
        // buat tabel disposisi dengan relasi ke surat masuk dan tweb_desa_pamong
        if (!$this->db->table_exists('disposisi_surat_masuk')) {
            $sql = [
                'id_disposisi' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'id_surat_masuk' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => false,
                ],
                'id_desa_pamong' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => false,
                    'null'       => true,
                ],
                'disposisi_ke' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
            ];
            $this->dbforge->add_field($sql);
            $this->dbforge->add_key('id_disposisi', true);
            $this->dbforge->create_table('disposisi_surat_masuk', false, ['ENGINE' => $this->engine]);

            //menambahkan constraint kolom tabel
            $this->dbforge->add_column('disposisi_surat_masuk', [
                'CONSTRAINT `id_surat_fk` FOREIGN KEY (`id_surat_masuk`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
                'CONSTRAINT `desa_pamong_fk` FOREIGN KEY (`id_desa_pamong`) REFERENCES `tweb_desa_pamong` (`pamong_id`) ON DELETE CASCADE ON UPDATE CASCADE',
            ]);

            if ($this->db->field_exists('disposisi_kepada', 'surat_masuk')) {
                // ambil semua data surat masuk
                $data = $this->db->select()->from('surat_masuk')->get()->result();

                // konversi data yang diperlukan
                // ke table disposisi_surat_masuk
                foreach ($data as $value) {
                    $data_pamong = $this->db->select('pamong_id')
                        ->from('tweb_desa_pamong')
                        ->where('jabatan', $value->disposisi_kepada)
                        ->get()->row();

                    $this->db->insert(
                        'disposisi_surat_masuk',
                        [
                            'id_surat_masuk' => $value->id,
                            'id_desa_pamong' => $data_pamong->pamong_id,
                            'disposisi_ke'   => $value->disposisi_kepada,
                        ]
                    );
                }
                // hapus kolom disposisi dari surat masuk
                $this->dbforge->drop_column('surat_masuk', 'disposisi_kepada');
            }
        }
    }

    private function migrasi_1808_ke_1809()
    {
        // Hapus tabel inventaris lama
        $query = 'DROP TABLE IF EXISTS mutasi_inventaris;';
        $this->db->query($query);
        $query = 'DROP TABLE IF EXISTS inventaris;';
        $this->db->query($query);
        $query = 'DROP TABLE IF EXISTS jenis_barang;';
        $this->db->query($query);

        // Siapkan warna polygon dan line supaya tampak di tampilan-admin baru
        $sql = "UPDATE polygon SET color = CONCAT('#', color)
				WHERE color NOT LIKE '#%' AND color <> ''
		";
        $this->db->query($sql);
        $sql = "UPDATE line SET color = CONCAT('#', color)
				WHERE color NOT LIKE '#%' AND color <> ''
		";
        $this->db->query($sql);

        // Tambahkan perubahan menu untuk tampilan-admin baru
        if (!$this->db->field_exists('parent', 'setting_modul') || strpos($this->getCurrentVersion(), '18.08') !== false) {
            if (!$this->db->field_exists('parent', 'setting_modul')) {
                $fields           = [];
                $fields['parent'] = [
                    'type'       => 'int',
                    'constraint' => 2,
                    'null'       => false,
                    'default'    => 0,
                ];
                $this->dbforge->add_column('setting_modul', $fields);
            }

            $this->db->truncate('setting_modul');
            $query = "
		INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `parent`, `hidden`, `ikon_kecil`) VALUES
		('1', 'Home', 'hom_sid', '1', 'fa-home', '1', '2', '0', '1', 'fa fa-home'),
		('200', 'Info [Desa]', 'hom_desa', '1', 'fa-dashboard', '2', '2', '0', '1', 'fa fa-home'),
		('2', 'Kependudukan', 'penduduk/clear', '1', 'fa-users', '3', '2', '0', '0', 'fa fa-users'),
		('3', 'Statistik', 'statistik', '1', 'fa-line-chart', '4', '2', '0', '0', 'fa fa-line-chart'),
		('4', 'Layanan Surat', 'surat', '1', 'fa-book', '5', '2', '0', '0', 'fa fa-book'),
		('5', 'Analisis', 'analisis_master/clear', '1', '   fa-check-square-o', '6', '2', '0', '0', 'fa fa-check-square-o'),
		('6', 'Bantuan', 'program_bantuan/clear', '1', 'fa-heart', '7', '2', '0', '0', 'fa fa-heart'),
		('7', 'Pertanahan', 'data_persil/clear', '1', 'fa-map-signs', '8', '2', '0', '0', 'fa fa-map-signs'),
		('8', 'Pengaturan Peta', 'plan', '1', 'fa-location-arrow', '9', '2', '9', '0', 'fa fa-location-arrow'),
		('9', 'Pemetaan', 'gis', '1', 'fa-globe', '10', '2', '0', '0', 'fa fa-globe'),
		('10', 'SMS', 'sms', '1', 'fa-envelope', '11', '2', '0', '0', 'fa fa-envelope'),
		('11', 'Pengaturan', 'man_user/clear', '1', 'fa-users', '12', '1', '0', '1', 'fa-users'),
		('13', 'Admin Web', 'web', '1', 'fa-desktop', '14', '4', '0', '0', 'fa fa-desktop'),
		('14', 'Layanan Mandiri', 'lapor', '1', 'fa-inbox', '15', '2', '0', '0', 'fa fa-inbox'),
		('15', 'Sekretariat', 'sekretariat', '1', 'fa-archive', '5', '2', '0', '0', 'fa fa-archive'),
		('16', 'SID', 'hom_sid', '1', 'fa-globe', '1', '2', '1', '0', ''),
		('17', 'Identitas [Desa]', 'hom_desa/konfigurasi', '1', 'fa-id-card', '2', '2', '200', '0', ''),
		('18', 'Pemerintahan [Desa]', 'pengurus', '1', 'fa-sitemap', '3', '2', '200', '0', ''),
		('19', 'Donasi', 'hom_sid/donasi', '1', 'fa-money', '4', '2', '1', '0', ''),
		('20', 'Wilayah Administratif', 'sid_core', '1', 'fa-map', '2', '2', '200', '0', ''),
		('21', 'Penduduk', 'penduduk/clear', '1', 'fa-user', '2', '2', '2', '0', ''),
		('22', 'Keluarga', 'keluarga/clear', '1', 'fa-users', '3', '2', '2', '0', ''),
		('23', 'Rumah Tangga', 'rtm/clear', '1', 'fa-venus-mars', '4', '2', '2', '0', ''),
		('24', 'Kelompok', 'kelompok/clear', '1', 'fa-sitemap', '5', '2', '2', '0', ''),
		('25', 'Data Suplemen', 'suplemen', '1', 'fa-slideshare', '6', '2', '2', '0', ''),
		('26', 'Calon Pemilih', 'dpt/clear', '1', 'fa-podcast', '7', '2', '2', '0', ''),
		('27', 'Statistik Kependudukan', 'statistik', '1', 'fa-bar-chart', '1', '2', '3', '0', ''),
		('28', 'Laporan Bulanan', 'laporan/clear', '1', 'fa-file-text', '2', '2', '3', '0', ''),
		('29', 'Laporan Kelompok Rentan', 'laporan_rentan/clear', '1', 'fa-wheelchair', '3', '2', '3', '0', ''),
		('30', 'Pengaturan Surat', 'surat_master/clear', '1', 'fa-cog', '1', '2', '4', '0', ''),
		('31', 'Cetak Surat', 'surat', '1', 'fa-files-o', '2', '2', '4', '0', ''),
		('32', 'Arsip Layanan', 'keluar', '1', 'fa-folder-open', '3', '2', '4', '0', ''),
		('33', 'Panduan', 'surat/panduan', '1', 'fa fa-book', '4', '2', '4', '0', ''),
		('39', 'SMS', 'sms', '1', 'fa-envelope-open-o', '1', '2', '10', '0', ''),
		('40', 'Daftar Kontak', 'sms/kontak', '1', 'fa-id-card-o', '2', '2', '10', '0', ''),
		('41', 'Pengaturan SMS', 'sms/setting', '1', 'fa-gear', '3', '2', '10', '0', ''),
		('42', 'Modul', 'modul', '1', 'fa-tags', '1', '1', '11', '0', ''),
		('43', 'Aplikasi', 'setting', '1', 'fa-codepen', '2', '1', '11', '0', ''),
		('44', 'Pengguna', 'man_user', '1', 'fa-users', '3', '1', '11', '0', ''),
		('45', 'Database', 'database', '1', 'fa-database', '4', '1', '11', '0', ''),
		('46', 'Info Sistem', 'setting/info_sistem', '1', 'fa-server', '5', '1', '11', '0', ''),
		('47', 'Artikel', 'web/index/1', '1', 'fa-file-movie-o', '1', '4', '13', '0', ''),
		('48', 'Widget', 'web_widget', '1', 'fa-windows', '2', '4', '13', '0', ''),
		('49', 'Menu', 'menu/index/1', '1', 'fa-bars', '3', '4', '13', '0', ''),
		('50', 'Komentar', 'komentar', '1', 'fa-comments', '4', '4', '13', '0', ''),
		('51', 'Galeri', 'gallery', '1', 'fa-image', '5', '5', '13', '0', ''),
		('52', 'Dokumen', 'dokumen', '1', 'fa-file-text', '6', '4', '13', '0', ''),
		('53', 'Media Sosial', 'sosmed', '1', 'fa-facebook', '7', '4', '13', '0', ''),
		('54', 'Slider', 'web/slider', '1', 'fa-film', '8', '4', '13', '0', ''),
		('55', 'Laporan Masuk', 'lapor', '1', 'fa-wechat', '1', '2', '14', '0', ''),
		('56', 'Pendaftar Layanan Mandiri', 'mandiri/clear', '1', 'fa-500px', '2', '2', '14', '0', ''),
		('57', 'Surat Masuk', 'surat_masuk', '1', 'fa-sign-in', '1', '2', '15', '0', ''),
		('58', 'Surat Keluar', '', '2', 'fa-sign-out', '2', '2', '15', '0', ''),
		('59', 'SK Kades', 'dokumen_sekretariat/index/2', '1', 'fa-legal', '3', '2', '15', '0', ''),
		('60', 'Perdes', 'dokumen_sekretariat/index/3', '1', 'fa-newspaper-o', '4', '2', '15', '0', ''),
		('61', 'Inventaris', 'inventaris_tanah', '1', 'fa-cubes', '5', '2', '15', '0', ''),
		('62', 'Peta', 'gis', '1', 'fa-globe', '1', '2', '9', '0', 'fa fa-globe');
	  ";
            $this->db->query($query);
        }

        if ($this->db->table_exists('anggota_grup_kontak')) {
            return;
        }
        // Perubahan tabel untuk modul SMS
        // buat table anggota_grup_kontak
        $sql = [
            'id_grup_kontak' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'id_grup' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
            ],
            'id_kontak' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
            ],
        ];
        $this->dbforge->add_field($sql);
        $this->dbforge->add_key('id_grup_kontak', true);
        $this->dbforge->create_table('anggota_grup_kontak', false, ['ENGINE' => $this->engine]);

        //perbaikan penamaan grup agar tidak ada html url code
        $this->db->query("UPDATE kontak_grup SET nama_grup = REPLACE(nama_grup, '%20', ' ')");
        //memindahkan isi kontak_grup ke anggota_grup_kontak
        $this->db->query('INSERT INTO anggota_grup_kontak (id_grup, id_kontak) SELECT b.id as id_grup, a.id_kontak FROM kontak_grup a RIGHT JOIN (SELECT id,nama_grup FROM kontak_grup GROUP BY nama_grup) b on a.nama_grup = b.nama_grup WHERE a.id_kontak <> 0');
        //Memperbaiki record kontak_grup agar tidak duplikat
        $this->db->query('DELETE t1 FROM kontak_grup t1 INNER JOIN kontak_grup t2  WHERE t1.id > t2.id AND t1.nama_grup = t2.nama_grup');

        //modifikasi tabel kontak dan kontak_grup
        if ($this->db->field_exists('id', 'kontak')) {
            $this->dbforge->modify_column('kontak', ['id' => ['name' => 'id_kontak', 'type' => 'INT',  'auto_increment' => true]]);
        }
        if ($this->db->field_exists('id_kontak', 'kontak_grup')) {
            $this->dbforge->drop_column('kontak_grup', 'id_kontak');
        }
        if ($this->db->field_exists('id', 'kontak_grup')) {
            $this->dbforge->modify_column('kontak_grup', ['id' => ['name' => 'id_grup', 'type' => 'INT',  'auto_increment' => true]]);
        }

        //menambahkan constraint kolom tabel
        $this->dbforge->add_column('anggota_grup_kontak', [
            'CONSTRAINT `anggota_grup_kontak_ke_kontak` FOREIGN KEY (`id_kontak`) REFERENCES `kontak` (`id_kontak`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT `anggota_grup_kontak_ke_kontak_grup` FOREIGN KEY (`id_grup`) REFERENCES `kontak_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);
        $this->dbforge->add_column('kontak', [
            'CONSTRAINT `kontak_ke_tweb_penduduk` FOREIGN KEY (`id_pend`) REFERENCES `tweb_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);
        //buat view
        $this->db->query('DROP VIEW IF EXISTS `daftar_kontak`');
        $this->db->query("CREATE VIEW `daftar_kontak` AS select `a`.`id_kontak` AS `id_kontak`,`a`.`id_pend` AS `id_pend`,`b`.`nama` AS `nama`,`a`.`no_hp` AS `no_hp`,(case when (`b`.`sex` = '1') then 'Laki-laki' else 'Perempuan' end) AS `sex`,`b`.`alamat_sekarang` AS `alamat_sekarang` from (`kontak` `a` left join `tweb_penduduk` `b` on((`a`.`id_pend` = `b`.`id`)))");
        $this->db->query('DROP VIEW IF EXISTS `daftar_grup`');
        $this->db->query('CREATE VIEW `daftar_grup` AS select `a`.*,(select count(`anggota_grup_kontak`.`id_kontak`) from `anggota_grup_kontak` where (`a`.`id_grup` = `anggota_grup_kontak`.`id_grup`)) AS `jumlah_anggota` from `kontak_grup` `a`');
        $this->db->query('DROP VIEW IF EXISTS `daftar_anggota_grup`');
        $this->db->query('CREATE VIEW `daftar_anggota_grup` AS select `a`.`id_grup_kontak` AS `id_grup_kontak`,`a`.`id_grup` AS `id_grup`,`c`.`nama_grup` AS `nama_grup`,`b`.`id_kontak` AS `id_kontak`,`b`.`nama` AS `nama`,`b`.`no_hp` AS `no_hp`,`b`.`sex` AS `sex`,`b`.`alamat_sekarang` AS `alamat_sekarang` from ((`anggota_grup_kontak` `a` left join `daftar_kontak` `b` on((`a`.`id_kontak` = `b`.`id_kontak`))) left join `kontak_grup` `c` on((`a`.`id_grup` = `c`.`id_grup`)))');
    }

    private function migrasi_1806_ke_1807()
    {
        // Tambahkan perubahan database di sini
        // Tambah kolom di tabel data_persil

        // Tambah wna_lk, wna_pr di log_bulanan
        // dan ubah lk menjadi wni_lk, dan pr menjadi wni_pr
        if (!$this->db->field_exists('wni_pr', 'log_bulanan')) {
            $fields       = [];
            $fields['lk'] = [
                'name'       => 'wni_lk',
                'type'       => 'int',
                'constraint' => 11,
            ];
            $fields['pr'] = [
                'name'       => 'wni_pr',
                'type'       => 'int',
                'constraint' => 11,
            ];
            $this->dbforge->modify_column('log_bulanan', $fields);
            $fields           = [];
            $fields['wna_lk'] = [
                'type'       => 'int',
                'constraint' => 11,
            ];
            $fields['wna_pr'] = [
                'type'       => 'int',
                'constraint' => 11,
            ];
            $this->dbforge->add_column('log_bulanan', $fields);
        }

        if (!$this->db->table_exists('inventaris_tanah')) {
            $query = "
			CREATE TABLE `inventaris_tanah` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`nama_barang` varchar(255) NOT NULL,
				`kode_barang` varchar(64) NOT NULL,
				`register` varchar(64) NOT NULL,
				`luas` int(64) NOT NULL,
				`tahun_pengadaan` year(4) NOT NULL,
				`letak` varchar(255) NOT NULL,
				`hak` varchar(255) NOT NULL,
				`no_sertifikat` varchar(255) NOT NULL,
				`tanggal_sertifikat` date NOT NULL,
				`penggunaan` varchar(255) NOT NULL,
				`asal` varchar(255) NOT NULL,
				`harga` double NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`status` int(1) NOT NULL DEFAULT '0',
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('mutasi_inventaris_tanah')) {
            $query = "
			CREATE TABLE `mutasi_inventaris_tanah` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_inventaris_tanah` int(11),
				`jenis_mutasi` varchar(255) NOT NULL,
				`tahun_mutasi` date NOT NULL,
				`harga_jual` double NOT NULL,
				`sumbangkan` varchar(255) NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id),
				CONSTRAINT FK_mutasi_inventaris_tanah FOREIGN KEY (id_inventaris_tanah) REFERENCES inventaris_tanah(id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('inventaris_peralatan')) {
            $query = "
			CREATE TABLE `inventaris_peralatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`nama_barang` varchar(255) NOT NULL,
				`kode_barang` varchar(64) NOT NULL,
				`register` varchar(64) NOT NULL,
				`merk` varchar(255) NOT NULL,
				`ukuran`text NOT NULL,
				`bahan` text NOT NULL,
				`tahun_pengadaan` year(4) NOT NULL,
				`no_pabrik` varchar(255) NULL,
				`no_rangka` varchar(255) NULL,
				`no_mesin` varchar(255) NULL,
				`no_polisi` varchar(255) NULL,
				`no_bpkb` varchar(255) NULL,
				`asal` varchar(255) NOT NULL,
				`harga` double NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`status` int(1) NOT NULL DEFAULT '0',
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('mutasi_inventaris_peralatan')) {
            $query = "
			CREATE TABLE `mutasi_inventaris_peralatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_inventaris_peralatan` int(11),
				`jenis_mutasi` varchar(255) NOT NULL,
				`tahun_mutasi` date NOT NULL,
				`harga_jual` double NOT NULL,
				`sumbangkan` varchar(255) NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id),
				CONSTRAINT FK_mutasi_inventaris_peralatan FOREIGN KEY (id_inventaris_peralatan) REFERENCES inventaris_peralatan(id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('inventaris_gedung')) {
            $query = "
			CREATE TABLE `inventaris_gedung` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`nama_barang` varchar(255) NOT NULL,
				`kode_barang` varchar(64) NOT NULL,
				`register` varchar(64) NOT NULL,
				`kondisi_bangunan` varchar(255) NOT NULL,
				`kontruksi_bertingkat` varchar(255) NOT NULL,
				`kontruksi_beton` int(1) NOT NULL,
				`luas_bangunan` int(64) NOT NULL,
				`letak` varchar(255) NOT NULL,
				`tanggal_dokument`DATE NULL,
				`no_dokument` varchar(255) NULL,
				`luas` int(64) NULL,
				`status_tanah` varchar(255) NULL,
				`kode_tanah` varchar(255) NULL,
				`asal` varchar(255) NOT NULL,
				`harga` double NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`status` int(1) NOT NULL DEFAULT '0',
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('mutasi_inventaris_gedung')) {
            $query = "
			CREATE TABLE `mutasi_inventaris_gedung` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_inventaris_gedung` int(11),
				`jenis_mutasi` varchar(255) NOT NULL,
				`tahun_mutasi` date NOT NULL,
				`harga_jual` double NOT NULL,
				`sumbangkan` varchar(255) NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id),
				CONSTRAINT FK_mutasi_inventaris_gedung FOREIGN KEY (id_inventaris_gedung) REFERENCES inventaris_gedung(id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('inventaris_jalan')) {
            $query = "
			CREATE TABLE `inventaris_jalan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`nama_barang` varchar(255) NOT NULL,
				`kode_barang` varchar(64) NOT NULL,
				`register` varchar(64) NOT NULL,
				`kontruksi` varchar(255) NOT NULL,
				`panjang` int(64) NOT NULL,
				`lebar`int(64) NOT NULL,
				`luas` int(64) NOT NULL,
				`letak` text NULL,
				`tanggal_dokument` date NOT NULL,
				`no_dokument` varchar(255) DEFAULT NULL,
				`status_tanah` varchar(255) DEFAULT NULL,
				`kode_tanah` varchar(255) DEFAULT NULL,
				`kondisi` varchar(255) NOT NULL,
				`asal` varchar(255) NOT NULL,
				`harga` double NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`status` int(1) NOT NULL DEFAULT '0',
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('mutasi_inventaris_jalan')) {
            $query = "
			CREATE TABLE `mutasi_inventaris_jalan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_inventaris_jalan` int(11),
				`jenis_mutasi` varchar(255) NOT NULL,
				`tahun_mutasi` date NOT NULL,
				`harga_jual` double NOT NULL,
				`sumbangkan` varchar(255) NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id),
				CONSTRAINT FK_mutasi_inventaris_jalan FOREIGN KEY (id_inventaris_jalan) REFERENCES inventaris_jalan(id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('inventaris_asset')) {
            $query = "
			CREATE TABLE `inventaris_asset` (
				`id` int(11) AUTO_INCREMENT NOT NULL,
				`nama_barang` varchar(255) NOT NULL,
				`kode_barang` varchar(64) NOT NULL,
				`register` varchar(64) NOT NULL,
				`jenis` varchar(255) NOT NULL,
				`judul_buku` varchar(255) NULL,
				`spesifikasi_buku` varchar(255) NULL,
				`asal_daerah` varchar(255) NULL,
				`pencipta` varchar(255) NULL,
				`bahan` varchar(255) NULL,
				`jenis_hewan` varchar(255) NULL,
				`ukuran_hewan` varchar(255) NULL,
				`jenis_tumbuhan` varchar(255) NULL,
				`ukuran_tumbuhan` varchar(255) NULL,
				`jumlah` int(64) NOT NULL,
				`tahun_pengadaan` year(4) NOT NULL,
				`asal` varchar(255) NOT NULL,
				`harga` double NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`status` int(1) NOT NULL DEFAULT '0',
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('mutasi_inventaris_asset')) {
            $query = "
			CREATE TABLE `mutasi_inventaris_asset` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`id_inventaris_asset` int(11),
				`jenis_mutasi` varchar(255) NOT NULL,
				`tahun_mutasi` date NOT NULL,
				`harga_jual` double NOT NULL,
				`sumbangkan` varchar(255) NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id),
				CONSTRAINT FK_mutasi_inventaris_asset FOREIGN KEY (id_inventaris_asset) REFERENCES inventaris_asset(id)
			)
			";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('inventaris_kontruksi')) {
            $query = "
			CREATE TABLE `inventaris_kontruksi` (
				`id` int(11) AUTO_INCREMENT NOT NULL ,
				`nama_barang` varchar(255) NOT NULL,
				`kondisi_bangunan` varchar(255) NOT NULL,
				`kontruksi_bertingkat` varchar(255) NOT NULL,
				`kontruksi_beton` int(1) NOT NULL,
				`luas_bangunan` int(64) NOT NULL,
				`letak` varchar(255) NOT NULL,
				`tanggal_dokument` date DEFAULT NULL,
				`no_dokument` varchar(255) DEFAULT NULL,
				`tanggal` date DEFAULT NULL,
				`status_tanah` varchar(255) DEFAULT NULL,
				`kode_tanah` varchar(255) DEFAULT NULL,
				`asal` varchar(255) NOT NULL,
				`harga` double NOT NULL,
				`keterangan` text NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_by` int(11) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`updated_by` int(11) NOT NULL,
				`status` int(1) NOT NULL DEFAULT '0',
				`visible` int(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (id)
			)
			";
            $this->db->query($query);
        }

        $fields = [];
        if (!$this->db->field_exists('jenis_pemilik', 'data_persil')) {
            $fields['jenis_pemilik'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
                'null'       => false,
                'default'    => 1, // pemilik desa
            ];
        }
        if (!$this->db->field_exists('pemilik_luar', 'data_persil')) {
            $fields['pemilik_luar'] = [
                'type'       => 'varchar',
                'constraint' => 100,
            ];
        }
        $this->dbforge->add_column('data_persil', $fields);
        // Sesuaikan data pemilik luar desa yg sudah ada ke kolom baru
        if (count($fields) > 0) {
            $data = $this->db->get('data_persil')->result_array();

            foreach ($data as $persil) {
                if (!is_numeric($persil['nik']) && $persil['nik'] != '') {
                    $data_update = [
                        'jenis_pemilik' => '2',
                        'pemilik_luar'  => $persil['nik'],
                        'nik'           => 999,   // NIK_LUAR_DESA
                    ];
                    $this->db->where('id', $persil['id'])->update('data_persil', $data_update);
                }
            }
        }
        if ($this->db->field_exists('alamat_ext', 'data_persil')) {
            $fields               = [];
            $fields['alamat_ext'] = [
                'name'       => 'alamat_luar',
                'type'       => 'varchar',
                'constraint' => 100,
            ];
            $this->dbforge->modify_column('data_persil', $fields);
        }
    }

    private function migrasi_211_ke_1806()
    {
        //ambil nilai path
        $config = $this->db->get('config')->row();
        if (!empty($config)) {
            //Cek apakah path kosong atau tidak
            if (!empty($config->path)) {
                //Cek pola path yang lama untuk diganti dengan yang baru
                //Jika pola path masih yang lama, ganti dengan yang baru
                if (preg_match('/((\([-+]?[0-9]{1,3}\.[0-9]*,(\s)?[-+]?[0-9]{1,3}\.[0-9]*\))\;)/', $config->path)) {
                    $new_path = str_replace([');', '(', ']['], [']', '[', '],['], $config->path);
                    $this->db->where('id', $config->id)->update('config', ['path' => "[[{$new_path}]]"]);
                }
            }
            //Cek zoom agar tidak lebih dari 18 dan agar tidak kosong
            if (empty($config->zoom) || $config->zoom > 18 || $config->zoom == 0) {
                $this->db->where('id', $config->id)->update('config', ['zoom' => 10]);
            }
        }

        //Penambahan widget peta wilayah desa
        $widget = $this->db->select('id, isi')->where('isi', 'peta_wilayah_desa.php')->get('widget')->row();
        if (empty($widget)) {
            //Penambahan widget peta wilayah desa sebagai widget sistem
            $peta_wilayah = [
                'isi'          => 'peta_wilayah_desa.php',
                'enabled'      => 1,
                'judul'        => 'Peta Wilayah Desa',
                'jenis_widget' => 1,
                'urut'         => 1,
                'form_admin'   => 'hom_desa/konfigurasi',
            ];
            $this->db->insert('widget', $peta_wilayah);
        } else {
            // Paksa update karena sudah ada yang menggunakan versi pra-rilis sebelumnya
            $this->db->where('id', $widget->id)
                ->update('widget', ['form_admin' => 'hom_desa/konfigurasi']);
        }

        //ubah icon kecil dan besar untuk modul Sekretariat
        $this->db->where('url', 'sekretariat')->update('setting_modul', ['ikon' => 'document-open-8.png', 'ikon_kecil' => 'fa fa-file fa-lg']);
        // Hapus kolom yg tidak digunakan
        if ($this->db->field_exists('alamat_tempat_lahir', 'tweb_penduduk')) {
            $this->dbforge->drop_column('tweb_penduduk', 'alamat_tempat_lahir');
        }
    }

    private function migrasi_210_ke_211()
    {
        // Tambah kolom jenis untuk analisis_master
        $fields = [];
        if (!$this->db->field_exists('jenis', 'analisis_master')) {
            $fields['jenis'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
                'null'       => false,
                'default'    => 2, // bukan bawaan sistem
            ];
        }
        $this->dbforge->add_column('analisis_master', $fields);
        // Impor analisis Data Dasar Keluarga kalau belum ada.
        // Ubah versi pra-rilis yang sudah diganti menjadi non-sistem
        $ddk_lama = $this->db->where('kode_analisis', 'DDKPD')->where('jenis', 1)
            ->get('analisis_master')->row();
        if ($ddk_lama) {
            $this->db->where('id', $ddk_lama->id)
                ->update('analisis_master', ['jenis' => 2, 'nama' => '[kadaluarsa] ' . $ddk_lama->nama]);
        }
        $query = $this->db->where('kode_analisis', 'DDK02')
            ->get('analisis_master')->result_array();
        if (count($query) == 0) {
            $file_analisis                                                             = FCPATH . 'assets/import/analisis_DDK_Profil_Desa.xlsx';
            $this->analisis_import_model->import_excel($file_analisis, 'DDK02', $jenis = 1);
        }
        // Impor analisis Data Anggota Keluarga kalau belum ada
        // Ubah versi pra-rilis yang sudah diganti menjadi non-sistem
        $dak_lama = $this->db->where('kode_analisis', 'DAKPD')->where('jenis', 1)
            ->get('analisis_master')->row();
        if ($dak_lama) {
            $this->db->where('id', $dak_lama->id)
                ->update('analisis_master', ['jenis' => 2, 'nama' => '[kadaluarsa] ' . $dak_lama->nama]);
        }
        $dak = $this->db->where('kode_analisis', 'DAK02')
            ->get('analisis_master')->row();
        if (empty($dak)) {
            $file_analisis = FCPATH . 'assets/import/analisis_DAK_Profil_Desa.xlsx';
            $id_dak        = $this->analisis_import_model->import_excel($file_analisis, 'DAK02', $jenis        = 1);
        } else {
            $id_dak = $dak->id;
        }
        // Tambah kolom is_teks pada analisis_indikator
        $fields = [];
        if (!$this->db->field_exists('is_teks', 'analisis_indikator')) {
            $fields['is_teks'] = [
                'type'       => 'tinyint',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0, // isian pertanyaan menggunakan kode
            ];
        }
        $this->dbforge->add_column('analisis_indikator', $fields);
        // Ubah pertanyaan2 DAK profil desa menggunakan teks
        $pertanyaan = [
            'Cacat Fisik',
            'Cacat Mental',
            'Kedudukan Anggota Keluarga sebagai Wajib Pajak dan Retribusi',
            'Lembaga Pemerintahan Yang Diikuti Anggota Keluarga',
            'Lembaga Kemasyarakatan Yang Diikuti Anggota Keluarga',
            'Lembaga Ekonomi Yang Dimiliki Anggota Keluarga',
        ];
        $list_pertanyaan = sql_in_list($pertanyaan);
        $this->db->where('id_master', $id_dak)->where("pertanyaan in({$list_pertanyaan})")
            ->update('analisis_indikator', ['is_teks' => 1]);
    }

    private function migrasi_29_ke_210()
    {
        // Tambah kolom untuk format impor respon untuk analisis_master
        $fields = [];
        if (!$this->db->field_exists('format_impor', 'analisis_master')) {
            $fields['format_impor'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
            ];
        }
        $this->dbforge->add_column('analisis_master', $fields);
        // Tambah setting timezone
        $setting = $this->db->where('key', 'timezone')->get('setting_aplikasi')->row()->id;
        if (!$setting) {
            $this->db->insert('setting_aplikasi', ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'keterangan' => 'Zona waktu perekaman waktu dan tanggal']);
        }
        // Tambah tabel inventaris
        if (!$this->db->table_exists('jenis_barang')) {
            $query = '
				CREATE TABLE jenis_barang (
					id int NOT NULL AUTO_INCREMENT,
					nama varchar(30),
					keterangan varchar(100),
					PRIMARY KEY (id)
				);
			';
            $this->db->query($query);
        }
        if (!$this->db->table_exists('inventaris')) {
            $query = '
				CREATE TABLE inventaris (
					id int NOT NULL AUTO_INCREMENT,
					id_jenis_barang int(6),
					asal_sendiri int(6),
					asal_pemerintah int(6),
					asal_provinsi int(6),
					asal_kab int(6),
					asal_sumbangan int(6),
					hapus_rusak int(6),
					hapus_dijual int(6),
					hapus_sumbangkan int(6),
					tanggal_mutasi date NOT NULL,
					jenis_mutasi int(6),
					keterangan varchar(100),
					PRIMARY KEY (id),
					FOREIGN KEY (id_jenis_barang)
						REFERENCES jenis_barang(id)
						ON DELETE CASCADE
				);
			';
            $this->db->query($query);
        }
        // Perubahan pada pra-rilis
        // Hapus kolom
        $daftar_kolom = ['asal_sendiri', 'asal_pemerintah', 'asal_provinsi', 'asal_kab', 'asal_sumbangan', 'tanggal_mutasi', 'jenis_mutasi', 'hapus_rusak', 'hapus_dijual', 'hapus_sumbangkan'];

        foreach ($daftar_kolom as $kolom) {
            if ($this->db->field_exists($kolom, 'inventaris')) {
                $this->dbforge->drop_column('inventaris', $kolom);
            }
        }
        // Tambah kolom
        $fields = [];
        if (!$this->db->field_exists('tanggal_pengadaan', 'inventaris')) {
            $fields['tanggal_pengadaan'] = [
                'type' => 'date',
                'null' => false,
            ];
        }
        if (!$this->db->field_exists('nama_barang', 'inventaris')) {
            $fields['nama_barang'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ];
        }
        if (!$this->db->field_exists('asal_barang', 'inventaris')) {
            $fields['asal_barang'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
            ];
        }
        if (!$this->db->field_exists('jml_barang', 'inventaris')) {
            $fields['jml_barang'] = [
                'type'       => 'int',
                'constraint' => 6,
            ];
        }
        $this->dbforge->add_column('inventaris', $fields);
        if (!$this->db->table_exists('mutasi_inventaris')) {
            $query = '
				CREATE TABLE mutasi_inventaris (
					id int NOT NULL AUTO_INCREMENT,
					id_barang int(6),
					tanggal_mutasi date NOT NULL,
					jenis_mutasi tinyint(2),
					jenis_penghapusan tinyint(2),
					jml_mutasi int(6),
					keterangan varchar(100),
					PRIMARY KEY (id),
					FOREIGN KEY (id_barang)
						REFERENCES inventaris(id)
						ON DELETE CASCADE
				);
			';
            $this->db->query($query);
        }
        // Ubah url modul program_bantuan
        $this->db->where('url', 'program_bantuan')->update('setting_modul', ['url' => 'program_bantuan/clear']);
    }

    private function migrasi_28_ke_29()
    {
        // Tambah data kelahiran ke tweb_penduduk
        $fields = [];
        if (!$this->db->field_exists('waktu_lahir', 'tweb_penduduk')) {
            $fields['waktu_lahir'] = [
                'type'       => 'VARCHAR',
                'constraint' => 5,
            ];
        }
        if (!$this->db->field_exists('tempat_dilahirkan', 'tweb_penduduk')) {
            $fields['tempat_dilahirkan'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
            ];
        }
        if (!$this->db->field_exists('alamat_tempat_lahir', 'tweb_penduduk')) {
            $fields['alamat_tempat_lahir'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ];
        }
        if (!$this->db->field_exists('jenis_kelahiran', 'tweb_penduduk')) {
            $fields['jenis_kelahiran'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
            ];
        }
        if (!$this->db->field_exists('kelahiran_anak_ke', 'tweb_penduduk')) {
            $fields['kelahiran_anak_ke'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
            ];
        }
        if (!$this->db->field_exists('penolong_kelahiran', 'tweb_penduduk')) {
            $fields['penolong_kelahiran'] = [
                'type'       => 'tinyint',
                'constraint' => 2,
            ];
        }
        if (!$this->db->field_exists('berat_lahir', 'tweb_penduduk')) {
            $fields['berat_lahir'] = [
                'type'       => 'varchar',
                'constraint' => 10,
            ];
        }
        if (!$this->db->field_exists('panjang_lahir', 'tweb_penduduk')) {
            $fields['panjang_lahir'] = [
                'type'       => 'varchar',
                'constraint' => 10,
            ];
        }
        $this->dbforge->add_column('tweb_penduduk', $fields);

        // Hapus kolom yg tidak digunakan
        if ($this->db->field_exists('pendidikan_id', 'tweb_penduduk')) {
            $this->dbforge->drop_column('tweb_penduduk', 'pendidikan_id');
        }
        // Tambah kolom e-ktp di tabel tweb_penduduk
        if (!$this->db->field_exists('ktp_el', 'tweb_penduduk')) {
            $fields = [
                'ktp_el' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                ],
            ];
            $this->dbforge->add_column('tweb_penduduk', $fields);
        }
        if (!$this->db->field_exists('status_rekam', 'tweb_penduduk')) {
            $fields = [
                'status_rekam' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                    'null'       => false,
                    'default'    => 0,
                ],
            ];
            $this->dbforge->add_column('tweb_penduduk', $fields);
        }
        // Tambah tabel status_rekam
        $query = 'DROP TABLE IF EXISTS tweb_status_ktp;';
        $this->db->query($query);

        $query = '
			CREATE TABLE tweb_status_ktp (
				id tinyint(5) NOT NULL AUTO_INCREMENT,
				nama varchar(50) NOT NULL,
				ktp_el tinyint(4) NOT NULL,
				status_rekam varchar(50) NOT NULL,
				PRIMARY KEY (id)
			) ENGINE=' . $this->engine . ' AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
		';
        $this->db->query($query);

        $query = "
			INSERT INTO tweb_status_ktp (id, nama, ktp_el, status_rekam) VALUES
			(1, 'BELUM REKAM', 1, '2'),
			(2, 'SUDAH REKAM', 2, '3'),
			(3, 'CARD PRINTED', 2, '4'),
			(4, 'PRINT READY RECORD', 2 ,'5'),
			(5, 'CARD SHIPPED', 2, '6'),
			(6, 'SENT FOR CARD PRINTING', 2, '7'),
			(7, 'CARD ISSUED', 2, '8');
		";
        $this->db->query($query);
    }

    private function migrasi_27_ke_28()
    {
        if (!$this->db->table_exists('suplemen')) {
            $query = '
				CREATE TABLE suplemen (
					id int NOT NULL AUTO_INCREMENT,
					nama varchar(100),
					sasaran tinyint(4),
					keterangan varchar(300),
					PRIMARY KEY (id)
				);
			';
            $this->db->query($query);
        }
        if (!$this->db->table_exists('suplemen_terdata')) {
            $query = '
				CREATE TABLE suplemen_terdata (
					id int NOT NULL AUTO_INCREMENT,
					id_suplemen int(10),
					id_terdata varchar(20),
					sasaran tinyint(4),
					keterangan varchar(100),
					PRIMARY KEY (id),
					FOREIGN KEY (id_suplemen)
						REFERENCES suplemen(id)
						ON DELETE CASCADE
				);
			';
            $this->db->query($query);
        }
        // Hapus surat permohonan perubahan kk (yang telah diubah menjadi kartu keluarga)
        $data = [
            'nama'       => 'Permohonan Perubahan Kartu Keluarga',
            'url_surat'  => 'surat_permohonan_perubahan_kartu_keluarga',
            'kode_surat' => 'S-41',
            'lampiran'   => 'f-1.16.php,f-1.01.php',
            'jenis'      => 1,
        ];
        $hasil = $this->db->where('url_surat', 'surat_permohonan_perubahan_kk')->get('tweb_surat_format');
        if ($hasil->num_rows() > 0) {
            $this->db->where('url_surat', 'surat_permohonan_perubahan_kk')->update('tweb_surat_format', $data);
        } else {
            // Tambah surat permohonan perubahan kartu keluarga
            $sql = $this->db->insert_string('tweb_surat_format', $data);
            $sql .= ' ON DUPLICATE KEY UPDATE
					nama = VALUES(nama),
					url_surat = VALUES(url_surat),
					kode_surat = VALUES(kode_surat),
					lampiran = VALUES(lampiran),
					jenis = VALUES(jenis)';
            $this->db->query($sql);
        }
    }

    private function migrasi_26_ke_27()
    {
        // Sesuaikan judul kelompok umur dengan SID 3.10 versi Okt 2017
        $this->db->truncate('tweb_penduduk_umur');
        $sql = '
			INSERT INTO tweb_penduduk_umur VALUES
			("1","BALITA","0","5","0"),
			("2","ANAK-ANAK","6","17","0"),
			("3","DEWASA","18","30","0"),
			("4","TUA","31","120","0"),
			("6","Di bawah 1 Tahun","0","1","1"),
			("9","2 s/d 4 Tahun","2","4","1"),
			("12","5 s/d 9 Tahun","5","9","1"),
			("13","10 s/d 14 Tahun","10","14","1"),
			("14","15 s/d 19 Tahun","15","19","1"),
			("15","20 s/d 24 Tahun","20","24","1"),
			("16","25 s/d 29 Tahun","25","29","1"),
			("17","30 s/d 34 Tahun","30","34","1"),
			("18","35 s/d 39 Tahun ","35","39","1"),
			("19","40 s/d 44 Tahun","40","44","1"),
			("20","45 s/d 49 Tahun","45","49","1"),
			("21","50 s/d 54 Tahun","50","54","1"),
			("22","55 s/d 59 Tahun","55","59","1"),
			("23","60 s/d 64 Tahun","60","64","1"),
			("24","65 s/d 69 Tahun","65","69","1"),
			("25","70 s/d 74 Tahun","70","74","1"),
			("26","Di atas 75 Tahun","75","99999","1");
		';
        $this->db->query($sql);
        // Tambah tombol media sosial Instagram
        $query = "
			INSERT INTO media_sosial (id, gambar, link, nama, enabled) VALUES ('5', 'ins.png', '', 'Instagram', '1')
			ON DUPLICATE KEY UPDATE
				gambar = VALUES(gambar),
				nama = VALUES(nama)";
        $this->db->query($query);
        // Ganti kelas sosial dengan tingkatan keluarga sejahtera dari BKKBN
        if ($this->db->table_exists('ref_kelas_sosial')) {
            $this->dbforge->drop_table('ref_kelas_sosial');
        }
        if (!$this->db->table_exists('tweb_keluarga_sejahtera')) {
            $query = '
				CREATE TABLE `tweb_keluarga_sejahtera` (
					`id` int(10),
					`nama` varchar(100),
					PRIMARY KEY  (`id`)
				);
			';
            $this->db->query($query);
            $query = "
				INSERT INTO `tweb_keluarga_sejahtera` (`id`, `nama`) VALUES
				(1,  'Keluarga Pra Sejahtera'),
				(2,  'Keluarga Sejahtera I'),
				(3,  'Keluarga Sejahtera II'),
				(4,  'Keluarga Sejahtera III'),
				(5,  'Keluarga Sejahtera III Plus')
			";
            $this->db->query($query);
        }
        // Tambah surat izin orang tua/suami/istri
        $data = [
            'nama'       => 'Keterangan Izin Orang Tua/Suami/Istri',
            'url_surat'  => 'surat_izin_orangtua_suami_istri',
            'kode_surat' => 'S-39',
            'jenis'      => 1,
        ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);
        // Tambah surat sporadik
        $data = [
            'nama'       => 'Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK)',
            'url_surat'  => 'surat_sporadik',
            'kode_surat' => 'S-40',
            'jenis'      => 1,
        ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);
    }

    private function migrasi_25_ke_26()
    {
        // Tambah tabel provinsi
        if (!$this->db->table_exists('provinsi')) {
            $query = '
				CREATE TABLE `provinsi` (
					`kode` tinyint(2),
					`nama` varchar(100),
					PRIMARY KEY  (`kode`)
				);
			';
            $this->db->query($query);
            $query = "
				INSERT INTO `provinsi` (`kode`, `nama`) VALUES
				(11,  'Aceh'),
				(12,  'Sumatera Utara'),
				(13,  'Sumatera Barat'),
				(14,  'Riau'),
				(15,  'Jambi'),
				(16,  'Sumatera Selatan'),
				(17,  'Bengkulu'),
				(18,  'Lampung'),
				(19,  'Kepulauan Bangka Belitung'),
				(21,  'Kepulauan Riau'),
				(31,  'DKI Jakarta'),
				(32,  'Jawa Barat'),
				(33,  'Jawa Tengah'),
				(34,  'DI Yogyakarta'),
				(35,  'Jawa Timur'),
				(36,  'Banten'),
				(51,  'Bali'),
				(52,  'Nusa Tenggara Barat'),
				(53,  'Nusa Tenggara Timur'),
				(61,  'Kalimantan Barat'),
				(62,  'Kalimantan Tengah'),
				(63,  'Kalimantan Selatan'),
				(64,  'Kalimantan Timur'),
				(65,  'Kalimantan Utara'),
				(71,  'Sulawesi Utara'),
				(72,  'Sulawesi Tengah'),
				(73,  'Sulawesi Selatan'),
				(74,  'Sulawesi Tenggara'),
				(75,  'Gorontalo'),
				(76,  'Sulawesi Barat'),
				(81,  'Maluku'),
				(82,  'Maluku Utara'),
				(91,  'Papua'),
				(92,  'Papua Barat')
			";
            $this->db->query($query);
        }
        // Konversi nama provinsi tersimpan di identitas desa
        $konversi = [
            'ntb'                        => 'Nusa Tenggara Barat',
            'ntt'                        => 'Nusa Tenggara Timur',
            'daerah istimewa yogyakarta' => 'DI Yogyakarta',
            'diy'                        => 'DI Yogyakarta',
            'yogyakarta'                 => 'DI Yogyakarta',
            'jabar'                      => 'Jawa Barat',
            'jawabarat'                  => 'Jawa Barat',
            'jateng'                     => 'Jawa Tengah',
            'jatim'                      => 'Jawa Timur',
            'jatimi'                     => 'Jawa Timur',
            'jawa timu'                  => 'Jawa Timur',
            'nad'                        => 'Aceh',
            'kalimatnan barat'           => 'Kalimantan Barat',
            'sulawesi teanggara'         => 'Sulawesi Tenggara',
        ];
        $nama_propinsi = $this->db->select('nama_propinsi')->where('id', '1')->get('config')->row()->nama_propinsi;

        foreach ($konversi as $salah => $benar) {
            if (strtolower($nama_propinsi) == $salah) {
                $this->db->where('id', '1')->update('config', ['nama_propinsi' => $benar]);
                break;
            }
        }
        // Tambah lampiran untuk Surat Keterangan Kematian
        $this->db->where('url_surat', 'surat_ket_kematian')->update('tweb_surat_format', ['lampiran' => 'f-2.29.php']);
        // Ubah nama lampiran untuk Surat Keterangan Kelahiran
        $this->db->where('url_surat', 'surat_ket_kelahiran')->update('tweb_surat_format', ['lampiran' => 'f-2.01.php']);
        // Tambah modul Sekretariat di urutan sesudah Cetak Surat
        $list_modul = [
            '5'  => 6,    // Analisis
            '6'  => 7,    // Bantuan
            '7'  => 8,    // Persil
            '8'  => 9,    // Plan
            '9'  => 10,   // Peta
            '10' => 11,   // SMS
            '11' => 12,   // Pengguna
            '12' => 13,   // Database
            '13' => 14,   // Admin Web
            '14' => 15,
        ];  // Laporan

        foreach ($list_modul as $key => $value) {
            $this->db->where('id', $key)->update('setting_modul', ['urut' => $value]);
        }
        $query = "
			INSERT INTO setting_modul (id, modul, url, aktif, ikon, urut, level, hidden, ikon_kecil) VALUES
			('15','Sekretariat','sekretariat','1','applications-office-5.png','5','2','0','fa fa-print fa-lg')
			ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				url = VALUES(url)";
        $this->db->query($query);
        // Tambah kolom kode di tabel kelompok
        if (!$this->db->field_exists('kode', 'kelompok')) {
            $fields = [
                'kode' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 16,
                    'null'       => false,
                ],
            ];
            $this->dbforge->add_column('kelompok', $fields);
        }
        // Tambah kolom no_anggota di tabel kelompok_anggota
        if (!$this->db->field_exists('no_anggota', 'kelompok_anggota')) {
            $fields = [
                'no_anggota' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false,
                ],
            ];
            $this->dbforge->add_column('kelompok_anggota', $fields);
        }
    }

    private function migrasi_24_ke_25()
    {
        // Tambah setting current_version untuk migrasi
        $setting = $this->db->where('key', 'current_version')->get('setting_aplikasi')->row()->id;
        if (!$setting) {
            $this->db->insert('setting_aplikasi', ['key' => 'current_version', 'value' => '2.4', 'keterangan' => 'Versi sekarang untuk migrasi']);
        }
        // Tambah kolom ikon_kecil di tabel setting_modul
        if (!$this->db->field_exists('ikon_kecil', 'setting_modul')) {
            $fields = [
                'ikon_kecil' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
            ];
            $this->dbforge->add_column('setting_modul', $fields);
            $list_modul = [
                '1'  => 'fa fa-home fa-lg',         // SID Home
                '2'  => 'fa fa-group fa-lg',        // Penduduk
                '3'  => 'fa fa-bar-chart fa-lg',    // Statistik
                '4'  => 'fa fa-print fa-lg',        // Cetak Surat
                '5'  => 'fa fa-dashboard fa-lg',    // Analisis
                '6'  => 'fa fa-folder-open fa-lg',  // Bantuan
                '7'  => 'fa fa-road fa-lg',         // Persil
                '8'  => 'fa fa-sitemap fa-lg',      // Plan
                '9'  => 'fa fa-map fa-lg',          // Peta
                '10' => 'fa fa-envelope-o fa-lg',  // SMS
                '11' => 'fa fa-user-plus fa-lg',   // Pengguna
                '12' => 'fa fa-database fa-lg',    // Database
                '13' => 'fa fa-cloud fa-lg',       // Admin Web
                '14' => 'fa fa-comments fa-lg',
            ];   // Laporan

            foreach ($list_modul as $key => $value) {
                $this->db->where('id', $key)->update('setting_modul', ['ikon_kecil' => $value]);
            }
        }
        // Tambah kolom id_pend di tabel tweb_penduduk_mandiri
        if (!$this->db->field_exists('id_pend', 'tweb_penduduk_mandiri')) {
            $fields = [
                'id_pend' => [
                    'type'       => 'int',
                    'constraint' => 9,
                    'null'       => false,
                    'first'      => true,
                ],
            ];
            $this->dbforge->add_column('tweb_penduduk_mandiri', $fields);
        }
        // Isi kolom id_pend
        $mandiri = $this->db->select('nik')->get('tweb_penduduk_mandiri')->result_array();

        foreach ($mandiri as $individu) {
            $id_pend = $this->db->select('id')->where('nik', $individu['nik'])->get('tweb_penduduk')->row()->id;
            if (empty($id_pend)) {
                $this->db->where('nik', $individu['nik'])->delete('tweb_penduduk_mandiri');
            } else {
                $this->db->where('nik', $individu['nik'])->update('tweb_penduduk_mandiri', ['id_pend' => $id_pend]);
            }
        }
        // Buat id_pend menjadi primary key
        $sql = 'ALTER TABLE tweb_penduduk_mandiri
							DROP PRIMARY KEY,
							ADD PRIMARY KEY (id_pend)';
        $this->db->query($sql);
        // Tambah kolom kategori di tabel dokumen
        if (!$this->db->field_exists('kategori', 'dokumen')) {
            $fields = [
                'kategori' => [
                    'type'       => 'tinyint',
                    'constraint' => 3,
                    'default'    => 1,
                ],
            ];
            $this->dbforge->add_column('dokumen', $fields);
        }
        // Tambah kolom attribute dokumen
        if (!$this->db->field_exists('attr', 'dokumen')) {
            $fields = [
                'attr' => [
                    'type' => 'text',
                ],
            ];
            $this->dbforge->add_column('dokumen', $fields);
        }
    }

    private function migrasi_23_ke_24()
    {
        // Tambah surat keterangan beda identitas KIS
        $data = [
            'nama'       => 'Keterangan Beda Identitas KIS',
            'url_surat'  => 'surat_ket_beda_identitas_kis',
            'kode_surat' => 'S-38',
            'jenis'      => 1,
        ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis)';
        $this->db->query($sql);
        // Tambah setting sebutan kepala dusun
        $setting = $this->db->where('key', 'sebutan_singkatan_kadus')->get('setting_aplikasi')->row()->id;
        if (!$setting) {
            $this->db->insert('setting_aplikasi', ['key' => 'sebutan_singkatan_kadus', 'value' => 'kawil', 'keterangan' => 'Sebutan singkatan jabatan kepala dusun']);
        }
    }

    private function migrasi_22_ke_23()
    {
        // Tambah widget menu_left untuk menampilkan menu kategori
        $widget = $this->db->select('id')->where('isi', 'menu_kategori.php')->get('widget')->row();
        if (!$widget->id) {
            $menu_kategori = ['judul' => 'Menu Kategori', 'isi' => 'menu_kategori.php', 'enabled' => 1, 'urut' => 1, 'jenis_widget' => 1];
            $this->db->insert('widget', $menu_kategori);
        }
        // Tambah tabel surat_masuk
        if (!$this->db->table_exists('surat_masuk')) {
            $query = '
				CREATE TABLE `surat_masuk` (
					`id` int NOT NULL AUTO_INCREMENT,
					`nomor_urut` smallint(5),
					`tanggal_penerimaan` date NOT NULL,
					`nomor_surat` varchar(20),
					`kode_surat` varchar(10),
					`tanggal_surat` date NOT NULL,
					`pengirim` varchar(100),
					`isi_singkat` varchar(200),
					`disposisi_kepada` varchar(50),
					`isi_disposisi` varchar(200),
					`berkas_scan` varchar(100),
					PRIMARY KEY  (`id`)
				);
			';
            $this->db->query($query);
        }
        // Artikel bisa di-comment atau tidak
        if (!$this->db->field_exists('boleh_komentar', 'artikel')) {
            $fields = [
                'boleh_komentar' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'default'    => 1,
                ],
            ];
            $this->dbforge->add_column('artikel', $fields);
        }
    }

    private function migrasi_21_ke_22()
    {
        // Tambah lampiran untuk Surat Keterangan Kelahiran
        $this->db->where('url_surat', 'surat_ket_kelahiran')->update('tweb_surat_format', ['lampiran' => 'f-kelahiran.php']);
        // Tambah setting sumber gambar slider
        $pilihan_sumber = $this->db->where('key', 'sumber_gambar_slider')->get('setting_aplikasi')->row()->id;
        if (!$pilihan_sumber) {
            $this->db->insert('setting_aplikasi', ['key' => 'sumber_gambar_slider', 'value' => 1, 'keterangan' => 'Sumber gambar slider besar']);
        }
        // Tambah gambar kartu peserta program bantuan
        if (!$this->db->field_exists('kartu_peserta', 'program_peserta')) {
            $fields = [
                'kartu_peserta' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
            ];
            $this->dbforge->add_column('program_peserta', $fields);
        }
    }

    private function migrasi_20_ke_21()
    {
        if (!$this->db->table_exists('widget')) {
            $query = '
				CREATE TABLE `widget` (
					`id` int NOT NULL AUTO_INCREMENT,
					`isi` text,
					`enabled` int(2),
					`judul` varchar(100),
					`jenis_widget` tinyint(2) NOT NULL DEFAULT 3,
					`urut` int(5),
					PRIMARY KEY  (`id`)
				);
			';
            $this->db->query($query);
            // Pindahkan data widget dari tabel artikel ke tabel widget
            $widgets = $this->db->select('isi, enabled, judul, jenis_widget, urut')->where('id_kategori', 1003)->get('artikel')->result_array();

            foreach ($widgets as $widget) {
                $this->db->insert('widget', $widget);
            }
            // Hapus kolom widget dari tabel artikel
            $kolom_untuk_dihapus = ['urut', 'jenis_widget'];

            foreach ($kolom_untuk_dihapus as $kolom) {
                $this->dbforge->drop_column('artikel', $kolom);
            }
        }
        // Hapus setiap kali migrasi, karena ternyata masih ada di database contoh s/d v2.4
        // TODO: pindahkan ini jika nanti ada kategori dengan nilai 1003.
        $this->db->where('id_kategori', 1003)->delete('artikel');
        // Tambah tautan ke form administrasi widget
        if (!$this->db->field_exists('form_admin', 'widget')) {
            $fields = [
                'form_admin' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
            ];
            $this->dbforge->add_column('widget', $fields);
            $this->db->where('isi', 'layanan_mandiri.php')->update('widget', ['form_admin' => 'mandiri']);
            $this->db->where('isi', 'aparatur_desa.php')->update('widget', ['form_admin' => 'pengurus']);
            $this->db->where('isi', 'agenda.php')->update('widget', ['form_admin' => 'web/index/1000']);
            $this->db->where('isi', 'galeri.php')->update('widget', ['form_admin' => 'gallery']);
            $this->db->where('isi', 'komentar.php')->update('widget', ['form_admin' => 'komentar']);
            $this->db->where('isi', 'media_sosial.php')->update('widget', ['form_admin' => 'sosmed']);
            $this->db->where('isi', 'peta_lokasi_kantor.php')->update('widget', ['form_admin' => 'hom_desa']);
        }
        // Tambah kolom setting widget
        if (!$this->db->field_exists('setting', 'widget')) {
            $fields = [
                'setting' => [
                    'type' => 'text',
                ],
            ];
            $this->dbforge->add_column('widget', $fields);
        }
        // Ubah nama widget menjadi sinergi_program
        $this->db->select('id')->where('isi', 'sinergitas_program.php')->update('widget', ['isi' => 'sinergi_program.php', 'judul' => 'Sinergi Program', 'form_admin' => 'web_widget/admin/sinergi_program']);
        // Tambah widget sinergi_program
        $widget = $this->db->select('id')->where('isi', 'sinergi_program.php')->get('widget')->row();
        if (!$widget->id) {
            $widget_baru = ['judul' => 'Sinergi Program', 'isi' => 'sinergi_program.php', 'enabled' => 1, 'urut' => 1, 'jenis_widget' => 1, 'form_admin' => 'web_widget/admin/sinergi_program'];
            $this->db->insert('widget', $widget_baru);
        }
    }

    private function migrasi_117_ke_20()
    {
        if (!$this->db->table_exists('setting_aplikasi')) {
            $query = '
				CREATE TABLE `setting_aplikasi` (
					`id` int NOT NULL AUTO_INCREMENT,
					`key` varchar(50),
					`value` varchar(200),
					`keterangan` varchar(200),
					`jenis` varchar(30),
					`kategori` varchar(30),
					PRIMARY KEY  (`id`)
				);
			';
            $this->db->query($query);

            $this->reset_setting_aplikasi();
        }
        // Update untuk tambahan offline mode 2, sesudah masuk pra-rilis (ada yang sudah migrasi)
        $this->db->where('id', 12)->update('setting_aplikasi', ['value' => '0', 'jenis' => '']);
        // Update media_sosial
        $this->db->where('id', 3)->update('media_sosial', ['nama' => 'Google Plus']);
        $this->db->where('id', 4)->update('media_sosial', ['nama' => 'YouTube']);
        // Tambah widget aparatur_desa
        $widget = $this->db->select('id')->where(['isi' => 'aparatur_desa.php', 'id_kategori' => 1003])->get('artikel')->row();
        if (!$widget->id) {
            $aparatur_desa = ['judul' => 'Aparatur Desa', 'isi' => 'aparatur_desa.php', 'enabled' => 1, 'id_kategori' => 1003, 'urut' => 1, 'jenis_widget' => 1];
            $this->db->insert('artikel', $aparatur_desa);
        }
        // Tambah foto aparatur desa
        if (!$this->db->field_exists('foto', 'tweb_desa_pamong')) {
            $fields = [
                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
            ];
            $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }
    }

    private function migrasi_116_ke_117()
    {
        // Tambah kolom log_penduduk
        if (!$this->db->field_exists('no_kk', 'log_penduduk')) {
            $query = 'ALTER TABLE log_penduduk ADD no_kk decimal(16,0)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('nama_kk', 'log_penduduk')) {
            $query = 'ALTER TABLE log_penduduk ADD nama_kk varchar(100)';
            $this->db->query($query);
        }
        // Hapus surat_ubah_sesuaikan
        $this->db->where('url_surat', 'surat_ubah_sesuaikan')->delete('tweb_surat_format');
        // Tambah kolom log_surat untuk surat non-warga
        if (!$this->db->field_exists('nik_non_warga', 'log_surat')) {
            $query = 'ALTER TABLE log_surat ADD nik_non_warga decimal(16,0)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('nama_non_warga', 'log_surat')) {
            $query = 'ALTER TABLE log_surat ADD nama_non_warga varchar(100)';
            $this->db->query($query);
        }
        $query = 'ALTER TABLE log_surat MODIFY id_pend int(11) DEFAULT NULL';
        $this->db->query($query);
        // Tambah contoh surat non-warga
        $query = "
			INSERT INTO tweb_surat_format(nama, url_surat, kode_surat, jenis) VALUES
			('Domisili Usaha Non-Warga', 'surat_domisili_usaha_non_warga', 'S-37', 1)
			ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis);
		";
        $this->db->query($query);
    }

    private function migrasi_115_ke_116()
    {
        // Ubah surat N-1 menjadi surat gabungan N-1 s/d N-7
        $this->db->where('url_surat', 'surat_ket_nikah')->update('tweb_surat_format', ['nama' => 'Keterangan Untuk Nikah (N-1 s/d N-7)']);
        // Hapus surat N-2 s/d N-7 yang sudah digabungkan ke surat_ket_nikah
        $this->db->where('url_surat', 'surat_ket_asalusul')->delete('tweb_surat_format');
        $this->db->where('url_surat', 'surat_persetujuan_mempelai')->delete('tweb_surat_format');
        $this->db->where('url_surat', 'surat_ket_orangtua')->delete('tweb_surat_format');
        $this->db->where('url_surat', 'surat_izin_orangtua')->delete('tweb_surat_format');
        $this->db->where('url_surat', 'surat_ket_kematian_suami_istri')->delete('tweb_surat_format');
        $this->db->where('url_surat', 'surat_kehendak_nikah')->delete('tweb_surat_format');
        $this->db->where('url_surat', 'surat_ket_wali')->delete('tweb_surat_format');
        // Tambah kolom untuk penandatangan surat
        if (!$this->db->field_exists('pamong_ttd', 'tweb_desa_pamong')) {
            $query = 'ALTER TABLE tweb_desa_pamong ADD pamong_ttd tinyint(1)';
            $this->db->query($query);
        }
        // Hapus surat_pindah_antar_kab_prov
        $this->db->where('url_surat', 'surat_pindah_antar_kab_prov')->delete('tweb_surat_format');
    }

    private function migrasi_114_ke_115()
    {
        // Tambah kolom untuk peserta program
        if (!$this->db->field_exists('kartu_nik', 'program_peserta')) {
            $query = 'ALTER TABLE program_peserta ADD kartu_nik decimal(16,0)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('kartu_nama', 'program_peserta')) {
            $query = 'ALTER TABLE program_peserta ADD kartu_nama varchar(100)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('kartu_tempat_lahir', 'program_peserta')) {
            $query = 'ALTER TABLE program_peserta ADD kartu_tempat_lahir varchar(100)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('kartu_tanggal_lahir', 'program_peserta')) {
            $query = 'ALTER TABLE program_peserta ADD kartu_tanggal_lahir date';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('kartu_alamat', 'program_peserta')) {
            $query = 'ALTER TABLE program_peserta ADD kartu_alamat varchar(200)';
            $this->db->query($query);
        }
    }

    private function migrasi_113_ke_114()
    {
        // Tambah kolom untuk slider
        if (!$this->db->field_exists('slider', 'gambar_gallery')) {
            $query = 'ALTER TABLE gambar_gallery ADD slider tinyint(1)';
            $this->db->query($query);
        }
    }

    private function migrasi_112_ke_113()
    {
        // Tambah data desa
        if (!$this->db->field_exists('nip_kepala_desa', 'config')) {
            $query = 'ALTER TABLE config ADD nip_kepala_desa decimal(18,0)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('email_desa', 'config')) {
            $query = 'ALTER TABLE config ADD email_desa varchar(50)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('telepon', 'config')) {
            $query = 'ALTER TABLE config ADD telepon varchar(50)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('website', 'config')) {
            $query = 'ALTER TABLE config ADD website varchar(100)';
            $this->db->query($query);
        }
        // Gabung F-1.15 dan F-1.01 menjadi satu lampiran surat_permohonan_kartu_keluarga
        $this->db->where('url_surat', 'surat_permohonan_kartu_keluarga')->update('tweb_surat_format', ['lampiran' => 'f-1.15.php,f-1.01.php']);
    }

    // Berdasarkan analisa database yang dikirim oleh AdJie Reverb Impulse
    private function migrasi_cri_lama()
    {
        if (!$this->db->field_exists('enabled', 'kategori')) {
            $query = 'ALTER TABLE kategori ADD enabled tinyint(4) DEFAULT 1';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('parrent', 'kategori')) {
            $query = 'ALTER TABLE kategori ADD parrent tinyint(4) DEFAULT 0';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('kode_surat', 'tweb_surat_format')) {
            $query = 'ALTER TABLE tweb_surat_format ADD kode_surat varchar(10)';
            $this->db->query($query);
        }
    }

    private function migrasi_03_ke_04()
    {
        $query = '
			CREATE TABLE IF NOT EXISTS `tweb_penduduk_mandiri` (
				`nik` decimal(16,0) NOT NULL,
				`pin` char(32) NOT NULL,
				`last_login` datetime,
				`tanggal_buat` date NOT NULL,
				PRIMARY KEY  (`nik`)
			);
		';
        $this->db->query($query);

        $query = '
			CREATE TABLE IF NOT EXISTS `program` (
				`id` int NOT NULL AUTO_INCREMENT,
				`nama` varchar(100) NOT NULL,
				`sasaran` tinyint,
				`ndesc` varchar(200),
				`sdate` date NOT NULL,
				`edate` date NOT NULL,
				`userid` mediumint NOT NULL,
				`status` int(10),
				PRIMARY KEY  (`id`)
			);
		';
        $this->db->query($query);

        $query = '
			CREATE TABLE IF NOT EXISTS `program_peserta` (
				`id` int NOT NULL AUTO_INCREMENT,
				`peserta` decimal(16,0) NOT NULL,
				`program_id` int NOT NULL,
				`sasaran` tinyint,
				PRIMARY KEY  (`id`)
			);
		';
        $this->db->query($query);

        $query = '
			CREATE TABLE IF NOT EXISTS `data_persil` (
				`id` int NOT NULL AUTO_INCREMENT,
				`nik` decimal(16,0) NOT NULL,
				`nama` varchar(100) NOT NULL,
				`persil_jenis_id` int NOT NULL,
				`id_clusterdesa` int NOT NULL,
				`luas` int,
				`no_sppt_pbb` int,
				`kelas` varchar(50),
				`persil_peruntukan_id` int NOT NULL,
				`alamat_ext` varchar(100),
				`userID` mediumint,
				PRIMARY KEY  (`id`)
			);
		';
        $this->db->query($query);

        $query = '
			CREATE TABLE IF NOT EXISTS `data_persil_peruntukan` (
				`id` int NOT NULL AUTO_INCREMENT,
				`nama` varchar(100) NOT NULL,
				`ndesc` varchar(200),
				PRIMARY KEY  (`id`)
			);
		';
        $this->db->query($query);

        $query = '
			CREATE TABLE IF NOT EXISTS `data_persil_jenis` (
				`id` int NOT NULL AUTO_INCREMENT,
				`nama` varchar(100) NOT NULL,
				`ndesc` varchar(200),
				PRIMARY KEY  (`id`)
			);
		';
        $this->db->query($query);
    }

    private function migrasi_08_ke_081()
    {
        if (!$this->db->field_exists('nama_surat', 'log_surat')) {
            $query = 'ALTER TABLE `log_surat` ADD `nama_surat` varchar(100)';
            $this->db->query($query);
        }
    }

    private function migrasi_082_ke_09()
    {
        if (!$this->db->field_exists('catatan', 'log_penduduk')) {
            $query = 'ALTER TABLE `log_penduduk` ADD `catatan` text';
            $this->db->query($query);
        }
    }

    private function migrasi_092_ke_010()
    {
        // CREATE UNIQUE INDEX migrasi_0_10_url_surat ON tweb_surat_format (url_surat);

        // Hapus surat duplikat
        $kriteria = ['id' => 19, 'url_surat' => 'surat_ket_kehilangan'];
        $this->db->where($kriteria);
        $this->db->delete('tweb_surat_format');

        $query = "
			INSERT INTO `tweb_surat_format` (`id`, `nama`, `url_surat`, `kode_surat`) VALUES
			(1, 'Keterangan Pengantar', 'surat_ket_pengantar', 'S-01'),
			(2, 'Keterangan Penduduk', 'surat_ket_penduduk', 'S-02'),
			(3, 'Biodata Penduduk', 'surat_bio_penduduk', 'S-03'),
			(5, 'Keterangan Pindah Penduduk', 'surat_ket_pindah_penduduk', 'S-04'),
			(6, 'Keterangan Jual Beli', 'surat_ket_jual_beli', 'S-05'),
			(7, 'Pengantar Pindah Antar Kabupaten/ Provinsi', 'surat_pindah_antar_kab_prov', 'S-06'),
			(8, 'Pengantar Surat Keterangan Catatan Kepolisian', 'surat_ket_catatan_kriminal', 'S-07'),
			(9, 'Keterangan KTP dalam Proses', 'surat_ket_ktp_dalam_proses', 'S-08'),
			(10, 'Keterangan Beda Identitas', 'surat_ket_beda_nama', 'S-09'),
			(11, 'Keterangan Bepergian / Jalan', 'surat_jalan', 'S-10'),
			(12, 'Keterangan Kurang Mampu', 'surat_ket_kurang_mampu', 'S-11'),
			(13, 'Pengantar Izin Keramaian', 'surat_izin_keramaian', 'S-12'),
			(14, 'Pengantar Laporan Kehilangan', 'surat_ket_kehilangan', 'S-13'),
			(15, 'Keterangan Usaha', 'surat_ket_usaha', 'S-14'),
			(16, 'Keterangan JAMKESOS', 'surat_ket_jamkesos', 'S-15'),
			(17, 'Keterangan Domisili Usaha', 'surat_ket_domisili_usaha', 'S-16'),
			(18, 'Keterangan Kelahiran', 'surat_ket_kelahiran', 'S-17'),
			(20, 'Permohonan Akta Lahir', 'surat_permohonan_akta', 'S-18'),
			(21, 'Pernyataan Belum Memiliki Akta Lahir', 'surat_pernyataan_akta', 'S-19'),
			(22, 'Permohonan Duplikat Kelahiran', 'surat_permohonan_duplikat_kelahiran', 'S-20'),
			(24, 'Keterangan Kematian', 'surat_ket_kematian', 'S-21'),
			(25, 'Keterangan Lahir Mati', 'surat_ket_lahir_mati', 'S-22'),
			(26, 'Keterangan Untuk Nikah (N-1)', 'surat_ket_nikah', 'S-23'),
			(27, 'Keterangan Asal Usul (N-2)', 'surat_ket_asalusul', 'S-24'),
			(28, 'Persetujuan Mempelai (N-3)', 'surat_persetujuan_mempelai', 'S-25'),
			(29, 'Keterangan Tentang Orang Tua (N-4)', 'surat_ket_orangtua', 'S-26'),
			(30, 'Keterangan Izin Orang Tua(N-5)', 'surat_izin_orangtua', 'S-27'),
			(31, 'Keterangan Kematian Suami/Istri(N-6)', 'surat_ket_kematian_suami_istri', 'S-28'),
			(32, 'Pemberitahuan Kehendak Nikah (N-7)', 'surat_kehendak_nikah', 'S-29'),
			(33, 'Keterangan Pergi Kawin', 'surat_ket_pergi_kawin', 'S-30'),
			(34, 'Keterangan Wali', 'surat_ket_wali', 'S-31'),
			(35, 'Keterangan Wali Hakim', 'surat_ket_wali_hakim', 'S-32'),
			(36, 'Permohonan Duplikat Surat Nikah', 'surat_permohonan_duplikat_surat_nikah', 'S-33'),
			(37, 'Permohonan Cerai', 'surat_permohonan_cerai', 'S-34'),
			(38, 'Keterangan Pengantar Rujuk/Cerai', 'surat_ket_rujuk_cerai', 'S-35')
			ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat);
		";
        $this->db->query($query);
        // surat_ubah_sesuaikan perlu ditangani berbeda, karena ada pengguna di mana
        // url surat_ubah_sesuaikan memiliki id yang bukan 39, sedangkan id 39 juga dipakai untuk surat lain
        $this->db->where('url_surat', 'surat_ubah_sesuaikan');
        $query = $this->db->get('tweb_surat_format');
        // Tambahkan surat_ubah_sesuaikan apabila belum ada
        if ($query->num_rows() == 0) {
            $data = [
                'nama'       => 'Ubah Sesuaikan',
                'url_surat'  => 'surat_ubah_sesuaikan',
                'kode_surat' => 'S-36',
            ];
            $this->db->insert('tweb_surat_format', $data);
        }

        // DROP INDEX migrasi_0_10_url_surat ON tweb_surat_format;

        /* Jangan buat index unik kode_surat, karena kolom ini digunakan
             untuk merekam klasifikasi surat yang tidak unik. */
        // $db = $this->db->database;
        // $query = "
        //   SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS
        //   WHERE table_schema=? AND table_name='tweb_surat_format' AND index_name='kode_surat';
        // ";
        // $hasil = $this->db->query($query, $db);
        // $data = $hasil->row_array();
        // if ($data['IndexIsThere'] == 0) {
        //   $query = "
        //     CREATE UNIQUE INDEX kode_surat ON tweb_surat_format (kode_surat);
        //   ";
        //   $this->db->query($query);
        // }

        if (!$this->db->field_exists('tgl_cetak_kk', 'tweb_keluarga')) {
            $query = 'ALTER TABLE tweb_keluarga ADD tgl_cetak_kk datetime';
            $this->db->query($query);
        }
        $query = 'ALTER TABLE tweb_penduduk_mandiri MODIFY tanggal_buat datetime';
        $this->db->query($query);
    }

    private function migrasi_010_ke_10()
    {
        $query = "
			INSERT INTO tweb_penduduk_pekerjaan(id, nama) VALUES (89, 'LAINNYA')
			ON DUPLICATE KEY UPDATE
				id = VALUES(id),
				nama = VALUES(nama);
		";
        $this->db->query($query);
    }

    private function migrasi_10_ke_11()
    {
        if (!$this->db->field_exists('kk_lk', 'log_bulanan')) {
            $query = 'ALTER TABLE log_bulanan ADD kk_lk int(11)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('kk_pr', 'log_bulanan')) {
            $query = 'ALTER TABLE log_bulanan ADD kk_pr int(11)';
            $this->db->query($query);
        }

        if (!$this->db->field_exists('urut', 'artikel')) {
            $query = 'ALTER TABLE artikel ADD urut int(5)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('jenis_widget', 'artikel')) {
            $query = 'ALTER TABLE artikel ADD jenis_widget tinyint(2) NOT NULL DEFAULT 3';
            $this->db->query($query);
        }

        if (!$this->db->table_exists('log_keluarga')) {
            $query = '
				CREATE TABLE `log_keluarga` (
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`id_kk` int(11) NOT NULL,
					`kk_sex` tinyint(2) NOT NULL,
					`id_peristiwa` int(4) NOT NULL,
					`tgl_peristiwa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					UNIQUE KEY `id_kk` (`id_kk`,`id_peristiwa`,`tgl_peristiwa`)
				) ENGINE=' . $this->engine . ' AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
			';
            $this->db->query($query);
        }

        $query = '
			DROP VIEW IF EXISTS data_surat;
		';
        $this->db->query($query);

        $query = '
			DROP TABLE IF EXISTS data_surat;
		';
        $this->db->query($query);

        $query = "
			CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `data_surat` AS select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`)));
		";
        $this->db->query($query);

        $system_widgets = [
            'Layanan Mandiri'      => 'layanan_mandiri.php',
            'Agenda'               => 'agenda.php',
            'Galeri'               => 'galeri.php',
            'Statistik'            => 'statistik.php',
            'Komentar'             => 'komentar.php',
            'Media Sosial'         => 'media_sosial.php',
            'Peta Lokasi Kantor'   => 'peta_lokasi_kantor.php',
            'Statistik Pengunjung' => 'statistik_pengunjung.php',
            'Arsip Artikel'        => 'arsip_artikel.php',
        ];

        foreach ($system_widgets as $key => $value) {
            $this->db->select('id');
            $this->db->where(['isi' => $value, 'id_kategori' => 1003]);
            $q      = $this->db->get('artikel');
            $widget = $q->row_array();
            if (!$widget['id']) {
                $query = "
					INSERT INTO artikel (judul,isi,enabled,id_kategori,urut,jenis_widget)
					VALUES ('{$key}','{$value}',1,1003,1,1);";
                $this->db->query($query);
            }
        }
    }

    private function migrasi_111_ke_12()
    {
        if (!$this->db->field_exists('alamat', 'tweb_keluarga')) {
            $query = 'ALTER TABLE tweb_keluarga ADD alamat varchar(200)';
            $this->db->query($query);
        }
    }

    private function migrasi_124_ke_13()
    {
        if (!$this->db->field_exists('urut', 'menu')) {
            $query = 'ALTER TABLE menu ADD urut int(5)';
            $this->db->query($query);
        }
    }

    private function migrasi_13_ke_14()
    {
        $query = "
			INSERT INTO user_grup (id, nama) VALUES (4, 'Kontributor')
			ON DUPLICATE KEY UPDATE
				id = VALUES(id),
				nama = VALUES(nama);
		";
        $this->db->query($query);

        // Buat tanggalperkawinan dan tanggalperceraian boleh NULL
        $query = 'ALTER TABLE tweb_penduduk CHANGE tanggalperkawinan tanggalperkawinan DATE NULL DEFAULT NULL;';
        $this->db->query($query);
        $query = 'ALTER TABLE tweb_penduduk CHANGE tanggalperceraian tanggalperceraian DATE NULL DEFAULT NULL;';
        $this->db->query($query);

        // Ubah tanggal menjadi NULL apabila 0000-00-00
        $query = "UPDATE tweb_penduduk SET tanggalperkawinan=NULL WHERE tanggalperkawinan='0000-00-00' OR tanggalperkawinan='00-00-0000';";
        $this->db->query($query);
        $query = "UPDATE tweb_penduduk SET tanggalperceraian=NULL WHERE tanggalperceraian='0000-00-00' OR tanggalperceraian='00-00-0000';";
        $this->db->query($query);
    }

    private function migrasi_14_ke_15()
    {
        // Tambah kolom di tabel tweb_penduduk
        if (!$this->db->field_exists('cara_kb_id', 'tweb_penduduk')) {
            $query = 'ALTER TABLE tweb_penduduk ADD cara_kb_id tinyint(2) NULL DEFAULT NULL;';
            $this->db->query($query);
        }

        // Tambah tabel cara_kb
        $query = 'DROP TABLE IF EXISTS tweb_cara_kb;';
        $this->db->query($query);

        $query = '
			CREATE TABLE tweb_cara_kb (
				id tinyint(5) NOT NULL AUTO_INCREMENT,
				nama varchar(50) NOT NULL,
				sex tinyint(2),
				PRIMARY KEY (id)
			) ENGINE=' . $this->engine . ' AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
		';
        $this->db->query($query);

        $query = "
			INSERT INTO tweb_cara_kb (id, nama, sex) VALUES
			(1, 'Pil', 2),
			(2, 'IUD', 2),
			(3, 'Suntik', 2),
			(4, 'Kondom', 1),
			(5, 'Susuk KB', 2),
			(6, 'Sterilisasi Wanita', 2),
			(7, 'Sterilisasi Pria', 1),
			(99, 'Lainnya', 3);
		";
        $this->db->query($query);

        // Ubah tanggallahir supaya tidak tampil apabila kosong
        $query = 'ALTER TABLE tweb_penduduk CHANGE tanggallahir tanggallahir DATE NULL DEFAULT NULL;';
        $this->db->query($query);
        $query = "
			UPDATE tweb_penduduk SET tanggallahir=NULL
			WHERE tanggallahir='0000-00-00' OR tanggallahir='00-00-0000';
		";
        $this->db->query($query);
    }

    private function migrasi_15_ke_16()
    {
        // Buat kk_sex boleh NULL
        $query = 'ALTER TABLE log_keluarga CHANGE kk_sex kk_sex tinyint(2) NULL DEFAULT NULL;';
        $this->db->query($query);

        // ==== Gabung program bantuan keluarga statik ke dalam modul Program Bantuan

        $program_keluarga = [
            'Raskin'      => 'raskin',
            'BLSM'        => 'id_blt',
            'PKH'         => 'id_pkh',
            'Bedah Rumah' => 'id_bedah_rumah',
        ];

        foreach ($program_keluarga as $key => $value) {
            // cari keluarga anggota program
            if (!$this->db->field_exists($value, 'tweb_keluarga')) {
                continue;
            }

            $this->db->select('no_kk');
            $this->db->where("{$value}", 1);
            $q = $this->db->get('tweb_keluarga');
            if ($q->num_rows() > 0) {
                // buat program
                $data = [
                    'sasaran' => 2,
                    'nama'    => $key,
                    'ndesc'   => '',
                    'userid'  => 0,
                    'sdate'   => date('Y-m-d', strtotime('-1 year')),
                    'edate'   => date('Y-m-d', strtotime('+1 year')),
                ];
                $this->db->insert('program', $data);
                $id_program = $this->db->insert_id();
                // untuk setiap keluarga anggota program buat program_peserta
                $data = $q->result_array();

                foreach ($data as $peserta_keluarga) {
                    $peserta = [
                        'peserta'    => $peserta_keluarga['no_kk'],
                        'program_id' => $id_program,
                        'sasaran'    => 2,
                    ];
                    $this->db->insert('program_peserta', $peserta);
                }
            }
            // Hapus kolom program di tweb_keluarga
            $sql = "ALTER TABLE tweb_keluarga DROP COLUMN {$value}";
            $this->db->query($sql);
        }
        // ==== Gabung program bantuan penduduk statik ke dalam modul Program Bantuan

        $program_penduduk = [
            'JAMKESMAS' => 'jamkesmas',
        ];

        foreach ($program_penduduk as $key => $value) {
            // cari penduduk anggota program
            if (!$this->db->field_exists($value, 'tweb_penduduk')) {
                continue;
            }

            $this->db->select('nik');
            $this->db->where("{$value}", 1);
            $q = $this->db->get('tweb_penduduk');
            if ($q->num_rows() > 0) {
                // buat program
                $data = [
                    'sasaran' => 1,
                    'nama'    => $key,
                    'ndesc'   => '',
                    'userid'  => 0,
                    'sdate'   => date('Y-m-d', strtotime('-1 year')),
                    'edate'   => date('Y-m-d', strtotime('+1 year')),
                ];
                $this->db->insert('program', $data);
                $id_program = $this->db->insert_id();
                // untuk setiap penduduk anggota program buat program_peserta
                $data = $q->result_array();

                foreach ($data as $peserta_penduduk) {
                    $peserta = [
                        'peserta'    => $peserta_penduduk['nik'],
                        'program_id' => $id_program,
                        'sasaran'    => 2,
                    ];
                    $this->db->insert('program_peserta', $peserta);
                }
            }
            // Hapus kolom program di tweb_penduduk
            $sql = "ALTER TABLE tweb_penduduk DROP COLUMN {$value}";
            $this->db->query($sql);
        }
    }

    private function migrasi_16_ke_17()
    {
        // Tambahkan id_cluster ke tabel keluarga
        if (!$this->db->field_exists('id_cluster', 'tweb_keluarga')) {
            $query = 'ALTER TABLE tweb_keluarga ADD id_cluster int(11);';
            $this->db->query($query);

            // Untuk setiap keluarga
            $query = $this->db->get('tweb_keluarga');
            $data  = $query->result_array();

            foreach ($data as $keluarga) {
                // Ambil id_cluster kepala keluarga
                $this->db->select('id_cluster');
                $this->db->where('id', $keluarga['nik_kepala']);
                $query     = $this->db->get('tweb_penduduk');
                $kepala_kk = $query->row_array();
                // Tulis id_cluster kepala keluarga ke keluarga
                if (isset($kepala_kk['id_cluster'])) {
                    $this->db->where('id', $keluarga['id']);
                    $this->db->update('tweb_keluarga', ['id_cluster' => $kepala_kk['id_cluster']]);
                }
            }
        }
    }

    private function migrasi_17_ke_18()
    {
        // Tambah lampiran surat dgn template html2pdf
        if (!$this->db->field_exists('lampiran', 'log_surat')) {
            $query = 'ALTER TABLE `log_surat` ADD `lampiran` varchar(100)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('lampiran', 'tweb_surat_format')) {
            $query = 'ALTER TABLE `tweb_surat_format` ADD `lampiran` varchar(100)';
            $this->db->query($query);
        }
        $query = "
			INSERT INTO `tweb_surat_format` (`id`, `url_surat`, `lampiran`) VALUES
			(5, 'surat_ket_pindah_penduduk', 'f-1.08.php')
			ON DUPLICATE KEY UPDATE
				url_surat = VALUES(url_surat),
				lampiran = VALUES(lampiran);
		";
        $this->db->query($query);
    }

    private function migrasi_18_ke_19()
    {
        // Hapus index unik untuk kode_surat kalau sempat dibuat sebelumnya
        $db    = $this->db->database;
        $query = "
			SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS
			WHERE table_schema=? AND table_name='tweb_surat_format' AND index_name='kode_surat';
		";
        $hasil = $this->db->query($query, $db);
        $data  = $hasil->row_array();
        if ($data['IndexIsThere'] > 0) {
            $query = '
				DROP INDEX kode_surat ON tweb_surat_format;
			';
            $this->db->query($query);
        }

        // Hapus tabel yang tidak terpakai lagi
        $query = 'DROP TABLE IF EXISTS ref_bedah_rumah, ref_blt, ref_jamkesmas, ref_pkh, ref_raskin, tweb_alamat_sekarang';
        $this->db->query($query);
    }

    private function migrasi_19_ke_110()
    {
        // Tambah nomor id_kartu untuk peserta program bantuan
        if (!$this->db->field_exists('no_id_kartu', 'program_peserta')) {
            $query = 'ALTER TABLE program_peserta ADD no_id_kartu varchar(30)';
            $this->db->query($query);
        }
    }

    private function migrasi_110_ke_111()
    {
        // Buat folder desa/upload/pengesahan apabila belum ada
        if (!file_exists(LOKASI_PENGESAHAN)) {
            mkdir(LOKASI_PENGESAHAN, 0755);
        }
        // Tambah akti/non-aktifkan dan pilihan favorit format surat
        if (!$this->db->field_exists('kunci', 'tweb_surat_format')) {
            $query = "ALTER TABLE tweb_surat_format ADD kunci tinyint(1) NOT NULL DEFAULT '0'";
            $this->db->query($query);
        }
        if (!$this->db->field_exists('favorit', 'tweb_surat_format')) {
            $query = "ALTER TABLE tweb_surat_format ADD favorit tinyint(1) NOT NULL DEFAULT '0'";
            $this->db->query($query);
        }
        if (!$this->db->field_exists('id_pend', 'dokumen')) {
            $query = "ALTER TABLE dokumen ADD id_pend int(11) NOT NULL DEFAULT '0'";
            $this->db->query($query);
        }

        if (!$this->db->table_exists('setting_modul')) {
            $query = "
				CREATE TABLE `setting_modul` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`modul` varchar(50) NOT NULL,
					`url` varchar(50) NOT NULL,
					`aktif` tinyint(1) NOT NULL DEFAULT '0',
					`ikon` varchar(50) NOT NULL,
					`urut` tinyint(4) NOT NULL,
					`level` tinyint(1) NOT NULL DEFAULT '2',
					`hidden` tinyint(1) NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`)
					) ENGINE=" . $this->engine . ' AUTO_INCREMENT=15 DEFAULT CHARSET=utf8
			';
            $this->db->query($query);

            $query = "
				INSERT INTO setting_modul VALUES
				('1','SID Home','hom_desa','1','go-home-5.png','1','2','1'),
				('2','Penduduk','penduduk/clear','1','preferences-contact-list.png','2','2','0'),
				('3','Statistik','statistik','1','statistik.png','3','2','0'),
				('4','Cetak Surat','surat','1','applications-office-5.png','4','2','0'),
				('5','Analisis','analisis_master/clear','1','analysis.png','5','2','0'),
				('6','Bantuan','program_bantuan','1','program.png','6','2','0'),
				('7','Persil','data_persil/clear','1','persil.png','7','2','0'),
				('8','Plan','plan','1','plan.png','8','2','0'),
				('9','Peta','gis','1','gis.png','9','2','0'),
				('10','SMS','sms','1','mail-send-receive.png','10','2','0'),
				('11','Pengguna','man_user/clear','1','system-users.png','11','1','1'),
				('12','Database','database','1','database.png','12','1','0'),
				('13','Admin Web','web','1','message-news.png','13','4','0'),
				('14','Laporan','lapor','1','mail-reply-all.png','14','2','0');
			";
            $this->db->query($query);
        }

        /**
         * Sesuaikan data modul analisis dengan SID 3.10
         */

        // Tabel analisis_indikator
        $ubah_kolom = [
            '`nomor` int(3) NOT NULL',
        ];

        foreach ($ubah_kolom as $kolom_def) {
            $query = 'ALTER TABLE analisis_indikator MODIFY ' . $kolom_def;
            $this->db->query($query);
        }
        if (!$this->db->field_exists('is_publik', 'analisis_indikator')) {
            $query = "ALTER TABLE analisis_indikator ADD `is_publik` tinyint(1) NOT NULL DEFAULT '0'";
            $this->db->query($query);
        }

        // Tabel analisis_kategori_indikator
        if (!$this->db->field_exists('kategori_kode', 'analisis_kategori_indikator')) {
            $query = 'ALTER TABLE analisis_kategori_indikator ADD `kategori_kode` varchar(3) NOT NULL';
            $this->db->query($query);
        }

        // Tabel analisis_master
        if ($this->db->field_exists('kode_analiusis', 'analisis_master')) {
            $query = "ALTER TABLE analisis_master CHANGE `kode_analiusis` `kode_analisis` varchar(5) NOT NULL DEFAULT '00000'";
            $this->db->query($query);
        }
        if (!$this->db->field_exists('id_child', 'analisis_master')) {
            $query = 'ALTER TABLE analisis_master ADD `id_child` smallint(4) NOT NULL';
            $this->db->query($query);
        }

        // Tabel analisis_parameter
        if (!$this->db->field_exists('kode_jawaban', 'analisis_parameter')) {
            $query = 'ALTER TABLE analisis_parameter ADD `kode_jawaban` int(3) NOT NULL';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('asign', 'analisis_parameter')) {
            $query = "ALTER TABLE analisis_parameter ADD `asign` tinyint(1) NOT NULL DEFAULT '0'";
            $this->db->query($query);
        }

        // Tabel analisis_respon
        $drop_kolom = [
            'id',
            'tanggal_input',
        ];

        foreach ($drop_kolom as $kolom_def) {
            if ($this->db->field_exists($kolom_def, 'analisis_respon')) {
                $query = 'ALTER TABLE analisis_respon DROP ' . $kolom_def;
                $this->db->query($query);
            }
        }

        // Tabel analisis_respon_bukti
        $query = '
			CREATE TABLE IF NOT EXISTS `analisis_respon_bukti` (
				`id_master` tinyint(4) NOT NULL,
				`id_periode` tinyint(4) NOT NULL,
				`id_subjek` int(11) NOT NULL,
				`pengesahan` varchar(100) NOT NULL,
				`tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
			) ENGINE=' . $this->engine . ' DEFAULT CHARSET=utf8;
			';
        $this->db->query($query);

        // Tabel analisis_respon_hasil
        if ($this->db->field_exists('id', 'analisis_respon_hasil')) {
            $query = 'ALTER TABLE analisis_respon_hasil DROP `id`';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('tgl_update', 'analisis_respon_hasil')) {
            $query = 'ALTER TABLE analisis_respon_hasil ADD `tgl_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP';
            $this->db->query($query);
        }
        $db    = $this->db->database;
        $query = "
			SELECT COUNT(1) ConstraintSudahAda
			FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
			WHERE TABLE_SCHEMA = ?
			AND TABLE_NAME = 'analisis_respon_hasil'
			AND CONSTRAINT_NAME = 'id_master'
		";
        $hasil = $this->db->query($query, $db);
        $data  = $hasil->row_array();
        if ($data['ConstraintSudahAda'] == 0) {
            $query = 'ALTER TABLE analisis_respon_hasil ADD CONSTRAINT `id_master` UNIQUE (`id_master`,`id_periode`,`id_subjek`)';
            $this->db->query($query);
        }

        /**
         * Sesuaikan data modul persil dengan SID 3.10
         */

        // Tabel data_persil
        $ubah_kolom = [
            '`nik` varchar(64) NOT NULL',
            "`nama` varchar(128) NOT NULL COMMENT 'nomer persil'",
            '`persil_jenis_id` tinyint(2) NOT NULL',
            '`luas` decimal(7,2) NOT NULL',
            '`kelas` varchar(128) DEFAULT NULL',
            '`no_sppt_pbb` varchar(128) NOT NULL',
            '`persil_peruntukan_id` tinyint(2) NOT NULL',
        ];

        foreach ($ubah_kolom as $kolom_def) {
            $query = 'ALTER TABLE data_persil MODIFY ' . $kolom_def;
            $this->db->query($query);
        }
        if (!$this->db->field_exists('peta', 'data_persil')) {
            $query = 'ALTER TABLE data_persil ADD `peta` text';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('rdate', 'data_persil')) {
            $query = 'ALTER TABLE data_persil ADD `rdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP';
            $this->db->query($query);
        }

        // Tabel data_persil_jenis
        $ubah_kolom = [
            '`nama` varchar(128) NOT NULL',
            '`ndesc` text NOT NULL',
        ];

        foreach ($ubah_kolom as $kolom_def) {
            $query = 'ALTER TABLE data_persil_jenis MODIFY ' . $kolom_def;
            $this->db->query($query);
        }

        // Tabel data_persil_peruntukan
        $ubah_kolom = [
            '`nama` varchar(128) NOT NULL',
            '`ndesc` text NOT NULL',
        ];

        foreach ($ubah_kolom as $kolom_def) {
            $query = 'ALTER TABLE data_persil_peruntukan MODIFY ' . $kolom_def;
            $this->db->query($query);
        }

        // Ubah surat keterangan pindah penduduk untuk bisa memilih format lampiran
        $query = "
			INSERT INTO `tweb_surat_format` (`id`, `url_surat`, `lampiran`) VALUES
			(5, 'surat_ket_pindah_penduduk', 'f-1.08.php,f-1.25.php')
			ON DUPLICATE KEY UPDATE
				url_surat = VALUES(url_surat),
				lampiran = VALUES(lampiran);
		";
        $this->db->query($query);
    }

    private function migrasi_111_ke_112()
    {
        // Ubah surat bio penduduk untuk menambah format lampiran
        $query = "
			INSERT INTO `tweb_surat_format` (`id`, `url_surat`, `lampiran`) VALUES
			(3, 'surat_bio_penduduk', 'f-1.01.php')
			ON DUPLICATE KEY UPDATE
				url_surat = VALUES(url_surat),
				lampiran = VALUES(lampiran);
		";
        $this->db->query($query);

        // Tabel tweb_penduduk melengkapi data F-1.01
        if (!$this->db->field_exists('telepon', 'tweb_penduduk')) {
            $query = 'ALTER TABLE tweb_penduduk ADD `telepon` varchar(20)';
            $this->db->query($query);
        }
        if (!$this->db->field_exists('tanggal_akhir_paspor', 'tweb_penduduk')) {
            $query = 'ALTER TABLE tweb_penduduk ADD `tanggal_akhir_paspor` date';
            $this->db->query($query);
        }

        // Ketinggalan tabel gis_simbol
        if (!$this->db->table_exists('gis_simbol')) {
            $query = '
				CREATE TABLE `gis_simbol` (
					`simbol` varchar(40) DEFAULT NULL
				) ENGINE=' . $this->engine . ' DEFAULT CHARSET=utf8;
			';
            $this->db->query($query);
            // Isi dengan daftar icon yang ada di folder assets/images/gis/point
            $simbol_folder   = FCPATH . 'assets/images/gis/point';
            $list_gis_simbol = scandir($simbol_folder);

            foreach ($list_gis_simbol as $simbol) {
                if ($simbol['0'] == '.') {
                    continue;
                }
                $this->db->insert('gis_simbol', ['simbol' => $simbol]);
            }
        }
        if (!$this->db->field_exists('jenis', 'tweb_surat_format')) {
            $query = 'ALTER TABLE tweb_surat_format ADD jenis tinyint(2) NOT NULL DEFAULT 2';
            $this->db->query($query);
            // Update semua surat yang disediakan oleh rilis OpenSID
            $surat_sistem = [
                'surat_ket_pengantar',
                'surat_ket_penduduk',
                'surat_bio_penduduk',
                'surat_ket_pindah_penduduk',
                'surat_ket_jual_beli',
                'surat_pindah_antar_kab_prov',
                'surat_ket_catatan_kriminal',
                'surat_ket_ktp_dalam_proses',
                'surat_ket_beda_nama',
                'surat_jalan',
                'surat_ket_kurang_mampu',
                'surat_izin_keramaian',
                'surat_ket_kehilangan',
                'surat_ket_usaha',
                'surat_ket_jamkesos',
                'surat_ket_domisili_usaha',
                'surat_ket_kelahiran',
                'surat_permohonan_akta',
                'surat_pernyataan_akta',
                'surat_permohonan_duplikat_kelahiran',
                'surat_ket_kematian',
                'surat_ket_lahir_mati',
                'surat_ket_nikah',
                'surat_ket_asalusul',
                'surat_persetujuan_mempelai',
                'surat_ket_orangtua',
                'surat_izin_orangtua',
                'surat_ket_kematian_suami_istri',
                'surat_kehendak_nikah',
                'surat_ket_pergi_kawin',
                'surat_ket_wali',
                'surat_ket_wali_hakim',
                'surat_permohonan_duplikat_surat_nikah',
                'surat_permohonan_cerai',
                'surat_ket_rujuk_cerai',
            ];
            // Jenis surat yang bukan bagian rilis sistem sudah otomatis berisi nilai default (yaitu, 2)
            foreach ($surat_sistem as $url_surat) {
                $this->db->where('url_surat', $url_surat)->update('tweb_surat_format', ['jenis' => 1]);
            }
        }
        // Tambah surat_permohonan_kartu_keluarga
        $this->db->where('url_surat', 'surat_ubah_sesuaikan')->update('tweb_surat_format', ['kode_surat' => 'P-01']);
        $query = "
			INSERT INTO tweb_surat_format (nama, url_surat, lampiran, kode_surat, jenis) VALUES
			('Permohonan Kartu Keluarga', 'surat_permohonan_kartu_keluarga', 'f-1.15.php', 'S-36', 1)
			ON DUPLICATE KEY UPDATE
				nama = VALUES(nama),
				url_surat = VALUES(url_surat),
				lampiran = VALUES(lampiran),
				kode_surat = VALUES(kode_surat),
				jenis = VALUES(jenis);
		";
        $this->db->query($query);
        // Tambah kolom no_kk_sebelumnya untuk penduduk yang pecah dari kartu keluarga
        if (!$this->db->field_exists('no_kk_sebelumnya', 'tweb_penduduk')) {
            $query = 'ALTER TABLE tweb_penduduk ADD no_kk_sebelumnya varchar(30)';
            $this->db->query($query);
        }
    }

    public function impor_data_awal_analisis()
    {
        $this->load->model('analisis_import_model');

        // Tambahkan kembali Analisis DDK Profil Desa dan Analisis DAK Profil Desa
        $file_analisis = FCPATH . 'assets/import/analisis_DDK_Profil_Desa.xlsx';
        $this->analisis_import_model->impor_analisis($file_analisis, 'DDK02', 1);
        $file_analisis = FCPATH . 'assets/import/analisis_DAK_Profil_Desa.xlsx';
        $this->analisis_import_model->impor_analisis($file_analisis, 'DAK02', 1);
    }

    public function get_views()
    {
        $db    = $this->db->database;
        $sql   = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'VIEW' AND TABLE_SCHEMA = '{$db}'";
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        return array_column($data, 'TABLE_NAME');
    }

    private function kirimVersi()
    {
        if (empty($this->header['desa']['kode_desa'])) {
            return;
        }

        $this->load->driver('cache');

        $versi = AmbilVersi();

        if ($versi != $this->cache->file->get('versi_app_cache')) {
            try {
                $client = new \GuzzleHttp\Client();
                $client->post(config_item('server_layanan') . '/api/v1/pelanggan/catat-versi', [
                    'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                    'form_params' => [
                        'kode_desa' => kode_wilayah($this->header['desa']['kode_desa']),
                        'versi'     => $versi,
                    ],
                ])
                    ->getBody();
                $this->cache->file->save('versi_app_cache', $versi);
            } catch (Exception $e) {
                log_message('error', $e);
            }
        }
    }
}
