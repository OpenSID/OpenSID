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

class Migrasi_fitur_premium_2102 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2101');

        $hasil = $hasil && $this->pengaturan_latar($hasil);

        //tambah kolom urut di tabel tweb_wil_clusterdesa
        if (! $this->db->field_exists('urut', 'tweb_wil_clusterdesa')) {
            $hasil = $this->dbforge->add_column('tweb_wil_clusterdesa', [
                'urut' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        }

        $hasil = $hasil && $this->url_suplemen($hasil);
        // Buat folder untuk cache - 'cache\';
        mkdir(config_item('cache_path'), 0775, true);

        $hasil = $hasil && $this->create_table_pembangunan($hasil);
        $hasil = $hasil && $this->create_table_pembangunan_ref_dokumentasi($hasil);
        $hasil = $hasil && $this->add_modul_pembangunan($hasil);
        $hasil = $hasil && $this->sebutan_kepala_desa($hasil);
        $hasil = $hasil && $this->urut_cetak($hasil);
        $hasil = $hasil && $this->bumindes_updates($hasil);

        // Tambah kolom ganti_pin di tabel tweb_penduduk_mandiri
        if (! $this->db->field_exists('ganti_pin', 'tweb_penduduk_mandiri')) {
            $fields = [
                'ganti_pin' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 1],
            ];
            $hasil = $this->dbforge->add_column('tweb_penduduk_mandiri', $fields);
            // Set ulang value ganti_pin = 0 jika last_login sudah terisi
            $hasil = $hasil && $this->db
                ->where('last_login !=', null)
                ->set('ganti_pin', 0)
                ->update('tweb_penduduk_mandiri');
        }

        $hasil = $hasil && $this->tambahIndeks('tweb_penduduk', 'id_rtm', 'INDEX');

        // Perbaiki jenis untuk key 'offline_mode'
        $this->db->where('key', 'offline_mode')->update('setting_aplikasi', ['jenis' => 'option-value']);

        return $hasil;
    }

    private function pengaturan_latar($hasil)
    {
        $old = 'desa/css';
        $new = 'desa/pengaturan';

        if (is_dir($old)) {
            // Ubah nama folder desa/csss jadi desa/pengaturan
            rename($old, $new);
        }
        // Buat folder untuk latar
        mkdir($new . '/siteman/images', 0775, true);
        mkdir($new . '/klasik/images', 0775, true);
        mkdir($new . '/natra/images', 0775, true);

        return $hasil;
    }

    // Tambahkan clear pada url suplemen
    private function url_suplemen($hasil)
    {
        return $hasil && $this->db->where('id', 25)
            ->set('url', 'suplemen/clear')
            ->update('setting_modul');
    }

    protected function create_table_pembangunan($hasil)
    {
        $this->dbforge->add_field([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_lokasi'          => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'sumber_dana'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'judul'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'keterangan'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'lokasi'             => ['type' => 'VARCHAR', 'constraint' => 225, 'null' => true],
            'lat'                => ['type' => 'VARCHAR', 'constraint' => 225, 'null' => true],
            'lng'                => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'volume'             => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun_anggaran'     => ['type' => 'YEAR', 'null' => true],
            'pelaksana_kegiatan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'             => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 1],
            'created_at'         => ['type' => 'datetime', 'null' => true],
            'updated_at'         => ['type' => 'datetime', 'null' => true],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('id_lokasi');

        return $hasil && $this->dbforge->create_table('pembangunan', true);
    }

    protected function create_table_pembangunan_ref_dokumentasi($hasil)
    {
        $this->dbforge->add_field([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_pembangunan' => ['type' => 'INT', 'constraint' => 11],
            'gambar'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'persentase'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('id_pembangunan');

        return $hasil && $this->dbforge->create_table('pembangunan_ref_dokumentasi', true);
    }

    protected function add_modul_pembangunan($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 220,
            'modul'      => 'Pembangunan',
            'url'        => 'pembangunan',
            'aktif'      => 1,
            'ikon'       => 'fa-institution',
            'urut'       => 9,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-institution',
            'parent'     => 0,
        ]);

        $hasil = $hasil && $this->tambah_modul([
            'id'         => 221,
            'modul'      => 'Pembangunan Dokumentasi',
            'url'        => 'pembangunan_dokumentasi',
            'aktif'      => 1,
            'ikon'       => '',
            'urut'       => 0,
            'level'      => 0,
            'hidden'     => 2,
            'ikon_kecil' => '',
            'parent'     => 220,
        ]);

        //tambah kolom Foto utama di tabel pembangunan
        if (! $this->db->field_exists('foto', 'pembangunan')) {
            $hasil = $this->dbforge->add_column('pembangunan', [
                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }

        //tambah kolom Anggaran di tabel pembangunan
        if (! $this->db->field_exists('anggaran', 'pembangunan')) {
            $hasil = $this->dbforge->add_column('pembangunan', [
                'anggaran' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'default'    => '0',
                ],
            ]);
        }

        return $hasil;
    }

    // Tambah Sebutan jabatan kepala desa
    private function sebutan_kepala_desa($hasil)
    {
        $setting = [
            'key'        => 'sebutan_kepala_desa',
            'value'      => 'Kepala',
            'keterangan' => 'Pengganti sebutan jabatan Kepala Desa',
        ];

        return $hasil && $this->tambah_setting($setting);
    }

    private function urut_cetak($hasil)
    {
        //tambah kolom urut untuk tabel cetak semua di tabel tweb_wil_clusterdesa
        if (! $this->db->field_exists('urut_cetak', 'tweb_wil_clusterdesa')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_wil_clusterdesa', [
                'urut_cetak' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        }

        return $hasil;
    }

    // Bumindes updates
    protected function bumindes_updates($hasil)
    {
        // Updates for issues #2777
        $hasil = $hasil && $this->penduduk_induk($hasil);
        // Updates for issues #2778
        $hasil = $hasil && $this->penduduk_mutasi($hasil);

        // Menambahkan data pada setting_modul untuk controller bumindes_penduduk
        $data = [
            ['id' => 303, 'modul' => 'Administrasi Penduduk', 'url' => 'bumindes_penduduk_induk/clear', 'aktif' => 1, 'ikon' => 'fa-users', 'urut' => 2, 'level' => 2, 'hidden' => 0, 'ikon_kecil' => 'fa fa-users', 'parent' => 301],
            ['id' => 315, 'modul' => 'Buku Mutasi Penduduk', 'url' => 'bumindes_penduduk_mutasi/clear', 'aktif' => '1', 'ikon' => 'fa-files-o', 'urut' => 0, 'level' => 0, 'hidden' => 0, 'ikon_kecil' => '', 'parent' => 303],
            ['id' => 316, 'modul' => 'Buku Rekapitulasi Jumlah Penduduk', 'url' => 'bumindes_penduduk_rekapitulasi/clear', 'aktif' => '1', 'ikon' => 'fa-files-o', 'urut' => 0, 'level' => 0, 'hidden' => 0, 'ikon_kecil' => '', 'parent' => 303],
            ['id' => 317, 'modul' => 'Buku Penduduk Sementara', 'url' => 'bumindes_penduduk_sementara/clear', 'aktif' => '1', 'ikon' => 'fa-files-o', 'urut' => 0, 'level' => 0, 'hidden' => 0, 'ikon_kecil' => '', 'parent' => 303],
            ['id' => 318, 'modul' => 'Buku KTP dan KK', 'url' => 'bumindes_penduduk_ktpkk/clear', 'aktif' => '1', 'ikon' => 'fa-files-o', 'urut' => 0, 'level' => 0, 'hidden' => 0, 'ikon_kecil' => '', 'parent' => 303],
        ];

        foreach ($data as $modul) {
            $hasil = $hasil && $this->tambah_modul($modul);
        }

        return $hasil;
    }

    protected function penduduk_induk($hasil)
    {
        // Membuat table ref_penduduk_bahasa
        $this->dbforge->add_field([
            'id'      => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'inisial' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false],
        ]);

        $this->dbforge->add_key('id', true);
        $hasil = $hasil && $this->dbforge->create_table('ref_penduduk_bahasa', true);

        // Menambahkan bahasa_id pada table tweb_penduduk, digunakan untuk define bahasa penduduk
        if (! $this->db->field_exists('bahasa_id', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', [
                'bahasa_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        }

        // Menambahkan column ket pada table tweb_penduduk, digunakan untuk keterangan penduduk
        if (! $this->db->field_exists('ket', 'tweb_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', [
                'ket' => [
                    'type' => 'TINYTEXT',
                    'null' => true,
                ],
            ]);
        }

        // Tambah data awal tabel ref_penduduk_bahasa
        if ($hasil && $this->db->truncate('ref_penduduk_bahasa')) {
            $ref_penduduk_bahasa = [
                ['id' => 1, 'nama' => 'Latin', 'inisial' => 'L'],
                ['id' => 2, 'nama' => 'Daerah', 'inisial' => 'D'],
                ['id' => 3, 'nama' => 'Arab', 'inisial' => 'A'],
                ['id' => 4, 'nama' => 'Arab dan Latin', 'inisial' => 'AL'],
                ['id' => 5, 'nama' => 'Arab dan Daerah', 'inisial' => 'AD'],
                ['id' => 6, 'nama' => 'Arab, Latin dan Daerah', 'inisial' => 'ALD'],
            ];

            $hasil = $hasil && $this->db->insert_batch('ref_penduduk_bahasa', $ref_penduduk_bahasa);
        }

        return $hasil;
    }

    protected function penduduk_mutasi($hasil)
    {
        // Mengubah column tanggal menjadi tanggal_lapor
        if (! $this->db->field_exists('tgl_lapor', 'log_penduduk')) {
            $hasil = $hasil && $this->db->query('ALTER TABLE log_penduduk CHANGE COLUMN tanggal tgl_lapor TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        }

        // Menambahkan column created_at
        if (! $this->db->field_exists('created_at', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', 'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        }

        // Menambahkan column created_by
        if (! $this->db->field_exists('created_by', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', [
                'created_by' => [
                    'type' => 'INT',
                    'null' => true,
                ],
            ]);
        }

        // Menambahkan column updated_at
        if (! $this->db->field_exists('updated_at', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', 'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        }

        // Menambahkan column created_by
        if (! $this->db->field_exists('updated_by', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', [
                'updated_by' => [
                    'type' => 'INT',
                    'null' => true,
                ],
            ]);
        }

        // Menambahkan column meninggal_di
        if (! $this->db->field_exists('meninggal_di', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', [
                'meninggal_di' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'after'      => 'id_detail',
                ],
            ]);
        }

        // Menambahkan column alamat_tujuan
        if (! $this->db->field_exists('alamat_tujuan', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->add_column('log_penduduk', [
                'alamat_tujuan' => [
                    'type'  => 'TINYTEXT',
                    'null'  => true,
                    'after' => 'meninggal_di',
                ],
            ]);
        }

        // Menghapus column tahun, dan bulan
        if ($this->db->field_exists('tahun', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->drop_column('log_penduduk', 'tahun');
        }
        if ($this->db->field_exists('bulan', 'log_penduduk')) {
            $hasil = $hasil && $this->dbforge->drop_column('log_penduduk', 'bulan');
        }

        // Merubah status pendatang menjadi tidak tetap
        $hasil = $hasil && $this->db->set('status', 2)->where('status', 3)->update('tweb_penduduk');

        // Mengubah column id_detail menjadi kode_peristiwa
        if (! $this->db->field_exists('kode_peristiwa', 'log_penduduk')) {
            $hasil = $hasil && $this->db->query('ALTER TABLE log_penduduk CHANGE COLUMN id_detail kode_peristiwa INT NULL AFTER id_pend');
        }

        // Menghapus data Pendatang dari table status, dan mengubah auto increment ke 3
        if ($this->db->get_where('tweb_penduduk_status', ['id' => 3])) {
            $hasil = $hasil && $this->db->delete('tweb_penduduk_status', ['id' => 3]);
            $hasil = $hasil && $this->db->query('ALTER TABLE tweb_penduduk_status AUTO_INCREMENT = 3');
        }

        // Membuat table ref_peristiwa
        $this->dbforge->add_field([
            'id'   => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
        ]);

        $this->dbforge->add_key('id', true);
        $hasil = $hasil && $this->dbforge->create_table('ref_peristiwa', true);

        // Menambahkan data ke ref_peristiwa
        $data = [
            ['id' => 1, 'nama' => 'Lahir'],
            ['id' => 2, 'nama' => 'Mati'],
            ['id' => 3, 'nama' => 'Pindah Keluar'],
            ['id' => 4, 'nama' => 'Hilang'],
            ['id' => 5, 'nama' => 'Pindah Masuk'],
        ];

        foreach ($data as $peristiwa) {
            $sql = $this->db->insert_string('ref_peristiwa', $peristiwa);
            $sql .= ' ON DUPLICATE KEY UPDATE
                    id = VALUES(id),
                    nama = VALUES(nama)';
            $hasil = $hasil && $this->db->query($sql);
        }

        /* KETERANGAN id_detail (sebelum konversi ke kode_peristiwa)
           1 = status hidup, insert penduduk baru lahir
           2 = status menjadi mati
             3 = status menjadi pindah
             4 = status menjadi hilang
             5 = insert penduduk baru dengan status tetap/tidak tetap
             6 = pindah dalam desa
             7 = hapus anggota keluarga
             8 = insert penduduk baru dengan status pendatang
             9 = tambah keluarga baru dari penduduk yang sudah ada
        */

        /* KETERANGAN kode_peristiwa
           1 = insert penduduk baru dengan status lahir
           2 = penduduk mati
             3 = penduduk pindah
             4 = penduduk hilang
             5 = insert penduduk baru dengan status masuk
        */

        // Hapus log untuk penduduk yg sudah terhapus
        $hasil = $hasil && $this->db
            ->where('id_pend IN
                (select id_pend from
                    (select l.id_pend
                        from log_penduduk l
                        left join tweb_penduduk p on l.id_pend = p.id
                        where p.id is null) x
                )')
            ->delete('log_penduduk');

        // Konversi id_detail ke kode_peristiwa di log_penduduk
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa', 8)
            ->set('kode_peristiwa', 5)
            ->update('log_penduduk');

        // Konversi kode_peristiwa null dari migrasi salah sebelumnya
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa IS NULL')
            ->set('kode_peristiwa', 5)
            ->update('log_penduduk');

        // Hapus log_penduduk yg tidak diperlukan lagi (id_detail tidak berlaku lagi)
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa IN (6,7,9)')
            ->delete('log_penduduk');

        // Hapus log salah untuk penduduk dgn status dasar hidup
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa NOT IN (1,5)')
            ->where('id_pend IN (select id from penduduk_hidup)')
            ->delete('log_penduduk');

        // Hapus log salah untuk penduduk dgn status dasar mati
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa NOT IN (1,5,2)')
            ->where('id_pend IN (select id from tweb_penduduk where status_dasar = 2)')
            ->delete('log_penduduk');

        // Hapus log salah untuk penduduk dgn status dasar pindah
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa NOT IN (1,5,3)')
            ->where('id_pend IN (select id from tweb_penduduk where status_dasar = 3)')
            ->delete('log_penduduk');

        // Hapus log salah untuk penduduk dgn status dasar hilang
        $hasil = $hasil && $this->db
            ->where('kode_peristiwa NOT IN (1,5,4)')
            ->where('id_pend IN (select id from tweb_penduduk where status_dasar = 4)')
            ->delete('log_penduduk');

        // Menambahkan data yang sudah ada ke tabel log_penduduk kalau belum ada
        $hasil = $hasil && $this->db->query(
            '
            INSERT INTO log_penduduk (id_pend, tgl_lapor, tgl_peristiwa, created_at, kode_peristiwa)
            SELECT p.id, p.created_at, p.created_at, p.created_at,
            (CASE when YEAR(p.tanggallahir) = YEAR(p.created_at) AND MONTH(p.tanggallahir) = MONTH(p.created_at) then 1 else 5 end)
            FROM tweb_penduduk p
            LEFT JOIN log_penduduk l on l.id_pend = p.id and l.kode_peristiwa in (1,5)
            WHERE l.tgl_lapor IS NULL'
        );

        // Hapus log tertua untuk duplikat (id_pend, kode_peristiwa).
        // Misalnya hapus kalau ada dua entri 'mati' untuk penduduk yg sama.
        // https://stackoverflow.com/questions/6107167/mysql-delete-duplicate-records-but-keep-latest/6108860
        $hapus_dupl_sql = 'delete log_penduduk
            from log_penduduk
                inner join (
                    select max(id) as last_id, id_pend, kode_peristiwa
                    from log_penduduk
                    group by id_pend, kode_peristiwa
                        having count(*) > 1
                    ) dup
                on dup.id_pend = log_penduduk.id_pend and dup.kode_peristiwa = log_penduduk.kode_peristiwa
            where log_penduduk.id < dup.last_id';
        $hasil = $hasil && $this->db->query($hapus_dupl_sql);

        // Menambahkan data ke setting_aplikasi
        $data_setting = [
            ['key' => 'tgl_data_lengkap', 'keterangan' => 'Atur data tanggal sudah lengkap', 'jenis' => 'datetime'],
            ['key' => 'tgl_data_lengkap_aktif', 'value' => 0, 'keterangan' => 'Aktif / Non-aktif data tanggal sudah lengkap', 'jenis' => 'boolean'],
        ];

        foreach ($data_setting as $setting) {
            $sql = $this->db->insert_string('setting_aplikasi', $setting);
            $sql .= ' ON DUPLICATE KEY UPDATE
                    keterangan = VALUES(keterangan),
                    jenis = VALUES(jenis)';
            $hasil = $hasil && $this->db->query($sql);
        }

        return $hasil;
    }
}
