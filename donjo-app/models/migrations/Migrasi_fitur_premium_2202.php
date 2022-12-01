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

        // TODO:: Hapus migrasi ini jika v22.09-premium digabungkan ke rilis ini
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_foto_aparatur');

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2201');
        $hasil = $hasil && $this->migrasi_2022010671($hasil);
        $hasil = $hasil && $this->migrasi_2022011071($hasil);
        $hasil = $hasil && $this->migrasi_2022011251($hasil);
        $hasil = $hasil && $this->migrasi_2022011351($hasil);
        $hasil = $hasil && $this->migrasi_2022011471($hasil);
        $hasil = $hasil && $this->migrasi_2022012071($hasil);
        $hasil = $hasil && $this->migrasi_2022012471($hasil);
        $hasil = $hasil && $this->migrasi_2022012651($hasil);
        $hasil = $hasil && $this->migrasi_2022012751($hasil);
        $hasil = $hasil && $this->migrasi_2022012771($hasil);
        $hasil = $hasil && $this->migrasi_2022013071($hasil);

        return $hasil && $this->migrasi_2022013171($hasil);
    }

    protected function migrasi_2022010671($hasil)
    {
        // Tambah tabel ref_penduduk_kehamilan
        if (! $this->db->table_exists('ref_penduduk_hamil')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->add_key('id', true);
            $hasil = $hasil && $this->dbforge->create_table('ref_penduduk_hamil', true);
        }

        // Tambahkan data awal tabel ref_penduduk_hamil
        if ($hasil && $this->db->truncate('ref_penduduk_hamil')) {
            $ref_penduduk_hamil = [
                ['nama' => 'Hamil'],
                ['nama' => 'Tidak Hamil'],
            ];
            $hasil = $hasil && $this->db->insert_batch('ref_penduduk_hamil', $ref_penduduk_hamil);
        }

        return $hasil;
    }

    protected function migrasi_2022011071($hasil)
    {
        $folder = 'upload/pendaftaran';
        if (! file_exists('/desa/' . $folder)) {
            mkdir('desa/' . $folder, 0755, true);
            xcopy('desa-contoh/' . $folder, 'desa/' . $folder);
        }

        if (! $this->db->field_exists('aktif', 'tweb_penduduk_mandiri')) {
            $fields = [
                'aktif' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'null'       => true,
                    'default'    => 1,
                    'after'      => 'id_pend',
                ],
                'scan_ktp' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'aktif',
                ],
                'scan_kk' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'scan_ktp',
                ],
                'foto_selfie' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'scan_kk',
                ],

            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk_mandiri', $fields);
        }

        return $hasil && $this->tambah_setting([
            'key'        => 'tampilkan_pendaftaran',
            'value'      => 0,
            'keterangan' => 'Aktifkan / Non Aktifkan Pendaftaran Layanan Mandiri',
            'jenis'      => 'boolean',
            'kategori'   => 'setting_mandiri',
        ]);
    }

    protected function migrasi_2022011251($hasil)
    {
        $hasil = $hasil && $this->keuangan_ta_pencairan($hasil);
        $hasil = $hasil && $this->keuangan_ta_spjpot($hasil);
        $hasil = $hasil && $this->keuangan_ta_spj_bukti($hasil);
        $hasil = $hasil && $this->keuangan_ta_sppbukti($hasil);

        return $hasil && $this->keuangan_ta_spppot($hasil);
    }

    protected function keuangan_ta_pencairan($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('No_Ref', 'keuangan_ta_pencairan')) {
            $fields['No_Ref'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_pencairan')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_pencairan')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_pencairan', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_spjpot($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Billing_Pajak', 'keuangan_ta_spjpot')) {
            $fields['Billing_Pajak'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spjpot', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_spj_bukti($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Kd_Bank', 'keuangan_ta_spj_bukti')) {
            $fields['Kd_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Ref_Bayar', 'keuangan_ta_spj_bukti')) {
            $fields['Ref_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_spj_bukti')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_spj_bukti')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spj_bukti', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_sppbukti($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Kd_Bank', 'keuangan_ta_sppbukti')) {
            $fields['Kd_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Ref_Bayar', 'keuangan_ta_sppbukti')) {
            $fields['Ref_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_sppbukti')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_sppbukti')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_sppbukti', $fields);
        }

        return $hasil;
    }

    protected function keuangan_ta_spppot($hasil)
    {
        $fields = [];

        if (! $this->db->field_exists('Billing_Pajak', 'keuangan_ta_spppot')) {
            $fields['Billing_Pajak'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spppot', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022011351($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'tampilan_anjungan_audio',
            'value'      => 0,
            'keterangan' => 'Apakah audio diaktifkan atau tidak saat video diputar',
            'jenis'      => 'boolean',
            'kategori'   => 'setting_mandiri',
        ]);
    }

    protected function migrasi_2022011471($hasil)
    {
        if (! $this->db->field_exists('email_token', 'tweb_penduduk')) {
            $fields = [
                'email_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'unique'     => true,
                    'null'       => true,
                    'after'      => 'email',
                ],
                'email_tgl_kadaluarsa' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'email_token',
                ],
                'email_tgl_verifikasi' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'email_tgl_kadaluarsa',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', $fields);
        }

        $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', [
            'email' => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
        ]);

        foreach ($this->db->get_where('tweb_penduduk', ['email' => ''])->result_object() as $row) {
            $penduduk[] = [
                'id'    => $row->id,
                'email' => null,
            ];
        }

        if ($penduduk) {
            $hasil = $hasil && $this->db->update_batch('tweb_penduduk', $penduduk, 'id');
        }

        return $hasil && $this->tambahIndeks('tweb_penduduk', 'email');
    }

    protected function migrasi_2022012071($hasil)
    {
        if (! $this->db->field_exists('email_verified_at', 'user')) {
            $fields = [
                'email_verified_at' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'last_login',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022012471($hasil)
    {
        $daftar_ubah = [
            'config'               => 'warna',
            'tweb_desa_pamong'     => 'bagan_warna',
            'tweb_wil_clusterdesa' => 'warna',
            'line'                 => 'color',
            'polygon'              => 'color',
        ];

        if ($daftar_ubah) {
            foreach ($daftar_ubah as $tabel => $kolom) {
                if ($this->db->field_exists($kolom, $tabel)) {
                    $fields = [
                        $kolom => [
                            'type'       => 'varchar',
                            'constraint' => 25,
                            'null'       => true,
                        ],
                    ];

                    $hasil = $hasil && $this->dbforge->modify_column($tabel, $fields);
                }
            }
        }

        return $hasil;
    }

    protected function migrasi_2022012651($hasil)
    {
        // Hapus modul pembangunan dokumentasi
        $hasil = $hasil && $this->db->where('id', '221')->delete('setting_modul');

        // Hapus group akses modul pembangunan dan pembangunan dokumentasi
        $hasil = $hasil && $this->db->where_in('id_modul', [220, 221])->delete('grup_akses');

        // Tambah group akses modul pembangunan untuk operator
        $hasil = $hasil && $this->grupAkses(2, 220, 3);

        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    protected function migrasi_2022012751($hasil)
    {
        $hasil = $hasil && $this->dbforge->modify_column('user', [
            'email'    => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'username' => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
        ]);

        foreach ($this->db->get_where('user', ['email' => ''])->result_object() as $row) {
            $users[] = [
                'id'    => $row->id,
                'email' => null,
            ];
        }

        if ($users) {
            $hasil = $hasil && $this->db->update_batch('user', $users, 'id');
        }

        $hasil = $hasil && $this->tambahIndeks('user', 'username');

        return $hasil && $this->tambahIndeks('user', 'email');
    }

    protected function migrasi_2022012771($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 336,
            'modul'      => 'Arsip [Desa]',
            'url'        => 'bumindes_arsip',
            'aktif'      => 1,
            'ikon'       => 'fa-archive',
            'urut'       => 5,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-archive',
            'parent'     => 301,
        ]);

        $list_tabel = ['surat_masuk', 'surat_keluar', 'dokumen', 'log_surat'];

        foreach ($list_tabel as $tabel) {
            if (! $this->db->field_exists('lokasi_arsip', $tabel)) {
                $hasil = $hasil && $this->dbforge->add_column($tabel, ['lokasi_arsip' => ['type' => 'VARCHAR', 'constraint' => '150', 'default' => '']]);
            }
        }

        // Perbaharui view dokumen_hidup
        $hasil = $hasil && $this->db->query('DROP VIEW dokumen_hidup');

        return $hasil && $this->db->query('CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1');
    }

    protected function migrasi_2022013071($hasil)
    {
        return $this->db->where('hamil', 0)->update('tweb_penduduk', ['hamil' => 2]);
    }

    protected function migrasi_2022013171($hasil)
    {
        // Ambil kepala rumah tangga yang masih hidup
        $data = $this->db->select(['id as nik_kepala', 'id_rtm as no_kk'])->get_where('penduduk_hidup', ['rtm_level' => 1])->result_array();

        // Ubah data nik kepala berdasarkan data dari penduduk dengan kk_level 1
        if ($data) {
            $hasil && $this->db->update_batch('tweb_rtm', $data, 'no_kk');
        }

        return $hasil;
    }
}
