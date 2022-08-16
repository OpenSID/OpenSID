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

class Migrasi_fitur_premium_2112 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2111');

        $hasil = $hasil && $this->migrasi_2021110571($hasil);
        $hasil = $hasil && $this->migrasi_2021111251($hasil);
        $hasil = $hasil && $this->migrasi_2021111451($hasil);
        $hasil = $hasil && $this->migrasi_2021111551($hasil);
        $hasil = $hasil && $this->migrasi_2021111552($hasil);
        $hasil = $hasil && $this->migrasi_2021111571($hasil);
        $hasil = $hasil && $this->migrasi_2021112051($hasil);
        $hasil = $hasil && $this->migrasi_2021112171($hasil);
        $hasil = $hasil && $this->migrasi_2021112571($hasil);

        return $hasil && $this->migrasi_2021112572($hasil);
    }

    // Tambah modul kader pemberdayaan masyarakat
    protected function migrasi_2021110571($hasil)
    {
        $hasil = $hasil && $this->tambah_modul_kader_pemberdayaan_masyarakat($hasil);

        return $hasil && $this->tabel_referensi($hasil);
    }

    protected function tambah_modul_kader_pemberdayaan_masyarakat($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'     => 332,
            'modul'  => 'Kader Pemberdayaan Masyarakat',
            'url'    => 'bumindes_kader',
            'aktif'  => 1,
            'hidden' => 2,
            'parent' => 301,
        ]);
    }

    public function tabel_referensi($hasil)
    {
        // Tambah tabel kader_pemberdayaan_masyarakat
        if (! $this->db->table_exists('kader_pemberdayaan_masyarakat')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 12,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'penduduk_id' => [
                    'type'       => 'INT',
                    'constraint' => 12,
                ],
                'kursus' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
                'bidang' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
                'keterangan' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->add_key('id', true);
            $hasil = $hasil && $this->dbforge->create_table('kader_pemberdayaan_masyarakat', true);
        }

        // Tambah tabel ref_penduduk_bidang
        if (! $this->db->table_exists('ref_penduduk_bidang')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 12,
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
            $hasil = $hasil && $this->dbforge->create_table('ref_penduduk_bidang', true);
        }

        // Tambahkan data awal tabel ref_penduduk_bidang
        if ($hasil && $this->db->truncate('ref_penduduk_bidang')) {
            $ref_penduduk_bidang = [
                ['nama' => 'Service Komputer'],
                ['nama' => 'Operator Buldoser'],
                ['nama' => 'Operator Komputer'],
                ['nama' => 'Operator Genset'],
                ['nama' => 'Service HP'],
                ['nama' => 'Rias Pengantin'],
                ['nama' => 'Design Grafis'],
                ['nama' => 'Menjahit'],
                ['nama' => 'Menulis'],
                ['nama' => 'Reporter'],
                ['nama' => 'Sosial Media Manajer'],
                ['nama' => 'Manajemen Trainee'],
                ['nama' => 'Kasir'],
                ['nama' => 'HRD'],
                ['nama' => 'Guru'],
                ['nama' => 'Digital Marketing'],
                ['nama' => 'Customer Services'],
                ['nama' => 'Welder'],
                ['nama' => 'Mekanik Alat Berat'],
                ['nama' => 'Teknisi Listrik'],
                ['nama' => 'Internet Marketing'],
            ];
            $hasil = $hasil && $this->db->insert_batch('ref_penduduk_bidang', $ref_penduduk_bidang);
        }

        // Tambah tabel ref_penduduk_kursus
        if (! $this->db->table_exists('ref_penduduk_kursus')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 12,
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
            $hasil = $hasil && $this->dbforge->create_table('ref_penduduk_kursus', true);
        }

        // Tambahkan data awal tabel ref_penduduk_kursus
        if ($hasil && $this->db->truncate('ref_penduduk_kursus')) {
            $ref_penduduk_kursus = [
                ['nama' => 'Kursus Komputer'],
                ['nama' => 'Kursus Menjahit'],
                ['nama' => 'Pelatihan Kelistrikan'],
                ['nama' => 'Kursus Mekanik Motor'],
                ['nama' => 'Pelatihan Security'],
                ['nama' => 'Kursus Otomotif'],
                ['nama' => 'Kursus Bahasa Inggris'],
                ['nama' => 'Kursus Tata Kecantikan Kulit'],
                ['nama' => 'Kursus Megemudi'],
                ['nama' => 'Kursus Tata Boga'],
                ['nama' => 'Kursus Meubeler'],
                ['nama' => 'Kursus Las'],
                ['nama' => 'Kursus Sablon'],
                ['nama' => 'Kursus Penerbangan'],
                ['nama' => 'Kursus Desain Interior'],
                ['nama' => 'Kursus Teknisi HP'],
                ['nama' => 'Kursus Garment'],
                ['nama' => 'Kursus Akupuntur'],
                ['nama' => 'Kursus Senam'],
                ['nama' => 'Kursus Pendidik PAUD'],
                ['nama' => 'Kursus Baby Sitter'],
                ['nama' => 'Kursus Desain Grafis'],
                ['nama' => 'Kursus Bahasa Indonesia'],
                ['nama' => 'Kursus Photografi'],
                ['nama' => 'Kursus Expor Impor'],
                ['nama' => 'Kursus Jurnalistik'],
                ['nama' => 'Kursus Bahasa Arab'],
                ['nama' => 'Kursus Bahasa Jepang'],
                ['nama' => 'Kursus Anak Buah Kapal'],
                ['nama' => 'Kursus Refleksi'],
                ['nama' => 'Kursus Akupuntur'],
                ['nama' => 'Kursus Perhotelan'],
                ['nama' => 'Kursus Tata Rias'],
                ['nama' => 'Kursus Administrasi Perkantoran'],
                ['nama' => 'Kursus Broadcasting'],
                ['nama' => 'Kursus Kerajinan Tangan'],
                ['nama' => 'Kursus Sosial Media Marketing'],
                ['nama' => 'Kursus Internet Marketing'],
                ['nama' => 'Kursus Sekretaris'],
                ['nama' => 'Kursus Perpajakan'],
                ['nama' => 'Kursus Publik Speaking'],
                ['nama' => 'Kursus Publik Relation'],
                ['nama' => 'Kursus Batik'],
                ['nama' => 'Kursus Pengobatan Tradisional'],
            ];
            $hasil = $hasil && $this->db->insert_batch('ref_penduduk_kursus', $ref_penduduk_kursus);
        }

        return $hasil;
    }

    protected function migrasi_2021111251($hasil)
    {
        // Ubah default kk_level menjadi null; tadinya 0
        $fields = [
            'kk_level' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'null'       => true,
                'default'    => null,
            ],
        ];
        $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);

        $hasil = $hasil && $this->db
            ->set('kk_level', null)
            ->where('kk_level', 0)
            ->update('tweb_penduduk');

        // Ubah rentang umur kategori TUA untuk kasus salah pengisian tanggal lahir
        $hasil = $hasil && $this->db
            ->set('sampai', '99999')
            ->where('id', 4)
            ->update('tweb_penduduk_umur');

        // Ubah cara_kb_id yg nilainya tidak valid
        return $hasil && $this->db
            ->set('cara_kb_id', null)
            ->where_not_in('cara_kb_id', [1, 2, 3, 4, 5, 6, 7, 99])
            ->update('tweb_penduduk');
    }

    protected function migrasi_2021111451($hasil)
    {
        // Ubah judul status hubungan dalam keluarga
        return $hasil && $this->db->where('id', 9)->update('tweb_penduduk_hubungan', ['nama' => 'FAMILI LAIN']);
    }

    protected function migrasi_2021111551($hasil)
    {
        // Hapus data analisis_parameter dengan responden 0 untuk tipe pertanyaan 3 dan 4
        $this->load->model('analisis_statistik_jawaban_model');

        return $hasil && $this->analisis_statistik_jawaban_model->hapus_data_kosong();
    }

    protected function migrasi_2021111552($hasil)
    {
        // Tambah lampiran untuk Surat Keterangan Kelahiran
        return $hasil && $this->db->where('url_surat', 'surat_ket_kelahiran')->update('tweb_surat_format', ['lampiran' => 'f-2.01.php']);
    }

    protected function migrasi_2021111571($hasil)
    {
        if (! $this->db->field_exists('slug', 'pembangunan')) {
            $fields = [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'unique'     => true,
                    'after'      => 'judul',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', $fields);
        }

        if ($data_pembangunan = $this->db->get('pembangunan')->result_array()) {
            foreach ($data_pembangunan as $pembangunan) {
                $slug  = unique_slug('pembangunan', $pembangunan['judul'], $pembangunan['id']);
                $hasil = $hasil && $this->db->where('id', $pembangunan['id'])->update('pembangunan', ['slug' => $slug]);
            }
        }

        return $hasil;
    }

    protected function migrasi_2021112051($hasil)
    {
        return $hasil && $this->dbforge->drop_table('log_bulanan', true);
    }

    protected function migrasi_2021112171($hasil)
    {
        $hasil = $hasil && $this->tambah_kolom($hasil);
        $hasil = $hasil && $this->hapus_tabel_migrations($hasil);

        return $hasil && $this->tambah_tabel($hasil);
    }

    protected function tambah_kolom($hasil)
    {
        $table = 'tweb_penduduk_mandiri';

        if (! $this->db->field_exists('email_verified_at', $table)) {
            $hasil = $hasil && $this->dbforge->add_column($table, 'email_verified_at TIMESTAMP');
        }

        if (! $this->db->field_exists('remember_token', $table)) {
            $fields = [
                'remember_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column($table, $fields);
        }

        if (! $this->db->field_exists('updated_at', $table)) {
            $hasil = $hasil && $this->dbforge->add_column($table, 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        }

        return $hasil;
    }

    protected function hapus_tabel_migrations($hasil)
    {
        // Hapus tabel migrations bagi yang terlanjur menjalankan php artisan migrate di api
        return $hasil && $this->dbforge->drop_table('migrations', true);
    }

    protected function tambah_tabel($hasil)
    {
        $fields = [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('email', true);

        return $hasil && $this->dbforge->create_table('password_resets', true);
    }

    protected function migrasi_2021112571($hasil)
    {
        if (! $this->db->field_exists('slug', 'suplemen')) {
            $fields = [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'unique'     => true,
                    'after'      => 'nama',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('suplemen', $fields);
        }

        $this->load->model('suplemen_model');

        if ($data_suplemen = $this->suplemen_model->list_data()) {
            foreach ($data_suplemen as $suplemen) {
                $slug  = unique_slug('suplemen', $suplemen['nama'], $suplemen['id']);
                $hasil = $hasil && $this->db->where('id', $suplemen['id'])->update('suplemen', ['slug' => $slug]);
            }
        }

        return $hasil;
    }

    protected function migrasi_2021112572($hasil)
    {
        if (! $this->db->field_exists('slug', 'kelompok')) {
            $fields = [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'unique'     => true,
                    'after'      => 'nama',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('kelompok', $fields);
        }

        $this->load->model('kelompok_model');

        if ($data_kelompok = $this->kelompok_model->list_data()) {
            foreach ($data_kelompok as $kelompok) {
                $slug  = unique_slug('kelompok', $kelompok['nama'], $kelompok['id']);
                $hasil = $hasil && $this->db->where('id', $kelompok['id'])->update('kelompok', ['slug' => $slug]);
            }
        }

        return $hasil;
    }
}