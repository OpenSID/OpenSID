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

class Migrasi_fitur_premium_2308 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2307', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && $this->migrasi_xxxxxxxxxx($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->suratKeteranganKematian($hasil, $id);
            $hasil = $hasil &&  $this->suratKeteranganBedaIdentitasKIS($hasil, $id);
        }

        // Migrasi tanpa config_id
        return $hasil && true;
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    protected function suratKeteranganBedaIdentitasKIS($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Beda Identitas KIS',
            'kode_surat'          => 'S-38',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Keperluan","required":"1","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h3 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h3>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa : <br /><br />[Pengikut_kiS]</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Nama tersebut di atas merupakan identitas yang tertera pada KTP dan Kartu Keluarga (KK) sedangkan pada Kartu Indonesia Sehat (KIS) tertulis :<br /><br />[Pengikut_kartu_kiS]<br /><br /></p><p style=\"text-align: justify; text-indent: 30px;\">Menurut pengamatan dan pengetahuan kami hingga saat dikeluarkannya surat keterangan ini bahwa yang namanya di atas merupakan orang yang satu / sama.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Surat keterangan ini dibuat untuk keperluan : <strong>[Form_keperluaN]</strong>.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya.</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratKeteranganKematian($hasil, $id)
    {
        $template = <<<HTML
                <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
                <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br /><br /></p>
                <p style="text-align: justify; text-indent: 30px;">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>
                <table style="border-collapse: collapse; width: 100%; height: 118px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18px; text-align: left;">1.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Nama</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; height: 18px; text-align: justify;"><strong>[NAma]</strong></td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 18px;">2.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">NIK / No. KTP</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 18px;">[Nik]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18px; text-align: left;">3.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Jenis Kelamin</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; height: 18px; text-align: justify;">[Jenis_kelamin]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18px; text-align: left;">4.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Tempat / Tanggal Lahir</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; height: 18px; text-align: justify;">[TtL]</td>
                </tr>
                <tr style="height: 10px;">
                <td style="width: 4.31655%; text-align: center; height: 10px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 10px;">5.</td>
                <td style="width: 30.5242%; text-align: left; height: 10px;">Pekerjaan</td>
                <td style="width: 1.2333%; text-align: center; height: 10px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 10px;">[Pekerjaan]</td>
                </tr>
                <tr style="height: 36px;">
                <td style="width: 4.31655%; text-align: center; height: 36px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 36px; text-align: left;">6.<br /><br /></td>
                <td style="width: 30.5242%; text-align: left; height: 36px;">Alamat / Tempat Tinggal<br /><br /></td>
                <td style="width: 1.2333%; text-align: center; height: 36px;">:<br /><br /></td>
                <td style="width: 60.0206%; height: 36px; text-align: justify;">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Telah meninggal dunia pada:</p>
                <table style="border-collapse: collapse; width: 100%; height: 63px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 22px;">
                <td style="width: 4.3222%; text-align: center; height: 22px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 22px; text-align: left;">7.</td>
                <td style="width: 30.5174%; text-align: left; height: 22px;">Hari / Tanggal / Jam</td>
                <td style="width: 1.24427%; text-align: center; height: 22px;">:</td>
                <td style="width: 60.0524%; height: 22px; text-align: justify;">[Hari_kematiaN], [Tanggal_kematiaN], [Jam_kematiaN]</td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.3222%; text-align: center; height: 19px;">\u{a0}</td>
                <td style="width: 3.92927%; text-align: left; height: 19px;">8.</td>
                <td style="width: 30.5174%; text-align: left; height: 19px;">Bertempat di</td>
                <td style="width: 1.24427%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0524%; text-align: justify; height: 19px;">[Tempat_kematiaN]</td>
                </tr>
                <tr style="height: 22px;">
                <td style="width: 4.3222%; text-align: center; height: 22px;">\u{a0}</td>
                <td style="width: 3.92927%; height: 22px; text-align: left;">9.</td>
                <td style="width: 30.5174%; text-align: left; height: 22px;">Penyebab Kematian</td>
                <td style="width: 1.24427%; text-align: center; height: 22px;">:</td>
                <td style="width: 60.0524%; height: 22px; text-align: justify;">[Penyebab_kematiaN]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Surat keterangan ini dibuat berdasarkan keterangan pelapor:</p>
                <table style="border-collapse: collapse; width: 100%; height: 109.688px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 18.6719px;">
                <td style="width: 4.31655%; text-align: center; height: 18.6719px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18.6719px; text-align: left;">10.</td>
                <td style="width: 30.5242%; text-align: left; height: 18.6719px;">Nama Lengkap</td>
                <td style="width: 1.2333%; text-align: center; height: 18.6719px;">:</td>
                <td style="width: 60.0206%; height: 18.6719px; text-align: justify;">[NAma_pelapor]</td>
                </tr>
                <tr style="height: 18.6719px;">
                <td style="width: 4.31655%; text-align: center; height: 18.6719px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 18.6719px;">11.</td>
                <td style="width: 30.5242%; text-align: left; height: 18.6719px;">NIK / No. KTP</td>
                <td style="width: 1.2333%; text-align: center; height: 18.6719px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 18.6719px;">[Nik_pelapoR]</td>
                </tr>
                <tr style="height: 18.6719px;">
                <td style="width: 4.31655%; text-align: center; height: 18.6719px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18.6719px; text-align: left;">12.</td>
                <td style="width: 30.5242%; text-align: left; height: 18.6719px;">Tanggal Lahir</td>
                <td style="width: 1.2333%; text-align: center; height: 18.6719px;">:</td>
                <td style="width: 60.0206%; height: 18.6719px; text-align: justify;">[Tanggallahir_pelapoR]</td>
                </tr>
                <tr style="height: 18.6719px;">
                <td style="width: 4.31655%; text-align: center; height: 18.6719px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 18.6719px;">13.</td>
                <td style="width: 30.5242%; text-align: left; height: 18.6719px;">Pekerjaan</td>
                <td style="width: 1.2333%; text-align: center; height: 18.6719px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 18.6719px;">[Pekerjaan]</td>
                </tr>
                <tr>
                <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left;">14.</td>
                <td style="width: 30.5242%; text-align: left;">Alamat / Tempat Tinggal</td>
                <td style="width: 1.2333%; text-align: center;">:</td>
                <td style="width: 60.0206%; text-align: justify;">[Alamat_pelapoR]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 18px;">15.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Hubungan dengan yang mati</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 18px;">[Form_hubungan_pelapor_dengan_yang_matI]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.<br /><br /></p>
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
            'nama'                => 'Keterangan Kematian',
            'kode_surat'          => 'S-21',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"kategori":"Pelapor","tipe":"text","kode":"[form_hubungan_pelapor_dengan_yang_mati]","nama":"Hubungan pelapor dengan yang mati","deskripsi":"Hubungan pelapor dengan yang mati","required":"1","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"2","kk_level":""},"data_orang_tua":"1","Pelapor":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_I":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_II":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => 'F-2.01',
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }
}
