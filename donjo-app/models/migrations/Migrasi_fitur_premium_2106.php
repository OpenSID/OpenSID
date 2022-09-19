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

class Migrasi_fitur_premium_2106 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2105');

        $hasil = $hasil && $this->migrasi_2021050551($hasil);
        $hasil = $hasil && $this->migrasi_2021050651($hasil);
        $hasil = $hasil && $this->migrasi_2021050653($hasil);
        $hasil = $hasil && $this->migrasi_2021050654($hasil);
        $hasil = $hasil && $this->migrasi_2021051002($hasil);
        $hasil = $hasil && $this->migrasi_2021051003($hasil);
        $hasil = $hasil && $this->migrasi_2021051402($hasil);
        $hasil = $hasil && $this->migrasi_2021051701($hasil);
        $hasil = $hasil && $this->migrasi_2021052501($hasil);
        $hasil = $hasil && $this->migrasi_2021052651($hasil);
        $hasil = $hasil && $this->migrasi_2021052751($hasil);
        $hasil = $hasil && $this->migrasi_2021052851($hasil);
        $hasil = $hasil && $this->migrasi_2021052951($hasil);

        return $hasil && $this->migrasi_2021052952($hasil);
    }

    protected function migrasi_2021050551($hasil)
    {
        $hasil = $hasil && $this->create_table_ref_asal_tanah_kas($hasil);
        $hasil = $hasil && $this->create_table_ref_peruntukan_tanah_kas($hasil);
        $hasil = $hasil && $this->add_value_ref_asal_tanah_kas($hasil);

        return $hasil && $this->add_value_ref_peruntukan_tanah_kas($hasil);
    }

    protected function migrasi_2021050651($hasil)
    {
        $hasil = $hasil && $this->pindah_modul_tanah_desa($hasil);

        return $hasil && $this->tambah_modul_tanah_kas_desa($hasil);
    }

    protected function migrasi_2021050653($hasil)
    {
        // Anggap link status_idm menuju statu idm tahun 2021
        return $hasil && $hasil && $this->db->where('link', 'status_idm')->update('menu', ['link' => 'status-idm/2021', 'link_tipe' => 10]);
    }

    protected function migrasi_2021050654($hasil)
    {
        return $hasil && $hasil && $this->db->where('link', 'status_sdgs')->update('menu', ['link' => 'status-sdgs']);
    }

    protected function migrasi_2021051002($hasil)
    {
        if (! $this->db->field_exists('permohonan', 'komentar')) {
            $hasil = $hasil && $this->dbforge->add_column('komentar', ['permohonan' => ['type' => 'TEXT', 'null' => true]]);
        }

        return $hasil;
    }

    protected function migrasi_2021051003($hasil)
    {
        $hasil = $hasil && $this->db->query("UPDATE `menu` SET `link` = REPLACE(`link`, 'kelompok/', 'data-kelompok/') WHERE `link_tipe` = '7' AND link NOT LIKE '%data-kelompok%'");

        // Hapus kolem foto pada tabel kelompok_anggota yang tidak digunakan
        if ($this->db->field_exists('foto', 'kelompok_anggota')) {
            $hasil = $hasil && $this->dbforge->drop_column('kelompok_anggota', 'foto');
        }

        return $hasil;
    }

    protected function migrasi_2021051402($hasil)
    {
        $hasil = $hasil && $this->tambah_tabel_pendapat($hasil);

        return $hasil && $this->tambah_modul_pendapat($hasil);
    }

    protected function migrasi_2021051701($hasil)
    {
        $fields = [
            'foto' => [
                'type'    => 'TEXT',
                'default' => null,
            ],

            'pamong_status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('tweb_desa_pamong', $fields);
    }

    protected function migrasi_2021052501($hasil)
    {
        return $hasil && $this->tambah_jenis_mutasi_inventaris($hasil);
    }

    protected function migrasi_2021052651($hasil)
    {
        $fields = [
            'kontruksi_beton' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true, 'default' => 0],
        ];

        return $hasil && $this->dbforge->modify_column('inventaris_gedung', $fields);
    }

    protected function migrasi_2021052751($hasil)
    {
        $list_inventaris = [
            ['mutasi' => 'mutasi_inventaris_asset', 'inventaris' => 'inventaris_asset', 'key' => 'id_inventaris_asset'],
            ['mutasi' => 'mutasi_inventaris_gedung', 'inventaris' => 'inventaris_gedung', 'key' => 'id_inventaris_gedung'],
            ['mutasi' => 'mutasi_inventaris_jalan', 'inventaris' => 'inventaris_jalan', 'key' => 'id_inventaris_jalan'],
            ['mutasi' => 'mutasi_inventaris_peralatan', 'inventaris' => 'inventaris_peralatan', 'key' => 'id_inventaris_peralatan'],
            ['mutasi' => 'mutasi_inventaris_tanah', 'inventaris' => 'inventaris_tanah', 'key' => 'id_inventaris_tanah'],
        ];

        $jenis_mutasi = ['Rusak', 'Diperbaiki'];

        // Ubah status mutasi & inventaris bukan hapus
        foreach ($list_inventaris as $inv) {
            $hasil = $hasil && $this->db
                ->where_in('jenis_mutasi', $jenis_mutasi)
                ->set('status_mutasi', 'jenis_mutasi', false)
                ->update($inv['mutasi']);

            $bukan_hapus = $this->db
                ->select("{$inv['key']} as key_inv")
                ->where_in('jenis_mutasi', $jenis_mutasi)
                ->get($inv['mutasi'])
                ->result_array();
            if (count($bukan_hapus)) {
                $hasil = $hasil && $this->db
                    ->where_in('id', array_column($bukan_hapus, 'key_inv'))
                    ->set('status', 0)
                    ->update($inv['inventaris']);
            }
        }

        return $hasil;
    }

    protected function migrasi_2021052851($hasil)
    {
        // Kolom tidak harus diisi
        $fields = [
            'merk' => [
                'type' => 'varchar(255)',
                'null' => true,
            ],
            'ukuran' => [
                'type' => 'text',
                'null' => true,
            ],
            'bahan' => [
                'type' => 'text',
                'null' => true,
            ],
        ];
        $hasil  = $hasil && $this->dbforge->modify_column('inventaris_peralatan', $fields);
        $fields = [
            'no_sertifikat' => [
                'type' => 'varchar(255)',
                'null' => true,
            ],
            'tanggal_sertifikat' => [
                'type' => 'date',
                'null' => true,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('inventaris_tanah', $fields);
    }

    protected function migrasi_2021052951($hasil)
    {
        // Pindah Buku Inventaris dan Kekayaan Desa
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 322,
            'modul'      => 'Buku Inventaris dan Kekayaan Desa',
            'url'        => 'bumindes_inventaris_kekayaan',
            'aktif'      => 1,
            'ikon'       => 'fa-files-o',
            'urut'       => 0,
            'level'      => 0,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-files-o',
            'parent'     => 302,
        ]);

        // Hapus Administrasi Lainnya
        $hasil = $hasil && $this->db->where('id', 306)->delete('setting_modul');

        // Tambah Buku Rencana Kerja Pembangunan
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 323,
            'modul'      => 'Buku Rencana Kerja Pembangunan',
            'url'        => 'bumindes_rencana_pembangunan',
            'aktif'      => 1,
            'ikon'       => 'fa-files-o',
            'urut'       => 0,
            'level'      => 0,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-files-o',
            'parent'     => 305,
        ]);

        // Ubah link Buku Administrasi Pembangunan
        return $hasil && $this->tambah_modul([
            'id'         => 305,
            'modul'      => 'Administrasi Pembangunan',
            'url'        => 'bumindes_rencana_pembangunan',
            'aktif'      => 1,
            'ikon'       => 'fa-university',
            'urut'       => 4,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-university',
            'parent'     => 301,
        ]);
    }

    protected function migrasi_2021052952($hasil)
    {
        // Tambah cdesa supaya bisa ditentukan hak akses
        return $hasil && $this->tambah_modul([
            'id'         => 214,
            'modul'      => 'C-Desa',
            'url'        => 'cdesa',
            'aktif'      => 1,
            'ikon'       => 'fa-files-o',
            'urut'       => 0,
            'level'      => 0,
            'hidden'     => 2,
            'ikon_kecil' => '',
            'parent'     => 7,
        ]);
    }

    protected function create_table_ref_asal_tanah_kas($hasil)
    {
        $this->dbforge->add_field([
            'id'   => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama' => ['type' => 'TEXT'],
        ]);

        $this->dbforge->add_key('id', true);

        return $hasil && $this->dbforge->create_table('ref_asal_tanah_kas', true);
    }

    protected function create_table_ref_peruntukan_tanah_kas($hasil)
    {
        $this->dbforge->add_field([
            'id'   => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama' => ['type' => 'TEXT'],
        ]);

        $this->dbforge->add_key('id', true);

        return $hasil && $this->dbforge->create_table('ref_peruntukan_tanah_kas', true);
    }

    public function add_value_ref_asal_tanah_kas($hasil)
    {
        $data = [
            ['id' => 1, 'nama' => 'Jual Beli'],
            ['id' => 2, 'nama' => 'Hibah / Sumbangan'],
            ['id' => 3, 'nama' => 'Lain - lain'],
        ];

        foreach ($data as $modul) {
            $sql = $this->db->insert_string('ref_asal_tanah_kas', $modul);
            $sql .= ' ON DUPLICATE KEY UPDATE
                    id = VALUES(id),
                    nama = VALUES(nama)';
            $hasil = $hasil && $this->db->query($sql);
        }

        return $hasil;
    }

    public function add_value_ref_peruntukan_tanah_kas($hasil)
    {
        $data = [
            ['id' => 1, 'nama' => 'Sewa'],
            ['id' => 2, 'nama' => 'Pinjam Pakai'],
            ['id' => 3, 'nama' => 'Kerjasama Pemanfaatan'],
            ['id' => 4, 'nama' => 'Bangun Guna Serah atau Bangun Serah Guna'],
        ];

        foreach ($data as $modul) {
            $sql = $this->db->insert_string('ref_peruntukan_tanah_kas', $modul);
            $sql .= ' ON DUPLICATE KEY UPDATE
                    id = VALUES(id),
                    nama = VALUES(nama)';
            $hasil = $hasil && $this->db->query($sql);
        }

        return $hasil;
    }

    protected function pindah_modul_tanah_desa($hasil)
    {
        // Ubah parent buku tanah desa ke administrasi umum.
        return $hasil && $this->ubah_modul(319, ['parent' => 302]);
    }

    protected function tambah_modul_tanah_kas_desa($hasil)
    {
        //menambahkan data pada setting_modul untuk controller 'bumindes_tanah_desa'
        return $hasil && $this->tambah_modul([
            'id'         => 320,
            'modul'      => 'Buku Tanah di Desa',
            'url'        => 'bumindes_tanah_desa/clear',
            'aktif'      => 1,
            'ikon'       => 'fa-files-o',
            'urut'       => 0,
            'level'      => 0,
            'hidden'     => 0,
            'ikon_kecil' => '',
            'parent'     => 302,
        ]);
    }

    protected function tambah_jenis_mutasi_inventaris()
    {
        $hasil = true;
        if (! $this->db->field_exists('status_mutasi', 'mutasi_inventaris_asset')) {
            $hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_asset', 'status_mutasi varchar(50) NOT NULL');
            $hasil = $hasil && $this->db->update('mutasi_inventaris_asset', ['status_mutasi' => 'Hapus']);
        }

        if (! $this->db->field_exists('status_mutasi', 'mutasi_inventaris_gedung')) {
            $hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_gedung', 'status_mutasi varchar(50) NOT NULL');
            $hasil = $hasil && $this->db->update('mutasi_inventaris_gedung', ['status_mutasi' => 'Hapus']);
        }

        if (! $this->db->field_exists('status_mutasi', 'mutasi_inventaris_jalan')) {
            $hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_jalan', 'status_mutasi varchar(50) NOT NULL');
            $hasil = $hasil && $this->db->update('mutasi_inventaris_jalan', ['status_mutasi' => 'Hapus']);
        }

        if (! $this->db->field_exists('status_mutasi', 'mutasi_inventaris_peralatan')) {
            $hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_peralatan', 'status_mutasi varchar(50) NOT NULL');
            $hasil = $hasil && $this->db->update('mutasi_inventaris_peralatan', ['status_mutasi' => 'Hapus']);
        }

        if (! $this->db->field_exists('status_mutasi', 'mutasi_inventaris_tanah')) {
            $hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_tanah', 'status_mutasi varchar(50) NOT NULL');
            $hasil = $hasil && $this->db->update('mutasi_inventaris_tanah', ['status_mutasi' => 'Hapus']);
        }

        //hilangkan default value pada kolom jenis mutasi
        $fields = [
            'jenis_mutasi' => [
                'type' => 'varchar(100)',
                'null' => true,
            ],
        ];
        $this->dbforge->modify_column('mutasi_inventaris_asset', $fields);
        $this->dbforge->modify_column('mutasi_inventaris_gedung', $fields);
        $this->dbforge->modify_column('mutasi_inventaris_jalan', $fields);
        $this->dbforge->modify_column('mutasi_inventaris_peralatan', $fields);
        $this->dbforge->modify_column('mutasi_inventaris_tanah', $fields);

        $hasil = $hasil && $this->db->query("CREATE OR REPLACE VIEW `master_inventaris` AS SELECT 'inventaris_asset' AS asset, inventaris_asset.id, inventaris_asset.nama_barang, inventaris_asset.kode_barang, 'Baik' AS kondisi, inventaris_asset.keterangan, inventaris_asset.asal, inventaris_asset.tahun_pengadaan FROM inventaris_asset WHERE visible = 1 UNION ALL SELECT 'inventaris_gedung' AS asset, inventaris_gedung.id, inventaris_gedung.nama_barang, inventaris_gedung.kode_barang, inventaris_gedung.kondisi_bangunan, inventaris_gedung.keterangan, inventaris_gedung.asal, YEAR( inventaris_gedung.tanggal_dokument) AS tahun_pengadaan FROM inventaris_gedung WHERE visible = 1 UNION ALL SELECT 'inventaris_jalan' AS asset, inventaris_jalan.id, inventaris_jalan.nama_barang, inventaris_jalan.kode_barang, inventaris_jalan.kondisi, inventaris_jalan.keterangan, inventaris_jalan.asal, YEAR ( inventaris_jalan.tanggal_dokument ) AS tahun_pengadaan FROM inventaris_jalan WHERE visible = 1 UNION ALL SELECT 'inventaris_peralatan' AS asset, inventaris_peralatan.id, inventaris_peralatan.nama_barang, inventaris_peralatan.kode_barang, 'Baik', inventaris_peralatan.keterangan, inventaris_peralatan.asal, inventaris_peralatan.tahun_pengadaan FROM inventaris_peralatan WHERE visible = 1");

        return $hasil && $this->db->query("CREATE OR REPLACE VIEW `rekap_mutasi_inventaris` AS SELECT 'inventaris_asset' AS asset, id_inventaris_asset, status_mutasi, jenis_mutasi, tahun_mutasi, keterangan FROM mutasi_inventaris_asset WHERE visible = 1 UNION ALL SELECT 'inventaris_gedung', id_inventaris_gedung, status_mutasi, jenis_mutasi, tahun_mutasi, keterangan FROM mutasi_inventaris_gedung WHERE visible = 1 UNION ALL SELECT 'inventaris_jalan', id_inventaris_jalan, status_mutasi, jenis_mutasi, tahun_mutasi, keterangan FROM mutasi_inventaris_jalan WHERE visible = 1 UNION ALL SELECT 'inventaris_peralatan', id_inventaris_peralatan, status_mutasi, jenis_mutasi, tahun_mutasi, keterangan FROM mutasi_inventaris_peralatan WHERE visible = 1");
    }

    protected function tambah_tabel_pendapat($hasil)
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],

            'pengguna' => [
                'type' => 'TEXT',
            ],

            'tanggal TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',

            'pilihan' => [
                'type'       => 'int',
                'constraint' => 1,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', true);

        return $hasil && $this->dbforge->create_table('pendapat', true);
    }

    protected function tambah_modul_pendapat($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'         => 321,
            'modul'      => 'Pendapat',
            'url'        => 'pendapat',
            'aktif'      => 1,
            'ikon'       => 'fa-thumbs-o-up',
            'urut'       => 5,
            'level'      => 0,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-thumbs-o-up',
            'parent'     => 14,
        ]);
    }
}