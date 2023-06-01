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

use App\Enums\SistemEnum;
use App\Models\AnjunganMenu;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2210 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2209');
        $hasil = $hasil && $this->migrasi_2022090751($hasil);
        $hasil = $hasil && $this->migrasi_2022082472($hasil);
        $hasil = $hasil && $this->migrasi_2022090671($hasil);
        $hasil = $hasil && $this->migrasi_2022090751($hasil);
        $hasil = $hasil && $this->migrasi_2022090851($hasil);
        $hasil = $hasil && $this->migrasi_2022091171($hasil);
        $hasil = $hasil && $this->migrasi_2022091251($hasil);
        $hasil = $hasil && $this->migrasi_2022091271($hasil);
        $hasil = $hasil && $this->migrasi_2022091371($hasil);
        $hasil = $hasil && $this->migrasi_2022091372($hasil);
        $hasil = $hasil && $this->migrasi_2022091373($hasil);
        $hasil = $hasil && $this->migrasi_2022091374($hasil);
        $hasil = $hasil && $this->migrasi_2022091470($hasil);
        $hasil = $hasil && $this->migrasi_2022091471($hasil);
        $hasil = $hasil && $this->migrasi_2022091551($hasil);
        $hasil = $hasil && $this->migrasi_2022091651($hasil);
        $hasil = $hasil && $this->migrasi_2022091770($hasil);
        $hasil = $hasil && $this->migrasi_2022091951($hasil);
        $hasil = $hasil && $this->migrasi_2022092070($hasil);
        $hasil = $hasil && $this->migrasi_2022092170($hasil);
        $hasil = $hasil && $this->migrasi_2022092272($hasil);
        $hasil = $hasil && $this->migrasi_2022092351($hasil);
        $hasil = $hasil && $this->migrasi_2022092451($hasil);
        $hasil = $hasil && $this->migrasi_2022092571($hasil);
        $hasil = $hasil && $this->migrasi_2022092751($hasil);

        return $hasil && $this->migrasi_2022093071($hasil);
    }

    protected function migrasi_2022090671($hasil)
    {
        // tambahkan kolom id_browser
        if (! $this->db->field_exists('id_pengunjung', 'anjungan')) {
            $fields = [
                'id_pengunjung' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('anjungan', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022090751($hasil)
    {
        // Cek apakah pamong dengan pamong_id = 1 ada
        if (! Pamong::find(1)) {
            // Jika tidak ada, ganti id_pamong = 1 pada log_surat dengan kepala desa yang aktif
            $pamongId = Pamong::kepalaDesa()->first()->pamong_id;
            LogSurat::where('id_pamong', 1)->update(['id_pamong' => $pamongId]);
        }

        return $hasil;
    }

    protected function migrasi_2022082472($hasil)
    {
        // buat tabel log restore folder desa
        if (! $this->db->table_exists('log_restore_desa')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'ukuran' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
                'path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'restore_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
                'status' => [
                    'type'    => 'int',
                    'null'    => false,
                    'default' => 0,
                ],
                'pid_process' => [
                    'type'       => 'int',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('log_restore_desa', true);

            $hasil = $hasil && $this->timestamps('log_restore_desa', true);
        }

        return $hasil;
    }

    protected function migrasi_2022090851($hasil)
    {
        // Sesuaikan surat status surat rtf dan tinymce mengikuti alur baru
        LogSurat::status(LogSurat::CETAK)->whereNull('verifikasi_operator')->update(['verifikasi_operator' => 1]);

        // Sesuaikan surat status konsep tapi masuk ke arsip
        $surat_tiny_mce = FormatSurat::jenis(FormatSurat::TINYMCE)->pluck('id');
        if ($surat_tiny_mce) {
            LogSurat::whereIn('id_format_surat', $surat_tiny_mce)->status(LogSurat::KONSEP)->update(['verifikasi_operator' => 0]);
        }

        return $hasil;
    }

    protected function migrasi_2022091171($hasil)
    {
        if (! $this->db->field_exists('tipe', 'teks_berjalan')) {
            $fields = [
                'tipe' => [
                    'type'       => 'TINYINT',
                    'constraint' => 2,
                    'null'       => true,
                    'default'    => SistemEnum::WEBSITE,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('teks_berjalan', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022091251($hasil)
    {
        // Hapus tabel ref_font_surat
        if ($this->db->table_exists('ref_font_surat')) {
            $hasil = $hasil && $this->dbforge->drop_table('ref_font_surat');
        }

        return $hasil;
    }

    protected function migrasi_2022091271($hasil)
    {
        // Ganti url 'peraturan_desa' jadi 'peraturan-desa
        DB::table('menu')->where('link', 'peraturan_desa')->update(['link' => 'peraturan-desa']);

        return $hasil;
    }

    protected function migrasi_2022091371($hasil)
    {
        if (! $this->db->field_exists('url', 'dokumen')) {
            $fields = [
                'url' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                    'after'   => 'attr',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('dokumen', $fields);
        }

        // Perbaharui view dokumen_hidup
        $hasil = $hasil && $this->db->query('DROP VIEW dokumen_hidup');

        return $hasil && $this->db->query('CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1');
    }

    protected function migrasi_2022091372($hasil)
    {
        // Ganti tipe text jadi textarea
        $daftar_surat = FormatSurat::jenis(FormatSurat::TINYMCE)->pluck('kode_isian', 'id');

        foreach ($daftar_surat as $id => $kode_isian) {
            $kode_isian = str_replace('"tipe":"text"', '"tipe":"textarea"', json_encode($kode_isian));
            FormatSurat::find($id)->update(['kode_isian' => json_decode($kode_isian)]);
        }

        return $hasil;
    }

    protected function migrasi_2022091373($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'min_zoom_peta',
            'value'      => 1,
            'keterangan' => 'Minimal pembesaran wilayah pada peta',
            'jenis'      => 'int',
        ]);

        return $hasil && $this->tambah_setting([
            'key'        => 'max_zoom_peta',
            'value'      => 30,
            'keterangan' => 'Maksimal pembesaran wilayah pada peta',
            'jenis'      => 'int',
        ]);
    }

    protected function migrasi_2022091374($hasil)
    {
        $result = $this->db
            ->where('key', 'footer_surat_tte')
            ->like('value', 'UU ITE No. 11 Tahun 2008')
            ->get('setting_aplikasi')
            ->result();

        if (! $result) {
            $hasil = $hasil && $this->db
                ->where('key', 'footer_surat_tte')
                ->update('setting_aplikasi', [
                    'value' => \App\Libraries\TinyMCE::FOOTER_TTE,
                ]);
        }

        return $hasil;
    }

    protected function migrasi_2022091470($hasil)
    {
        if (! $this->db->field_exists('gelar_depan', 'tweb_desa_pamong')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_desa_pamong', [
                'gelar_depan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'pamong_nama',
                ],
                'gelar_belakang' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'gelar_depan',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2022091471($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'   => 'notifikasi_pengajuan_surat',
            'value' => <<<'EOD'
                Segera cek Halaman Admin, penduduk atas nama [nama_penduduk] telah mengajukan [judul_surat] melalui [melalui] pada tanggal [tanggal]

                TERIMA KASIH.
                EOD,
            'keterangan' => 'Pesan notifikasi pengajuan surat',
            'jenis'      => 'textarea',
        ]);
    }

    protected function migrasi_2022091551($hasil)
    {
        return $hasil && $this->dbforge->modify_column('anjungan', [
            'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
    }

    protected function migrasi_2022091651($hasil)
    {
        return $hasil && $this->dbforge->modify_column('sys_traffic', [
            'ipAddress' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'Jumlah' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'null'       => false,
            ],
        ]);
    }

    protected function migrasi_2022091770($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(312, ['urut' => 180, 'url' => '', 'parent' => 0]);

        $hasil = $hasil && $this->tambah_modul([
            'id'         => 347,
            'modul'      => 'Daftar Anjungan',
            'url'        => 'anjungan',
            'aktif'      => 1,
            'ikon'       => 'fa-list',
            'urut'       => 1,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-list',
            'parent'     => 312,
        ]);

        $hasil = $hasil && $this->tambah_modul([
            'id'         => 348,
            'modul'      => 'Menu',
            'url'        => 'anjungan_menu',
            'aktif'      => 1,
            'ikon'       => 'fa-bars',
            'urut'       => 2,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-bars',
            'parent'     => 312,
        ]);

        return $hasil && $this->tambah_modul([
            'id'         => 349,
            'modul'      => 'Pengaturan',
            'url'        => 'anjungan_pengaturan',
            'aktif'      => 1,
            'ikon'       => 'fa-gear',
            'urut'       => 3,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-gear',
            'parent'     => 312,
        ]);
    }

    protected function migrasi_2022091951($hasil)
    {
        // Tambahkan kode kelompok / lembaga jika masih kosong
        $daftar_data = DB::table('kelompok')->where('kode', '')->pluck('tipe', 'id');

        foreach ($daftar_data as $key => $value) {
            DB::table('kelompok')->where('id', $key)->update(['kode' => $value . '-' . $key]);
        }

        return $hasil;
    }

    protected function migrasi_2022092070($hasil)
    {
        if (! $this->db->table_exists('anjungan_menu')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'icon' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
                'link' => [
                    'type' => 'TEXT',
                ],
                'link_tipe' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                ],
                'urut' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                ],
                'status' => [
                    'type'       => 'INT',
                    'constraint' => 4,
                    'default'    => 1,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('anjungan_menu', true);
            $hasil = $hasil && $this->timestamps('anjungan_menu', true);

            $menu = [
                ['nama' => 'Peta Desa', 'icon' => 'peta.svg', 'link' => 'peta', 'link_tipe' => 5, 'urut' => 1, 'status' => 1],
                ['nama' => 'Informasi Pubik', 'icon' => 'public.svg', 'link' => 'informasi_publik', 'link_tipe' => 5, 'urut' => 2, 'status' => 1],
                ['nama' => 'Data Pekerjaan', 'icon' => 'statistik.svg', 'link' => 'statistik/1', 'link_tipe' => 2, 'urut' => 3, 'status' => 1],
                ['nama' => 'Layanan Mandiri', 'icon' => 'mandiri.svg', 'link' => 'layanan-mandiri/beranda', 'link_tipe' => 5, 'urut' => 4, 'status' => 1],
                ['nama' => 'Lapak', 'icon' => 'lapak.svg', 'link' => 'lapak', 'link_tipe' => 5, 'urut' => 5, 'status' => 1],
                ['nama' => 'Keuangan', 'icon' => 'keuangan.svg', 'link' => 'artikel/100', 'link_tipe' => 6, 'urut' => 6, 'status' => 1],
                ['nama' => 'IDM 2020', 'icon' => 'idm.svg', 'link' => 'status-idm/2022', 'link_tipe' => 10, 'urut' => 7, 'status' => 1],
            ];

            $hasil = $hasil && AnjunganMenu::insert($menu);

            folder(LOKASI_ICON_MENU_ANJUNGAN);
            $from  = FCPATH . LOKASI_ICON_MENU_ANJUNGAN_DEFAULT . 'contoh/';
            $to    = FCPATH . LOKASI_ICON_MENU_ANJUNGAN;
            $files = array_filter(glob("{$from}*"), 'is_file');

            foreach ($files as $file) {
                copy($file, $to . basename($file));
            }
        }

        return $hasil;
    }

    protected function migrasi_2022092170($hasil)
    {
        $hasil && $this->tambah_setting([
            'key'        => 'anjungan_artikel',
            'value'      => '',
            'keterangan' => 'Pengaturan artikel untuk anjungan',
            'kategori'   => 'anjungan',
        ]);

        return $hasil;
    }

    protected function migrasi_2022092272($hasil)
    {
        if (! $this->db->table_exists('kehadiran_alasan_keluar')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'alasan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'keterangan' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                ],
            ];
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('kehadiran_alasan_keluar', true);
            $hasil = $hasil && $this->timestamps('kehadiran_alasan_keluar', true);
        }

        return $hasil && $this->tambah_modul([
            'id'         => 350,
            'modul'      => 'Alasan Keluar',
            'url'        => 'kehadiran_keluar',
            'aktif'      => 1,
            'ikon'       => 'fa-sign-out',
            'urut'       => 5,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-sign-out',
            'parent'     => 337,
        ]);
    }

    protected function migrasi_2022092351($hasil)
    {
        $cluster = DB::table('tweb_wil_clusterdesa')->where('rt', '=', '0')->where('rw', '=', '')->get();

        foreach ($cluster as $value) {
            // cek duplikat
            $cek = DB::table('tweb_wil_clusterdesa')->where('rt', '=', '0')->where('rw', '=', '0')->where('dusun', '=', $value->dusun)->exists();
            if (! $cek) {
                DB::table('tweb_wil_clusterdesa')->where('rt', '=', '0')->where('rw', '=', '')->where('dusun', '=', $value->dusun)->update(['rw' => '0']);
            } else {
                DB::table('tweb_wil_clusterdesa')->where('rt', '=', '0')->where('rw', '=', '')->where('dusun', '=', $value->dusun)->delete();
            }
        }

        return $hasil;
    }

    protected function migrasi_2022092451($hasil)
    {
        // Hapus pengaturan sebutan_kepala_desa
        $this->db->delete('setting_aplikasi', ['key' => 'sebutan_kepala_desa']);

        return $hasil;
    }

    protected function migrasi_2022092571($hasil)
    {
        // Tambahkan lampiran F-1.02
        FormatSurat::where('url_surat', 'surat_bio_penduduk')->update(['lampiran' => 'f-1.01.php,f-1.02.php']);
        FormatSurat::where('url_surat', 'surat_permohonan_perubahan_kartu_keluarga')->update(['lampiran' => 'f-1.16.php,f-1.01.php,f-1.02.php']);
        FormatSurat::where('url_surat', 'surat_permohonan_kartu_keluarga')->update(['lampiran' => 'f-1.15.php,f-1.01.php,f-1.02.php']);

        return $hasil;
    }

    protected function migrasi_2022092751($hasil)
    {
        // Hapus setting verifikasi_operator yang tidak digunakan
        SettingAplikasi::where('key', '=', 'verifikasi_operator')->delete();

        // Sesuaikan log surat berdasarkan pengaturan alur surat saat ini
        // Jika verifikasi kades / sekdes tidak diaktifkan
        if (! setting('verifikasi_kades') || ! setting('verifikasi_sekdes')) {
            LogSurat::status(LogSurat::CETAK)->where('verifikasi_operator', LogSurat::PERIKSA)->update(['verifikasi_operator' => LogSurat::TERIMA]);
        }

        return $hasil;
    }

    protected function migrasi_2022093071($hasil)
    {
        if (! $this->db->field_exists('tipe', 'dokumen')) {
            $fields = [
                'tipe' => [
                    'type'       => 'TINYINT',
                    'default'    => 1, // 1 => file, 2 => url
                    'after'      => 'attr',
                    'constraint' => 3,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('dokumen', $fields);
        }

        // Perbaharui view dokumen_hidup
        $hasil = $hasil && $this->db->query('DROP VIEW dokumen_hidup');

        return $hasil && $this->db->query('CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1');
    }
}
