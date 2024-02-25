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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
        $hasil = $hasil && $this->migrasi_2023062871($hasil);

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

            // Jalankan Migrasi TinyMCE'
            $hasil = $hasil && $this->migrasi_2023062251($hasil, $id);
            $hasil = $hasil && $this->suratKetDomisili($hasil, $id);
            $hasil = $hasil && $this->suratLahirMati($hasil, $id);
            $hasil = $hasil && $this->suratPenerbitanBukuPas($hasil, $id);
            $hasil = $hasil && $this->suratKepemilikanKendaraan($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPenghasilanOrangTua($hasil, $id);
            $hasil = $hasil && $this->suratBiodataPenduduk($hasil, $id);
            $hasil = $hasil && $this->suratPerintahPerjalananDinas($hasil, $id);
            $hasil = $hasil && $this->suratPermohonanDuplikatNikah($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganKepemilikanTanah($hasil, $id);
            $hasil = $hasil && $this->suratPermohonanDuplikatKelahiran($hasil, $id);
            $hasil = $hasil && $this->suratPermohonanKartuKeluarga($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPengantarRujukCerai($hasil, $id);
            $hasil = $hasil && $this->suratPermohonanPerubahanKartuKeluarga($hasil, $id);
            // Jalankan Migrasi TinyMCE
        }
        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023060572($hasil);
        $hasil = $hasil && $this->migrasi_2023061451($hasil);
        $hasil = $hasil && $this->migrasi_2023061452($hasil);
        $hasil = $hasil && $this->migrasi_2023061552($hasil);
        $hasil = $hasil && $this->migrasi_2023061951($hasil);

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
        if (! $this->db->field_exists('foto', 'widget')) {
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

        if (! $this->db->field_exists('Kd_Bank', 'keuangan_ta_spp')) {
            $fields['Kd_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Nm_Bank', 'keuangan_ta_spp')) {
            $fields['Nm_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Nm_Penerima', 'keuangan_ta_spp')) {
            $fields['Nm_Penerima'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Ref_Bayar', 'keuangan_ta_spp')) {
            $fields['Ref_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Rek_Bank', 'keuangan_ta_spp')) {
            $fields['Rek_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Rek_Bank', 'keuangan_ta_spp')) {
            $fields['Rek_Bank'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Tgl_Bayar', 'keuangan_ta_spp')) {
            $fields['Tgl_Bayar'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ];
        }

        if (! $this->db->field_exists('Validasi', 'keuangan_ta_spp')) {
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
        if (! $this->db->field_exists('slug', 'user_grup')) {
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
        }

        $data = [];

        foreach ($this->db->get_where('user_grup', ['slug' => null])->result() as $row) {
            $data[] = [
                'id'   => $row->id,
                'slug' => unique_slug('user_grup', $row->nama),
            ];
        }

        if ($data) {
            $hasil = $hasil && $this->db->update_batch('user_grup', $data, 'id');
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
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 195.75px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 21px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 21px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 21px;\">5.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 21px;\">Agama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 21px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 21px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 20.25px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 20.25px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 20.25px;\">6.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 20.25px;\">Status</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 20.25px;\"> </td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 20.25px;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">7..</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Pendidikan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.3222%; text-align: center;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5174%; text-align: left;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.24427%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 36px; text-align: left;\">10.<br /><br /></td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0524%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut di atas adalah benar-benar warga kami yang bertempat tinggal di [AlamaT] [Sebutan_desa] [NaMa_desa] dan tercatat dengan No. KK : [No_kK] Kepala Keluarga : [Kepala_kK].</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\"><br />       Surat Keterangan ini dibuat untuk Keperluan : <strong>[Form_keperluaN]</strong><br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>",
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

    protected function migrasi_2023061951($hasil)
    {
        $surat = FormatSurat::whereIn('jenis', FormatSurat::TINYMCE)->get();

        foreach ($surat as $surat_item) {
            $kode = $surat_item->kode_isian;

            foreach ($kode as $value) {
                if (str_contains($value->atribut, 'required')) {
                    $value->required = '1';
                    if ($value->atribut == 'class="required"') {
                        $value->atribut = trim(str_replace('class="required"', '', $value->atribut));
                    } else {
                        $value->atribut = trim(str_replace('required', '', $value->atribut));
                    }
                } else {
                    $value->required = '0';
                }
            }
            $hasil = $hasil && FormatSurat::find($surat_item->id)->update(['kode_isian' => $kode]);
        }

        return $hasil && true;
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
            'lampiran'            => 'F-1.01,F-1.02',
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
                        <td style="width: 74.5527%; height: 18.75px;">: [Format_nomor_suraT]</td>
                    </tr>
                </tbody>
            </table>
            <p>\u{a0}</p>
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
            <p>\u{a0}</p>
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
                                                            <p><br><br><strong>( [Nama_pamonG]</strong><strong>\u{a0})</strong></p>
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
                            <br>Pada Tanggal : [Form_tanggal_kembalI]</td>
                        <td style="width: 52.6865%; height: 41.1133px;" width="351"><em>Telah diperiksa dengan keterangan bahwa
                                perjalan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang
                                sesingkat-singkatnya</em></td>
                    </tr>
                    <tr style="height: 80.988px;">
                        <td style="text-align: center; width: 99.9951%; height: 80.988px;" colspan="2" width="666">
                            [Sebutan_kepala_desA] [Nama_desA] <br><br><br><br><strong>( [Nama_pamonG]</strong><strong>\u{a0})</strong>
                        </td>
                    </tr>
                    <tr style="height: 58.2422px;">
                        <td style="width: 99.9951%; height: 58.2422px;" colspan="2" width="666">VI. <u>PERHATIAN </u> <br>Pengguna
                            Anggaran yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan
                            tanggal berangkat/tiba, \u{a0}\u{a0}\u{a0}serta bendahara pengeluaran bertanggungjawab berdasarkan Peraturan Keuangan
                            Negara. Apabila Negara menderita \u{a0}rugi akibat \u{a0}kesalahan, kelalaian dan kealpaannya.</td>
                    </tr>
                </tbody>
            </table>
            <h4 style="margin: 0; text-align: center;">\u{a0}</h4>
            <h4 style="margin: 0; text-align: center;">\u{a0}</h4>
            <h4 style="margin: 0; text-align: center;">\u{a0}</h4>
            <h4 style="margin: 0; text-align: center;">\u{a0}</h4>
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
            <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]</p>
            <p style="text-indent: 30px; text-align: center;"><strong>MEMERINTAHKAN</strong></p>
            <table style="border-collapse: collapse; width: 95%; margin-left: auto; margin-right: auto;">
                <tbody>
                    <tr>
                        <td width="20">1.</td>
                        <td width="50">Nama</td>
                        <td width="5">:</td>
                        <td width="600"><strong>[NaMA]</strong></td>
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
            <p>\u{a0}</p>
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
            <p><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            </p>
            <p>\u{a0}</p>
            <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">HASIL
                    BIMTEK/PELATIHAN/KONSULTASI/MONEV/PENGIRIMAN DATA, DLL</span></h4>
            <div><br>Sebagai berikut :<br><br>1.<br>2.<br>3.</div>
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

    protected function suratKeteranganKepemilikanTanah($hasil, $id)
    {
        $template = <<<HTML
                    <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
                    <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br /><br /></p>
                    <p style="text-indent: 30px; text-align: justify;">Yang bertanda tangan di bawah ini :</p>
                    <table style="border-collapse: collapse; width: 100.114%; height: 42px;" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr style="height: 21px;">
                    <td style="width: 30px; text-align: center; height: 21px;">\u{a0}</td>
                    <td style="width: 20.5239%; text-align: left; height: 21px;">Nama</td>
                    <td style="width: 1.14525%; height: 21px; text-align: left;">:</td>
                    <td style="width: 76.445%; height: 21px; text-align: justify;"><strong>[NAma]</strong></td>
                    </tr>
                    <tr style="height: 21px;">
                    <td style="width: 30px; text-align: center; height: 21px;">\u{a0}</td>
                    <td style="width: 20.5239%; text-align: left; height: 21px;">Jabatan</td>
                    <td style="width: 1.14525%; height: 21px; text-align: left;">:</td>
                    <td style="width: 76.445%; text-align: justify; height: 21px;">[JabataN]</td>
                    </tr>
                    </tbody>
                    </table>
                    <p style="text-align: justify; text-indent: 30px;">Dengan ini menerangkan bahwa :</p>
                    <table style="border-collapse: collapse; width: 100%; height: 118.922px;" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr style="height: 19.8125px;">
                    <td style="width: 30px; text-align: center; height: 19.8125px;">\u{a0}</td>
                    <td style="width: 20.6571%; text-align: left; height: 19.8125px;">Nama  </td>
                    <td style="width: 1.03204%; height: 19.8125px; text-align: left;">:</td>
                    <td style="width: 76.5364%; height: 19.8125px; text-align: justify;">[NamA]</td>
                    </tr>
                    <tr style="height: 19.8125px;">
                    <td style="width: 30px; text-align: center; height: 19.8125px;">\u{a0}</td>
                    <td style="width: 20.6571%; text-align: left; height: 19.8125px;">Tempat / Tanggal Lahir</td>
                    <td style="width: 1.03204%; height: 19.8125px; text-align: left;">:</td>
                    <td style="width: 76.5364%; height: 19.8125px; text-align: justify;">[TempatlahiR] / [TanggallahiR]</td>
                    </tr>
                    <tr style="height: 19.8125px;">
                    <td style="width: 30px; text-align: center; height: 19.8125px;">\u{a0}</td>
                    <td style="width: 20.6571%; text-align: left; height: 19.8125px;">Umur</td>
                    <td style="width: 1.03204%; height: 19.8125px; text-align: left;">:</td>
                    <td style="width: 76.5364%; text-align: justify; height: 19.8125px;">[UsiA]</td>
                    </tr>
                    <tr style="height: 19.8125px;">
                    <td style="width: 30px; text-align: center; height: 19.8125px;">\u{a0}</td>
                    <td style="width: 20.6571%; text-align: left; height: 19.8125px;">Jenis Kelamin</td>
                    <td style="width: 1.03204%; height: 19.8125px; text-align: left;">:</td>
                    <td style="width: 76.5364%; height: 19.8125px; text-align: justify;">[Jenis_kelamiN]</td>
                    </tr>
                    <tr style="height: 19.8125px;">
                    <td style="width: 30px; text-align: center; height: 19.8125px;">\u{a0}</td>
                    <td style="width: 20.6571%; text-align: left; height: 19.8125px;">Pekerjaan</td>
                    <td style="width: 1.03204%; height: 19.8125px; text-align: left;">:</td>
                    <td style="width: 76.5364%; text-align: justify; height: 19.8125px;">[PekerjaaN]</td>
                    </tr>
                    <tr style="height: 19.8594px;">
                    <td style="width: 30px; text-align: center; height: 19.8594px;">\u{a0}</td>
                    <td style="width: 20.6571%; text-align: left; height: 19.8594px;">Alamat</td>
                    <td style="width: 1.03204%; height: 19.8594px; text-align: left;">:</td>
                    <td style="width: 76.5364%; text-align: justify; height: 19.8594px;">[AlamaT]</td>
                    </tr>
                    </tbody>
                    </table>
                    <p style="text-indent: 30px; text-align: justify;">Adalah benar-benar penduduk [Sebutan_desA] [NaMa_desa], yang memiliki/menguasai tanah/lahan berupa <strong>[FOrm_jenis_tanah]</strong> atas nama <strong>[Form_atas_namA]</strong>, yang berada di [Sebutan_desA] [NaMa_desa]. Tercatat dalam <strong>[FOrm_bukti_kepemilikan]</strong>, Nomor : <strong>[Form_nomor_bukti_kepemilikaN]</strong>, Luas :<strong>[Form_luas_tanaH]</strong>M<sup>2</sup>, dengan batas-batas :</p>
                    <table style="border-collapse: collapse; width: 100%; height: 72px;" border="0">
                    <tbody>
                    <tr style="height: 18px;">
                    <td style="width: 30px; height: 18px;">\u{a0}</td>
                    <td style="width: 20.2564%; height: 18px;">Sebelah Utara</td>
                    <td style="width: 1.14646%; height: 18px;">:</td>
                    <td style="width: 76.5937%; height: 18px;">[Form_batas_sebelah_utarA]</td>
                    </tr>
                    <tr style="height: 18px;">
                    <td style="width: 30px; height: 18px;">\u{a0}</td>
                    <td style="width: 20.2564%; height: 18px;">Sebelah Timur</td>
                    <td style="width: 1.14646%; height: 18px;">:</td>
                    <td style="width: 76.5937%; height: 18px;">[Form_batas_sebelah_timuR]</td>
                    </tr>
                    <tr style="height: 18px;">
                    <td style="width: 30px; height: 18px;">\u{a0}</td>
                    <td style="width: 20.2564%; height: 18px;">Sebelah Selatan</td>
                    <td style="width: 1.14646%; height: 18px;">:</td>
                    <td style="width: 76.5937%; height: 18px;">[Form_batas_sebelah_selataN]</td>
                    </tr>
                    <tr style="height: 18px;">
                    <td style="width: 30px; height: 18px;">\u{a0}</td>
                    <td style="width: 20.2564%; height: 18px;">Sebelah Barat</td>
                    <td style="width: 1.14646%; height: 18px;">:</td>
                    <td style="width: 76.5937%; height: 18px;">[Form_batas_sebelah_baraT]</td>
                    </tr>
                    </tbody>
                    </table>
                    <ol>
                    <li style="text-align: justify;">Tanah tersebut benar-benar <em>MILIK</em> yang bersangkutan dan <strong>tidak dalam keadaan sengketa</strong>.</li>
                    <li style="text-align: justify;">Tanah tersebut berasal dari <strong>[FOrm_asal_kepmilikan_tanah]</strong> dan sampai dengan sekarang belum terdaftar / didaftarkan Hak nya ke BPN (belumditerbitkan : SIIM / SIIGB / SIIGU / LAINNYA)</li>
                    <li style="text-align: justify;">Bukti pendukung kepemilikan sementara ini berupa <strong>[Form_bukti_pendukung_kepemilikan]</strong>.</li>
                    </ol>
                    <p style="text-indent: 30px;">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.</p>
                    <p style="text-align: justify; text-indent: 30px;"><br /><br /></p>
                    <table style="border-collapse: collapse; width: 100%;" border="0">
                    <tbody>
                    <tr>
                    <td style="width: 35%; text-align: center;">\u{a0}</td>
                    <td style="width: 30%;">\u{a0}</td>
                    <td style="width: 35%; text-align: center;">[NaMa_desa], [TgL_surat]</td>
                    </tr>
                    <tr>
                    <td style="width: 35%; text-align: center;">Pemilih</td>
                    <td style="width: 30%;">\u{a0}</td>
                    <td style="width: 35%; text-align: center;">[Atas_namA]</td>
                    </tr>
                    <tr>
                    <td style="width: 35%; text-align: center;">\u{a0}</td>
                    <td style="width: 30%;"><br /><br /><br /><br /></td>
                    <td style="width: 35%;">\u{a0}</td>
                    </tr>
                    <tr>
                    <td style="width: 35%; text-align: center;">[NAma]</td>
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
            'nama'                => 'Keterangan Kepemilikan Tanah',
            'kode_surat'          => 'S-49',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"select-manual","kode":"[form_jenis_tanah]","nama":"Jenis Tanah","deskripsi":"- Pilih Jenis Tanah -","atribut":"class=\"required\"","pilihan":["Tanah Sawah","Tanah Darat","Tanah Bangunan"],"refrensi":null},{"tipe":"number","kode":"[form_luas_tanah]","nama":"Luas Tanah","deskripsi":"Luas Tanah (dalam M2)","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"select-manual","kode":"[form_bukti_kepemilikan]","nama":"Bukti Kepemilikan","deskripsi":"- Pilih Bukti Kepemilikan Tanah -","atribut":"class=\"required\"","pilihan":["Petok lama","Petok baru","Sit segel","Akta","Copy","Buku Krawangan Desa","Lainnya"],"refrensi":null},{"tipe":"text","kode":"[form_nomor_bukti_kepemilikan]","nama":"Nomor Bukti Kepemilikan","deskripsi":"- Nomor Bukti Kepemilikan -","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_atas_nama]","nama":"Atas Nama","deskripsi":"Atas Nama","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"select-manual","kode":"[form_asal_kepmilikan_tanah]","nama":"Asal Kepmilikan Tanah","deskripsi":"- Pilih Asal Kepemilikan Tanah -","atribut":"class=\"required\"","pilihan":["Yayasan","Warisan","Hibah","Jual Beli","Lainnya"],"refrensi":null},{"tipe":"text","kode":"[form_bukti_pendukung_kepemilikan]","nama":"Bukti Pendukung Kepemilikan","deskripsi":"Bukti Pendukung Kepemilikan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_utara]","nama":"Batas Sebelah Utara","deskripsi":"Batas Sebelah Utara","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_timur]","nama":"Batas Sebelah Timur","deskripsi":"Batas Sebelah Timur","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_selatan]","nama":"Batas Sebelah Selatan","deskripsi":"Batas Sebelah Selatan","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_barat]","nama":"Batas Sebelah Barat","deskripsi":"Batas Sebelah Barat","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratPermohonanDuplikatKelahiran($hasil, $id)
    {
        $template = <<<HTML
                <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
                <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br /><br /></p>
                <p style="text-align: justify; text-indent: 30px;">YDengan ini kami mengajukan orang untuk mengadakan [Judul_suraT] seperti tersebut di bawah ini\u{a0} :</p>
                <table style="border-collapse: collapse; width: 100%; height: 481.547px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">1.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Nama Lengkap</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;"><strong>[NAma]</strong></td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">2.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">NIK / No KTP</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[NiK]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">3.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Jenis Kelamin / Agama</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Jenis_kelamiN] / [AgamA]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">4.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Alamat/Tempat Tinggal</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[AlamaT]</td>
                </tr>
                <tr style="height: 39.75px;">
                <td style="width: 4.3222%; text-align: center; height: 39.75px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 39.75px; text-align: left;">\u{a0}</td>
                <td style="width: 30.5174%; text-align: left; height: 39.75px;"><strong>Telah Lahir Pada :</strong></td>
                <td style="width: 1.24427%; text-align: center; height: 39.75px;">\u{a0}</td>
                <td style="width: 59.9869%; height: 39.75px; text-align: justify;">\u{a0}</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">5.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Hari, Tanggal, Pukul</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Form_hari_lahiR], [TanggallahiR], [Form_jam_lahiR]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">6.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Bertempat di</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Form_tempat_lahiR]</td>
                </tr>
                <tr style="height: 19.375px;">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">\u{a0}</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;"><strong>Dengan orang tua</strong>:</td>
                <td style="width: 1.24427%; height: 19.375px; text-align: center;">\u{a0}</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">\u{a0}</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">7.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Nama Ibu</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Nama_ibU]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">8.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">NIK / Tanggal Lahir Ibu</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Nik_ibU] / [Tempatlahir_ibU]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">9.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Pekerjaan Ibu</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Pekerjaan_ibU]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.375px; text-align: left;">10.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Alamat Ibu</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; height: 19.375px; text-align: justify;">[Alamat_ibU] [Sebutan_desA] [Nama_desA], [Sebutan_kecamataN] [Nama_kecamataN], [Sebutan_kabupateN] [Nama_kabupateN]</td>
                </tr>
                <tr style="height: 19.4219px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.4219px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 19.4219px; text-align: left;">11.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.4219px;">Nama Ayah</td>
                <td style="width: 1.24427%; text-align: center; height: 19.4219px;">:</td>
                <td style="width: 59.9869%; height: 19.4219px; text-align: justify;">[Nama_ayaH]</td>
                </tr>
                <tr style="height: 19.2344px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">12.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">NIK / Tanggal Lahir Ayah</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">[Nik_ayaH] / [Tanggallahir_ayaH]</td>
                </tr>
                <tr style="height: 19.2344px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">13.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">Pekerjaan Ayah</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">[Pekerjaan_ayaH]\u{a0}</td>
                </tr>
                <tr style="height: 36px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 36px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 36px;">14.</td>
                <td style="width: 30.5174%; text-align: left; height: 36px;">Alamat Ayah</td>
                <td style="width: 1.24427%; text-align: center; height: 36px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 36px;">[Alamat_ayaH] [Sebutan_desA] [Nama_desA], [Sebutan_kecamataN] [Nama_kecamataN], [Sebutan_kabupateN] [Nama_kabupateN]</td>
                </tr>
                <tr style="height: 19.2344px;">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">\u{a0}</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">\u{a0}</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">\u{a0}</td>
                </tr>
                <tr style="height: 19.2344px;">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="text-align: left; height: 19.2344px; width: 95.6778%;" colspan="4">Surat Keterangan ini dibuat berdasarkan keterangan pelapor:</td>
                </tr>
                <tr style="height: 19.2344px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">15.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">Nama Lengkap</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">[Form_nama_pelapoR]</td>
                </tr>
                <tr style="height: 19.2344px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">16.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">NIK / Jenis Kelamin</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">[Form_nik_pelapoR] / [Form_jenis_kelamin_pelapoR]</td>
                </tr>
                <tr style="height: 19.2344px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">17.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">Tempat / Tanggal Lahir</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">[Form_tempat_lahiR] / [Form_tanggal_lahir_pelapoR]</td>
                </tr>
                <tr style="height: 19.2344px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.2344px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.2344px;">18.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.2344px;">Pekerjaan</td>
                <td style="width: 1.24427%; text-align: center; height: 19.2344px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.2344px;">[Form_pekerjaan_pelapoR]</td>
                </tr>
                <tr style="height: 19.375px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 19.375px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19.375px;">19.</td>
                <td style="width: 30.5174%; text-align: left; height: 19.375px;">Alamat</td>
                <td style="width: 1.24427%; text-align: center; height: 19.375px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 19.375px;">[Form_alamat_pelapoR]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Demikian surat keterangan ini dibuat dengan sebenarnya, atas perhatian dan terkabulnya diucapkan terima kasih.<br /><br /></p>
                <table style="border-collapse: collapse; width: 100%; height: 415.985px;" border="0">
                <tbody>
                <tr style="height: 38.2344px;">
                <td style="width: 35.0462%; text-align: center; height: 38.2344px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 38.2344px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 38.2344px;">\u{a0}</td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; text-align: center; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 22.4688px;">[Nama_desA], [TgL_surat]</td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 22.4688px;">[Sebutan_kepala_desA] [Nama_desA]</td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 22.4688px;">\u{a0}</td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: left; height: 22.4688px;">\u{a0}</td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: left; height: 22.4688px;">\u{a0}</td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; text-align: center; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; height: 22.4688px; text-align: center;"><strong>Nama_pamonG</strong></td>
                </tr>
                <tr style="height: 22.4688px;">
                <td style="width: 35.0462%; height: 22.4688px;">\u{a0}</td>
                <td style="width: 30.0103%; text-align: center; height: 22.4688px;">\u{a0}</td>
                <td style="width: 35.0462%; height: 22.4688px; text-align: center;">NIP : [Nip_kepala_camaT]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 18px;">\u{a0}</td>
                </tr>
                <tr style="height: 92px;">
                <td style="width: 35.0462%; height: 92px;">\u{a0}</td>
                <td style="width: 30.0103%; text-align: center; height: 92px;">[qr_code]</td>
                <td style="width: 35.0462%; text-align: center; height: 92px;">\u{a0}</td>
                </tr>
                </tbody>
                </table>
                <div style="text-align: center;">\u{a0}</div>
            HTML;
        $data = [
            'nama'                => 'Permohonan Duplikat Kelahiran',
            'kode_surat'          => 'S-20',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_hari_lahir]","nama":"Hari Lahir","deskripsi":"Hari Lahir","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"time","kode":"[form_jam_lahir]","nama":"Jam Lahir","deskripsi":"Jam Lahir","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir]","nama":"Tempat Lahir","deskripsi":"Tempat Lahir","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pelapor]","nama":"Nama Pelapor","deskripsi":"Nama Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_pelapor]","nama":"Tanggal Lahir Pelapor","deskripsi":"Tanggal Lahir Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nik_pelapor]","nama":"Nik Pelapor","deskripsi":"Nik Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_jenis_kelamin_pelapor]","nama":"Jenis Kelamin Pelapor","deskripsi":"Jenis Kelamin Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":"tweb_penduduk_sex"},{"tipe":"text","kode":"[form_tempat_lahir_pelapor]","nama":"Tempat Lahir Pelapor","deskripsi":"Tempat Lahir Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_pelapor]","nama":"Tanggal Lahir Pelapor","deskripsi":"Tanggal Lahir Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_pekerjaan_pelapor]","nama":"Pekerjaan Pelapor","deskripsi":"Pekerjaan Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_alamat_pelapor]","nama":"Alamat Pelapor","deskripsi":"Alamat Pelapor","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratPermohonanKartuKeluarga($hasil, $id)
    {
        $data = [
            'nama'                => 'Permohonan Kartu Keluarga',
            'kode_surat'          => 'S-36',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"select-manual","kode":"[form_alasan_permohonan]","nama":"Alasan Permohonan","deskripsi":"Alasan Permohonan","atribut":null,"pilihan":["KARENA MEMBENTUK RUMAH TANGGA BARU","KARENA KARTU KELUARGA HILANG\/RUSAK","LAINNYA"],"refrensi":null},{"tipe":"text","kode":"[form_nomor_kartu_keluarga_semula]","nama":"Nomor Kartu Keluarga Semula","deskripsi":"Nomor Kartu Keluarga Semula","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Keterangan","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"1","kk_level":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => 'F-1.15,F-1.01,F-1.02',
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[UsIa]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Warga Negara</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 36px; text-align: left;\">8.<br /><br /></td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 36px;\">Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0211%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Surat bukti diri</td>\r\n<td style=\"width: 1.26582%; height: 18px; text-align: center;\"> </td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KTP</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KK</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">Permohonan Kartu Keluarga baru WNI.</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Mulai_berlaku] s/d [Berlaku_sampai]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Keterangan lain-lain</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">Orang tersebut di atas adalah benar-benar penduduk [SeButan_desa] kami dan ada istiadat baik.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 324px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">Pemegang Surat</td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35.0462%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\"><strong>[NAma]</strong></td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\">No</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\">:</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\">Tanggal</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\">:</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; text-align: center; height: 18px;\">Mengetahui,</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; text-align: center; height: 18px;\">Camat - [NaMa_kecamatan]</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"height: 72px; width: 35.0462%;\" rowspan=\"2\">[qr_code]</td>\r\n<td style=\"width: 30.0103%; height: 72px;\"><br /><br /><br /></td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 30.0103%; text-align: center; height: 18px;\">..............................................</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\"> </td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"> </div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratKeteranganPengantarRujukCerai($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Pengantar Rujuk/Cerai',
            'kode_surat'          => 'S-35',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_nama_lengkap]","nama":"Nama Lengkap","deskripsi":"Nama Lengkap Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir]","nama":"Tempat Lahir","deskripsi":"Tempat Lahir Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir]","nama":"Tanggal Lahir","deskripsi":"Tanggal Lahir Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_warganegara]","nama":"Warganegara","deskripsi":"Warganegara Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_ayah]","nama":"Nama Ayah","deskripsi":"Nama Ayah Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_agama]","nama":"Agama","deskripsi":"Agama Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_pekerjaan]","nama":"Pekerjaan","deskripsi":"Pekerjaan Pasangan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_tinggal]","nama":"Tempat Tinggal","deskripsi":"Tempat Tinggal Pasangan","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>
            <p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>
            <p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan bahwa berdasarkan keterangan dari :</p>
            <table style=\"border-collapse: collapse; width: 100%; height: 154px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 19.25px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 19.25px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 19.25px;\">1.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 19.25px;\">Nama Lengkap</td>
            <td style=\"width: 1.2333%; text-align: center; height: 19.25px;\">:</td>
            <td style=\"width: 62.4872%; height: 19.25px; text-align: justify;\"><strong>[NAma]</strong></td>
            </tr>
            <tr style=\"height: 19.25px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 19.25px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 19.25px;\">2.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 19.25px;\">Bin</td>
            <td style=\"width: 1.2333%; text-align: center; height: 19.25px;\">:</td>
            <td style=\"width: 62.4872%; height: 19.25px; text-align: justify;\">[NaMa_ayah]</td>
            </tr>
            <tr style=\"height: 19.25px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 19.25px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 19.25px;\">3.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 19.25px;\">Tempat / Tanggal Lahir</td>
            <td style=\"width: 1.2333%; text-align: center; height: 19.25px;\">:</td>
            <td style=\"width: 62.4872%; height: 19.25px; text-align: justify;\">[TtL]</td>
            </tr>
            <tr style=\"height: 19.25px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 19.25px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 19.25px;\">4.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 19.25px;\">Kewarganegaraan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 19.25px;\">:</td>
            <td style=\"width: 62.4872%; text-align: justify; height: 19.25px;\">[WArga_negara]</td>
            </tr>
            <tr style=\"height: 19.25px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 19.25px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 19.25px;\">5.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 19.25px;\">Agama</td>
            <td style=\"width: 1.2333%; text-align: center; height: 19.25px;\">:</td>
            <td style=\"width: 62.4872%; height: 19.25px; text-align: justify;\">[AgAma]</td>
            </tr>
            <tr style=\"height: 19.25px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 19.25px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 19.25px;\">6.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 19.25px;\">Pekerjaan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 19.25px;\">:</td>
            <td style=\"width: 62.4872%; text-align: justify; height: 19.25px;\">[PeKerjaan]</td>
            </tr>
            <tr style=\"height: 38.5px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 38.5px;\">\u{a0}</td>
            <td style=\"width: 2.98047%; text-align: center; height: 38.5px;\">7.</td>
            <td style=\"width: 29.9075%; text-align: left; height: 38.5px;\">Alamat / Tempat Tinggal</td>
            <td style=\"width: 1.2333%; text-align: center; height: 38.5px;\">:</td>
            <td style=\"width: 62.4872%; text-align: justify; height: 38.5px;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">Telah rujuk/cerai *) dengan :<strong><br /></strong></p>
            <table style=\"border-collapse: collapse; width: 100%; height: 163px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 23.2812px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.2812px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.2812px;\">8.</td>
            <td style=\"width: 30.0103%; height: 23.2812px;\">Nama Lengkap</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.2812px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.2812px;\">[FoRm_nama_lengkap]</td>
            </tr>
            <tr style=\"height: 23.2812px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.2812px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.2812px;\">9.</td>
            <td style=\"width: 30.0103%; height: 23.2812px;\">Binti</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.2812px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.2812px;\">[FoRm_nama_ayah]</td>
            </tr>
            <tr style=\"height: 23.2812px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.2812px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.2812px;\">10.</td>
            <td style=\"width: 30.0103%; height: 23.2812px;\">Tempat dan Tanggal Lahir</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.2812px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.2812px;\">[FoRm_tempat_lahir], [FoRm_tanggal_lahir]</td>
            </tr>
            <tr style=\"height: 23.2812px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.2812px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.2812px;\">11.</td>
            <td style=\"width: 30.0103%; height: 23.2812px;\">Kewarganegaraan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.2812px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.2812px;\">[FoRm_warganegara]</td>
            </tr>
            <tr style=\"height: 23.2812px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.2812px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.2812px;\">12.</td>
            <td style=\"width: 30.0103%; height: 23.2812px;\">Agama</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.2812px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.2812px;\">[FoRm_agama]</td>
            </tr>
            <tr style=\"height: 23.2812px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.2812px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.2812px;\">13.</td>
            <td style=\"width: 30.0103%; height: 23.2812px;\">Pekerjaan</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.2812px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.2812px;\">[FoRm_pekerjaan]</td>
            </tr>
            <tr style=\"height: 23.3125px;\">
            <td style=\"width: 3.39157%; text-align: center; height: 23.3125px;\">\u{a0}</td>
            <td style=\"width: 2.8777%; text-align: center; height: 23.3125px;\">14.</td>
            <td style=\"width: 30.0103%; height: 23.3125px;\">Alamat</td>
            <td style=\"width: 1.2333%; text-align: center; height: 23.3125px;\">:</td>
            <td style=\"width: 62.4872%; height: 23.3125px;\">[FoRm_tempat_tinggal]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>
            <p style=\"text-align: justify; text-indent: 30px;\">\u{a0}</p>
            <table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">
            <tbody>
            <tr style=\"height: 18px;\">
            <td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 30%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 30%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>
            </tr>
            <tr style=\"height: 72px;\">
            <td style=\"width: 35%; text-align: center; height: 72px;\">\u{a0}</td>
            <td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>
            <td style=\"width: 35%; height: 72px;\">\u{a0}</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 30%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 35%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 30%; height: 18px;\">\u{a0}</td>
            <td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>
            </tr>
            </tbody>
            </table>
            <div style=\"text-align: center;\">\u{a0}</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function migrasi_2023062251($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Buku Tamu Kamera',
            'key'        => 'buku_tamu_kamera',
            'value'      => 1,
            'keterangan' => 'Gunakan kamera untuk proses registrasi',
            'kategori'   => 'buku-tamu',
            'jenis'      => 'boolean',
            'option'     => null,
        ], $id);
    }

    protected function suratPermohonanPerubahanKartuKeluarga($hasil, $id)
    {
        $template = <<<HTML
                <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
                <p style="margin: 0; text-align: center;">Nomor : [format_nomor_surat]<br /><br /></p>
                <p style="text-align: justify;">\u{a0} \u{a0} \u{a0} Yang bertanda tangan di bawah ini [Jabatan] [Nama_desa], Kecamatan [Nama_kecamatan], [Sebutan_kabupaten] [Nama_kabupaten], Provinsi [Nama_provinsi] menerangkan dengan sebenarnya bahwa :</p>
                <table style="border-collapse: collapse; width: 100%; height: 270px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">1.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Nama</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;"><strong>[NAma]</strong></td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">2.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Tempat/tanggal lahir</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[TtL]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">3.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Umur</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[UsIa]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">4.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Warga negara</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[WArga_negara]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">5.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Agama</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[AgAma]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">6.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Jenis Kelamin</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[JeNis_kelamin]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">7.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Pekerjaan</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[Pekerjaan]</td>
                </tr>
                <tr style="height: 36px;">
                <td style="width: 5%; text-align: center; height: 36px;">8.</td>
                <td style="width: 33.773%; text-align: left; height: 36px;">Tempat tinggal</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 36px;">[AlamaT] [Sebutan_desa] [Nama_desa], Kecamatan [Nama_kecamatan], [Sebutan_kabupaten] [Nama_kabupaten]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">9.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Surat bukti diri</td>
                <td style="width: 1.22703%; text-align: left;">\u{a0}</td>
                <td style="width: 60%; text-align: left; height: 18px;">\u{a0}</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">KTK</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[Nik]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">KK</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">[No_kk]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">10.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Keperluan</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">Permohonan Perubahan Kartu Keluarga WNI.</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; text-align: center; height: 18px;">11.</td>
                <td style="width: 33.773%; text-align: left; height: 18px;">Keterangan lain-lain</td>
                <td style="width: 1.22703%; text-align: center;">:</td>
                <td style="width: 60%; text-align: left; height: 18px;">Orang tersebut di atas adalah benar benar penduduk desa kami dan adat istiadat baik.</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify;">\u{a0} \u{a0} \u{a0} \u{a0} \u{a0} \u{a0} \u{a0}</p>
                <p style="text-align: justify; text-indent: 30px;">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>
                <table style="border-collapse: collapse; width: 100%; height: 324px;" border="0">
                <tbody>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 18px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 18px;">[NaMa_desa], [TgL_surat]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; text-align: center; height: 18px;">Pemegang Surat</td>
                <td style="width: 30.0103%; height: 18px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 18px;">[Atas_namA]</td>
                </tr>
                <tr style="height: 72px;">
                <td style="width: 35.0462%; text-align: center; height: 72px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 72px;"><br /><br /><br /><br /></td>
                <td style="width: 35.0462%; height: 72px;">\u{a0}</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; text-align: center; height: 18px;"><strong>[NAma]</strong></td>
                <td style="width: 30.0103%; height: 18px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 18px;">[Nama_pamonG]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 18px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 18px;">[SEbutan_nip_desa] : [nip_pamong]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 18px;">\u{a0}</td>
                <td style="width: 35.0462%; text-align: center; height: 18px;">\u{a0}</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 18px;">No</td>
                <td style="width: 35.0462%; text-align: left; height: 18px;">:</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; height: 18px;">Tanggal</td>
                <td style="width: 35.0462%; text-align: left; height: 18px;">:</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; text-align: center; height: 18px;">Mengetahui,</td>
                <td style="width: 35.0462%; text-align: left; height: 18px;">\u{a0}</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 35.0462%; height: 18px;">\u{a0}</td>
                <td style="width: 30.0103%; text-align: center; height: 18px;">Camat - [NaMa_kecamatan]</td>
                <td style="width: 35.0462%; text-align: left; height: 18px;">\u{a0}</td>
                </tr>
                <tr style="height: 72px;">
                <td style="height: 72px; width: 35.0462%;" rowspan="2">[qr_code]</td>
                <td style="width: 30.0103%; height: 72px;"><br /><br /><br /></td>
                <td style="width: 35.0462%; text-align: left; height: 72px;">\u{a0}</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 30.0103%; text-align: center; height: 18px;">..............................................</td>
                <td style="width: 35.0462%; text-align: left; height: 18px;">\u{a0}</td>
                </tr>
                </tbody>
                </table>
                <div style="text-align: center;">\u{a0}</div>
            HTML;

        $data = [
            'nama'                => 'Permohonan Perubahan Kartu Keluarga',
            'kode_surat'          => 'S-41',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"select-manual","kode":"[form_alasan_permohonan]","nama":"Alasan Permohonan","deskripsi":"Pilih Alasan Permohonan","required":"1","atribut":"class=\"required\"","pilihan":["Karena Penambahan Anggota Keluarga (Kelahiran, Kedatangan)","Karena Pengurangan Anggota Keluarga (Kematian, Kepindahan)","Lainnya"],"refrensi":null},{"tipe":"textarea","kode":"[form_alasan_lainnya]","nama":"Alasan Lainnya","deskripsi":"Alasan Lainnya","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => 'F-1.01,F-1.02,F-1.16',
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function migrasi_2023062871($hasil)
    {
        if (! $this->db->field_exists('margin_global', 'tweb_surat_format')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', [
                'margin_global' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => true,
                    'default'    => 0,
                    'after'      => 'margin',
                ],
            ]);
        }

        return $hasil;
    }

    // Function Migrasi TinyMCE
}
