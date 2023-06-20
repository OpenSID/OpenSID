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

use App\Enums\StatusEnum;
use App\Models\FormatSurat;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2307 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2306', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        // Data perlu dihapus karena ada perubahan struktur tabel
        $hasil = $hasil && $this->migrasi_2023060451($hasil);
        $hasil = $hasil && $this->migrasi_2023060452($hasil);
        $hasil = $hasil && $this->migrasi_2023061271($hasil);
        $hasil = $hasil && $this->migrasi_2023061351($hasil);
        $hasil = $hasil && $this->migrasi_2023061451($hasil);
        $hasil = $hasil && $this->migrasi_2023061752($hasil);
        $hasil = $hasil && $this->migrasi_2023061751($hasil);

        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023060571($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023060573($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023061251($hasil, $id);
            $hasil = $hasil && $this->suratKetDomisili($hasil, $id);
            $hasil = $hasil && $this->suratLahirMati($hasil, $id);
            $hasil = $hasil && $this->suratPenerbitanBukuPas($hasil, $id);
            $hasil = $hasil && $this->suratKepemilikanKendaraan($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPenghasilanOrangTua($hasil, $id);
            $hasil = $hasil && $this->suratBiodataPenduduk($hasil, $id);
            $hasil = $hasil && $this->suratPerintahPerjalananDinas($hasil, $id);
            $hasil = $hasil && $this->suratPermohonanDuplikatNikah($hasil, $id);
            // Jalankan Migrasi TinyMCE
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023060572($hasil);
        $hasil = $hasil && $this->migrasi_2023061451($hasil);
        $hasil = $hasil && $this->migrasi_2023061452($hasil);
        $hasil = $hasil && $this->migrasi_2023061552($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023060451($hasil)
    {
        DB::table('log_penduduk')->whereNotIn('id_pend', static function ($q) {
            return $q->select('id')->from('tweb_penduduk');
        })->delete();

        return $hasil;
    }

    protected function migrasi_2023060452($hasil)
    {
        $db    = $this->db->database;
        $query = "
            SELECT COUNT(1) ConstraintSudahAda
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = 'log_penduduk'
            AND CONSTRAINT_NAME = 'fk_tweb_penduduk'
        ";
        $checkConstraint = DB::select($query, [$db])[0];
        if ($checkConstraint->ConstraintSudahAda <= 0) {
            DB::statement('alter table log_penduduk add CONSTRAINT fk_tweb_penduduk foreign key (id_pend) REFERENCES tweb_penduduk(id) ON UPDATE CASCADE ON DELETE CASCADE');
        }

        return $hasil;
    }

    protected function migrasi_2023060571($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Tampilkan Tombol Peta',
            'key'        => 'tampilkan_tombol_peta',
            'value'      => $value = '["Statistik Penduduk", "Statistik Bantuan", "Aparatur Desa", "Kepala Wilayah"]',
            'keterangan' => 'Tampilkan tombol di peta',
            'jenis'      => 'multiple-option',
            'option'     => $value,
            'attribute'  => null,
            'kategori'   => 'peta',
        ], $id);
    }

    protected function migrasi_2023060572($hasil)
    {
        $this->db->where_in('key', [
            'max_zoom_peta',
            'min_zoom_peta',
            'mapbox_key',
            'tampil_luas_peta',
        ])
            ->where('kategori !=', 'peta')
            ->update('setting_aplikasi', ['kategori' => 'peta']);

        return $hasil;
    }

    protected function migrasi_2023060573($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Margin Global',
            'key'        => 'surat_margin',
            'value'      => json_encode(FormatSurat::MARGINS),
            'keterangan' => 'Margin Global untuk surat',
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat',
        ], $id);
    }

    protected function migrasi_2023061251($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Kunci Pilihan Tema',
            'key'        => 'lock_theme',
            'value'      => 1,
            'keterangan' => '1. bisa ganti tema, 0. tidak bisa pilih tema',
            'kategori'   => 'openkab',
            'jenis'      => 'option',
            'option'     => '{"0": "Kunci","1": "Bebas pilih"}',
        ], $id);
    }

    protected function migrasi_2023061271($hasil)
    {
        if (!$this->db->field_exists('foto', 'widget')) {
            $hasil = $hasil && $this->dbforge->add_column('widget', [
                'foto' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2023061351($hasil)
    {
        $fields = [];

        if (!$this->db->field_exists('Kd_Bank', 'keuangan_ta_spp')) {
            $fields['Kd_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Nm_Bank', 'keuangan_ta_spp')) {
            $fields['Nm_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Nm_Penerima', 'keuangan_ta_spp')) {
            $fields['Nm_Penerima'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Ref_Bayar', 'keuangan_ta_spp')) {
            $fields['Ref_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Rek_Bank', 'keuangan_ta_spp')) {
            $fields['Rek_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Rek_Bank', 'keuangan_ta_spp')) {
            $fields['Rek_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Tgl_Bayar', 'keuangan_ta_spp')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (!$this->db->field_exists('Validasi', 'keuangan_ta_spp')) {
            $fields['Validasi'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spp', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2023061451($hasil)
    {
        if (!$this->db->field_exists('slug', 'user_grup')) {
            $hasil = $hasil && $this->dbforge->add_column('user_grup', [
                'slug' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'nama',
                ],
            ]);

            if ($this->cek_indeks('user_grup', 'nama_grup_config')) {
                $hasil = $hasil && $this->db->query('ALTER TABLE `user_grup` DROP INDEX `nama_grup_config`, ADD UNIQUE INDEX `slug_config` (`config_id`, `slug`)');
            }
            $data = [];

            foreach ($this->db->get('user_grup')->result() as $row) {
                $data[] = [
                    'id'   => $row->id,
                    'slug' => unique_slug('user_grup', $row->nama),
                ];
            }

            if ($data) {
                $hasil = $hasil && $this->db->update_batch('user_grup', $data, 'id');
            }
        }

        // Hapus cache menu navigasi
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    protected function migrasi_2023061452($hasil)
    {
        $check = $this->db
            ->where_in('Nama_Bidang', [
                'BIDANG PEMBINAAN KEMASYARAKATAN',
                'BIDANG PEMBERDAYAAN MASYARAKAT',
            ])
            ->get('keuangan_manual_ref_bidang')
            ->result_array();

        if ($check) {
            // keuangan manual ref bidang
            foreach ([
                ['3', 'BIDANG PEMBINAAN KEMASYARAKATAN DESA'],
                ['4', 'BIDANG PEMBERDAYAAN MASYARAKAT DESA'],
            ] as $value) {
                [$id, $nama_bidang] = $value;

                $hasil = $hasil && $this->db
                    ->where('id', $id)
                    ->set('Nama_Bidang', $nama_bidang)
                    ->update('keuangan_manual_ref_bidang');
            }
        }

        return $hasil;
    }

    protected function suratKetDomisili($hasil, $id)
    {
        $nama_surat = 'Keterangan Domisili';

        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-41',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 195.75px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 21px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 21px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 21px;\">5.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 21px;\">Agama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 21px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 21px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 20.25px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 20.25px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 20.25px;\">6.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 20.25px;\">Status</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 20.25px;\"> </td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 20.25px;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">7..</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Pendidikan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.3222%; text-align: center;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5174%; text-align: left;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.24427%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 36px; text-align: left;\">10.<br /><br /></td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0524%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut di atas adalah benar-benar warga kami yang bertempat tinggal di [AlamaT] [Sebutan_desa] [NaMa_desa] dan tercatat dengan No. KK : [No_kK] Kepala Keluarga : [Kepala_kK].</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\"><br />       Surat Keterangan ini dibuat untuk Keperluan : [Form_keperluaN]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratLahirMati($hasil, $id)
    {
        $nama_surat = 'Keterangan Lahir Mati';

        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-22',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_hari]","nama":"Hari","deskripsi":"Masukkan hari","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal]","nama":"Tanggal","deskripsi":"Masukkan Tanggal","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_mati]","nama":"Tempat Mati","deskripsi":"Masukkan tempat lahir mati","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_hubungan]","nama":"Hubungan","deskripsi":"Masukkan hubungan dengan yang lahir mati","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_pelapor]","nama":"Pelapor","deskripsi":"Masukkan nama pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_lama_kandungan]","nama":"Lama Kandungan","deskripsi":"Masukkan lama kandungan dalam bulan","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa seorang ibu :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144.875px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 36px; text-align: left;\">4.<br /><br /></td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0524%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 10px;\">5.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 10px;\">Agama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 10px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 19.875px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.875px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.875px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.875px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.875px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.875px; text-align: justify;\">[Warga_negarA]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Telah lahir bayi dalam keadaan mati, setelah dikandungannya selama [Form_lama_kandungaN] bulan:</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 34.391px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pada hari, tanggal</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 18px; text-align: justify;\">[Form_harI], [Form_tanggaL]</td>\r\n</tr>\r\n<tr style=\"height: 16.391px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 16.391px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 16.391px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 16.391px;\">Di</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 16.391px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 16.391px; text-align: justify;\">[Form_tempat_matI]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Surat keterangan ini dibuat berdasarkan keterangan pelapor:</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 28px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 18px; text-align: justify;\">[Form_pelapoR]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 10px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 10px;\">Hubungan dgn yang lahir mati</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 10px; text-align: justify;\">[Form_hubungaN]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"> </div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratPermohonanDuplikatNikah($hasil, $id)
    {
        $nama_surat = 'Permohonan Duplikat Surat Nikah';

        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-33',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_kecamatan_kua]","nama":"Kecamatan KUA","deskripsi":"Isi Kecamatan KUA","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_nikah]","nama":"Tanggal Nikah","deskripsi":"Isi Tanggal Nikah","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pasangan]","nama":"Nama Pasangan","deskripsi":"Isi Nama Pasangan","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 251.4px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 19.7625px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.7625px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.7625px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.7625px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.7625px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.7625px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 19.7625px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.7625px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.7625px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.7625px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.7625px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.7625px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 25.8125px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 25.8125px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 25.8125px;\">3.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 25.8125px;\">Nomor KK</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 25.8125px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 25.8125px;\">[No_kK]</td>\r\n</tr>\r\n<tr style=\"height: 21.8125px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 21.8125px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 21.8125px;\">4.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 21.8125px;\">Kepala Keluarga</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 21.8125px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 21.8125px;\">[Kepala_kK]</td>\r\n</tr>\r\n<tr style=\"height: 19.7625px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.7625px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.7625px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.7625px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.7625px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.7625px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 19.7625px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.7625px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.7625px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.7625px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.7625px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.7625px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 43.4875px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 43.4875px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 43.4875px;\">7.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 43.4875px;\">Alamat / Tempat Tinggal</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 43.4875px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 43.4875px;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 21.825px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 21.825px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 21.825px;\">8.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 21.825px;\">Agama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 21.825px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 21.825px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 19.7625px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.7625px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.7625px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.7625px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.7625px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.7625px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 19.7625px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.7625px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.7625px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.7625px;\">Pendidikan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.7625px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.7625px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 19.8875px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.8875px;\">\u{a0}</td>\r\n<td style=\"width: 3.92927%; height: 19.8875px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.8875px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.8875px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.8875px; text-align: justify;\">[Warga_negarA]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang namanya tersebut di atas memang benar warga kami dan telah menikah di KUA [Form_kecamatan_kuA] pada [Form_tanggal_nikaH] dengan seseorang yang bernama [Form_nama_pasangaN]. Berdasarkan data di atas mohon untuk dibuatkan Duplikat Surat Nikah orang tersebut di atas.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 30%; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 30%; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\">\u{a0}</td>\r\n<td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%; height: 72px;\">\u{a0}</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 30%; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 30%; height: 18px;\">\u{a0}</td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratKepemilikanKendaraan($hasil, $id)
    {
        $nama_surat = 'Keterangan Kepemilikan Kendaraan';

        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-48',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_merktype]","nama":"Merk\/Type","deskripsi":"Merk\/Type Kendaraan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_tahun_pembuatan]","nama":"Tahun Pembuatan","deskripsi":"Tahun Pembuatan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_warna_kendaraan]","nama":"Warna Kendaraan","deskripsi":"Warna Kendaraan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_polisi]","nama":"Nomor Polisi","deskripsi":"Nomor Polisi","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_mesin]","nama":"Nomor Mesin","deskripsi":"Nomor Mesin","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_rangka]","nama":"Nomor Rangka","deskripsi":"Nomor Rangka","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_bpkb]","nama":"Nomor BPKB","deskripsi":"Nomor BPKB","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_bahan_bakar]","nama":"Bahan Bakar","deskripsi":"Bahan Bakar","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_isi_silinder]","nama":"Isi Silinder","deskripsi":"Isi Silinder","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_atas_nama]","nama":"Atas Nama","deskripsi":"Atas Nama","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_keperluan_pembuatan_surat]","nama":"Keperluan Pembuatan Surat","deskripsi":"Untuk Keperluan","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>
            <p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>
            <p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan bahwa berdasarkan keterangan dari :</p>
            <table style=\"border-collapse: collapse; width: 100%; height: 118px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 10px;\">
            <td style=\"width: 4.5221%; text-align: center; height: 10px;\">\u{a0}</td>
            <td style=\"width: 31.7575%; text-align: left; height: 10px;\">Nama Lengkap</td>
            <td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>
            <td style=\"width: 62.4872%; height: 10px; text-align: justify;\"><strong>[NAma]</strong></td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.5221%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7575%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>
            <td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.4872%; height: 18px; text-align: justify;\">[TtL]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.5221%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7575%; text-align: left; height: 18px;\">Umur</td>
            <td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.4872%; text-align: justify; height: 18px;\">[UsIa]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.5221%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7575%; text-align: left; height: 18px;\">Jenis Kelamin</td>
            <td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.4872%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.5221%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7575%; text-align: left; height: 18px;\">Pekerjaan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.4872%; text-align: justify; height: 18px;\">[PeKerjaan]</td>
            </tr>
            <tr style=\"height: 36px;\">
            <td style=\"width: 4.5221%; text-align: center; height: 36px;\">\u{a0}</td>
            <td style=\"width: 31.7575%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>
            <td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>
            <td style=\"width: 62.4872%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut adalah penduduk [Sebutan_desa] [NaMa_desa], yang mempunyai kendaraan dengan rincian sebagai berikut, sesuai BPKB :<strong><br /></strong></p>
            <table style=\"border-collapse: collapse; width: 100%; height: 180px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Merk / Type</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_merktypE]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Tahun Pembuatan</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_tahun_pembuataN]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Warna</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_warna_kendaraaN]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Nomor Polisi</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_nomor_polisI]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Nomor Mesin</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_nomor_mesiN]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Nomor Rangka</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_nomor_rangkA]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Nomor BPKB</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_nomor_bpkB]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Bahan Bakar</td>
            <td style=\"width: 1.20846%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_bahan_bakaR]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Isi Silinder</td>
            <td style=\"width: 1.20846%; height: 18px;\">\u{a0}:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_isi_silindeR]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.53172%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 31.7221%; height: 18px;\">Atas Nama</td>
            <td style=\"width: 1.20846%; height: 18px;\">\u{a0}:</td>
            <td style=\"width: 62.5378%; height: 18px;\">[Form_atas_namA]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">Kendaraan tersebut di atas adalah milik <strong>[NAma]</strong> yang beralamat di <strong>[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten].</strong> Surat keterangan ini dipergunakan untuk : <strong>[Form_keperluan_pembuatan_suraT]</strong>.</p>
            <p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>
            <p style=\"text-align: justify; text-indent: 30px;\">\u{a0}</p>
            <table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">
            <tbody>
            <tr>
            <td style=\"width: 35%; text-align: center;\">\u{a0}</td>
            <td style=\"width: 30%;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>
            </tr>
            <tr>
            <td style=\"width: 35%; text-align: center;\">Pemilik</td>
            <td style=\"width: 30%;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>
            </tr>
            <tr>
            <td style=\"width: 35%; text-align: center;\">\u{a0}</td>
            <td style=\"width: 30%;\"><br /><br /><br /><br /></td>
            <td style=\"width: 35%;\">\u{a0}</td>
            </tr>
            <tr>
            <td style=\"width: 35%; text-align: center;\">[NamA]</td>
            <td style=\"width: 30%;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>
            </tr>
            <tr>
            <td style=\"width: 35%;\">\u{a0}</td>
            <td style=\"width: 30%;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>
            </tr>
            </tbody>
            </table>
            <div style=\"text-align: center;\">\u{a0}</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratPenerbitanBukuPas($hasil, $id)
    {
        $nama_surat = 'Pengantar Permohonan Penerbitan Buku Pas Lintas';
        $template   = <<<HTML
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
            <p style="margin: 0; text-align: center;">Nomor : [Kode_suraT]/[Nomer_suraT]/437.103.09/[TahuN]<br /><br /></p>
            <p style="text-align: justify; text-indent: 30px;">Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
            <table style="border-collapse: collapse; width: 100%; height: 289.336px;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr style="height: 19.7461px;">
            <td style="width: 4.31044%; text-align: center; height: 19.7461px;">\u{a0}</td>
            <td style="width: 3.91132%; height: 19.7461px; text-align: left;">1.</td>
            <td style="width: 30.4923%; text-align: left; height: 19.7461px;">Nomor Induk Kependudukan (NIK)</td>
            <td style="width: 1.27717%; text-align: center; height: 19.7461px;">:</td>
            <td style="width: 60.0268%; height: 19.7461px; text-align: justify;">[Nik]</td>
            </tr>
            <tr style="height: 19.7461px;">
            <td style="width: 4.31044%; text-align: center; height: 19.7461px;">\u{a0}</td>
            <td style="width: 3.91132%; height: 19.7461px; text-align: left;">2.</td>
            <td style="width: 30.4923%; text-align: left; height: 19.7461px;">Nama Lengkap Pemohon</td>
            <td style="width: 1.27717%; text-align: center; height: 19.7461px;">:</td>
            <td style="width: 60.0268%; height: 19.7461px; text-align: justify;">[NAma]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">3.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Jenis Kelamin</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[Jenis_kelamiN]</td>
            </tr>
            <tr style="height: 19.7461px;">
            <td style="width: 4.31044%; text-align: center; height: 19.7461px;">\u{a0}</td>
            <td style="width: 3.91132%; height: 19.7461px; text-align: left;">4.</td>
            <td style="width: 30.4923%; text-align: left; height: 19.7461px;">Tempat</td>
            <td style="width: 1.27717%; text-align: center; height: 19.7461px;">:</td>
            <td style="width: 60.0268%; height: 19.7461px; text-align: justify;">[TempatlahiR]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">5.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Tanggal Lahir</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[TanggallahiR]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">6.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Alamat</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[AlamaT]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">\u{a0}</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">a.</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[Sebutan_desA] [Nama_desA] : [Kode_desA]</td>
            </tr>
            <tr style="height: 20.7422px;">
            <td style="width: 4.31044%; text-align: center; height: 20.7422px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 20.7422px;">\u{a0} \u{a0} \u{a0}</td>
            <td style="width: 30.4923%; text-align: left; height: 20.7422px;">b.</td>
            <td style="width: 1.27717%; text-align: center; height: 20.7422px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 20.7422px;">[Sebutan_kecamataN] [Nama_kecamataN] : [Kode_kecamataN]</td>
            </tr>
            <tr style="height: 19.7461px;">
            <td style="width: 4.31044%; text-align: center; height: 19.7461px;">\u{a0}</td>
            <td style="width: 3.91132%; height: 19.7461px; text-align: left;">7.</td>
            <td style="width: 30.4923%; text-align: left; height: 19.7461px;">Pekerjaan</td>
            <td style="width: 1.27717%; text-align: center; height: 19.7461px;">:</td>
            <td style="width: 60.0268%; height: 19.7461px; text-align: justify;">[PeKerjaan]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">8.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Status Perkawinan</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[Status_kawiN]</td>
            </tr>
            <tr style="height: 19.8633px;">
            <td style="width: 4.31044%; text-align: center; height: 19.8633px;">\u{a0}</td>
            <td style="width: 3.91132%; height: 19.8633px; text-align: left;">9.</td>
            <td style="width: 30.4923%; text-align: left; height: 19.8633px;">Kewarganegaraan</td>
            <td style="width: 1.27717%; text-align: center; height: 19.8633px;">:</td>
            <td style="width: 60.0268%; height: 19.8633px; text-align: justify;">[Warga_negarA]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">10.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Agama</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[AgAma]</td>
            </tr>
            <tr style="height: 19.7461px;">
            <td style="width: 4.31044%; text-align: center; height: 19.7461px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 19.7461px;">11.</td>
            <td style="width: 30.4923%; text-align: left; height: 19.7461px;">Nomor Kartu Keluarga</td>
            <td style="width: 1.27717%; text-align: center; height: 19.7461px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 19.7461px;">[No_kK]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">12.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Nama Kepala Keluarga</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">[Kepala_kK]</td>
            </tr>
            <tr style="height: 18.75px;">
            <td style="width: 4.31044%; text-align: center; height: 18.75px;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left; height: 18.75px;">13.</td>
            <td style="width: 30.4923%; text-align: left; height: 18.75px;">Pengikut / Anggota Keluarga **)</td>
            <td style="width: 1.27717%; text-align: center; height: 18.75px;">:</td>
            <td style="width: 60.0268%; text-align: justify; height: 18.75px;">\u{a0}</td>
            </tr>
            <tr>
            <td style="width: 4.31044%; text-align: center;">\u{a0}</td>
            <td style="width: 3.91132%; text-align: left;">\u{a0}</td>
            <td style="text-align: left; width: 91.7963%;" colspan="3">[Pengikut_suraT]</td>
            </tr>
            </tbody>
            </table>
            <br />
            <p style="text-align: justify; text-indent: 30px;">Surat permohonan ini dipergunakan untuk pengurusan penerbitan Buku Pas Lintas Batas.<br /><br /></p>
            <table style="border-collapse: collapse; width: 100%; height: 144px;" border="0">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 35%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 30%; height: 18px;">\u{a0}</td>
            <td style="width: 35%; text-align: center; height: 18px;">[NaMa_desa], [TgL_surat]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 35%; text-align: center; height: 18px;">Mengetahui :\u{a0}</td>
            <td style="width: 30%; height: 18px;">\u{a0}</td>
            <td style="width: 35%; text-align: center; height: 18px;">[Atas_namA]</td>
            </tr>
            <tr>
            <td style="width: 35%; text-align: center;">[Sebutan_camaT] [Nama_kecamataN]</td>
            <td style="width: 30%;">\u{a0}</td>
            <td style="width: 35%; text-align: center;">\u{a0}</td>
            </tr>
            <tr style="height: 72px;">
            <td style="width: 35%; text-align: center; height: 72px;">\u{a0}</td>
            <td style="width: 30%; height: 72px;"><br /><br /><br /><br /></td>
            <td style="width: 35%; height: 72px;">\u{a0}</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 35%; text-align: center; height: 18px;">[Nama_kepala_camaT]</td>
            <td style="width: 30%; height: 18px;">\u{a0}</td>
            <td style="width: 35%; text-align: center; height: 18px;">[Nama_pamonG]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 35%; height: 18px; text-align: center;">[Nip_kepala_camaT]</td>
            <td style="width: 30%; height: 18px;">\u{a0}</td>
            <td style="width: 35%; text-align: center; height: 18px;">[SEbutan_nip_desa] : [nip_pamong]</td>
            </tr>
            </tbody>
            </table>
            <div style="text-align: left;"><br /><span style="font-size: 9pt;">Surat Pengantar ini rangkap 3 (tiga) :<br /></span><span style="font-size: 9pt;">Lembar 1 : untuk Kantor Imigrasi di Pos Lintas Batas;<br />Lembar 2 : untuk Arsip Kecamatan;</span><br /><span style="font-size: 9pt;">Lembar 3 : untuk Arsip Desa/Kelurahan<br /><strong>*) diisi oleh petugas</strong><br /><strong>**) Hanya untuk anak dibawah 18 tahun atau belum memilki KTP dan terdaftar dalam Kartu Keluarga (KK) Pemohon (Pemohon sebagai orang tua atau wali)</strong><br /></span><br /><br /></div>
            HTML;
        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-43',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => null,
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3'],
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function migrasi_2023061552($hasil)
    {
        $sql = <<<'SQL'
                    update tweb_surat_format set kode_isian = REPLACE (kode_isian, '"atribut":"required"', '"atribut":"class=\"required\""') where kode_isian like '%"atribut":"required"%'
            SQL;
        DB::statement($sql);

        return $hasil;
    }

    protected function migrasi_2023061751($hasil)
    {
        $sql = <<<'SQL'
                    ALTER TABLE tweb_penduduk MODIFY COLUMN hubung_warga varchar(50) NULL
            SQL;
        DB::statement($sql);

        return $hasil;
    }

    protected function migrasi_2023061752($hasil)
    {
        $sql = <<<'SQL'
                    SHOW TABLE STATUS WHERE ENGINE != 'InnoDB'
            SQL;
        $innoDb = DB::select($sql);
        if ($innoDb) {
            foreach ($innoDb as $table) {
                DB::statement('ALTER TABLE ' . $table->Name . ' ENGINE = InnoDB'); //query untuk ubah ke innoDB;
            }
        }

        return $hasil;
    }

    protected function suratKeteranganPenghasilanOrangTua($hasil, $id)
    {
        $template = <<<HTML
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
            <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br /><br /></p>
            <p style="text-align: justify; text-indent: 30px;">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>
            <table style="border-collapse: collapse; width: 100%; height: 90px;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.90545%; height: 18px; text-align: left;">1.</td>
            <td style="width: 30.5242%; text-align: left; height: 18px;">Nama Lengkap</td>
            <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
            <td style="width: 60.0206%; height: 18px; text-align: justify;"><strong>[NAma]</strong></td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">2.</td>
            <td style="width: 30.5242%; text-align: left;">Tempat / Tanggal Lahir</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[TtL]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">3.</td>
            <td style="width: 30.5242%; text-align: left;">NIK</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[NiK]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.90545%; height: 18px; text-align: left;">4.</td>
            <td style="width: 30.5242%; text-align: left; height: 18px;">Jenis Kelamin</td>
            <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
            <td style="width: 60.0206%; height: 18px; text-align: justify;">[Jenis_kelamin]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">5.</td>
            <td style="width: 30.5242%; text-align: left;">Nomor Induk Siswa/Mahasiswa</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[Form_nomor_induk_siswamahasiswA]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">6.</td>
            <td style="width: 30.5242%; text-align: left;">Jurusan/Fakultas/Prodi</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[Form_jurusanfakultasprodI]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">7.</td>
            <td style="width: 30.5242%; text-align: left;">Sekolah/Perguruan Tinggi</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[Form_sekolahperguruan_tinggI]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">8.</td>
            <td style="width: 30.5242%; text-align: left;">Kelas/Semester</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[Form_kelassemesteR]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">9.</td>
            <td style="width: 30.5242%; text-align: left;">Agama</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[AgamA]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">10.</td>
            <td style="width: 30.5242%; text-align: left;">Pekerjaan</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[PekerjaaN]</td>
            </tr>
            <tr>
            <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
            <td style="width: 3.90545%; text-align: left;">11.</td>
            <td style="width: 30.5242%; text-align: left;">Alamat</td>
            <td style="width: 1.2333%; text-align: center;">:</td>
            <td style="width: 60.0206%; text-align: justify;">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], [Nama_provinsI]</td>
            </tr>
            </tbody>
            </table>
            <p style="text-align: justify; text-indent: 30px;">Adalah benar penduduk yang berdomisili di [AlamaT], [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], [Nama_provinsI], dan merupakan <strong>Anak\u{a0}</strong>dari:</p>
            <table style="border-collapse: collapse; width: 100%; height: 310px;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 18px; text-align: left;">1.</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Nama Ayah</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; height: 18px; text-align: justify;">[Nama_ayaH]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 18px; text-align: left;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Tempat / Tanggal Lahir</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; height: 18px; text-align: justify;">[Ttl_ayaH]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 18px; text-align: left;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">NIK</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; height: 18px; text-align: justify;">[Nik_ayaH]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left; height: 18px;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Jenis Kelamin</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; text-align: justify; height: 18px;">[Jenis_kelamin_ayaH]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left; height: 18px;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Agama</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 70.5959%; text-align: justify; height: 18px;">[Agama_ayaH]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left; height: 18px;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Pekerjaan</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; text-align: justify; height: 18px;">[Pekerjaan_ayaH]</td>
            </tr>
            <tr style="height: 36px;">
            <td style="width: 4.3222%; text-align: center; height: 36px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 36px; text-align: left;"><br /><br /></td>
            <td style="width: 20.1703%; text-align: left; height: 36px;">Alamat<br /><br /></td>
            <td style="width: 1.04781%; text-align: center; height: 36px;">:<br /><br /></td>
            <td style="width: 70.5959%; height: 36px; text-align: justify;">[Alamat_ayaH] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], [Nama_provinsI]</td>
            </tr>
            <tr>
            <td style="width: 4.3222%; text-align: center;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left;">Penghasilan</td>
            <td style="width: 1.04781%; text-align: center;">:</td>
            <td style="width: 70.5959%; text-align: justify;">[Form_penghasilan_ayaH]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="text-align: center; width: 100.065%; height: 18px;" colspan="5">\u{a0}</td>
            </tr>
            <tr style="height: 22px;">
            <td style="width: 4.3222%; text-align: center; height: 22px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 18px; text-align: left;">2.</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Nama Ibu</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; height: 18px; text-align: justify;">[Nama_ibU]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 18px; text-align: left;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Tempat / Tanggal Lahir</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; height: 18px; text-align: justify;">[Ttl_ibU]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 18px; text-align: left;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">NIK</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; height: 18px; text-align: justify;">[Nik_ibU]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left; height: 18px;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Jenis Kelamin</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; text-align: justify; height: 18px;">[Jenis_kelamin_ibU]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left; height: 18px;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Agama</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 70.5959%; text-align: justify; height: 18px;">[Agama_ibU]</td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 4.3222%; text-align: center; height: 18px;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left; height: 18px;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left; height: 18px;">Pekerjaan</td>
            <td style="width: 1.04781%; text-align: center; height: 18px;">:</td>
            <td style="width: 70.5959%; text-align: justify; height: 18px;">[Pekerjaan_ibU]</td>
            </tr>
            <tr style="height: 36px;">
            <td style="width: 4.3222%; text-align: center; height: 36px;">\u{a0}</td>
            <td style="width: 3.92927%; height: 36px; text-align: left;"><br /><br /></td>
            <td style="width: 20.1703%; text-align: left; height: 36px;">Alamat<br /><br /></td>
            <td style="width: 1.04781%; text-align: center; height: 36px;">:<br /><br /></td>
            <td style="width: 70.5959%; height: 36px; text-align: justify;">[Alamat_ibU] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], [Nama_provinsI]</td>
            </tr>
            <tr>
            <td style="width: 4.3222%; text-align: center;">\u{a0}</td>
            <td style="width: 3.92927%; text-align: left;">\u{a0}</td>
            <td style="width: 20.1703%; text-align: left;">Penghasilan</td>
            <td style="width: 1.04781%; text-align: center;">:</td>
            <td style="width: 70.5959%; text-align: justify;">[Form_penghasilan_ibU]</td>
            </tr>
            </tbody>
            </table>
            <p style="text-align: justify; text-indent: 30px;">Dengan penghasilan rata-rata <strong>Orang Tua [Form_penghasilan_orang_tua_ayah_ibU]</strong><strong> </strong>setiap bulannya.</p>
            <p style="text-align: justify; text-indent: 30px;">Demikian Surat Keterangan Penghasilan Orangtua ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</p>
            <p>\u{a0}</p>
            <table style="border-collapse: collapse; width: 100%;" border="0">
            <tbody>
            <tr>
            <td style="width: 35%; text-align: center;">\u{a0}</td>
            <td style="width: 30%;">\u{a0}</td>
            <td style="width: 35%; text-align: center;">[NaMa_desa], [TgL_surat]</td>
            </tr>
            <tr>
            <td style="width: 35%; text-align: center;">\u{a0}</td>
            <td style="width: 30%;">\u{a0}</td>
            <td style="width: 35%; text-align: center;">[Atas_namA]</td>
            </tr>
            <tr>
            <td style="width: 35%; text-align: center;">\u{a0}</td>
            <td style="width: 30%;"><br /><br /><br /><br /></td>
            <td style="width: 35%;">\u{a0}</td>
            </tr>
            <tr>
            <td style="width: 35%; text-align: center;">\u{a0}</td>
            <td style="width: 30%;">\u{a0}</td>
            <td style="width: 35%; text-align: center;">[Nama_pamonG]</td>
            </tr>
            <tr>
            <td style="width: 35%;">\u{a0}</td>
            <td style="width: 30%;">\u{a0}</td>
            <td style="width: 35%; text-align: center;">[SEbutan_nip_desa] : [nip_pamong]</td>
            </tr>
            </tbody>
            </table>
            <div style="text-align: center;"><br />[qr_code]</div>
            HTML;

        $data = [
            'nama'                => 'Keterangan Penghasilan Orang Tua',
            'kode_surat'          => 'S-42',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_nomor_induk_siswamahasiswa]","nama":"Nomor Induk Siswa\/Mahasiswa","deskripsi":"Masukkan Nomor Induk Siswa\/Mahasiswa","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_jurusanfakultasprodi]","nama":"Jurusan\/Fakultas\/Prodi","deskripsi":"Masukkan Jurusan\/Fakultas\/Prodi","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_sekolahperguruan_tinggi]","nama":"Sekolah\/Perguruan Tinggi","deskripsi":"Masukkan Sekolah\/Perguruan Tinggi","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kelassemester]","nama":"Kelas\/Semester","deskripsi":"Masukkan Kelas\/Semester","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_penghasilan_ayah]","nama":"Penghasilan Ayah","deskripsi":"Masukkan Penghasilan Ayah","atribut":"class=\"required rupiah\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_penghasilan_ibu]","nama":"Penghasilan Ibu","deskripsi":"Masukkan Penghasilan Ibu","atribut":"class=\"required rupiah\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_penghasilan_orang_tua_ayah_ibu]","nama":"Penghasilan Orang Tua (Ayah + Ibu)","deskripsi":"Masukkan Penghasilan Orang Tua (Ayah + Ibu)","atribut":"class=\"required rupiah\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":"4"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratBiodataPenduduk($hasil, $id)
    {
        $nama_surat = 'Biodata Penduduk';

        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-03',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['3', '4'],
            'lampiran'            => 'F-1.01, F-1.02',
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>
            <p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>
            <p style=\"text-align: justify; text-indent: 30px;\"><strong>I. DATA KELURGA</strong></p>
            <table style=\"border-collapse: collapse; width: 100%; height: 79px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 25px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 25px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; height: 25px; text-align: left;\">1.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 25px;\">Nama Kepala Keluarga</td>
            <td style=\"width: 1.26582%; text-align: center; height: 25px;\">:</td>
            <td style=\"width: 60.0211%; height: 25px; text-align: justify;\">[KePala_kk]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; text-align: left; height: 18px;\">2.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 18px;\">Nomor Kartu Keluarga</td>
            <td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 60.0211%; text-align: justify; height: 18px;\">[No_kk]</td>
            </tr>
            <tr style=\"height: 36px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 36px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; text-align: left; height: 36px;\">3.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 36px;\">Alamat Keluarga</td>
            <td style=\"width: 1.26582%; text-align: center; height: 36px;\">:</td>
            <td style=\"width: 60.0211%; text-align: justify; height: 36px;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\"><strong>II. DATA INDIVIDU</strong></p>
            <table style=\"border-collapse: collapse; width: 100%; height: 519px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; height: 25.9375px; text-align: left;\">1.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Nama Lengkap</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; height: 25.9375px; text-align: justify;\"><strong>[NAma]</strong></td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; height: 25.9375px; text-align: left;\">2.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">NIK</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; height: 25.9375px; text-align: justify;\">[NiK]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; height: 25.9375px; text-align: left;\">3.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Alamat Sebelumnya</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; height: 25.9375px; text-align: justify;\">[AlAmat_sebelumnya]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; height: 25.9375px; text-align: left;\">4.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Nomor Paspor</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; height: 25.9375px; text-align: justify;\">[DoKumen_pasport]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; height: 25.9375px; text-align: left;\">5.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Tanggal Berakhir Paspor</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; height: 25.9375px; text-align: justify;\">[TaNggal_akhir_paspor]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; height: 25.9375px; text-align: left;\">6.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Jenis Kelamin</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; height: 25.9375px; text-align: justify;\">[Jenis_kelamin]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">7.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Tempat Lahir</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[TeMpatlahir]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">8.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Tanggal Lahir</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[TtL]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">9.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Akta Kelahiran / Surat Kelahiran</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[AkTa_lahir]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">10.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Golongan Darah</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[GoL_darah]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">11.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Agama</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[AgAma]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">12.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Status Perkawinan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[StAtus_kawin]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">13.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Akta Perkawinan / Buku Nikah</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[AkTa_perkawinan]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">14.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Tanggal Akta Perkawinan / Buku Nikah</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[TaNggalperkawinan]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">15.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Akta Perceraian</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[AkTa_perceraian]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">16.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Tanggal Perceraian</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[TaNggalperceraian]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">17.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Status Hubungan Dalam Keluarga</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[HuBungan_kk]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">18.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Kelainan Fisik / Mental</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[CaCat]</td>
            </tr>
            <tr style=\"height: 25.9375px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 25.9375px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 25.9375px;\">19.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 25.9375px;\">Pendidikan Akhir</td>
            <td style=\"width: 1.2333%; text-align: center; height: 25.9375px;\">:</td>
            <td style=\"width: 60.0206%; text-align: justify; height: 25.9375px;\">[PeNdidikan_kk]</td>
            </tr>
            <tr style=\"height: 26.1875px;\">
            <td style=\"width: 4.31655%; text-align: center; height: 26.1875px;\">\u{a0}</td>
            <td style=\"width: 3.90545%; text-align: left; height: 26.1875px;\">20.</td>
            <td style=\"width: 30.5242%; text-align: left; height: 26.1875px;\">Pekerjaan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 26.1875px;\">:</td>
            <td style=\"width: 60.0206%; height: 26.1875px; text-align: justify;\">[PeKerjaan]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\"><strong>III. DATA ORANG TUA<br /></strong></p>
            <table style=\"border-collapse: collapse; width: 100%; height: 83px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 25px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 25px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; height: 25px; text-align: left;\">1.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 25px;\">Nama Ibu</td>
            <td style=\"width: 1.26582%; text-align: center; height: 25px;\">:</td>
            <td style=\"width: 60.0211%; height: 25px; text-align: justify;\">[Nama_ibu]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; text-align: left; height: 18px;\">2.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 18px;\">NIK Ibu</td>
            <td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 60.0211%; text-align: justify; height: 18px;\">[nik_ibu]</td>
            </tr>
            <tr style=\"height: 22px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 22px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; text-align: left; height: 22px;\">3.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 22px;\">Nama Ayah</td>
            <td style=\"width: 1.26582%; text-align: center; height: 22px;\">:</td>
            <td style=\"width: 60.0211%; text-align: justify; height: 22px;\">[Nama_ayah]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.32489%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 3.90295%; text-align: left; height: 18px;\">4.</td>
            <td style=\"width: 30.4852%; text-align: left; height: 18px;\">NIK Ayah</td>
            <td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 60.0211%; text-align: justify; height: 18px;\">[nik_ayah]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">\u{a0}</p>
            <table style=\"border-collapse: collapse; width: 100%; height: 199.906px;\" border=\"0\">
            <tbody>
            <tr style=\"height: 28.625px;\">
            <td style=\"width: 35.0462%; text-align: center; height: 28.625px;\">\u{a0}</td>
            <td style=\"width: 30.0103%; height: 28.625px;\">\u{a0}</td>
            <td style=\"width: 35.0462%; text-align: center; height: 28.625px;\">[NaMa_desa], [TgL_surat]</td>
            </tr>
            <tr style=\"height: 22.625px;\">
            <td style=\"width: 35.0462%; text-align: center; height: 22.625px;\">\u{a0}</td>
            <td style=\"width: 30.0103%; height: 22.625px;\">\u{a0}</td>
            <td style=\"width: 35.0462%; text-align: center; height: 22.625px;\">[Atas_namA]</td>
            </tr>
            <tr style=\"height: 96.3438px;\">
            <td style=\"width: 35.0462%; text-align: center; height: 96.3438px;\">\u{a0}</td>
            <td style=\"width: 30.0103%; height: 96.3438px;\"><br /><br /><br /><br /></td>
            <td style=\"width: 35.0462%; height: 96.3438px;\">\u{a0}</td>
            </tr>
            <tr style=\"height: 21.625px;\">
            <td style=\"width: 35.0462%; text-align: center; height: 21.625px;\">\u{a0}</td>
            <td style=\"width: 30.0103%; height: 21.625px;\">\u{a0}</td>
            <td style=\"width: 35.0462%; text-align: center; height: 21.625px;\">[Nama_pamonG]</td>
            </tr>
            <tr style=\"height: 30.6875px;\">
            <td style=\"width: 35.0462%; height: 30.6875px;\">\u{a0}</td>
            <td style=\"width: 30.0103%; height: 30.6875px;\">\u{a0}</td>
            <td style=\"width: 35.0462%; text-align: center; height: 30.6875px;\">[SEbutan_nip_desa] : [nip_pamong]</td>
            </tr>
            </tbody>
            </table>
            <div style=\"text-align: center;\">\u{a0}</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratPerintahPerjalananDinas($hasil, $id)
    {
        $nama_surat = 'Perintah Perjalanan Dinas';
        $template   = <<<HTML
                    <div>
                <table style="border-collapse: collapse; width: 42.264%; height: 58px;" border="0" align="right">
                    <tbody>
                        <tr style="height: 18.75px;">
                            <td style="width: 25.4998%; height: 18.75px;">Lembar Ke</td>
                            <td style="width: 74.5527%; height: 18.75px;">:\u{a0}</td>
                        </tr>
                        <tr style="height: 18.75px;">
                            <td style="width: 25.4998%; height: 18.75px;">Kode Ke</td>
                            <td style="width: 74.5527%; height: 18.75px;">:\u{a0}</td>
                        </tr>
                        <tr style="height: 18.75px;">
                            <td style="width: 25.4998%; height: 18.75px;">Nomor</td>
                            <td style="width: 74.5527%; height: 18.75px;">: [Kode_suraT]/[Nomer_suraT]/437.103.09/[TahuN]</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]<br /><br /></span>
            </h4>
            <table style="border-collapse: collapse; width: 94.9743%; height: 803.203px; margin-left: auto; margin-right: auto;"
                border="1">
                <tbody>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">1. Pengguna Anggaran</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Sebutan_kepala_desA] [Nama_desA]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">2. Nama pegawai yang diperintah</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;"><strong>[NamA]</strong></td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">3. a. Pangkat dan Golongan</td>
                        <td style="width: 2.01724%; height: 18.75px;">a</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_pangkat_dan_golongaN]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">\u{a0} \u{a0} b. Jabatan/Instansi</td>
                        <td style="width: 2.01724%; height: 18.75px;">b</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_jabataninstansI]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">\u{a0} \u{a0} c. Tingkat Biaya Perjalanan</td>
                        <td style="width: 2.01724%; height: 18.75px;">c</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_tingkat_biaya_perjalanaN]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">4. Maksud Perjalanan Dinas</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;"><strong>[Form_maksud_perjalanan_dinaS]</strong></td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">5. a. Tempat Berangkat</td>
                        <td style="width: 2.01724%; height: 18.75px;">a.</td>
                        <td style="width: 64.9718%; height: 18.75px;">Kantor [Sebutan_desA] [Nama_desA]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">\u{a0} \u{a0} b. Tempat Tujuan</td>
                        <td style="width: 2.01724%; height: 18.75px;">b.</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_tempat_tujuaN]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">6. a. Tanggal Berangkat</td>
                        <td style="width: 2.01724%; height: 18.75px;">a.</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_tanggal_berangkaT]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">b. Tanggal Kembali</td>
                        <td style="width: 2.01724%; height: 18.75px;">b.</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_tanggal_kembalI]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">7. Alat angkut yang dipergunakan</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_alat_angkut_yang_digunakaN]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">8. Pengikut Nama</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 35.0495%;" colspan="3">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td width="50%">
                                            <table>
                                                <tbody>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 8.4878%; height: 18.75px;" width="23">1.</td>
                                                        <td style="width: 91.5091%; height: 18.75px;" width="283">
                                                            [Form_nama_pengikut_I]</td>
                                                    </tr>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 8.4878%; height: 18.75px;" width="23">2.</td>
                                                        <td style="width: 91.5091%; height: 18.75px;" width="283">
                                                            [Form_nama_pengikut_iI]</td>
                                                    </tr>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 8.4878%; height: 18.75px;" width="23">3.</td>
                                                        <td style="width: 91.5091%; height: 18.75px;" width="283">
                                                            [Form_nama_pengikut_iiI]</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 8.19431%;" width="25">4.</td>
                                                        <td style="width: 92.0655%;" width="262">[Form_nama_pengikut_iV]</td>
                                                    </tr>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 8.19431%;" width="25">5.</td>
                                                        <td style="width: 92.0655%;" width="262">[Form_nama_pengikut_V]</td>
                                                    </tr>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 8.19431%;" width="25">6.</td>
                                                        <td style="width: 92.0655%;" width="262">[Form_nama_pengikut_vI]</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">9. Pembebanan Anggaran</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">a. Instansi</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">a. Kantor [Sebutan_desA] [Nama_desA]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">b. Mata Anggaran</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">b. APBKam\u{a0}\u{a0}Tahun [TahuN]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 33.0323%; height: 18.75px;">10. Keterangan lain-lain</td>
                        <td style="width: 2.01724%; height: 18.75px;">:</td>
                        <td style="width: 64.9718%; height: 18.75px;">[Form_keterangan_laiN]</td>
                    </tr>
                </tbody>
            </table>
            <h4 style="margin: 0; text-align: center;">\u{a0}</h4>
            <table style="border-collapse: collapse; width: 97.4463%; height: 192px;" border="0">
                <tbody>
                    <tr style="height: 18.75px;">
                        <td style="width: 67.8329%; text-align: left;">\u{a0}</td>
                        <td style="width: 32.1882%; text-align: left; height: 18.75px;">Ditetapkan di : [NaMa_desa]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 67.8329%; text-align: left;">\u{a0}</td>
                        <td style="width: 32.1882%; text-align: left;">Pada Tanggal :\u{a0} [TgL_surat]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 67.8329%; text-align: left;">\u{a0}</td>
                        <td style="width: 32.1882%; text-align: left; height: 18.75px;">[Sebutan_kepala_desA] [Nama_desA]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 67.8329%; text-align: center;">\u{a0}</td>
                        <td style="width: 32.1882%; text-align: center; height: 18.75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 75px;">
                        <td style="width: 67.8329%;">\u{a0}</td>
                        <td style="width: 32.1882%; height: 75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 67.8329%; text-align: center;">\u{a0}</td>
                        <td style="width: 32.1882%; text-align: center; height: 18.75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 67.8329%; text-align: left;"><strong>\u{a0}</strong></td>
                        <td style="width: 32.1882%; text-align: left; height: 18.75px;"><strong>[Nama_pamonG]</strong></td>
                    </tr>
                </tbody>
            </table>
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">\u{a0}</span></h4>
            <table style="border-collapse: collapse; width: 95%; margin-left: auto; margin-right: auto; height: 977.29px;"
                border="1">
                <tbody>
                    <tr style="height: 160.445px;">
                        <td style="height: 160.445px;" colspan="2">
                            <table style="width: 100.29%;" align="left">
                                <tbody>
                                    <tr>
                                        <td style="width: 64.8654%;">
                                            <table style="border-collapse: collapse; width: 48.2556%;" width="100%">
                                                <tbody>
                                                    <tr style="height: 18.5px;">
                                                        <td style="width: 29.6649%; text-align: right; height: 18.5px;" width="105">
                                                            I. Berangkat dari</td>
                                                        <td style="text-align: left; width: 70.2443%; height: 18.5px;" width="304">:
                                                            Kantor [Sebutan_desA] [Nama_desA]</td>
                                                    </tr>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 29.6649%; height: 18.75px; text-align: right;"
                                                            width="105">Ke</td>
                                                        <td style="text-align: left; width: 70.2443%; height: 18.75px;" width="304">
                                                            : [Form_tempat_tujuaN]</td>
                                                    </tr>
                                                    <tr style="height: 18.75px;">
                                                        <td style="width: 29.6649%; height: 18.75px; text-align: right;"
                                                            width="105">Pada Tanggal</td>
                                                        <td style="text-align: left; width: 70.2443%; height: 18.75px;" width="304">
                                                            : [Form_tanggal_berangkaT]</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="width: 35.2169%;">
                                            <div style="width: 200px;">\u{a0}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100.082%;" colspan="2">
                                            <table style="border-collapse: collapse; height: 68.2422px;" width="100%"
                                                align="center">
                                                <tbody>
                                                    <tr style="height: 68.2422px;">
                                                        <td style="text-align: center; height: 68.2422px;">[Sebutan_kepala_desA]
                                                            [Nama_desA]
                                                            <p><br /><br /><strong>( [Nama_pamonG]</strong><strong>\u{a0})</strong></p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 177.188px;">
                        <td style="width: 47.3086%; height: 185.172px;" width="315">
                            <table style="border-collapse: collapse; width: 100%; height: 177.188px;">
                                <tbody>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">II.</td>
                                        <td style="width: 31.335%; height: 18.75px;">Tiba di\u{a0}</td>
                                        <td style="width: 63.765%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 31.335%; height: 18.75px;">Pada Tanggal</td>
                                        <td style="width: 63.765%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 31.335%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 63.765%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 31.335%; height: 18.75px;">Kepala</td>
                                        <td style="width: 63.765%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 31.335%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 63.765%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 31.335%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 63.765%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="height: 18.75px; width: 95.1%;" colspan="2">( ...................................
                                            )</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 52.6865%; height: 177.188px;" width="351">
                            <table style="border-collapse: collapse; width: 100.85%; height: 177.188px;" border="0">
                                <tbody>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9707%; height: 18.75px;">Berangkat dari\u{a0}</td>
                                        <td style="width: 66.5695%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9707%; height: 18.75px;">Ke</td>
                                        <td style="width: 66.5695%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9707%; height: 18.75px;">Pada Tanggal</td>
                                        <td style="width: 66.5695%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9707%; height: 18.75px;">Kepala</td>
                                        <td style="width: 66.5695%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9707%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 66.5695%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9707%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 66.5695%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="height: 18.75px; width: 95.5402%;" colspan="2">(
                                            ................................... )</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 177.188px;">
                        <td style="width: 47.3086%; height: 177.188px;" width="315">
                            <table style="border-collapse: collapse; width: 100%; height: 177.188px;">
                                <tbody>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">III.</td>
                                        <td style="width: 30.7993%; height: 18.75px;">Tiba di\u{a0}</td>
                                        <td style="width: 64.3008%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.7993%; height: 18.75px;">Pada Tanggal</td>
                                        <td style="width: 64.3008%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.7993%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 64.3008%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.7993%; height: 18.75px;">Kepala</td>
                                        <td style="width: 64.3008%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.7993%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 64.3008%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.7993%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 64.3008%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="height: 18.75px; width: 95.1%;" colspan="2">( ...................................
                                            )</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 52.6865%; height: 177.188px;" width="351">
                            <table style="border-collapse: collapse; width: 100.85%; height: 177.188px;" border="0">
                                <tbody>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Berangkat dari\u{a0}</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Ke</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Pada Tanggal</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Kepala</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 66.5597%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 66.5597%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%;">\u{a0}</td>
                                        <td style="height: 18.75px; width: 95.5402%;" colspan="2">(
                                            ................................... )</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 177.188px;">
                        <td style="width: 47.3086%; height: 177.188px;" width="315">
                            <table style="border-collapse: collapse; width: 100%; height: 177.188px;">
                                <tbody>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">IV.</td>
                                        <td style="width: 30.2693%; height: 18.75px;">Tiba di\u{a0}</td>
                                        <td style="width: 64.6516%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.2693%; height: 18.75px;">Pada Tanggal</td>
                                        <td style="width: 64.6516%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.2693%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 64.6516%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.2693%; height: 18.75px;">Kepala</td>
                                        <td style="width: 64.6516%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.2693%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 64.6516%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 30.2693%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 64.6516%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 5.01469%; height: 18.75px;">\u{a0}</td>
                                        <td style="height: 18.75px; width: 94.9209%;" colspan="2">(
                                            ................................... )</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 52.6865%; height: 177.188px;" width="351">
                            <table style="border-collapse: collapse; width: 100.85%; height: 177.188px;" border="0">
                                <tbody>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Berangkat dari\u{a0}</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Ke</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Pada Tanggal</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">Kepala</td>
                                        <td style="width: 66.5597%; height: 18.75px;">:</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 66.5597%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 28.9805%; height: 18.75px;">\u{a0}</td>
                                        <td style="width: 66.5597%; height: 18.75px;">\u{a0}</td>
                                    </tr>
                                    <tr style="height: 18.75px;">
                                        <td style="width: 4.45854%; height: 18.75px;">\u{a0}</td>
                                        <td style="height: 18.75px; width: 95.5402%;" colspan="2">(
                                            ................................... )</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 41.1133px;">
                        <td style="width: 47.3086%; height: 41.1133px;" width="315">V. Tiba di : Kantor [Sebutan_desA] [Nama_desA]
                            <br />Pada Tanggal : [Form_tanggal_kembalI]</td>
                        <td style="width: 52.6865%; height: 41.1133px;" width="351"><em>Telah diperiksa dengan keterangan bahwa
                                perjalan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang
                                sesingkat-singkatnya</em></td>
                    </tr>
                    <tr style="height: 80.988px;">
                        <td style="text-align: center; width: 99.9951%; height: 80.988px;" colspan="2" width="666">
                            [Sebutan_kepala_desA] [Nama_desA] <br /><br /><br /><br /><strong>(
                                [Nama_pamonG]</strong><strong>\u{a0})</strong></td>
                    </tr>
                    <tr style="height: 58.2422px;">
                        <td style="width: 99.9951%; height: 58.2422px;" colspan="2" width="666">VI. <u>PERHATIAN </u> <br />Pengguna
                            Anggaran yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan
                            tanggal berangkat/tiba, \u{a0}\u{a0}\u{a0}serta bendahara pengeluaran bertanggungjawab berdasarkan Peraturan Keuangan
                            Negara. Apabila Negara menderita \u{a0}rugi akibat \u{a0}kesalahan, kelalaian dan kealpaannya.</td>
                    </tr>
                </tbody>
            </table>
            <br /><br />
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;"><br /><br />[JUdul_surat]</span>
            </h4>
            <p style="margin: 0; text-align: center;">Nomor : [Kode_suraT]/[Nomer_suraT]/437.103.09/[TahuN]</p>
            <p style="text-indent: 30px; text-align: center;"><strong>MEMERINTAHKAN</strong></p>
            <table style="border-collapse: collapse; width: 95%; margin-left: auto; margin-right: auto;">
                <tbody>
                    <tr>
                        <td width="20">1.</td>
                        <td width="50">Nama</td>
                        <td width="5">:</td>
                        <td width="600"><strong>[NiK]</strong></td>
                    </tr>
                    <tr>
                        <td width="20">\u{a0}</td>
                        <td width="50">Jabatan</td>
                        <td width="5">:</td>
                        <td width="600">[Form_jabataninstansI]</td>
                    </tr>
                    <tr>
                        <td width="20">\u{a0}</td>
                        <td width="50">Alamat</td>
                        <td width="5">:</td>
                        <td width="600">[AlamaT] [Sebutan_desA] [Nama_desA] : [Kode_desA]</td>
                    </tr>
                    <tr>
                        <td width="20">\u{a0}</td>
                        <td width="50">\u{a0}</td>
                        <td width="5">\u{a0}</td>
                        <td width="600">[Sebutan_kecamataN] [Nama_kecamataN] : [Kode_kecamataN]</td>
                    </tr>
                    <tr>
                        <td width="20">2.</td>
                        <td width="50">Maksud Tugas</td>
                        <td width="5">:</td>
                        <td width="600"><strong>[Form_maksud_perjalanan_dinaS]</strong></td>
                    </tr>
                    <tr>
                        <td width="20">\u{a0}</td>
                        <td width="50">Tujuan</td>
                        <td width="5">:</td>
                        <td width="600">[Form_tempat_tujuaN]</td>
                    </tr>
                    <tr>
                        <td width="20">\u{a0}</td>
                        <td width="50">Tanggal</td>
                        <td width="5">:</td>
                        <td width="600">[Form_tanggal_berangkaT] s/d [Form_tanggal_kembalI]</td>
                    </tr>
                    <tr>
                        <td width="20">\u{a0}</td>
                        <td width="50">Pengikut</td>
                        <td width="5">:</td>
                        <td width="600">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="23">1.</td>
                                                        <td width="283">[Form_nama_pengikut_I]</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="23">2.</td>
                                                        <td width="283">[Form_nama_pengikut_iI]</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="23">3.</td>
                                                        <td width="283">[Form_nama_pengikut_iiI]</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table style="width: 59.6213%;">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 8.73963%;" width="25">4.</td>
                                                        <td style="width: 91.258%;" width="262">[Form_nama_pengikut_iV]</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 8.73963%;" width="25">5.</td>
                                                        <td style="width: 91.258%;" width="262">[Form_nama_pengikut_V]</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 8.73963%;" width="25">6.</td>
                                                        <td style="width: 91.258%;" width="262">[Form_nama_pengikut_vI]</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <p style="text-align: justify; text-indent: 30px;">Demikian Surat Tugas ini dikeluarkan untuk dilaksanakan sebagaimana
                mestinya.</p>
            <p style="text-align: justify; text-indent: 30px;">.</p>
            <table style="border-collapse: collapse; width: 32.5353%;" border="0" align="right">
                <tbody>
                    <tr style="height: 18.75px;">
                        <td style="width: 100%; text-align: left;">[Nama_desA], [TgL_surat]</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 100%; height: 18.75px; text-align: left;">[Sebutan_kepala_desA] [Nama_desA]</td>
                    </tr>
                    <tr style="height: 75px;">
                        <td style="width: 100%; height: 75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 100%; text-align: center; height: 18.75px;">\u{a0}</td>
                    </tr>
                    <tr style="height: 18.75px;">
                        <td style="width: 100%; text-align: left; height: 18.75px;"><strong>[Nama_pamonG]</strong></td>
                    </tr>
                </tbody>
            </table>
            <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">HASIL
                    BIMTEK/PELATIHAN/KONSULTASI/MONEV/PENGIRIMAN DATA, DLL</span></h4>
            <div><br />Sebagai berikut :<br /><br />1.<br />2.<br />3.</div>
            HTML;
        $data = [
            'nama'                => $nama_surat,
            'kode_surat'          => 'S-46',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_pangkat_dan_golongan]","nama":"Pangkat dan Golongan","deskripsi":"Pangkat dan Golongan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_jabataninstansi]","nama":"Jabatan\/Instansi","deskripsi":"Jabatan\/Instansi","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tingkat_biaya_perjalanan]","nama":"Tingkat Biaya Perjalanan","deskripsi":"Tingkat Biaya Perjalanan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_maksud_perjalanan_dinas]","nama":"Maksud Perjalanan Dinas","deskripsi":"Maksud Perjalanan Dinas","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_tujuan]","nama":"Tempat Tujuan","deskripsi":"Tempat Tujuan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_alat_angkut_yang_digunakan]","nama":"Alat Angkut Yang Digunakan","deskripsi":"Alat Angkut Yang Digunakan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pengikut_i]","nama":"Nama Pengikut I","deskripsi":"Nama Pengikut I","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pengikut_ii]","nama":"Nama Pengikut II","deskripsi":"Nama Pengikut II","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pengikut_iii]","nama":"Nama Pengikut III","deskripsi":"Nama Pengikut III","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pengikut_iv]","nama":"Nama Pengikut IV","deskripsi":"Nama Pengikut IV","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pengikut_v]","nama":"Nama Pengikut V","deskripsi":"Nama Pengikut V","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pengikut_vi]","nama":"Nama Pengikut VI","deskripsi":"Nama Pengikut VI","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_keterangan_lain]","nama":"Keterangan Lain","deskripsi":"Keterangan Lain","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_berangkat]","nama":"Tanggal Berangkat","deskripsi":"Tanggal Berangkat","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_kembali]","nama":"Tanggal Kembali","deskripsi":"Tanggal Kembali","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    // Function Migrasi TinyMCE
}
