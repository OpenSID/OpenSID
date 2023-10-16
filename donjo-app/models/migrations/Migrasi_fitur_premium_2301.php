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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Area;
use App\Models\Garis;
use App\Models\LogSurat;
use App\Models\Lokasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2301 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2212');
        $hasil = $hasil && $this->migrasi_2022120651($hasil);
        $hasil = $hasil && $this->migrasi_2022120771($hasil);
        $hasil = $hasil && $this->migrasi_2022121252($hasil);
        $hasil = $hasil && $this->migrasi_2022122151($hasil);
        $hasil = $hasil && $this->migrasi_2022122152($hasil);
        $hasil = $hasil && $this->migrasi_2022122153($hasil);
        $hasil = $hasil && $this->migrasi_2022122154($hasil);
        $hasil = $hasil && $this->migrasi_2022122371($hasil);
        $hasil = $hasil && $this->migrasi_2022122751($hasil);
        $hasil = $hasil && $this->migrasi_2022122552($hasil);
        $hasil = $hasil && $this->migrasi_2022122851($hasil);
        $hasil = $hasil && $this->migrasi_2022122852($hasil);
        $hasil = $hasil && $this->migrasi_2022123052($hasil);
        $hasil = $hasil && $this->migrasi_2022123053($hasil);
        $hasil = $hasil && $this->migrasi_2022123171($hasil);

        return $hasil && true;
    }

    protected function migrasi_2022120651($hasil)
    {
        // Ubah Perdes menjadi Peraturan
        $this->db
            ->where([
                'id'   => 3,
                'nama' => 'Perdes',
            ])
            ->set('nama', 'Peraturan')
            ->update('ref_dokumen');

        return $hasil;
    }

    protected function migrasi_2022120771($hasil)
    {
        if (! $this->db->field_exists('kecamatan', 'tweb_surat_format')) {
            $fields = [
                'kecamatan' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'logo_garuda',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022121252($hasil)
    {
        // Ubah panjang kolom judul 100 menjadi 200
        $fields = [
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('artikel', $fields);
    }

    protected function migrasi_2022122151($hasil)
    {
        $semua_foto = Area::pluck('foto')->toArray();

        foreach (get_filenames(LOKASI_FOTO_AREA, false, false) as $file) {
            if ($file == 'index.html' || in_array(str_replace(['kecil_', 'sedang_'], '', $file), $semua_foto)) {
                continue;
            }

            unlink(LOKASI_FOTO_AREA . $file);
        }

        return $hasil;
    }

    protected function migrasi_2022122152($hasil)
    {
        $semua_foto = Garis::pluck('foto')->toArray();

        foreach (get_filenames(LOKASI_FOTO_GARIS, false, false) as $file) {
            if ($file == 'index.html' || in_array(str_replace(['kecil_', 'sedang_'], '', $file), $semua_foto)) {
                continue;
            }

            unlink(LOKASI_FOTO_GARIS . $file);
        }

        return $hasil;
    }

    protected function migrasi_2022122153($hasil)
    {
        $hasil && $this->tambah_setting([
            'judul'      => 'Latar Login Mandiri',
            'key'        => 'latar_login_mandiri',
            'value'      => 'latar_login_mandiri.jpg',
            'keterangan' => 'Latar untuk Login Layanan Mandiri',
            'jenis'      => 'unggah',
            'kategori'   => 'latar',
        ]);

        return $hasil;
    }

    protected function migrasi_2022122154($hasil)
    {
        $semua_foto = Lokasi::pluck('foto')->toArray();

        foreach (get_filenames(LOKASI_FOTO_LOKASI, false, false) as $file) {
            if ($file == 'index.html' || in_array(str_replace(['kecil_', 'sedang_'], '', $file), $semua_foto)) {
                continue;
            }

            unlink(LOKASI_FOTO_LOKASI . $file);
        }

        return $hasil;
    }

    protected function migrasi_2022122751($hasil)
    {
        $hasil && $this->tambah_setting([
            'judul'      => 'Latar Website',
            'key'        => 'latar_website',
            'value'      => 'latar_website.jpg',
            'keterangan' => 'Latar untuk login ke halaman website',
            'jenis'      => 'unggah',
            'kategori'   => 'latar',
        ]);

        $hasil && $this->tambah_setting([
            'judul'      => 'Latar Login Admin',
            'key'        => 'latar_login',
            'value'      => 'latar_login.jpg',
            'keterangan' => 'Latar untuk login ke halaman admin',
            'jenis'      => 'unggah',
            'kategori'   => 'latar',
        ]);

        return $hasil;
    }

    public function migrasi_2022122552($hasil)
    {
        // Modul Buku Tamu
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 354,
            'modul'      => 'Buku Tamu',
            'url'        => '',
            'aktif'      => 1,
            'ikon'       => 'fa-book',
            'urut'       => 180,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-book',
            'parent'     => 0,
        ]);

        // Modul Data Tamu
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 355,
            'modul'      => 'Data Tamu',
            'url'        => 'buku_tamu',
            'aktif'      => 1,
            'ikon'       => 'fa-bookmark-o',
            'urut'       => 1,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-bookmark-o',
            'parent'     => 354,
        ]);

        // Modul Data Kepuasan
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 356,
            'modul'      => 'Data Kepuasan',
            'url'        => 'buku_kepuasan',
            'aktif'      => 1,
            'ikon'       => 'fa-smile-o',
            'urut'       => 2,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-smile-o',
            'parent'     => 354,
        ]);

        // Modul Data Pertanyaan
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 357,
            'modul'      => 'Data Pertanyaan',
            'url'        => 'buku_pertanyaan',
            'aktif'      => 1,
            'ikon'       => 'fa-question',
            'urut'       => 3,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-question',
            'parent'     => 354,
        ]);

        // Modul Data Keperluan
        $hasil = $hasil && $this->tambah_modul([
            'id'         => 358,
            'modul'      => 'Data Keperluan',
            'url'        => 'buku_keperluan',
            'aktif'      => 1,
            'ikon'       => 'fa-send',
            'urut'       => 4,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-send',
            'parent'     => 354,
        ]);

        // Tabel buku_keperluan
        if (! $this->db->table_exists('buku_keperluan')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'keperluan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
                'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('buku_keperluan', true);
        }

        // Tabel buku_pertanyaan
        if (! $this->db->table_exists('buku_pertanyaan')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'pertanyaan' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
                'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('buku_pertanyaan', true);
        }

        // Tabel buku_kepuasan
        if (! $this->db->table_exists('buku_kepuasan')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'id_nama' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'id_pertanyaan' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'id_jawaban' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('buku_kepuasan', true);
        }

        // Tabel buku_tamu
        if (! $this->db->table_exists('buku_tamu')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
                'telepon' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false,
                ],
                'instansi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'jenis_kelamin' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'null'       => false,
                ],
                'alamat' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
                'id_bidang' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'id_keperluan' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'default'    => null,
                ],
                'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
                'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('buku_tamu', true);
        }

        return $hasil;
    }

    protected function migrasi_2022122851($hasil)
    {
        if ($this->db->where('nama', 'Keputusan Kades')->get('ref_dokumen')->row()) {
            $hasil = $hasil && $this->db
                ->where('nama', 'Keputusan Kades')
                ->set('nama', 'Keputusan Kepala Desa')
                ->update('ref_dokumen');
        }

        return $hasil;
    }

    protected function migrasi_2022122852($hasil)
    {
        $check = $this->db
            ->where_in('nama', [
                'Jual Beli',
                'Hibah / Sumbangan',
                'Lain - lain',
            ])
            ->get('ref_asal_tanah_kas')
            ->result_array();

        if ($check) {
            $hasil = $hasil && $this->db->update('ref_asal_tanah_kas', ['nama' => 'APB Desa'], ['nama' => 'Jual Beli']);
            $hasil = $hasil && $this->db->update('ref_asal_tanah_kas', ['nama' => 'Perolehan Lainnya yang Sah'], ['nama' => 'Hibah / Sumbangan']);
            $hasil = $hasil && $this->db->update('ref_asal_tanah_kas', ['nama' => 'Kekayaan Asli Desa'], ['nama' => 'Lain - lain']);
        }

        return $hasil;
    }

    protected function migrasi_2022122371($hasil)
    {
        return $hasil && $this->db
            ->set([
                'lampiran'   => 'F-1.06',
                'updated_at' => date('Y-m-d H:i:s'),
            ])
            ->where('url_surat', 'surat-keterangan-beda-identitas')
            ->update('tweb_surat_format');
    }

    protected function migrasi_2022123052($hasil)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Inspect Element',
            'key'        => 'inspect_element',
            'value'      => 1,
            'keterangan' => 'Mengaktifkan inspect element pada halaman website',
            'jenis'      => 'boolean',
            'kategori'   => 'sistem',
        ]);
    }

    protected function migrasi_2022123053($hasil)
    {
        // Ganti status kehadiran dari 'keluar' menjadi 'tidak berada di kantor'
        DB::table('kehadiran_perangkat_desa')
            ->where('status_kehadiran', 'keluar')
            ->update(['status_kehadiran' => 'tidak berada di kantor']);

        return $hasil;
    }

    protected function migrasi_2022123171($hasil)
    {
        if (! $this->db->field_exists('nama_jabatan', 'log_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('log_surat', [
                'nama_jabatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'id_pamong',
                ],
            ]);
        }

        // ganti nama pamong yang masih null
        $check = LogSurat::whereNull('nama_jabatan')->get();

        foreach ($check as $surat) {
            $surat->nama_jabatan = $surat->pamong->jabatan->nama;
            $hasil               = $surat->save();
        }

        return $hasil;
    }
}
