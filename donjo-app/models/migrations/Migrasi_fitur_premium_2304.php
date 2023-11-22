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
use App\Enums\StatusSuratKecamatanEnum;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2304 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2303');
        $hasil = $hasil && $this->migrasi_2023030271($hasil);
        $hasil = $hasil && $this->tambah_kolom_kecamatan($hasil);
        $hasil = $hasil && $this->suratPermohonanAktaLahir($hasil);
        $hasil = $hasil && $this->suratKeteranganBepergian($hasil);
        $hasil = $hasil && $this->migrasi_2023032852($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023030271($hasil)
    {
        // Ubah tipe kolom id_telegram int menjadi varchar (100)
        $fields = [
            'id_telegram' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('user', $fields);
    }

    protected function tambah_kolom_kecamatan($hasil)
    {
        if (! $this->db->field_exists('kecamatan', 'log_surat')) {
            $fields = [
                'kecamatan' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => StatusSuratKecamatanEnum::TidakAktif,
                    'after'      => 'isi_surat',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        return $hasil;
    }

    protected function suratPermohonanAktaLahir($hasil)
    {
        $nama_surat = 'Permohonan Akta Lahir';

        $data = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => 'S-18',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_nama_anak]","nama":"Nama Anak","deskripsi":"Masukkan Nama Anak","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir]","nama":"Tempat Lahir","deskripsi":"Masukkan Tempat Lahir","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir]","nama":"Tanggal Lahir","deskripsi":"Masukkan Tanggal Lahir","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_hari_lahir]","nama":"Hari Lahir","deskripsi":"Masukkan Hari Lahir","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_alamat_anak]","nama":"Alamat Anak","deskripsi":"Masukkan Alamat Anak","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_ayah]","nama":"Nama Ayah","deskripsi":"Masukkan Nama Ayah","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_ibu]","nama":"Nama Ibu","deskripsi":"Masukkan Nama Ibu","atribut":"class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_alamat_orang_tua]","nama":"Alamat Orang Tua","deskripsi":"Masukkan Alamat Orang Tua","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 6.78314%;\">Nomor</td>\r\n<td style=\"width: 1.95177%; text-align: center;\">:</td>\r\n<td style=\"width: 91.2651%;\">[Format_nomor_suraT]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 6.78314%;\">Perihal</td>\r\n<td style=\"width: 1.95177%; text-align: center;\">:</td>\r\n<td style=\"width: 91.2651%;\">\r\n<h4 style=\"margin: 0px; text-align: left;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"margin: 0px; text-align: justify;\"><br />Kepada Yth<br /><br />Kepala Pengadilan Agama<br />[SeButan_kabupaten] [NaMa_kabupaten]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 108px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36px; text-align: left;\">4.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Mengajukan permohonan untuk diterbitkan penetapan Pengadilan Negeri sebagai persyaratan pencatatan peristiwa kelahiran dan penerbitan kutipan Akta Kelahiran atas nama:</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 90px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_nama_anaK]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_tempat_lahiR], [Form_tanggal_lahiR]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_hari_lahiR]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Alamat</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_alamat_anaK]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Ayah</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_nama_ayaH]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5253%; text-align: left;\">Nama Ibu</td>\r\n<td style=\"width: 1.2333%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify;\">[Form_nama_ibU]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5253%; text-align: left;\">Alamat Orang Tua</td>\r\n<td style=\"width: 1.2333%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify;\">[Form_alamat_orang_tuA]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>\r\n<p> </p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganBepergian($hasil)
    {
        $nama_surat = 'Keterangan Bepergian';

        $data = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => 'S-10',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['1', '2', '3'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270.984px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">No. KK</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Kepala Keluarga</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Kepala_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36.7344px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36.7344px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36.7344px; text-align: left;\">7.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36.7344px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36.7344px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36.7344px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 22.4375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 22.4375px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 22.4375px;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 22.4375px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 22.4375px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 22.4375px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Status</td>\r\n<td style=\"width: 1.2333%; height: 18.375px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.375px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.375px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18.4375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.4375px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18.4375px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18.4375px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.4375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.4375px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 10px;\">13.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 10px;\">[Form_keperluaN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">14.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Berlaku mulai</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Mulai_berlakU] s/d [Berlaku_sampaI]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut di atas adalah benar-benar warga [Sebutan_desa] [NaMa_desa] dengan data seperti di atas.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sesungguhnya untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function migrasi_2023032852($hasil)
    {
        // Ganti lampiran f-2.29.php menjadi f-2.01.php
        DB::table('tweb_surat_format')->where('lampiran', 'f-2.29.php')->update(['lampiran' => 'f-2.01.php']);

        return $hasil;
    }
}
