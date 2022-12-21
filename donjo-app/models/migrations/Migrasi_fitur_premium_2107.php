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

class Migrasi_fitur_premium_2107 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2106');

        $hasil = $hasil && $this->migrasi_2021060851($hasil);
        $hasil = $hasil && $this->migrasi_2021060901($hasil);
        $hasil = $hasil && $this->migrasi_2021061201($hasil);
        $hasil = $hasil && $this->migrasi_2021061301($hasil);
        $hasil = $hasil && $this->migrasi_2021061651($hasil);
        $hasil = $hasil && $this->migrasi_2021061652($hasil);
        $hasil = $hasil && $this->migrasi_2021061653($hasil);
        $hasil = $hasil && $this->migrasi_2021061951($hasil);
        $hasil = $hasil && $this->migrasi_2021062051($hasil);
        $hasil = $hasil && $this->migrasi_2021062052($hasil);
        $hasil = $hasil && $this->migrasi_2021062152($hasil);
        $hasil = $hasil && $this->migrasi_2021062154($hasil);
        $hasil = $hasil && $this->migrasi_2021062371($hasil);

        return $hasil && $this->migrasi_2021062373($hasil);
    }

    protected function migrasi_2021060851($hasil)
    {
        if (! $this->db->field_exists('id_peta', 'persil')) {
            $hasil = $hasil && $this->dbforge->add_column('persil', 'id_peta int(60)'); // tambah id peta untuk menyimpan id area
        }

        if (! $this->db->field_exists('id_peta', 'mutasi_cdesa')) {
            $hasil = $hasil && $this->dbforge->add_column('mutasi_cdesa', 'id_peta int(60)'); // tambah id peta untuk menyimpan id area
        }

        return $hasil;
    }

    protected function migrasi_2021060901($hasil)
    {
        $hasil = $hasil && $this->tambah_table_pelapak($hasil);
        $hasil = $hasil && $this->tambah_table_produk_kategori($hasil);
        $hasil = $hasil && $this->tambah_table_produk($hasil);
        $hasil = $hasil && $this->tambah_modul_produk($hasil);

        return $hasil && $this->tambah_pengaturan_aplikasi($hasil);
    }

    // Tabel Pelapak
    protected function tambah_table_pelapak($hasil)
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],

            'id_pend' => [
                'type'       => 'TINYINT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],

            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'lat' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'lng' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'zoom' => [
                'type'       => 'TINYINT',
                'constraint' => 4,
                'null'       => true,
            ],

            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);

        return $hasil && $this->dbforge->create_table('pelapak', true);
    }

    // Tabel Produk Kategori
    protected function tambah_table_produk_kategori($hasil)
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],

            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => null,
            ],

            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => null,
            ],
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);

        return $hasil && $this->dbforge->create_table('produk_kategori', true);
    }

    // Tabel Produk
    protected function tambah_table_produk($hasil)
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],

            'id_pelapak' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],

            'id_produk_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],

            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => null,
            ],

            'harga' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => null,
            ],

            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => null,
            ],

            'potongan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],

            'deskripsi' => [
                'type'    => 'TEXT',
                'default' => null,
            ],

            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 225,
                'null'       => true,
            ],

            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],

            'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);
        $hasil = $hasil && $this->dbforge->create_table('produk', true);

        $hasil = $hasil && $this->tambahForeignKey('lapak_fk', 'produk', 'id_pelapak', 'pelapak', 'id');

        return $hasil && $this->tambahForeignKey('produk_kategori_fk', 'produk', 'id_produk_kategori', 'produk_kategori', 'id');
    }

    // Menu Produk / Lapak
    protected function tambah_modul_produk($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'         => 324,
            'modul'      => 'Lapak',
            'url'        => 'lapak_admin',
            'aktif'      => 1,
            'ikon'       => 'fa-cart-plus',
            'urut'       => 122,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-cart-plus',
            'parent'     => 0,
        ]);
    }

    // Menambahkan data ke setting_aplikasi
    protected function tambah_pengaturan_aplikasi($hasil)
    {
        $hasil = $hasil && $this->db->query("
            INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('tampilkan_lapak_web', '1', 'Aktif / Non-aktif Lapak di Halaman Website Url Terpisah', 'boolean', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

        $hasil = $hasil && $this->db->query("
            INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('pesan_singkat_wa', 'Saya ingin membeli [nama_produk] yang anda tawarkan di Lapak Desa [link_web]', 'Pesan Singkat WhatsApp', 'textarea', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

        return $hasil && $this->db->query("
            INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('banyak_foto_tiap_produk', 3, 'Banyaknya foto tiap produk yang bisa di unggah', 'int', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");
    }

    protected function migrasi_2021061201($hasil)
    {
        // Ubah nilai default zoom yang sudah ada
        $hasil = $hasil && $this->db->where('zoom', null)->update('pelapak', ['zoom' => 10]);

        // Ubah default nilai zoom table pelapak
        $fields = [
            'zoom' => [
                'name'       => 'zoom',
                'type'       => 'TINYINT',
                'constraint' => 4,
                'null'       => false,
                'default'    => 10,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('pelapak', $fields);
    }

    protected function migrasi_2021061301($hasil)
    {
        // Ubah tipe data id_pend pada tabel pelapak
        $fields = [
            'id_pend' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('pelapak', $fields);
    }

    // Menambahkan data ke setting_aplikasi
    protected function migrasi_2021061651($hasil)
    {
        return $hasil && $this->db->query("
            INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('jumlah_produk_perhalaman', '10', 'Jumlah produk yang ditampilkan dalam satu halaman', 'int', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");
    }

    protected function migrasi_2021061652($hasil)
    {
        // Ubah nilai default foto pada tabel user
        $fields = [
            'foto' => [
                'name'       => 'foto',
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'kuser.png',
            ],
        ];

        return $hasil && $this->dbforge->modify_column('user', $fields);
    }

    protected function migrasi_2021061653($hasil)
    {
        // Hapus table provinsi
        return $hasil && $this->dbforge->drop_table('provinsi', true);
    }

    private function migrasi_2021061951($hasil)
    {
        // Tambah hak ases group operator dan redaksi
        $query = '
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,43,3), -- Aplikasi --
			(2,44,1), -- Pengguna --
			(2,45,3), -- Database --
			(2,46,3), -- Info Sistem --

			-- Redaksi --
			(3,65,7), -- Kategori --
			(3,324,7) -- Lapak --
		';

        return $hasil && $this->db->query($query);
    }

    protected function migrasi_2021062051($hasil)
    {
        $count = $this->db->like('path', '[[[[', 'AFTER')
            ->like('path', ']]]]', 'BEFORE')
            ->get('config')->num_rows();

        if ($count == 0) {
            //update data path menjadi [[[[x,y]]],[[[x,y]]]]
            $hasil = $this->db->set('path', 'concat("[",path,"]")', false)
                ->update('config');
        }

        //update data path pada dusun
        return $hasil && $this->db
            ->where('rt', '0')
            ->where('rw', '0')
            ->like('path', '[[[', 'AFTER')
            ->not_like('path', '[[[[', 'AFTER')
            ->set('path', 'concat("[",path,"]")', false)
            ->update('tweb_wil_clusterdesa');
    }

    protected function migrasi_2021062052($hasil)
    {
        // Tambahkan id_cluster pada tweb_keluarga yg null
        $query = '
            update tweb_keluarga as k,
                (select t.* from
                    (select id, id_kk, id_cluster from tweb_penduduk where id_kk in
                        (select id from tweb_keluarga where id_cluster is null)
                    ) t
                ) as p
                set k.id_cluster = p.id_cluster
                where k.id = p.id_kk
        ';

        $hasil = $hasil && $this->db->query($query);

        // Perbaiki struktur table tweb_keluarga field id_cluster tdk boleh null
        $fields = [
            'id_cluster' => [
                'name'       => 'id_cluster',
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('tweb_keluarga', $fields);
    }

    protected function migrasi_2021062152($hasil)
    {
        // Ubah struktur field potongan table produk
        $fields = [
            'potongan' => [
                'name'       => 'potongan',
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ];

        $hasil = $hasil && $this->dbforge->modify_column('produk', $fields);

        if (! $this->db->field_exists('tipe_potongan', 'produk')) {
            // Tambah field tipe_potongan pada table produk
            // Tipe 1 = persen, 2 = nominal
            $fields = [
                'tipe_potongan' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('produk', $fields, 'satuan');
        }

        return $hasil;
    }

    protected function migrasi_2021062154($hasil)
    {
        if (! $this->db->field_exists('status', 'produk_kategori')) {
            // Tambah field status pada table produk_kategori
            $fields = [
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 1,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('produk_kategori', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021062371($hasil)
    {
        // Hapus key tampilkan_di_halaman_utama_web jika terlanjur migrasi (untuk tester)
        return $hasil && $this->db->where('key', 'tampilkan_di_halaman_utama_web')->delete('setting_aplikasi');
    }

    protected function migrasi_2021062373($hasil)
    {
        // Tambah kolom suku pada tabel tweb_penduduk
        if (! $this->db->field_exists('suku', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', ['suku' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true]]);
        }

        // Tambah tabel ref_penduduk_suku
        if (! $this->db->table_exists('ref_penduduk_suku')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 65,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'suku' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'deskripsi' => [
                    'type' => 'TEXT',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->add_key('id', true);
            $hasil = $hasil && $this->dbforge->create_table('ref_penduduk_suku', true);
        }

        if ($hasil && $this->db->truncate('ref_penduduk_suku')) {

            // Tambahkan data awal tabel ref_penduduk_suku
            $insert_batch = [
                ['suku' => 'Aceh', 'deskripsi' => 'Aceh'],
                ['suku' => 'Alas', 'deskripsi' => 'Aceh'],
                ['suku' => 'Alor', 'deskripsi' => 'NTT'],
                ['suku' => 'Ambon', 'deskripsi' => 'Ambon'],
                ['suku' => 'Ampana', 'deskripsi' => 'Sulawesi Tengah'],
                ['suku' => 'Anak Dalam', 'deskripsi' => 'Jambi'],
                ['suku' => 'Aneuk Jamee', 'deskripsi' => 'Aceh'],
                ['suku' => 'Arab: Orang Hadhrami', 'deskripsi' => 'Arab: Orang Hadhrami'],
                ['suku' => 'Aru', 'deskripsi' => 'Maluku'],
                ['suku' => 'Asmat', 'deskripsi' => 'Papua'],
                ['suku' => 'Bare’e', 'deskripsi' => 'Bare’e di Kabupaten Tojo Una-Una Tojo dan Tojo Barat'],
                ['suku' => 'Banten', 'deskripsi' => 'Banten di Banten'],
                ['suku' => 'Besemah', 'deskripsi' => 'Besemah di Sumatera Selatan'],
                ['suku' => 'Bali', 'deskripsi' => "Bali\u{a0}di Bali terdiri dari: Suku Bali Majapahit di sebagian besar Pulau Bali; Suku Bali Aga di Karangasem dan Kintamani"],
                ['suku' => 'Balantak', 'deskripsi' => 'Balantak di Sulawesi Tengah'],
                ['suku' => 'Banggai', 'deskripsi' => 'Banggai di Sulawesi Tengah (Kabupaten Banggai Kepulauan)'],
                ['suku' => 'Baduy', 'deskripsi' => "Baduy\u{a0}di Banten"],
                ['suku' => 'Bajau', 'deskripsi' => 'Bajau di Kalimantan Timur'],
                ['suku' => 'Banjar', 'deskripsi' => 'Banjar di Kalimantan Selatan'],
                ['suku' => 'Batak', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Batak Karo', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Mandailing', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Angkola', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Toba', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Pakpak', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Simalungun', 'deskripsi' => 'Sumatera Utara'],
                ['suku' => 'Batin', 'deskripsi' => 'Batin di Jambi'],
                ['suku' => 'Bawean', 'deskripsi' => 'Bawean di Jawa Timur (Gresik)'],
                ['suku' => 'Bentong', 'deskripsi' => 'Bentong di Sulawesi Selatan'],
                ['suku' => 'Berau', 'deskripsi' => 'Berau di Kalimantan Timur (kabupaten Berau)'],
                ['suku' => 'Betawi', 'deskripsi' => 'Betawi di Jakarta'],
                ['suku' => 'Bima', 'deskripsi' => 'Bima NTB (kota Bima)'],
                ['suku' => 'Boti', 'deskripsi' => 'Boti di kabupaten Timor Tengah Selatan'],
                ['suku' => 'Bolang Mongondow', 'deskripsi' => 'Bolang Mongondow di Sulawesi Utara (Kabupaten Bolaang Mongondow)'],
                ['suku' => 'Bugis', 'deskripsi' => "Bugis\u{a0}di Sulawesi Selatan: Orang Bugis Pagatan di Kalimantan Selatan, Kusan Hilir, Tanah Bumbu"],
                ['suku' => 'Bungku', 'deskripsi' => 'Bungku di Sulawesi Tengah (Kabupaten Morowali)'],
                ['suku' => 'Buru', 'deskripsi' => 'Buru di Maluku (Kabupaten Buru)'],
                ['suku' => 'Buol', 'deskripsi' => 'Buol di Sulawesi Tengah (Kabupaten Buol)'],
                ['suku' => 'Bulungan ', 'deskripsi' => 'Bulungan di Kalimantan Timur (Kabupaten Bulungan)'],
                ['suku' => 'Buton', 'deskripsi' => 'Buton di Sulawesi Tenggara (Kabupaten Buton dan Kota Bau-Bau)'],
                ['suku' => 'Bonai', 'deskripsi' => 'Bonai di Riau (Kabupaten Rokan Hilir)'],
                ['suku' => 'Cham ', 'deskripsi' => 'Cham di Aceh'],
                ['suku' => 'Cirebon ', 'deskripsi' => 'Cirebon di Jawa Barat (Kota Cirebon)'],
                ['suku' => 'Damal', 'deskripsi' => 'Damal di Mimika'],
                ['suku' => 'Dampeles', 'deskripsi' => 'Dampeles di Sulawesi Tengah'],
                ['suku' => 'Dani ', 'deskripsi' => 'Dani di Papua (Lembah Baliem)'],
                ['suku' => 'Dairi', 'deskripsi' => 'Dairi di Sumatera Utara'],
                ['suku' => 'Daya ', 'deskripsi' => 'Daya di Sumatera Selatan'],
                ['suku' => 'Dayak', 'deskripsi' => "Dayak\u{a0}terdiri dari: Suku Dayak Ahe di Kalimantan Barat; Suku Dayak Bajare di Kalimantan Barat; Suku Dayak Damea di Kalimantan Barat; Suku Dayak Banyadu di Kalimantan Barat; Suku Bakati di Kalimantan Barat; Suku Punan di Kalimantan Tengah; Suku Kanayatn di Kalimantan Barat; Suku Dayak Krio di Kalimantan Barat (Ketapang); Suku Dayak Sungai Laur di Kalimantan Barat (Ketapang); Suku Dayak Simpangh di Kalimantan Barat (Ketapang); Suku Iban di Kalimantan Barat; Suku Mualang di Kalimantan Barat (Sekada"],
                ['suku' => 'Dompu', 'deskripsi' => 'Dompu NTB (Kabupaten Dompu)'],
                ['suku' => 'Donggo', 'deskripsi' => 'Donggo, Bima'],
                ['suku' => 'Dongga', 'deskripsi' => 'Donggala di Sulawesi Tengah'],
                ['suku' => 'Dondo ', 'deskripsi' => 'Dondo di Sulawesi Tengah (Kabupaten Toli-Toli)'],
                ['suku' => 'Duri', 'deskripsi' => 'Duri Terletak di bagian utara Kabupaten Enrekang berbatasan dengan Kabupaten Tana Toraja, meliputi tiga kecamatan induk Anggeraja, Baraka, dan Alla di Sulawesi Selatan'],
                ['suku' => 'Eropa ', 'deskripsi' => 'Eropa (orang Indo, peranakan Eropa-Indonesia, atau etnik Mestizo)'],
                ['suku' => 'Flores', 'deskripsi' => 'Flores di NTT (Flores Timur)'],
                ['suku' => 'Lamaholot', 'deskripsi' => 'Lamaholot, Flores Timur, terdiri dari: Suku Wandan, di Solor Timur, Flores Timur; Suku Kaliha, di Solor Timur, Flores Timur; Suku Serang Gorang, di Solor Timur, Flores Timur; Suku Lamarobak, di Solor Timur, Flores Timur; Suku Atanuhan, di Solor Timur, Flores Timur; Suku Wotan, di Solor Timur, Flores Timur; Suku Kapitan Belen, di Solor Timur, Flores Timur'],
                ['suku' => 'Gayo', 'deskripsi' => 'Gayo di Aceh (Gayo Lues Aceh Tengah Bener Meriah Aceh Tenggara Aceh Timur Aceh Tamiang)'],
                ['suku' => 'Gorontalo', 'deskripsi' => 'Gorontalo di Gorontalo (Kota Gorontalo)'],
                ['suku' => 'Gumai ', 'deskripsi' => 'Gumai di Sumatera Selatan (Lahat)'],
                ['suku' => 'India', 'deskripsi' => 'India, terdiri dari: Suku Tamil di Aceh, Sumatera Utara, Sumatera Barat, dan DKI Jakarta; Suku Punjab di Sumatera Utara, DKI Jakarta, dan Jawa Timur; Suku Bengali di DKI Jakarta; Suku Gujarati di DKI Jakarta dan Jawa Tengah; Orang Sindhi di DKI Jakarta dan Jawa Timur; Orang Sikh di Sumatera Utara, DKI Jakarta, dan Jawa Timur'],
                ['suku' => 'Jawa', 'deskripsi' => 'Jawa di Jawa Tengah, Jawa Timur, DI Yogyakarta'],
                ['suku' => 'Tengger', 'deskripsi' => "Tengger\u{a0}di Jawa Timur (Probolinggo, Pasuruan, dan Malang)"],
                ['suku' => 'Osing ', 'deskripsi' => 'Osing di Jawa Timur (Banyuwangi)'],
                ['suku' => 'Samin ', 'deskripsi' => 'Samin di Jawa Tengah (Purwodadi)'],
                ['suku' => 'Bawean', 'deskripsi' => 'Bawean di Jawa Timur (Pulau Bawean)'],
                ['suku' => 'Jambi ', 'deskripsi' => 'Jambi di Jambi (Kota Jambi)'],
                ['suku' => 'Jepang', 'deskripsi' => 'Jepang di DKI Jakarta, Jawa Timur, dan Bali'],
                ['suku' => 'Kei', 'deskripsi' => 'Kei di Maluku Tenggara (Kabupaten Maluku Tenggara dan Kota Tual)'],
                ['suku' => 'Kaili ', 'deskripsi' => 'Kaili di Sulawesi Tengah (Kota Palu)'],
                ['suku' => 'Kampar', 'deskripsi' => 'Kampar'],
                ['suku' => 'Kaur ', 'deskripsi' => 'Kaur di Bengkulu (Kabupaten Kaur)'],
                ['suku' => 'Kayu Agung', 'deskripsi' => 'Kayu Agung di Sumatera Selatan'],
                ['suku' => 'Kerinci', 'deskripsi' => 'Kerinci di Jambi (Kabupaten Kerinci)'],
                ['suku' => 'Komering ', 'deskripsi' => 'Komering di Sumatera Selatan (Kabupaten Ogan Komering Ilir, Baturaja)'],
                ['suku' => 'Konjo Pegunungan', 'deskripsi' => 'Konjo Pegunungan, Kabupaten Gowa, Sulawesi Selatan'],
                ['suku' => 'Konjo Pesisir', 'deskripsi' => 'Konjo Pesisir, Kabupaten Bulukumba, Sulawesi Selatan'],
                ['suku' => 'Koto', 'deskripsi' => 'Koto di Sumatera Barat'],
                ['suku' => 'Kubu', 'deskripsi' => 'Kubu di Jambi dan Sumatera Selatan'],
                ['suku' => 'Kulawi', 'deskripsi' => 'Kulawi di Sulawesi Tengah'],
                ['suku' => 'Kutai ', 'deskripsi' => 'Kutai di Kalimantan Timur (Kutai Kartanegara)'],
                ['suku' => 'Kluet ', 'deskripsi' => 'Kluet di Aceh (Aceh Selatan)'],
                ['suku' => 'Korea ', 'deskripsi' => 'Korea di DKI Jakarta'],
                ['suku' => 'Krui', 'deskripsi' => 'Krui di Lampung'],
                ['suku' => 'Laut,', 'deskripsi' => 'Laut, Kepulauan Riau'],
                ['suku' => 'Lampung', 'deskripsi' => 'Lampung, terdiri dari: Suku Sungkai di Lampung; Suku Abung di Lampung; Suku Way Kanan di Lampung, Sumatera Selatan dan Bengkulu; Suku Pubian di Lampung; Suku Tulang Bawang di Lampung; Suku Melinting di Lampung; Suku Peminggir Teluk di Lampung; Suku Ranau di Lampung, Sumatera Selatan dan Sumatera Utara; Suku Komering di Sumatera Selatan; Suku Cikoneng di Banten; Suku Merpas di Bengkulu; Suku Belalau di Lampung; Suku Smoung di Lampung; Suku Semaka di Lampung'],
                ['suku' => 'Lematang ', 'deskripsi' => 'Lematang di Sumatera Selatan'],
                ['suku' => 'Lembak', 'deskripsi' => 'Lembak, Kabupaten Rejang Lebong, Bengkulu'],
                ['suku' => 'Lintang', 'deskripsi' => 'Lintang, Sumatera Selatan'],
                ['suku' => 'Lom', 'deskripsi' => 'Lom, Bangka Belitung'],
                ['suku' => 'Lore', 'deskripsi' => 'Lore, Sulawesi Tengah'],
                ['suku' => 'Lubu', 'deskripsi' => 'Lubu, daerah perbatasan antara Provinsi Sumatera Utara dan Provinsi Sumatera Barat'],
                ['suku' => 'Moronene', 'deskripsi' => 'Moronene di Sulawesi Tenggara.'],
                ['suku' => 'Madura', 'deskripsi' => 'Madura di Jawa Timur (Pulau Madura, Kangean, wilayah Tapal Kuda)'],
                ['suku' => 'Makassar', 'deskripsi' => 'Makassar di Sulawesi Selatan: Kabupaten Gowa, Kabupaten Takalar, Kabupaten Jeneponto, Kabupaten Bantaeng, Kabupaten Bulukumba (sebagian), Kabupaten Sinjai (bagian perbatasan Kab Gowa), Kabupaten Maros (sebagian), Kabupaten Pangkep (sebagian), Kota Makassar'],
                ['suku' => 'Mamasa', 'deskripsi' => 'Mamasa (Toraja Barat) di Sulawesi Barat: Kabupaten Mamasa'],
                ['suku' => 'Manda', 'deskripsi' => 'Mandar Sulawesi Barat: Polewali Mandar'],
                ['suku' => 'Melayu', 'deskripsi' => 'Melayu, terdiri dari Suku Melayu Tamiang di Aceh (Aceh Tamiang); Suku Melayu Riau di Riau dan Kepulauan Riau; Suku Melayu Deli di Sumatera Utara; Suku Melayu Jambi di Jambi; Suku Melayu Bangka di Pulau Bangka; Suku Melayu Belitung di Pulau Belitung; Suku Melayu Sambas di Kalimantan Barat'],
                ['suku' => 'Mentawai', 'deskripsi' => 'Mentawai di Sumatera Barat (Kabupaten Kepulauan Mentawai)'],
                ['suku' => 'Minahasa', 'deskripsi' => 'Minahasa di Sulawesi Utara (Kabupaten Minahasa), terdiri 9 subetnik : Suku Babontehu; Suku Bantik; Suku Pasan Ratahan'],
                ['suku' => 'Ponosakan', 'deskripsi' => 'Ponosakan; Suku Tonsea; Suku Tontemboan; Suku Toulour; Suku Tonsawang; Suku Tombulu'],
                ['suku' => 'Minangkabau', 'deskripsi' => 'Minangkabau, Sumatera Barat'],
                ['suku' => 'Mongondow', 'deskripsi' => 'Mongondow, Sulawesi Utara'],
                ['suku' => 'Mori', 'deskripsi' => 'Mori, Kabupaten Morowali, Sulawesi Tengah'],
                ['suku' => 'Muko-Muko', 'deskripsi' => 'Muko-Muko di Bengkulu (Kabupaten Mukomuko)'],
                ['suku' => 'Muna', 'deskripsi' => 'Muna di Sulawesi Tenggara (Kabupaten Muna)'],
                ['suku' => 'Muyu', 'deskripsi' => 'Muyu di Kabupaten Boven Digoel, Papua'],
                ['suku' => 'Mekongga', 'deskripsi' => 'Mekongga di Sulawesi Tenggara (Kabupaten Kolaka dan Kabupaten Kolaka Utara)'],
                ['suku' => 'Moro', 'deskripsi' => 'Moro di Kalimantan Barat dan Kalimantan Utara'],
                ['suku' => 'Nias', 'deskripsi' => 'Nias di Sumatera Utara (Kabupaten Nias, Nias Selatan dan Nias Utara dari dua keturunan Jepang dan Vietnam)'],
                ['suku' => 'Ngada ', 'deskripsi' => 'Ngada di NTT: Kabupaten Ngada'],
                ['suku' => 'Osing', 'deskripsi' => 'Osing di Banyuwangi Jawa Timur'],
                ['suku' => 'Ogan', 'deskripsi' => 'Ogan di Sumatera Selatan'],
                ['suku' => 'Ocu', 'deskripsi' => 'Ocu di Kabupaten Kampar, Riau'],
                ['suku' => 'Padoe', 'deskripsi' => 'Padoe di Sulawesi Tengah dan Sulawesi Selatan'],
                ['suku' => 'Papua', 'deskripsi' => 'Papua / Irian, terdiri dari: Suku Asmat di Kabupaten Asmat; Suku Biak di Kabupaten Biak Numfor; Suku Dani, Lembah Baliem, Papua; Suku Ekagi, daerah Paniai, Abepura, Papua; Suku Amungme di Mimika; Suku Bauzi, Mamberamo hilir, Papua utara; Suku Arfak di Manokwari; Suku Kamoro di Mimika'],
                ['suku' => 'Palembang', 'deskripsi' => 'Palembang di Sumatera Selatan (Kota Palembang)'],
                ['suku' => 'Pamona', 'deskripsi' => 'Pamona di Sulawesi Tengah (Kabupaten Poso) dan di Sulawesi Selatan'],
                ['suku' => 'Pesisi', 'deskripsi' => 'Pesisi di Sumatera Utara (Tapanuli Tengah)'],
                ['suku' => 'Pasir', 'deskripsi' => 'Pasir di Kalimantan Timur (Kabupaten Pasir)'],
                ['suku' => 'Pubian', 'deskripsi' => 'Pubian di Lampung'],
                ['suku' => 'Pattae', 'deskripsi' => 'Pattae di Polewali Mandar'],
                ['suku' => 'Pakistani', 'deskripsi' => 'Pakistani di Sumatera Utara, DKI Jakarta, dan Jawa Tengah'],
                ['suku' => 'Peranakan', 'deskripsi' => 'Peranakan (Tionghoa-Peranakan atau Baba Nyonya)'],
                ['suku' => 'Rawa', 'deskripsi' => 'Rawa, Rokan Hilir, Riau'],
                ['suku' => 'Rejang', 'deskripsi' => 'Rejang di Bengkulu (Kabupaten Bengkulu Tengah, Kabupaten Bengkulu Utara, Kabupaten Kepahiang, Kabupaten Lebong, dan Kabupaten Rejang Lebong)'],
                ['suku' => 'Rote', 'deskripsi' => 'Rote di NTT (Kabupaten Rote Ndao)'],
                ['suku' => 'Rongga', 'deskripsi' => 'Rongga di NTT Kabupaten Manggarai Timur'],
                ['suku' => 'Rohingya', 'deskripsi' => 'Rohingya'],
                ['suku' => 'Sabu', 'deskripsi' => 'Sabu di Pulau Sabu, NTT'],
                ['suku' => 'Saluan', 'deskripsi' => 'Saluan di Sulawesi Tengah'],
                ['suku' => 'Sambas', 'deskripsi' => 'Sambas (Melayu Sambas) di Kalimantan Barat: Kabupaten Sambas'],
                ['suku' => 'Samin', 'deskripsi' => 'Samin di Jawa Tengah (Blora) dan Jawa Timur (Bojonegoro)'],
                ['suku' => 'Sangi', 'deskripsi' => 'Sangir di Sulawesi Utara (Kepulauan Sangihe)'],
                ['suku' => 'Sasak', 'deskripsi' => "Sasak\u{a0}di NTB, Lombok"],
                ['suku' => 'Sekak Bangka', 'deskripsi' => 'Sekak Bangka'],
                ['suku' => 'Sekayu', 'deskripsi' => 'Sekayu di Sumatera Selatan'],
                ['suku' => 'Semendo ', 'deskripsi' => 'Semendo di Bengkulu, Sumatera Selatan (Muara Enim)'],
                ['suku' => 'Serawai ', 'deskripsi' => 'Serawai di Bengkulu (Kabupaten Bengkulu Selatan dan Kabupaten Seluma)'],
                ['suku' => 'Simeulue', 'deskripsi' => 'Simeulue di Aceh (Kabupaten Simeulue)'],
                ['suku' => 'Sigulai ', 'deskripsi' => 'Sigulai di Aceh (Kabupaten Simeulue bagian utara'],
                ['suku' => 'Suluk', 'deskripsi' => 'Suluk di Kalimantan Utara)'],
                ['suku' => 'Sumbawa ', 'deskripsi' => 'Sumbawa Di NTB (Kabupaten Sumbawa)'],
                ['suku' => 'Sumba', 'deskripsi' => 'Sumba di NTT (Sumba Barat, Sumba Timur)'],
                ['suku' => 'Sunda', 'deskripsi' => 'Sunda di Jawa Barat, Banten, DKI Jakarta, Lampung, Sumatra Selatan dan Jawa Tengah'],
                ['suku' => 'Sungkai ', 'deskripsi' => 'Sungkai di Lampung Lampung Utara'],
                ['suku' => 'Talau', 'deskripsi' => 'Talaud di Sulawesi Utara (Kepulauan Talaud)'],
                ['suku' => 'Talang Mamak', 'deskripsi' => 'Talang Mamak di Riau (Indragiri Hulu)'],
                ['suku' => 'Tamiang ', 'deskripsi' => 'Tamiang di Aceh (Kabupaten Aceh Tamiang)'],
                ['suku' => 'Tengger ', 'deskripsi' => 'Tengger di Jawa Timur (Kabupaten Pasuruan) dan Probolinggo (lereng G. Bromo)'],
                ['suku' => 'Ternate ', 'deskripsi' => 'Ternate di Maluku Utara (Kota Ternate)'],
                ['suku' => 'Tidore', 'deskripsi' => 'Tidore di Maluku Utara (Kota Tidore)'],
                ['suku' => 'Tidung', 'deskripsi' => 'Tidung di Kalimantan Timur (Kabupaten Tanah Tidung)'],
                ['suku' => 'Timor', 'deskripsi' => 'Timor di NTT, Kota Kupang'],
                ['suku' => 'Tionghoa', 'deskripsi' => 'Tionghoa, terdiri dari: Orang Cina Parit di Pelaihari, Tanah Laut, Kalsel; Orang Cina Benteng di Tangerang, Provinsi Banten; Orang Tionghoa Hokkien di Jawa dan Sumatera Utara; Orang Tionghoa Hakka di Belitung dan Kalimantan Barat; Orang Tionghoa Hubei; Orang Tionghoa Hainan; Orang Tionghoa Kanton; Orang Tionghoa Hokchia; Orang Tionghoa Tiochiu'],
                ['suku' => 'Tojo', 'deskripsi' => 'Tojo di Sulawesi Tengah (Kabupaten Tojo Una-Una)'],
                ['suku' => 'Toraja', 'deskripsi' => 'Toraja di Sulawesi Selatan (Tana Toraja)'],
                ['suku' => 'Tolaki', 'deskripsi' => 'Tolaki di Sulawesi Tenggara (Kendari)'],
                ['suku' => 'Toli Toli', 'deskripsi' => 'Toli Toli di Sulawesi Tengah (Kabupaten Toli-Toli)'],
                ['suku' => 'Tomini', 'deskripsi' => 'Tomini di Sulawesi Tengah (Kabupaten Parigi Mouton'],
                ['suku' => 'Una-una ', 'deskripsi' => 'Una-una di Sulawesi Tengah (Kabupaten Tojo Una-Una)'],
                ['suku' => 'Ulu', 'deskripsi' => 'Ulu di Sumatera Utara (Mandailing natal)'],
                ['suku' => 'Wolio', 'deskripsi' => 'Wolio di Sulawesi Tenggara (Buton)'],
            ];

            $hasil = $hasil && $this->db->insert_batch('ref_penduduk_suku', $insert_batch);
        }

        // Update view supaya kolom baru ikut masuk
        return $hasil && $this->db->query('CREATE OR REPLACE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1');
    }
}