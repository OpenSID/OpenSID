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

class Migrasi_fitur_premium_2204 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2203');
        $hasil = $hasil && $this->migrasi_2022031171($hasil);
        $hasil = $hasil && $this->migrasi_2022032171($hasil);

        return $hasil && $this->migrasi_2022032471($hasil);
    }

    protected function migrasi_2022031171($hasil)
    {
        $this->db
            ->set('aktif', 0)
            ->where('id', 304)
            ->update('setting_modul');

        return $hasil && true;
    }

    protected function migrasi_2022032171($hasil)
    {
        // Ubah type data id penduduk
        return $hasil && $this->dbforge->modify_column('covid19_vaksin', [
            'id_penduduk' => [
                'type'       => 'int',
                'constraint' => 11,
            ],
        ]);
    }

    protected function migrasi_2022032471($hasil)
    {
        $hasil = $hasil && $this->tambahModulAbsensi($hasil);
        $hasil = $hasil && $this->tambahModulPengaturan($hasil);
        $hasil = $hasil && $this->tambahModulKehadiran($hasil);
        $hasil = $hasil && $this->modifikasiTabelTwebDesaPamong($hasil);
        $hasil = $hasil && $this->modifikasiTabelUser($hasil);
        $hasil = $hasil && $this->tambahTabelKehadiranPerangkatDesa($hasil);
        $hasil = $hasil && $this->modifikasiTabelAnjungan($hasil);
        $hasil = $hasil && $this->hariLibur($hasil);
        $hasil = $hasil && $this->jamKerja($hasil);
        $hasil = $hasil && $this->tambahModulPengaduan($hasil);

        return $hasil && $this->tambahTabelAbsensiPengaduan($hasil);
    }

    protected function tambahModulAbsensi($hasil)
    {
        // Tambah menu absensi
        return $hasil && $this->tambah_modul([
            'id'         => '337',
            'modul'      => 'Absensi',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-calendar-check-o',
            'urut'       => '170',
            'level'      => '0',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-calendar-check-o',
        ]);
    }

    protected function tambahModulPengaturan($hasil)
    {
        // Tambah menu absensi > pengaturan
        return $hasil && $this->tambah_modul([
            'id'         => '338',
            'modul'      => 'Pengaturan',
            'url'        => 'gawai',
            'aktif'      => '1',
            'ikon'       => 'fa-gear',
            'urut'       => '2',
            'level'      => '0',
            'parent'     => '337',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-gear',
        ]);
    }

    protected function tambahModulKehadiran($hasil)
    {
        // Tambah menu absensi > kehadiran
        return $hasil && $this->tambah_modul([
            'id'         => '341',
            'modul'      => 'Kehadiran',
            'url'        => 'admin_kehadiran',
            'aktif'      => '1',
            'ikon'       => 'fa-list',
            'urut'       => '2',
            'level'      => '0',
            'parent'     => '337',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-list',
        ]);
    }

    protected function modifikasiTabelTwebDesaPamong($hasil)
    {
        if (! $this->db->field_exists('pamong_tag_id_card', 'tweb_desa_pamong')) {
            $fields = [
                'pamong_tag_id_card' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 17,
                    'unique'     => true,
                    'null'       => true,
                    'after'      => 'pamong_nip',
                ],
                'pamong_pin' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 15,
                    'null'       => true,
                    'after'      => 'pamong_tag_id_card',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }

        return $hasil;
    }

    protected function tambahTabelKehadiranPerangkatDesa($hasil)
    {
        if (! $this->db->table_exists('kehadiran_perangkat_desa')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'tanggal' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'pamong_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'jam_masuk' => [
                    'type' => 'TIME',
                    'null' => true,
                ],
                'jam_pulang' => [
                    'type' => 'TIME',
                    'null' => true,
                ],
                'status_kehadiran' => [
                    'type'       => 'VARCHAR',
                    'null'       => true,
                    'constraint' => 255,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('kehadiran_perangkat_desa', true);
        }

        return $hasil;
    }

    protected function modifikasiTabelUser($hasil)
    {
        if (! $this->db->field_exists('pamong_id', 'user')) {
            $fields = [
                'pamong_id' => [
                    'type'       => 'INT',
                    'constraint' => 20,
                    'unique'     => true,
                    'null'       => true,
                    'after'      => 'id_grup',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        return $hasil;
    }

    protected function modifikasiTabelAnjungan($hasil)
    {
        if (! $this->db->field_exists('tipe', 'anjungan')) {
            $fields = [
                'tipe' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'default'    => 'anjungan',
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('anjungan', $fields);
        }

        return $hasil;
    }

    public function hariLibur($hasil)
    {
        if (! $this->db->table_exists('absensi_hari_libur')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'tanggal' => [
                    'type'   => 'DATE',
                    'unique' => true,
                    'null'   => false,
                ],
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('absensi_hari_libur', true);

            $hasil = $hasil && $this->tambah_modul([
                'id'         => '340',
                'modul'      => 'Hari Libur',
                'url'        => 'absensi_libur',
                'aktif'      => '1',
                'ikon'       => 'fa-calendar',
                'urut'       => '2',
                'level'      => '0',
                'parent'     => '337',
                'hidden'     => '0',
                'ikon_kecil' => 'fa-credit-card',
            ]);
        }

        return $hasil;
    }

    public function jamKerja($hasil)
    {
        if (! $this->db->table_exists('absensi_jam_kerja')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'nama_hari' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 65,
                    'null'       => false,
                ],
                'jam_mulai' => [
                    'type' => 'TIME',
                    'null' => false,
                ],
                'jam_akhir' => [
                    'type' => 'TIME',
                    'null' => false,
                ],
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 1,
                ],
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);

            $hasil = $hasil && $this->dbforge->create_table('absensi_jam_kerja', true);

            // tambahkan data hari awal
            $hari = [
                ['nama_hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 1],
                ['nama_hari' => 'Selasa', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 1],
                ['nama_hari' => 'Rabu', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 1],
                ['nama_hari' => 'Kamis', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 1],
                ['nama_hari' => 'Jumat', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 1],
                ['nama_hari' => 'Sabtu', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 0],
                ['nama_hari' => 'Minggu', 'jam_mulai' => '08:00:00', 'jam_akhir' => '16:00:00', 'status' => 0],
            ];

            $hasil = $hasil && $this->db->insert_batch('absensi_jam_kerja', $hari);

            $hasil = $hasil && $this->tambah_modul([
                'id'         => '339',
                'modul'      => 'Jam Kerja',
                'url'        => 'absensi_jam_kerja',
                'aktif'      => '1',
                'ikon'       => 'fa-clock-o',
                'urut'       => '2',
                'level'      => '0',
                'parent'     => '337',
                'hidden'     => '0',
                'ikon_kecil' => 'fa-credit-card',
            ]);
        }

        return $hasil;
    }

    protected function tambahModulPengaduan($hasil)
    {
        // Tambah menu absensi > kehadiran
        return $hasil && $this->tambah_modul([
            'id'         => '342',
            'modul'      => 'Pengaduan',
            'url'        => 'absensi_pengaduan',
            'aktif'      => '1',
            'ikon'       => 'fa-exclamation',
            'urut'       => '2',
            'level'      => '0',
            'parent'     => '337',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-exclamation',
        ]);
    }

    public function tambahTabelAbsensiPengaduan($hasil)
    {
        if (! $this->db->table_exists('absensi_pengaduan')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'waktu' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ],
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'id_penduduk' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'id_pamong' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);

            $hasil = $hasil && $this->dbforge->create_table('absensi_pengaduan', true);
        }

        return $hasil;
    }
}
