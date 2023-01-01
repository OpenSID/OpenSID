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

class Migrasi_fitur_premium_2201 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2112');

        $hasil = $hasil && $this->migrasi_2021120271($hasil);
        $hasil = $hasil && $this->migrasi_2021120371($hasil);
        $hasil = $hasil && $this->migrasi_2021120971($hasil);
        $hasil = $hasil && $this->migrasi_2021121371($hasil);
        $hasil = $hasil && $this->migrasi_2021121571($hasil);
        $hasil = $hasil && $this->migrasi_2021121651($hasil);
        $hasil = $hasil && $this->migrasi_2021122471($hasil);
        $hasil = $hasil && $this->migrasi_2021122971($hasil);
        $hasil = $hasil && $this->migrasi_2021122972($hasil);
        $hasil = $hasil && $this->migrasi_2021122973($hasil);

        return $hasil && $this->migrasi_2021123051($hasil);
    }

    protected function migrasi_2021120271($hasil)
    {
        if (! $this->db->field_exists('telegram', 'tweb_penduduk')) {
            $fields = [
                'telegram' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'unique'     => true,
                    'after'      => 'email',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021120371($hasil)
    {
        return $hasil && $this->db->where('url_surat', 'surat_ket_pindah_penduduk')->update('tweb_surat_format', ['lampiran' => 'f-1.03.php,f-1.08.php,f-1.25.php,f-1.27.php']);
    }

    protected function migrasi_2021120971($hasil)
    {
        $list_setting = [
            [
                'key'        => 'tampilan_anjungan',
                'value'      => 0,
                'keterangan' => 'Pilih tampilan di anjungan pada saat tidak ada aktifitas pada halaman login.',
                'jenis'      => 'option-kode',
                'kategori'   => 'setting_mandiri',
            ],
            [
                'key'        => 'tampilan_anjungan_waktu',
                'value'      => 30,
                'keterangan' => 'Atur waktu (detik) kapan tampilan di anjungan akan muncul pada saat tidak ada aktifitas di halaman login.',
                'jenis'      => 'int',
                'kategori'   => 'setting_mandiri',
            ],
            [
                'key'        => 'tampilan_anjungan_slider',
                'keterangan' => 'Pilih album yang akan ditampilkan pada anjungan.',
                'jenis'      => 'option',
                'kategori'   => 'setting_mandiri',
            ],
            [
                'key'        => 'tampilan_anjungan_video',
                'keterangan' => 'Masukan link video dengan format <code>.mp4</code> yang akan ditampilkan pada anjungan',
                'kategori'   => 'setting_mandiri',
            ],
        ];

        foreach ($list_setting as $setting) {
            $hasil = $hasil && $this->tambah_setting($setting);
        }

        $id_setting = $this->db->get_where('setting_aplikasi', ['key' => 'tampilan_anjungan'])->row()->id;
        if ($id_setting) {
            $this->db->where('id_setting', $id_setting)->delete('setting_aplikasi_options');

            $hasil = $hasil && $this->db->insert_batch(
                'setting_aplikasi_options',
                [
                    ['id_setting' => $id_setting, 'kode' => '0', 'value' => 'Tidak Aktif'],
                    ['id_setting' => $id_setting, 'kode' => '1', 'value' => 'Slider'],
                    ['id_setting' => $id_setting, 'kode' => '2', 'value' => 'Video'],
                ]
            );
        }

        return $hasil;
    }

    protected function migrasi_2021121371($hasil)
    {
        if (! $this->db->field_exists('telegram_token', 'tweb_penduduk')) {
            $fields = [
                'telegram_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'unique'     => true,
                    'null'       => true,
                    'after'      => 'telegram',
                ],
                'telegram_tgl_kadaluarsa' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'telegram_token',
                ],
                'telegram_tgl_verifikasi' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'telegram_tgl_kadaluarsa',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021121571($hasil)
    {
        if (! $this->db->field_exists('tebal', 'line')) {
            $fields = [
                'tebal' => [
                    'type'       => 'INT',
                    'constraint' => 2,
                    'null'       => true,
                    'default'    => '3',
                    'after'      => 'tipe',
                ],
                'jenis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => 'solid',
                    'after'      => 'tebal',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('line', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021121651($hasil)
    {
        // Ubah panjang kolom tag_id_card
        $fields = [
            'tag_id_card' => [
                'type'       => 'VARCHAR',
                'constraint' => 17,
                'null'       => true,
                'default'    => null,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);
    }

    protected function migrasi_2021122471($hasil)
    {
        $hasil = $hasil && $this->tambah_tabel_pengaduan($hasil);

        return $hasil && $this->tambah_modul_pengaduan($hasil);
    }

    protected function tambah_tabel_pengaduan($hasil)
    {
        if (! $this->db->table_exists('pengaduan')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],

                'id_pengaduan' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],

                'nik' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 16,
                    'null'       => true,
                ],

                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],

                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],

                'telepon' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                ],

                'judul' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],

                'isi' => [
                    'type' => 'TEXT',
                ],

                'status' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => '1',
                    'comment'    => '1 = menunggu proses, 2 = Sedang Diproses, 3 = Selesai Diproses',
                ],

                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],

                'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            ];

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('pengaduan', true);
        }

        return $hasil;
    }

    protected function tambah_modul_pengaduan($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'         => '334',
            'modul'      => 'Pengaduan',
            'url'        => 'pengaduan_admin',
            'aktif'      => '1',
            'ikon'       => 'fa-info',
            'urut'       => '124',
            'level'      => '2',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-info',
        ]);
    }

    protected function migrasi_2021122971($hasil)
    {
        $hasil = $hasil && $this->tambah_modul_hasil_pembangunan($hasil);

        return $hasil && $this->tambah_perubahan_anggaran($hasil);
    }

    protected function tambah_modul_hasil_pembangunan($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'     => 333,
            'modul'  => 'Buku Inventaris Hasil - Hasil Pembangunan',
            'url'    => 'bumindes_hasil_pembangunan',
            'aktif'  => 1,
            'hidden' => 2,
            'parent' => 301,
        ]);
    }

    public function tambah_perubahan_anggaran($hasil)
    {
        if (! $this->db->field_exists('perubahan_anggaran', 'pembangunan')) {
            $fields = [
                'perubahan_anggaran' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                    'after'      => 'anggaran',
                ],
            ];
            $hasil = $this->dbforge->add_column('pembangunan', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021122972($hasil)
    {
        // tambahkan field untuk vaksin covid 19

        if (! $this->db->table_exists('covid19_vaksin')) {
            $this->dbforge->add_field([
                'id_penduduk' => [
                    'type'       => 'varchar',
                    'constraint' => 100,
                ],
                'vaksin_1' => [
                    'type'       => 'int',
                    'constraint' => 1,
                    'null'       => true,
                ],
                'tgl_vaksin_1' => [
                    'type' => 'date',
                    'null' => true,
                ],
                'dokumen_vaksin_1' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'vaksin_2' => [
                    'type'       => 'int',
                    'constraint' => 1,
                    'null'       => true,
                ],
                'tgl_vaksin_2' => [
                    'type' => 'date',
                    'null' => true,
                ],
                'dokumen_vaksin_2' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'vaksin_3' => [
                    'type'       => 'int',
                    'constraint' => 1,
                    'null'       => true,
                ],
                'tgl_vaksin_3' => [
                    'type' => 'date',
                    'null' => true,
                ],
                'dokumen_vaksin_3' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'tunda' => [
                    'type'       => 'int',
                    'constraint' => 1,
                    'null'       => true,
                ],
                'keterangan' => [
                    'type'       => 'text',
                    'constraint' => 1,
                    'null'       => true,
                ],
                'surat_dokter' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
            $this->dbforge->add_key('id_penduduk', true);
            $hasil = $hasil && $this->dbforge->create_table('covid19_vaksin', true);
        }

        return $hasil && $this->tambah_modul([
            'id'         => 335,
            'modul'      => 'Vaksin',
            'url'        => 'vaksin_covid/clear',
            'aktif'      => 1,
            'ikon'       => 'fa fa-medkit',
            'urut'       => 2,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => '',
            'parent'     => 206,
        ]);
    }

    protected function migrasi_2021122973($hasil)
    {
        // Tambah surat
        $data = [
            'nama'       => 'Keterangan Untuk Nikah Warga Non Muslim',
            'url_surat'  => 'surat_ket_nikah_non_muslim',
            'kode_surat' => 'S-50',
            'lampiran'   => 'f-2.12.php',
            'jenis'      => 1,
        ];
        $sql = $this->db->insert_string('tweb_surat_format', $data);
        $sql .= ' ON DUPLICATE KEY UPDATE
            nama = VALUES(nama),
            url_surat = VALUES(url_surat),
            kode_surat = VALUES(kode_surat),
            lampiran = VALUES(lampiran),
            jenis = VALUES(jenis)';

        return $hasil && $this->db->query($sql);
    }

    public function migrasi_2021123051($hasil)
    {
        // tambah kolom jenis vaksin
        if (! $this->db->field_exists('jenis_vaksin_1', 'covid19_vaksin')) {
            $fields = [
                'jenis_vaksin_1' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'after'      => 'dokumen_vaksin_1',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('covid19_vaksin', $fields);
        }

        if (! $this->db->field_exists('jenis_vaksin_2', 'covid19_vaksin')) {
            $fields = [
                'jenis_vaksin_2' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'after'      => 'dokumen_vaksin_2',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('covid19_vaksin', $fields);
        }

        if (! $this->db->field_exists('jenis_vaksin_3', 'covid19_vaksin')) {
            $fields = [
                'jenis_vaksin_3' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'after'      => 'dokumen_vaksin_3',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('covid19_vaksin', $fields);
        }

        return $hasil;
    }
}
