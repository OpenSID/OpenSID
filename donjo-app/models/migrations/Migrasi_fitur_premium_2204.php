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

use App\Models\JamKerja;
use Illuminate\Support\Facades\Schema;

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
        $hasil = $hasil && $this->migrasi_2022032371($hasil);
        $hasil = $hasil && $this->migrasi_2022032471($hasil);
        $hasil = $hasil && $this->migrasi_2022032871($hasil);

        return $hasil && $this->migrasi_2022032951($hasil);
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

    protected function migrasi_2022032371($hasil)
    {
        // Tambahkan ulang data tabel tweb_status_ktp
        if ($this->db->truncate('tweb_status_ktp')) {
            $data = [
                ['nama' => 'BELUM REKAM', 'ktp_el' => 1, 'status_rekam' => 2],
                ['nama' => 'SUDAH REKAM', 'ktp_el' => 2, 'status_rekam' => 3],
                ['nama' => 'CARD PRINTED', 'ktp_el' => 2, 'status_rekam' => 4],
                ['nama' => 'PRINT READY RECORD', 'ktp_el' => 2, 'status_rekam' => 5],
                ['nama' => 'CARD SHIPPED', 'ktp_el' => 2, 'status_rekam' => 6],
                ['nama' => 'SENT FOR CARD PRINTING', 'ktp_el' => 2, 'status_rekam' => 7],
                ['nama' => 'CARD ISSUED', 'ktp_el' => 2, 'status_rekam' => 8],
                ['nama' => 'BELUM WAJIB', 'ktp_el' => 1, 'status_rekam' => 1],
            ];
            $hasil = $hasil && $this->db->insert_batch('tweb_status_ktp', $data);
        }

        return $hasil;
    }

    protected function migrasi_2022032471($hasil)
    {
        $hasil = $hasil && $this->tambahModulKehadiran($hasil);
        $hasil = $hasil && $this->tambahModulRekapitulasi($hasil);
        $hasil = $hasil && $this->modifikasiTabelTwebDesaPamong($hasil);
        $hasil = $hasil && $this->modifikasiTabelUser($hasil);
        $hasil = $hasil && $this->tambahTabelKehadiranPerangkatDesa($hasil);
        $hasil = $hasil && $this->hariLibur($hasil);
        $hasil = $hasil && $this->jamKerja($hasil);
        $hasil = $hasil && $this->tambahModulPengaduan($hasil);

        return $hasil && $this->tambahTabelKehadiranPengaduan($hasil);
    }

    protected function tambahModulKehadiran($hasil)
    {
        // Tambah menu kehadiran
        return $hasil && $this->tambah_modul([
            'id'         => '337',
            'modul'      => 'Kehadiran',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-calendar-check-o',
            'urut'       => '41',
            'level'      => '0',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-calendar-check-o',
        ]);
    }

    protected function tambahModulRekapitulasi($hasil)
    {
        // Tambah menu kehadiran > rekapitulasi
        return $hasil && $this->tambah_modul([
            'id'         => '341',
            'modul'      => 'Rekapitulasi',
            'url'        => 'kehadiran_rekapitulasi',
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
                'jam_keluar' => [
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

    public function hariLibur($hasil)
    {
        if (! $this->db->table_exists('kehadiran_hari_libur')) {
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
            $hasil = $hasil && $this->dbforge->create_table('kehadiran_hari_libur', true);
        }

        return $hasil && $this->tambah_modul([
            'id'         => '340',
            'modul'      => 'Hari Libur',
            'url'        => 'kehadiran_hari_libur',
            'aktif'      => '1',
            'ikon'       => 'fa-calendar',
            'urut'       => '2',
            'level'      => '0',
            'parent'     => '337',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-credit-card',
        ]);
    }

    public function jamKerja($hasil)
    {
        if (! $this->db->table_exists('kehadiran_jam_kerja')) {
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
                'jam_masuk' => [
                    'type' => 'TIME',
                    'null' => false,
                ],
                'jam_keluar' => [
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

            $hasil = $hasil && $this->dbforge->create_table('kehadiran_jam_kerja', true);
        }

        if (Schema::hasTable('kehadiran_jam_kerja') && JamKerja::count() != 7) {
            if (JamKerja::truncate()) {
                // Tambahkan data kehadiran jam kerja
                $hari = [
                    ['nama_hari' => 'Senin', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 1],
                    ['nama_hari' => 'Selasa', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 1],
                    ['nama_hari' => 'Rabu', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 1],
                    ['nama_hari' => 'Kamis', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 1],
                    ['nama_hari' => 'Jumat', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 1],
                    ['nama_hari' => 'Sabtu', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 0],
                    ['nama_hari' => 'Minggu', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'status' => 0],
                ];

                $hasil = $hasil && JamKerja::insert($hari);
            }
        }

        return $hasil && $this->tambah_modul([
            'id'         => '339',
            'modul'      => 'Jam Kerja',
            'url'        => 'kehadiran_jam_kerja',
            'aktif'      => '1',
            'ikon'       => 'fa-clock-o',
            'urut'       => '2',
            'level'      => '0',
            'parent'     => '337',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-credit-card',
        ]);
    }

    protected function tambahModulPengaduan($hasil)
    {
        // Tambah menu kehadiran > pengaduan
        return $hasil && $this->tambah_modul([
            'id'         => '342',
            'modul'      => 'Pengaduan',
            'url'        => 'kehadiran_pengaduan',
            'aktif'      => '1',
            'ikon'       => 'fa-exclamation',
            'urut'       => '2',
            'level'      => '0',
            'parent'     => '337',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-exclamation',
        ]);
    }

    public function tambahTabelKehadiranPengaduan($hasil)
    {
        if (! $this->db->table_exists('kehadiran_pengaduan')) {
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

            $hasil = $hasil && $this->dbforge->create_table('kehadiran_pengaduan', true);
        }

        return $hasil;
    }

    protected function migrasi_2022032871($hasil)
    {
        $hasil = $hasil && $this->settingKehadiran($hasil);

        return $hasil && $this->settingPamongKehadiran($hasil);
    }

    protected function settingKehadiran($hasil)
    {
        // Pengaturan Kehadiran
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'tampilkan_kehadiran',
            'value'      => 1,
            'keterangan' => 'Aktif / Non-aktifkan Halaman Websiten Kehadiran',
            'jenis'      => 'boolean',
            'kategori'   => 'kehadiran',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'ip_adress_kehadiran',
            'value'      => '',
            'keterangan' => 'IP Address Perangkat Kehadiran',
            'jenis'      => null,
            'kategori'   => 'kehadiran',
        ]);

        return $hasil && $this->tambah_setting([
            'key'        => 'mac_adress_kehadiran',
            'value'      => '',
            'keterangan' => 'MAC Address Perangkat Kehadiran',
            'jenis'      => null,
            'kategori'   => 'kehadiran',
        ]);
    }

    protected function settingPamongKehadiran($hasil)
    {
        if (! $this->db->field_exists('kehadiran', 'tweb_desa_pamong')) {
            $fields = [
                'kehadiran' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'default'    => 1,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022032951($hasil)
    {
        $this->db
            ->where([
                'id'   => 6,
                'nama' => 'Di Bawah 1 Tahun',
            ])
            ->set('nama', '0 S/D 1 TAHUN')
            ->update('tweb_penduduk_umur');

        return $hasil && true;
    }
}
