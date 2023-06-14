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
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023060572($hasil);
        $hasil = $hasil && $this->migrasi_2023061451($hasil);
        $hasil = $hasil && $this->migrasi_2023061452($hasil);

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
}
