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

use App\Enums\SHDKEnum;
use App\Enums\StatusEnum;
use App\Models\FormatSurat;
use App\Models\LampiranSurat;
use App\Models\StatusDasar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2312 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2311', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        // dimatikan sementara karena belum siap
        // $hasil = $hasil && $this->migrasi_2023102571($hasil);
        // $hasil = $hasil && $this->migrasi_2023110672($hasil);

        $hasil = $hasil && $this->migrasi_2023110771($hasil);
        $hasil = $hasil && $this->migrasi_2023114951($hasil);
        $hasil = $hasil && $this->migrasi_2023111571($hasil);
        $hasil = $hasil && $this->migrasi_2023111751($hasil);
        $hasil = $hasil && $this->migrasi_2023112251($hasil);
        $hasil = $hasil && $this->migrasi_2023112451($hasil);

        return $hasil && $this->migrasi_2023112371($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023110671($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPenghasilanAyah($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023111151($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023112352($hasil, $id);

            // dimatika sementara karena belum siap
            // $hasil = $hasil && $this->migrasi_2023110971($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023110251($hasil);
        $hasil = $hasil && $this->migrasi_2023110252($hasil);
        $hasil = $hasil && $this->migrasi_2023110651($hasil);
        $hasil = $hasil && $this->migrasi_2023110751($hasil);
        $hasil = $hasil && $this->migrasi_2023110951($hasil);
        $hasil = $hasil && $this->migrasi_2023112252($hasil);

        return $hasil && $this->migrasi_2023112351($hasil);
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    protected function migrasi_2023110251($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'home'],
            ['modul' => 'Beranda', 'slug' => 'beranda', 'url' => 'beranda']
        );
    }

    protected function migrasi_2023110252($hasil)
    {
        $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', [
            'id_kk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
        ]);

        DB::table('tweb_penduduk')->where('id_kk', 0)->update(['id_kk' => null]);

        return $hasil;
    }

    protected function migrasi_2023102571($hasil)
    {
        return $hasil && $this->dbforge->add_field([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'config_id'     => ['type' => 'INT', 'constraint' => 11],
            'nama'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'jenis'         => ['type' => 'TINYINT', 'default' => '2'],
            'template'      => ['type' => 'LONGTEXT', 'null' => true],
            'template_desa' => ['type' => 'LONGTEXT', 'null' => true],
            'status'        => ['type' => 'TINYINT', 'default' => '1'],
            'created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP',
            'created_by int(11) DEFAULT NULL',
            'updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_by int(11) DEFAULT NULL',
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `slug_config` (`config_id`,`slug`)',
            'CONSTRAINT `lampiran_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ])
            ->create_table('lampiran_surat', true);
    }

    protected function migrasi_2023110671($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Margin Lampiran Global',
            'key'        => 'lampiran_margin',
            'value'      => json_encode(LampiranSurat::MARGINS),
            'keterangan' => 'Margin global untuk lampiran surat',
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_lampiran',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Pengaturan Tampilan Kotak',
            'key'        => 'lampiran_kotak',
            'value'      => json_encode(LampiranSurat::KOTAK),
            'keterangan' => 'Pengaturan Tampilan Kotak',
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_lampiran',
        ], $id);
    }

    protected function migrasi_2023110672($hasil)
    {
        if (! $this->db->field_exists('margin', 'lampiran_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('lampiran_surat', [
                'margin' => [
                    'type'  => 'text',
                    'null'  => true,
                    'after' => 'status',
                ],
            ]);
        }

        if (! $this->db->field_exists('margin_global', 'lampiran_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('lampiran_surat', [
                'margin_global' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => true,
                    'default'    => 1,
                    'after'      => 'margin',
                ],
            ]);
        }

        if (! $this->db->field_exists('ukuran', 'lampiran_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('lampiran_surat', [
                'ukuran' => [
                    'type'       => 'varchar',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => FormatSurat::DEFAULT_SIZES,
                    'after'      => 'margin_global',
                ],
            ]);
        }

        if (! $this->db->field_exists('orientasi', 'lampiran_surat')) {
            return $hasil && $this->dbforge->add_column('lampiran_surat', [
                'orientasi' => [
                    'type'       => 'varchar',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => FormatSurat::DEFAULT_ORIENTATAIONS,
                    'after'      => 'ukuran',
                ],
            ]);
        }

        return $hasil;
    }

    protected function suratKeteranganPenghasilanAyah($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Penghasilan Ayah',
            'kode_surat'          => 'S-44',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_penghasilan_ayah]","nama":"Penghasilan Ayah","deskripsi":"Isi Jumlah Penghasilan Ayah Perbulan","required":"0","atribut":"class=\" rupiah\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Isi Keperluan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_sekolah]","nama":"Nama Sekolah","deskripsi":"Isi Nama Sekolah","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br><br></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 166.734px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.3906px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.3906px; text-align: justify;\"><strong>[NaMa_ayah]</strong></td>\r\n</tr>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.3906px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.3906px; text-align: justify;\">[Nik_ayaH]</td>\r\n</tr>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 22.3906px;\">3.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 22.3906px;\">[TtL_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 22.3906px;\">4.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 22.3906px;\">[JeNis_kelamin_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.3906px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">Agama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.3906px; text-align: justify;\">[AgAma_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 22.3906px;\">6</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 22.3906px;\">[PeKerjaan_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 22.3906px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.3906px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.3906px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.3906px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.3906px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.3906px; text-align: justify;\">[WArga_negara_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 10px;\">8.<br><br></td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 10px;\">Alamat / Tempat Tinggal<br><br></td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 10px;\">:<br><br></td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 10px;\">[AlAmat_ayah] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang yang tersebut di atas adalah benar-benar warga kami yang bertempat tinggal di [AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten] dan tercatat dalam No. KK : [No_kK] dengan NIK [Nik_ayaH] Kepala Keluarga : [Kepala_kK] dan menurut sepengetahuan kami memang benar berpenghasilan rata-rata [Form_penghasilan_ayaH] / Perbulan.<br><br></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Surat Keterangan ini dibuat atas permintaan yang bersangkutan untuk keperluan anaknya untuk [Form_keperluaN] di<strong> [Form_nama_sekolaH]</strong><strong> </strong>atas nama :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 269.297px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\"><strong>[NAma] </strong></td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 25.8125px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 25.8125px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 25.8125px;\">3.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 25.8125px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 25.8125px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 25.8125px;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 21.8125px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 21.8125px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 21.8125px;\">4.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 21.8125px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 21.8125px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 21.8125px;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Agama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.3222%; text-align: center;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left;\">6</td>\r\n<td style=\"width: 30.5174%; text-align: left;\">Status</td>\r\n<td style=\"width: 1.24427%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify;\">[Status_kawiN]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.3222%; text-align: center;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5174%; text-align: left;\">Pendidikan</td>\r\n<td style=\"width: 1.24427%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify;\">[Pendidikan_sedanG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\">8.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 19.75px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 19.75px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Warga_negarA]</td>\r\n</tr>\r\n<tr style=\"height: 43.4844px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 43.4844px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 43.4844px;\">10.<br><br></td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 43.4844px;\">Alamat / Tempat Tinggal<br><br></td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 43.4844px;\">:<br><br></td>\r\n<td style=\"width: 60.0524%; text-align: justify; height: 43.4844px;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br><br></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br><br><br><br></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br>[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function migrasi_2023110651($hasil)
    {
        FormatSurat::where('url_surat', 'surat-keterangan-domisili-non-warga')->update(['jenis' => FormatSurat::TINYMCE_DESA]);

        return $hasil;
    }

    protected function migrasi_2023111151($hasil, $id)
    {
        return $hasil && $this->ubah_modul(['slug' => 'qr-code', 'config_id' => $id], [
            'url' => 'qr_code/clear',
        ]);
    }

    protected function migrasi_2023110751($hasil)
    {
        $stDasar = array_keys(collect(StatusDasar::get()->toArray())->keyBy('id')->all());

        $this->db->trans_start();
        $query = $this->db->where('form_isian is NOT NULL')->get('tweb_surat_format');

        foreach ($query->result() as $row) {
            $data     = json_decode($row->form_isian, true);
            $dataBaru = [];

            foreach ($data as $key => $value) {
                $dataBaru[$key] = $value;
                if (is_array($dataBaru[$key]) && array_key_exists('kk_level', $dataBaru[$key])) {
                    if (! is_array($value['kk_level'])) {
                        $value                      = $value['kk_level'] == '' ? array_keys(SHDKEnum::all()) : [$value['kk_level']];
                        $dataBaru[$key]['kk_level'] = $value;
                    } elseif (isNestedArray($value['kk_level'], true)) {
                        if (! is_array($value['kk_level'][0])) {
                            $value['kk_level'][0] = json_decode($value['kk_level'][0], null);
                        }
                        $dataBaru[$key]['kk_level'] = $value['kk_level'][0];
                    }
                }
                if ((is_array($dataBaru[$key]) && array_key_exists('status_dasar', $dataBaru[$key])) && ! is_array($value['status_dasar'])) {
                    $value                          = $value['status_dasar'] == '' ? $stDasar : [$value['status_dasar']];
                    $dataBaru[$key]['status_dasar'] = $value;
                }
            }
            $this->db->update('tweb_surat_format', ['form_isian' => json_encode($dataBaru, JSON_THROW_ON_ERROR)], ['id' => $row->id]);
        }
        $this->db->trans_complete();

        return $hasil;
    }

    private function migrasi_2023110771(bool $hasil)
    {
        if (! Schema::hasTable('alias_kodeisian')) {
            Schema::create('alias_kodeisian', static function (Blueprint $table): void {
                $table->increments('id');
                $table->integer('config_id');
                $table->string('judul', 10);
                $table->string('alias', 50);
                $table->string('content', 200);
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('config_id')->references('id')->on('config')->onDelete('cascade');
                $table->unique(['config_id', 'judul', 'alias']);
            });
        }

        return $hasil;
    }

    protected function migrasi_2023110971($hasil, $config_id)
    {
        return $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Lampiran',
            'slug'       => 'lampiran',
            'url'        => 'lampiran',
            'aktif'      => 1,
            'ikon'       => 'fa-file-o',
            'urut'       => 3,
            'level'      => 1,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-file-o',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'layanan-surat'])->row()->id,
        ]);
    }

    protected function migrasi_2023110951($hasil)
    {
        FormatSurat::where('url_surat', 'surat-raw-tinymce')->update(['jenis' => FormatSurat::TINYMCE_DESA]);

        return $hasil;
    }

    protected function migrasi_2023114951($hasil)
    {
        if (! Schema::hasTable('fcm_token_mandiri')) {
            Schema::create('fcm_token_mandiri', static function (Blueprint $table): void {
                $table->integer('id_user_mandiri')->comment('id user mandiri');
                $table->mediumInteger('config_id');
                $table->string('device')->unique()->comment('id device dari android pemohon');
                $table->longText('token')->comment('token yang didapat dari FCM');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('log_notifikasi_mandiri')) {
            Schema::create('log_notifikasi_mandiri', static function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->mediumInteger('id_user_mandiri')->comment('id user mandiri');
                $table->integer('config_id');
                $table->string('judul')->comment('Judul notifikasi');
                $table->text('isi')->comment('Isi notifikasi');
                $table->longtext('token');
                $table->string('device')->unique()->comment('id device dari android pemohon');
                $table->string('image')->nullable()->comment('gambar notifikasi, jika ada');
                $table->string('payload')->length(100)->comment('Tujuan navicasi saat notifikasi di klik');
                $table->tinyInteger('read')->comment('menandatakan notifikasi sudah terbaca atau belum, 1 artinya sudah dibaca, 0 artinya belum dibaca');
                $table->timestamps();
                $table->index(['id', 'created_at', 'read', 'device', 'config_id']);
            });
        }

        return $hasil;
    }

    protected function migrasi_2023111571($hasil)
    {
        if (! $this->db->field_exists('pemohon', 'log_surat')) {
            return $hasil && $this->dbforge->add_column('log_surat', [
                'pemohon' => [
                    'type'       => 'varchar',
                    'constraint' => 200,
                    'null'       => true,
                    'default'    => null,
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2023111751($hasil)
    {
        if (! Schema::hasTable('log_login')) {
            Schema::create('log_login', static function (Blueprint $table): void {
                $table->uuid('uuid')->primary();
                $table->integer('config_id');
                $table->string('username');
                $table->string('ip_address');
                $table->string('user_agent');
                $table->string('referer');
                $table->string('lainnya')->nullable();
                $table->timestamps();
                $table->unique(['uuid', 'config_id']);
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        return $hasil;
    }

    protected function migrasi_2023112251($hasil)
    {
        if (Schema::hasColumn('log_notifikasi_admin', 'token')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table): void {
                $table->dropColumn('token');
            });
        }

        if (Schema::hasColumn('log_notifikasi_admin', 'device')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table): void {
                $table->dropColumn('device');
            });
        }

        return $hasil;
    }

    protected function migrasi_2023112252($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'pendaftar-layanan-mandiri', 'url' => 'mandiri/clear'],
            ['url' => 'mandiri']
        );
    }

    protected function migrasi_2023112371($hasil)
    {
        if (! $this->db->field_exists('border', 'config')) {
            $hasil = $hasil && $this->dbforge->add_column('config', [
                'border' => [
                    'type'       => 'varchar',
                    'constraint' => 25,
                    'null'       => true,
                    'after'      => 'warna',
                ],
            ]);
        }

        if (! $this->db->field_exists('border', 'tweb_wil_clusterdesa')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_wil_clusterdesa', [
                'border' => [
                    'type'       => 'varchar',
                    'constraint' => 25,
                    'null'       => true,
                    'after'      => 'warna',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2023112351($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'pengguna', 'url' => 'man_user/clear'],
            ['url' => 'man_user']
        );
    }

    protected function migrasi_2023112352($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Google Recaptcha Site Key',
            'key'        => 'google_recaptcha_site_key',
            'value'      => '',
            'keterangan' => 'Site key google recaptcha',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Google Recaptcha Secret Key',
            'key'        => 'google_recaptcha_secret_key',
            'value'      => '',
            'keterangan' => 'Secret key google recaptcha',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Google Recaptcha',
            'key'        => 'google_recaptcha',
            'value'      => 0,
            'keterangan' => 'Aktif atau nonaktifkan google recaptcha',
            'jenis'      => 'boolean',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ], $id);
    }

    protected function migrasi_2023112451($hasil)
    {
        if (Schema::hasColumn('log_notifikasi_mandiri', 'token')) {
            Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                $table->dropColumn('token');
            });
        }

        if (Schema::hasColumn('log_notifikasi_mandiri', 'device')) {
            Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                $table->dropColumn('device');
            });
        }

        return $hasil;
    }
}
