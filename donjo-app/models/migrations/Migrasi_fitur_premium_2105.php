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

class Migrasi_fitur_premium_2105 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2104');

        // Ubah kolom supaya ada nilai default
        $fields = [
            'kartu_tempat_lahir' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'default' => ''],
            'kartu_alamat'       => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false, 'default' => ''],
        ];

        // Ubah keterangan setting aplikasi
        $hasil = $hasil && $this->db->where('key', 'google_key')->update('setting_aplikasi', ['key' => 'mapbox_key', 'keterangan' => 'Mapbox API Key untuk peta']);

        $hasil = $hasil && $this->dbforge->modify_column('program_peserta', $fields);
        $hasil = $hasil && $this->server_publik();
        $hasil = $hasil && $this->convert_ip_address($hasil);
        $hasil = $hasil && $this->tambah_kolom_log_keluarga($hasil);
        $hasil = $hasil && $this->create_table_tanah_desa($hasil);
        $hasil = $hasil && $this->create_table_tanah_kas_desa($hasil);
        $hasil = $hasil && $this->hapus_kolom_tanah_di_desa();
        $hasil = $hasil && $this->hapus_kolom_tanah_kas_desa();
        $hasil = $hasil && $this->hapus_kolom_persil_tanah_kas_desa();
        $hasil = $hasil && $this->ubah_kolom_tanah_di_desa();
        $hasil = $hasil && $this->tambah_kolom_tanah_di_desa();
        $hasil = $hasil && $this->tambah_kolom_nik_tanah_di_desa();
        $hasil = $hasil && $this->tambah_kolom_tanah_kas_desa();
        $hasil = $hasil && $this->pengaturan_grup($hasil);
        $hasil = $hasil && $this->bumindes_updates($hasil);		//harus setelah fungsi pengaturan grup

        return $hasil && $this->impor_google_form($hasil);
    }

    protected function server_publik()
    {
        // Tampilkan menu Sekretariat di pengaturan modul
        $hasil = $this->db
            ->where('id', 15)
            ->set('hidden', 0)
            ->set('parent', 0)
            ->update('setting_modul');
        $hasil = $hasil && $this->tambah_kolom_updated_at();

        return $hasil && $this->buat_tabel_ref_sinkronisasi();
    }

    // Tambah kolom untuk memungkinkkan sinkronsisasi
    protected function tambah_kolom_updated_at()
    {
        $hasil = true;
        if (! $this->db->field_exists('updated_at', 'tweb_keluarga')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_keluarga', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $hasil = $hasil && $this->dbforge->add_column('tweb_keluarga', 'updated_by int(11) NOT NULL');
        }

        return $hasil;
    }

    protected function buat_tabel_ref_sinkronisasi()
    {
        $hasil = true;
        // Buat folder unggah sinkronisasi
        mkdir(LOKASI_SINKRONISASI_ZIP, 0775, true);
        // Tambah rincian pindah di log_penduduk
        $tabel = 'ref_sinkronisasi';
        if ($this->db->table_exists($tabel)) {
            return $hasil;
        }

        $this->dbforge->add_field([
            'tabel'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'server'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'jenis_update' => ['type' => 'TINYINT', 'constraint' => 4, 'null' => true, 'default' => null],
            'tabel_hapus'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'default' => null],
        ]);
        $this->dbforge->add_key('tabel', true);
        $hasil = $hasil && $this->dbforge->create_table($tabel, true);

        return $hasil && $this->db->insert_batch(
            $tabel,
            [
                ['tabel' => 'tweb_penduduk', 'server' => '6', 'jenis_update' => 1, 'tabel_hapus' => 'log_hapus_penduduk'],
                ['tabel' => 'tweb_keluarga', 'server' => '6', 'jenis_update' => 1, 'tabel_hapus' => 'log_keluarga'],
            ]
        );
    }

    /**
     * Convert ip address.
     *
     * @param mixed $hasil
     */
    protected function convert_ip_address($hasil)
    {
        $data = $this->db
            ->not_like('ipAddress', 'ip_address')
            ->get('sys_traffic')
            ->result();

        if ($data) {
            $batch = [];

            foreach ($data as $sys_traffic) {
                $remove_character = str_replace('{}', '', $sys_traffic->ipAddress);

                $batch[] = [
                    'ipAddress' => json_encode(['ip_address' => [$remove_character]]),
                    'Tanggal'   => $sys_traffic->Tanggal,
                ];
            }

            $hasil = $hasil && $this->db->update_batch('sys_traffic', $batch, 'Tanggal');
        }

        return $hasil;
    }

    protected function setting_script_id_gform($hasil)
    {
        // Menambahkan data Script ID Google API pada Setting Aplikasi
        $data_setting = [
            'key'        => 'api_gform_id_script',
            'value'      => '',
            'keterangan' => 'Script ID untuk Google API',
            'kategori'   => 'setting_analisis',
        ];

        $hasil = $hasil && $this->tambah_setting($data_setting);

        // Menambahkan data Credential Google API pada Setting Aplikasi
        $data_setting = [
            'key'        => 'api_gform_credential',
            'value'      => '',
            'keterangan' => 'Credential untuk Google API',
            'jenis'      => 'textarea',
            'kategori'   => 'setting_analisis',
        ];

        $hasil = $hasil && $this->tambah_setting($data_setting);

        // Menambahkan data Redirect URI Google API pada Setting Aplikasi
        $data_setting = [
            'key'        => 'api_gform_redirect_uri',
            'value'      => 'https://berputar.opendesa.id/index.php/first/get_form_info',
            'keterangan' => 'Redirecet URI untuk Google API',
            'kategori'   => 'setting_analisis',
        ];

        return $hasil && $this->tambah_setting($data_setting);
    }

    protected function tambah_kolom_log_keluarga($hasil)
    {
        if (! $this->db->field_exists('id_pend', 'log_keluarga')) {
            $hasil = $hasil && $this->dbforge->add_column('log_keluarga', [
                'id_pend'    => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'updated_by' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
            ]);
            $hasil = $hasil && $this->isi_ulang_log_keluarga($hasil);
        }
        if (! $this->db->field_exists('id_log_penduduk', 'log_keluarga')) {
            $hasil = $hasil && $this->dbforge->add_column('log_keluarga', [
                'id_log_penduduk' => ['type' => 'INT', 'constraint' => 10, 'null' => true],
            ]);
            $hasil = $hasil && $this->dbforge->add_column('log_keluarga', [
                'CONSTRAINT `log_penduduk_fk` FOREIGN KEY (`id_log_penduduk`) REFERENCES `log_penduduk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
            ]);
        }
        // Pindahkan log_penduduk lama ke log_keluarga
        // Perhatikan pemindahan ini tidak akan dilakukan jika semua log id_peristiwa = 7
        // terhapus pada Migrasi_fitur_premium_2102.php
        $log_keluar = $this->db
            ->select('l.id as id, l.id_pend, k.id as id_kk, p2.sex as kk_sex')
            ->where('l.kode_peristiwa', 7)
            ->from('log_penduduk l')
            ->join('tweb_penduduk p1', 'p1.id = l.id_pend')
            ->join('tweb_keluarga k', 'k.no_kk = p1.no_kk_sebelumnya', 'left')
            ->join('tweb_penduduk p2', 'p2.id = k.nik_kepala', 'left')
            ->get()
            ->result_array();
        if (count($log_keluar) == 0) {
            return $hasil;
        }
        $data = [];

        foreach ($log_keluar as $log) {
            if (! $log['id_kk']) {
                continue;
            } // Abaikan kasus keluar dari keluarga
            $data[] = [
                'id_peristiwa'  => 12,
                'tgl_peristiwa' => $log['tgl_peristiwa'],
                'updated_by'    => $log['updated_by'] ?: $this->session->user,
                'id_kk'         => $log['id_kk'],
                'kk_sex'        => $log['kk_sex'],
                'id_pend'       => $log['id_pend'],
            ];
        }
        $hasil = $hasil && $this->db->insert_batch('log_keluarga', $data);

        return $hasil && $this->db
            ->where_in('id', array_column($log_keluar, 'id'))
            ->delete('log_penduduk');
    }

    // Catat ulang semua keluarga di log_keluarga untuk laporan bulanan
    private function isi_ulang_log_keluarga($hasil)
    {
        // Kosongkan
        $this->db->truncate('log_keluarga');
        // Tambah keluarga yg ada sebagai keluarga baru
        $keluarga = $this->db
            ->select('k.id as id_kk, p.sex as kk_sex, "1" as id_peristiwa, tgl_daftar as tgl_peristiwa, "1" as updated_by')
            ->from('tweb_keluarga k')
            ->join('tweb_penduduk p', 'p.id = k.nik_kepala')
            ->get()->result_array();
        $hasil = $hasil && $this->db->insert_batch('log_keluarga', $keluarga);

        // Tambah mutasi keluarga
        $mutasi = $this->db
            ->select('k.id as id_kk, p.sex as kk_sex, lp.tgl_lapor as tgl_peristiwa')
            ->select('(case when lp.kode_peristiwa in (2, 3, 4) then lp.kode_peristiwa end) as id_peristiwa')
            ->select('"1" as updated_by')
            ->from('tweb_keluarga k')
            ->join('tweb_penduduk p', 'p.id = k.nik_kepala')
            ->join('log_penduduk lp', 'lp.id_pend = p.id and lp.kode_peristiwa = p.status_dasar')
            ->where('p.status_dasar <>', 1)
            ->get()->result_array();
        if (! empty($mutasi)) {
            $hasil = $hasil && $this->db->insert_batch('log_keluarga', $mutasi);
        }

        return $hasil;
    }

    protected function create_table_tanah_desa($hasil)
    {
        $this->dbforge->add_field([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_penduduk'       => ['type' => 'INT', 'constraint' => 10],
            'nama_pemilik_asal' => ['type' => 'VARCHAR', 'constraint' => 200],
            'hak_tanah'         => ['type' => 'TEXT'],
            'penggunaan_tanah'  => ['type' => 'TEXT'],
            'luas'              => ['type' => 'INT', 'constraint' => 10],
            'lain'              => ['type' => 'TEXT'],
            'mutasi'            => ['type' => 'TEXT'],
            'keterangan'        => ['type' => 'TEXT'],
            'created_at timestamp default current_timestamp',
            'created_by' => ['type' => 'INT', 'constraint' => 10],
            'updated_at timestamp default current_timestamp',
            'updated_by' => ['type' => 'INT', 'constraint' => 10],
            'visible'    => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 1],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('id_penduduk');

        return $hasil && $this->dbforge->create_table('tanah_desa', true);
    }

    protected function create_table_tanah_kas_desa($hasil)
    {
        $this->dbforge->add_field([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama_pemilik_asal' => ['type' => 'VARCHAR', 'constraint' => 200],
            'letter_c'          => ['type' => 'TEXT'],
            'persil'            => ['type' => 'TEXT'],
            'kelas'             => ['type' => 'TEXT'],
            'luas'              => ['type' => 'INT', 'constraint' => 10],
            'perolehan_tkd'     => ['type' => 'TEXT'],
            'jenis_tkd'         => ['type' => 'TEXT'],
            'patok'             => ['type' => 'TEXT'],
            'papan_nama'        => ['type' => 'TEXT'],
            'tanggal_perolehan date',
            'lokasi'     => ['type' => 'TEXT'],
            'peruntukan' => ['type' => 'TEXT'],
            'mutasi'     => ['type' => 'TEXT'],
            'keterangan' => ['type' => 'TEXT'],
            'created_at timestamp default current_timestamp',
            'created_by' => ['type' => 'INT', 'constraint' => 10],
            'updated_at timestamp default current_timestamp',
            'updated_by' => ['type' => 'INT', 'constraint' => 10],
            'visible'    => ['type' => 'TINYINT', 'constraint' => 2, 'default' => 1],
        ]);

        $this->dbforge->add_key('id', true);

        return $hasil && $this->dbforge->create_table('tanah_kas_desa', true);
    }

    // Hapus kolom tanah di desa
    protected function hapus_kolom_tanah_di_desa()
    {
        $hasil = true;
        if ($this->db->field_exists('hak_tanah', 'tanah_desa')) {
            $hasil = $hasil && $this->dbforge->drop_column('tanah_desa', 'hak_tanah');
            $hasil = $hasil && $this->dbforge->drop_column('tanah_desa', 'penggunaan_tanah');
        }

        return $hasil;
    }

    // Hapus kolom tanah kas desa
    protected function hapus_kolom_tanah_kas_desa()
    {
        $hasil = true;

        if ($this->db->field_exists('perolehan_tkd', 'tanah_kas_desa')) {
            $hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'perolehan_tkd');
            $hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'jenis_tkd');
            $hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'patok');
            $hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'papan_nama');
        }

        return $hasil;
    }

    // Hapus kolom persil tanah kas desa
    protected function hapus_kolom_persil_tanah_kas_desa()
    {
        $hasil = true;

        if ($this->db->field_exists('persil', 'tanah_kas_desa')) {
            $hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'persil');
        }

        return $hasil;
    }

    // Ubah kolom tanah desa
    protected function ubah_kolom_tanah_di_desa()
    {
        $hasil  = true;
        $fields = [
            'lain' => ['type' => 'int', 'constraint' => 11],
        ];

        return $hasil && $this->dbforge->modify_column('tanah_desa', $fields);
    }

    // Tambah kolom tanah di desa
    protected function tambah_kolom_tanah_di_desa()
    {
        $hasil = true;
        if (! $this->db->field_exists('hak_milik', 'tanah_desa')) {
            $hasil = $hasil && $this->dbforge->add_column('tanah_desa', [
                'jenis_pemilik'        => ['type' => 'TEXT', 'after' => 'id_penduduk'],
                'hak_milik'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'luas'],
                'hak_guna_bangunan'    => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_milik'],
                'hak_pakai'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_guna_bangunan'],
                'hak_guna_usaha'       => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_pakai'],
                'hak_pengelolaan'      => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_guna_usaha'],
                'hak_milik_adat'       => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_pengelolaan'],
                'hak_verponding'       => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_milik_adat'],
                'tanah_negara'         => ['type' => 'INT', 'constraint' => 11, 'after' => 'hak_verponding'],
                'perumahan'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'tanah_negara'],
                'perdagangan_jasa'     => ['type' => 'INT', 'constraint' => 11, 'after' => 'perumahan'],
                'perkantoran'          => ['type' => 'INT', 'constraint' => 11, 'after' => 'perdagangan_jasa'],
                'industri'             => ['type' => 'INT', 'constraint' => 11, 'after' => 'perkantoran'],
                'fasilitas_umum'       => ['type' => 'INT', 'constraint' => 11, 'after' => 'industri'],
                'sawah'                => ['type' => 'INT', 'constraint' => 11, 'after' => 'fasilitas_umum'],
                'tegalan'              => ['type' => 'INT', 'constraint' => 11, 'after' => 'sawah'],
                'perkebunan'           => ['type' => 'INT', 'constraint' => 11, 'after' => 'tegalan'],
                'peternakan_perikanan' => ['type' => 'INT', 'constraint' => 11, 'after' => 'perkebunan'],
                'hutan_belukar'        => ['type' => 'INT', 'constraint' => 11, 'after' => 'peternakan_perikanan'],
                'hutan_lebat_lindung'  => ['type' => 'INT', 'constraint' => 11, 'after' => 'hutan_belukar'],
                'tanah_kosong'         => ['type' => 'INT', 'constraint' => 11, 'after' => 'hutan_lebat_lindung'],
            ]);
        }

        return $hasil;
    }

    protected function tambah_kolom_nik_tanah_di_desa()
    {
        $hasil = true;
        if (! $this->db->field_exists('nik', 'tanah_desa')) {
            $hasil = $hasil && $this->dbforge->add_column('tanah_desa', [
                'nik' => ['type' => 'DECIMAL', 'constraint' => 16.0, 'after' => 'id_penduduk'],
            ]);
        }

        return $hasil;
    }

    // Tambah kolom tanah kas desa
    protected function tambah_kolom_tanah_kas_desa()
    {
        $hasil = true;
        if (! $this->db->field_exists('asli_milik_desa', 'tanah_kas_desa')) {
            $hasil = $hasil && $this->dbforge->add_column('tanah_kas_desa', [
                'asli_milik_desa'      => ['type' => 'INT', 'constraint' => 11, 'after' => 'luas'],
                'pemerintah'           => ['type' => 'INT', 'constraint' => 11, 'after' => 'asli_milik_desa'],
                'provinsi'             => ['type' => 'INT', 'constraint' => 11, 'after' => 'pemerintah'],
                'kabupaten_kota'       => ['type' => 'INT', 'constraint' => 11, 'after' => 'provinsi'],
                'lain_lain'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'kabupaten_kota'],
                'sawah'                => ['type' => 'INT', 'constraint' => 11, 'after' => 'lain_lain'],
                'tegal'                => ['type' => 'INT', 'constraint' => 11, 'after' => 'sawah'],
                'kebun'                => ['type' => 'INT', 'constraint' => 11, 'after' => 'tegal'],
                'tambak_kolam'         => ['type' => 'INT', 'constraint' => 11, 'after' => 'kebun'],
                'tanah_kering_darat'   => ['type' => 'INT', 'constraint' => 11, 'after' => 'tambak_kolam'],
                'ada_patok'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'tanah_kering_darat'],
                'tidak_ada_patok'      => ['type' => 'INT', 'constraint' => 11, 'after' => 'ada_patok'],
                'ada_papan_nama'       => ['type' => 'INT', 'constraint' => 11, 'after' => 'tidak_ada_patok'],
                'tidak_ada_papan_nama' => ['type' => 'INT', 'constraint' => 11, 'after' => 'ada_papan_nama'],
            ]);
        }

        return $hasil;
    }

    protected function pengaturan_grup($hasil)
    {
        $this->cache->hapus_cache_untuk_semua('_cache_modul');
        // Hapus controller 'wilayah' yang boleh diakses oleh semua pengguna yg telah login
        $hasil = $hasil && $this->db->where('url', 'wilayah')->delete('setting_modul');

        $hasil = $hasil && $this->modul_tambahan($hasil);
        $hasil = $hasil && $this->ubah_grup($hasil);
        $hasil = $hasil && $this->tambah_grupAkses($hasil);
        $hasil = $hasil && $this->urut_modul($hasil);
        $hasil = $hasil && $this->bersihkan_modul($hasil);

        return $hasil && $this->akses_grup_bawaan($hasil);
    }

    private function ubah_grup($hasil)
    {
        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 5, 'auto_increment' => true],
        ];
        $hasil = $hasil && $this->dbforge->modify_column('user_grup', $fields);
        if (! $this->db->field_exists('created_by', 'user_grup')) {
            $hasil = $hasil && $this->dbforge->add_column('user_grup', [
                'jenis' => ['type' => 'TINYINT', 'constraint' => 2, 'null' => false, 'default' => 1],
                'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'created_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_by' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
            ]);
        }
        // Grup tambahan
        return $hasil && $this->db->where('id >', 4)->update('user_grup', ['jenis' => 2]);
    }

    private function tambah_grupAkses($hasil)
    {
        if ($this->db->table_exists('grup_akses')) {
            return $hasil;
        }

        $this->dbforge->add_field([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_grup'  => ['type' => 'INT', 'null' => false],
            'id_modul' => ['type' => 'INT', 'null' => false],
            'akses'    => ['type' => 'TINYINT', 'null' => true],
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('id_grup');
        $this->dbforge->add_key('id_modul');
        $hasil = $hasil && $this->dbforge->create_table('grup_akses', true);

        return $hasil && $this->dbforge->add_column('grup_akses', [
            'CONSTRAINT fk_id_grup FOREIGN KEY(id_grup) REFERENCES user_grup(id) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT fk_id_modul FOREIGN KEY(id_modul) REFERENCES setting_modul(id) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);
    }

    private function urut_modul($hasil)
    {
        $urut = [
            ['id' => 206, 'urut' => 5], // Siaga Covid-19
            ['id' => 1, 'urut' => 10], // Home
            ['id' => 200, 'urut' => 20], // Info Desa
            ['id' => 2, 'urut' => 30], // Kependudukan
            ['id' => 3, 'urut' => 40], // Statistik
            ['id' => 4, 'urut' => 50], // Layanan Surat
            ['id' => 15, 'urut' => 60], // Sekretariat
            ['id' => 301, 'urut' => 70], // Buku Administrasi Desa
            ['id' => 201, 'urut' => 80], // Keuangan
            ['id' => 5, 'urut' => 90], // Analisis
            ['id' => 6, 'urut' => 100], // Bantuan
            ['id' => 7, 'urut' => 110], // Pertanahan
            ['id' => 220, 'urut' => 120], // Pembangunan
            ['id' => 9, 'urut' => 130], // Pemetaan
            ['id' => 10, 'urut' => 140], // SMS
            ['id' => 11, 'urut' => 150], // Pengaturan
            ['id' => 13, 'urut' => 160], // Admin Web
            ['id' => 14, 'urut' => 170], // Layanan Mandiri
        ];
        $fields = [
            'urut' => ['type' => 'INT', 'constraint' => 4],
        ];
        $hasil = $hasil && $this->dbforge->modify_column('setting_modul', $fields);

        foreach ($urut as $modul) {
            $hasil = $hasil && $this->db
                ->where('id', $modul['id'])
                ->update('setting_modul', ['urut' => $modul['urut']]);
        }

        return $hasil;
    }

    private function akses_grup_bawaan($hasil)
    {
        // Simpan grup akses yang ada sebelumnya kecuali grup_akses bawaan
        $grup = $this->db->where_not_in('id_grup', [2, 3, 4])->get('grup_akses')->result_array();
        array_walk($grup, static function (&$key) {
            unset($key['id']);
        });

        // Kosongkan tabel grup_akses
        if ($hasil && $this->db->truncate('grup_akses')) {
            if ($grup) {
                $hasil = $hasil && $this->db->insert_batch('grup_akses', $grup);
            }
        } else {
            return false;
        }

        // Operator, Redaksi, Kontributor, Satgas Covid-19
        $query = '
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,1,3),
			(2,2,0),
			(2,3,0),
			(2,4,0),
			(2,5,0),
			(2,6,3),
			(2,7,0),
			(2,8,3),
			(2,9,0),
			(2,10,0),
			(2,11,0),
			(2,13,0),
			(2,14,0),
			(2,15,0),
			(2,17,3),
			(2,18,3),
			(2,20,3),
			(2,21,3),
			(2,22,3),
			(2,23,3),
			(2,24,3),
			(2,25,3),
			(2,26,3),
			(2,27,3),
			(2,28,3),
			(2,29,3),
			(2,30,3),
			(2,31,3),
			(2,32,3),
			(2,39,3),
			(2,40,3),
			(2,42,3),
			(2,47,3),
			(2,48,3),
			(2,49,3),
			(2,50,3),
			(2,51,3),
			(2,52,3),
			(2,53,3),
			(2,54,3),
			(2,55,3),
			(2,56,3),
			(2,57,3),
			(2,58,3),
			(2,61,3),
			(2,62,3),
			(2,63,3),
			(2,64,3),
			(2,65,3),
			(2,66,3),
			(2,67,3),
			(2,68,3),
			(2,69,3),
			(2,70,3),
			(2,71,3),
			(2,72,3),
			(2,73,3),
			(2,75,3),
			(2,76,3),
			(2,77,3),
			(2,78,3),
			(2,79,3),
			(2,80,3),
			(2,81,3),
			(2,82,3),
			(2,83,3),
			(2,84,3),
			(2,85,3),
			(2,86,3),
			(2,87,3),
			(2,88,3),
			(2,89,3),
			(2,90,3),
			(2,91,3),
			(2,92,3),
			(2,93,3),
			(2,94,3),
			(2,95,3),
			(2,96,3),
			(2,97,3),
			(2,98,3),
			(2,101,3),
			(2,200,0),
			(2,201,0),
			(2,202,3),
			(2,203,3),
			(2,205,3),
			(2,206,0),
			(2,207,7),
			(2,208,7),
			(2,209,3),
			(2,210,3),
			(2,211,3),
			(2,212,3),
			(2,213,3),
			(2,220,0),
			(2,221,3),
            (2,301,0),
			(2,302,3),
			(2,303,3),
			(2,304,3),
			(2,305,3),
			(2,310,3),
			(2,311,3),
			(2,312,3),
			(2,314,3),
			(2,315,3),
			(2,316,3),
			(2,317,3),
			(2,318,3),
			-- Redaksi --
			(3,13,0),
			(3,47,7),
			(3,48,7),
			(3,49,7),
			(3,50,7),
			(3,51,7),
			(3,53,7),
			(3,54,7),
			(3,64,7),
			(3,205,7),
			(3,211,7),
			-- Kontributor --
			(4,13,0),
			(4,47,3),
			(4,50,3),
			(4,51,3),
			(4,54,3)
		';
        $hasil = $hasil && $this->db->query($query);

        // Hanya isi jika grup Satgas Covid masih ada dan grup_akses belum ada (Jangan ubah grup_akses satgas covid jika sudah ada)
        if ($this->db->get_where('user_grup', ['id' => 5])->row()) {
            if (! $this->db->get_where('grup_akses', ['id_grup' => 5, 'id_modul' => 3])->row()) {
                $this->grupAkses(5, 3, 0);
            }

            if (! $this->db->get_where('grup_akses', ['id_grup' => 5, 'id_modul' => 206])->row()) {
                $this->grupAkses(5, 206, 0);
            }

            if (! $this->db->get_where('grup_akses', ['id_grup' => 5, 'id_modul' => 208])->row()) {
                $this->grupAkses(5, 208, 7);
            }
        }

        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    // Kosongkan url modul yg mempunyai sub modul
    private function bersihkan_modul($hasil)
    {
        // Modul utama yg mempunyai sub
        $ada_sub = $this->db
            ->distinct()
            ->select('m.id')
            ->from('setting_modul as m')
            ->join('setting_modul sub', 'sub.parent = m.id and sub.hidden <> 2')
            ->where('m.parent', 0)
            ->where('m.url <>', '')
            ->where('sub.id is not null')
            ->order_by('m.id')
            ->get()
            ->result_array();

        if ($ada_sub) {
            $ada_sub = array_column($ada_sub, 'id');

            $hasil = $hasil && $this->db
                ->set('url', '')
                ->where_in('id', $ada_sub)
                ->update('setting_modul');
        }

        return $hasil;
    }

    // Beri nilai default setting_modul utk memudahkan menambah modul
    private function modul_tambahan($hasil)
    {
        $this->db->like('url', 'man_user')->update('setting_modul', ['url' => 'man_user/clear']);
        $fields = [
            'ikon'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'default' => ''],
            'ikon_kecil' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'default' => ''],
        ];
        $hasil = $hasil && $this->dbforge->modify_column('setting_modul', $fields);

        return $hasil && $this->tambah_modul([
            'id'     => 102,
            'modul'  => 'Pengaturan Grup',
            'url'    => 'grup/clear',
            'aktif'  => 1,
            'urut'   => 0,
            'level'  => 0,
            'hidden' => 2,
            'parent' => 44,
        ]);
    }

    protected function bumindes_updates($hasil)
    {
        //update nama modul Bumindes Tanah Desa
        $hasil = $hasil && $this->db->where('id', 305)->update('setting_modul', ['url' => 'bumindes_tanah_desa/clear']);

        //menambahkan data pada setting_modul untuk controller 'bumindes_tanah_kas_desa'
        return $hasil && $this->tambah_modul([
            'id'         => 319,
            'modul'      => 'Buku Tanah Kas Desa',
            'url'        => 'bumindes_tanah_kas_desa/clear',
            'aktif'      => 1,
            'ikon'       => 'fa-files-o',
            'urut'       => 0,
            'level'      => 0,
            'hidden'     => 0,
            'ikon_kecil' => '',
            'parent'     => 305,
        ]);
    }

    private function tambah_pengaturan_analisis($hasil)
    {
        // Kosongkan url modul analisis yg sekarang ditambahkan submodul
        $hasil = $hasil && $this->db
            ->set('url', '')
            ->where('id', 5)
            ->update('setting_modul');

        $hasil = $hasil && $this->tambah_modul([
            'id'         => 110,
            'modul'      => 'Master Analisis',
            'url'        => 'analisis_master/clear',
            'aktif'      => 1,
            'ikon'       => 'fa-check-square-o',
            'ikon_kecil' => 'fa-check-square-o',
            'urut'       => 1,
            'level'      => 1,
            'hidden'     => 0,
            'parent'     => 5,
        ]);

        return $hasil && $this->tambah_modul([
            'id'         => 111,
            'modul'      => 'Pengaturan',
            'url'        => 'setting/analisis',
            'aktif'      => 1,
            'ikon'       => 'fa-gear',
            'ikon_kecil' => 'fa-gear',
            'urut'       => 2,
            'level'      => 1,
            'hidden'     => 0,
            'parent'     => 5,
        ]);
    }

    private function impor_google_form($hasil)
    {
        $hasil = $hasil && $this->setting_script_id_gform($hasil);
        $hasil = $hasil && $this->field_gform_id_master_analisis($hasil);

        return $hasil && $this->tambah_pengaturan_analisis($hasil);
    }

    private function field_gform_id_master_analisis($hasil)
    {
        // Tambah field gfrom_id pada tabel analisis_master
        if (! $this->db->field_exists('gform_id', 'analisis_master')) {
            $fields = [
                'gform_id' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('analisis_master', $fields);
        }

        // Tambah field gform_nik_item_id pada tabel analisis_master
        if (! $this->db->field_exists('gform_nik_item_id', 'analisis_master')) {
            $fields = [
                'gform_nik_item_id' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('analisis_master', $fields);
        }

        // Tambah field gform_last_sync pada tabel analisis_master
        if (! $this->db->field_exists('gform_last_sync', 'analisis_master')) {
            $fields = [
                'gform_last_sync' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('analisis_master', $fields);
        }

        return $hasil;
    }
}