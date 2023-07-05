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
            $hasil = $hasil && $this->suratSuratKeteranganUntukNikahWargaNonMuslim($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganKematian($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganBedaIdentitasKIS($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPenghasilanIbu($hasil, $id);
            // Jalankan Migrasi TinyMCE
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
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Keperluan","atribut":class=\"required\","pilihan":null,"refrensi":null}]',
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
            'kode_isian'          => '[{"kategori":"Pelapor","tipe":"text","kode":"[form_hubungan_pelapor_dengan_yang_mati]","nama":"Hubungan pelapor dengan yang mati","deskripsi":"Hubungan pelapor dengan yang mati","atribut":"class=\"required\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"2","kk_level":""},"data_orang_tua":"1","Pelapor":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_I":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_II":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => 'F-2.01',
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratSuratKeteranganUntukNikahWargaNonMuslim($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Untuk Nikah Warga Non Muslim',
            'kode_surat'          => 'S-50',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"number","kode":"[form_anak_ke]","nama":"Anak ke","deskripsi":"Anak ke","atribut":"min=1 max=10 class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_perkawinan_ke]","nama":"Perkawinan ke","deskripsi":"Perkawinan ke" ,"atribut":"min=1 max=10 class=\"required\"","pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_paspor]","nama":"Paspor","deskripsi":"Paspor","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kebangsaan_bagi_wna]","nama":"Kebangsaan (Bagi WNA)","deskripsi":"Kebangsaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_tinggal_ayah_pasangan_pria]","nama":"Tempat Tinggal Ayah Pasangan Pria","deskripsi":"Tempat Tinggal Ayah Pasangan Pria","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_tinggal_ibu_pasangan_pria]","nama":"Tempat Tinggal Ibu Pasangan Pria","deskripsi":"Tempat Tinggal Ibu Pasangan Pria","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_telepon_ibu_pasangan_pria]","nama":"Telepon Ibu Pasangan Pria","deskripsi":"Telepon Ibu Pasangan Pria","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"hari-tanggal","kode":"[form_hari_tanggal_menikah]","nama":"Hari, Tanggal Menikah","deskripsi":"Hari, Tanggal Menikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"time","kode":"[form_jam_menikah]","nama":"Jam Menikah","deskripsi":"Jam Menikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_pemberkatan_perkawinan]","nama":"Tanggal Pemberkatan Perkawinan","deskripsi":"Tanggal Pemberkatan Perkawinan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_agamapenghayat_kepercayaan]","nama":"Agama\/Penghayat Kepercayaan","deskripsi":"Agama\/Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_badan_peradilan]","nama":"Nama Badan Peradilan","deskripsi":"Nama Badan Peradilan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_putusan_penetapan_pengadilan]","nama":"Nomor Putusan Penetapan Pengadilan","deskripsi":"Nomor Putusan Penetapan Pengadilan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_putusan_penetapan_pengadilan]","nama":"Tanggal Putusan Penetapan Pengadilan","deskripsi":"Tanggal Putusan Penetapan Pengadilan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pemuka_agamapghyt_kepercayaan]","nama":"Nama Pemuka Agama\/Pghyt Kepercayaan","deskripsi":"Nama Pemuka Agama\/Pghyt Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_ijin_perwakilan_bagi_wna_nomor]","nama":"Ijin Perwakilan bagi WNA \/ Nomor","deskripsi":"Ijin Perwakilan bagi WNA \/ Nomor","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_jumlah_anak_yang_telah_diakui_dan_disahkan]","nama":"Jumlah Anak Yang Telah Diakui dan Disahkan","deskripsi":"Jumlah Anak Yang Telah Diakui dan Disahkan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_pertama]","nama":"Nama Anak Pertama","deskripsi":"Nama Anak Pertama","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_pertama]","nama":"No Akta Lahir Anak Pertama","deskripsi":"No Akta Lahir Anak Pertama","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_pertama]","nama":"Tanggal Lahir Anak Pertama","deskripsi":"Tanggal Lahir Anak Pertama","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_kedua]","nama":"Nama Anak Kedua","deskripsi":"Nama Anak Kedua","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_kedua]","nama":"No Akta Lahir Anak Kedua","deskripsi":"No Akta Lahir Anak Kedua","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_kedua]","nama":"Tanggal Lahir Anak Kedua","deskripsi":"Tanggal Lahir Anak Kedua","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ketiga]","nama":"Nama Anak Ketiga","deskripsi":"Nama Anak Ketiga","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_ketiga]","nama":"No Akta Lahir Anak Ketiga","deskripsi":"No Akta Lahir Anak Ketiga","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ketiga]","nama":"Tanggal Lahir Anak Ketiga","deskripsi":"Tanggal Lahir Anak Ketiga","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ke_empat]","nama":"Nama Anak Ke Empat","deskripsi":"Nama Anak Ke Empat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_ke_empat]","nama":"No Akta Lahir Anak Ke Empat","deskripsi":"No Akta Lahir Anak Ke Empat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ke_empat]","nama":"Tanggal Lahir Anak Ke Empat","deskripsi":"Tanggal Lahir Anak Ke Empat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ke_lima]","nama":"Nama Anak Ke Lima","deskripsi":"Nama Anak Ke Lima","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_ke_lima]","nama":"No Akta Lahir Anak Ke Lima","deskripsi":"No Akta Lahir Anak Ke Lima","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ke_lima]","nama":"Tanggal Lahir Anak Ke Lima","deskripsi":"Tanggal Lahir Anak Ke Lima","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ke_enam]","nama":"Nama Anak Ke Enam","deskripsi":"Nama Anak Ke Enam","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_akta_lahir_anak_ke_enam]","nama":"No Akta Lahir Anak Ke Enam","deskripsi":"No Akta Lahir Anak Ke Enam","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ke_enam]","nama":"Tanggal Lahir Anak Ke Enam","deskripsi":"Tanggal Lahir Anak Ke Enam","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_anak_ke]","nama":"Anak ke","deskripsi":"Anak ke-","atribut":"min=1 max=10 class=\"required\"","pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_perkawinan_ke]","nama":"Perkawinan ke","deskripsi":"Perkawinan ke-","atribut":"min=1 max=10 class=\"required\"","pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_passport]","nama":"passport","deskripsi":"passport","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_kebangsaan_bagi_wna]","nama":"Kebangsaan (Bagi WNA)","deskripsi":"Kebangsaan (Bagi WNA)","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_no_ktp_ayah]","nama":"No KTP Ayah","deskripsi":"No KTP Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_bin_ayah_wanita]","nama":"Bin Ayah Wanita","deskripsi":"Bin Ayah Wanita","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_lengkap_ayah]","nama":"Nama Lengkap Ayah","deskripsi":"Nama Lengkap Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_tempat_tanggal_lahir]","nama":"Tempat Tanggal Lahir","deskripsi":"Tempat Tanggal Lahir","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"select-otomatis","kode":"[form_warganegara_ayah]","nama":"Warganegara  Ayah","deskripsi":"Warganegara ","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"kategori":"Calon_Pasangan_Wanita","tipe":"select-otomatis","kode":"[form_agama_ayah]","nama":"Agama Ayah","deskripsi":"Agama Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"kategori":"Calon_Pasangan_Wanita","tipe":"select-otomatis","kode":"[form_pekerjaan]","nama":"Pekerjaan","deskripsi":"Pekerjaan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_tempat_tinggal]","nama":"Tempat Tinggal","deskripsi":"Tempat Tinggal","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_lengkap_ibu]","nama":"Nama Lengkap Ibu","deskripsi":"Nama Lengkap Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_binti_ibu_wanita]","nama":"Binti Ibu Wanita","deskripsi":"Binti Ibu Wanita","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_no_ktp_ibu]","nama":"No Ktp Ibu","deskripsi":"No Ktp Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_tempat_tanggal_lahir]","nama":"Tempat Tanggal Lahir","deskripsi":"Tempat Tanggal Lahir","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"select-otomatis","kode":"[form_warga_negara_ibu]","nama":"Warga Negara Ibu","deskripsi":"Warga Negara Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"kategori":"Calon_Pasangan_Wanita","tipe":"select-otomatis","kode":"[form_agama_ibu]","nama":"Agama Ibu","deskripsi":"Agama ","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"kategori":"Calon_Pasangan_Wanita","tipe":"select-otomatis","kode":"[form_pekerjaan_ibu]","nama":"Pekerjaan Ibu","deskripsi":"Pekerjaan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_tempat_tinggal_ibu]","nama":"Tempat Tinggal Ibu","deskripsi":"Tempat Tinggal","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_telepon_ibu]","nama":"Telepon Ibu","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan_ibu]","nama":"Nama Organisasi Penghayat Kepercayaan Ibu","deskripsi":"Nama Organisasi Penghayat Kepercayaan Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_I","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_I","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_II","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_II","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""},"Calon_Pasangan_Wanita":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_I":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_II":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => 'F-2.12',
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>
                <p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>
                <p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>
                <table style=\"border-collapse: collapse; width: 100%; height: 198px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tbody>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18px; text-align: left;\">1.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Nama</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18px; text-align: left;\">2.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; height: 18px; text-align: justify;\">[TtL]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">3.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Jenis Kelamin</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[Jenis_kelamiN]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18px; text-align: left;\">4.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Surat Bukti Diri</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 60.0524%; height: 18px; text-align: justify;\">\u{a0}</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">KTP</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[NiK]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">KK</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[No_kK]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">5.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Warga Negara</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[Warga_negarA]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">6.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Agama</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[AgamA]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">7.</td>
                <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pekerjaan</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[PekerjaaN]</td>
                </tr>
                <tr style=\"height: 36px;\" valign=\"top\">
                <td style=\"width: 4.3222%; text-align: center; height: 36px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 36px; text-align: left;\">8.<br /><br /></td>
                <td style=\"width: 30.5174%; text-align: left; height: 36px;\">Alamat<br /><br /></td>
                <td style=\"width: 1.24427%; text-align: center; height: 36px;\">:<br /><br /></td>
                <td style=\"width: 60.0524%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
                </tr>
                </tbody>
                </table>
                <p style=\"text-align: justify; text-indent: 30px;\">Nama tersebut di atas betul telah menikah dengan seorang perempuan yang bernama :</p>
                <table style=\"border-collapse: collapse; width: 100%; height: 294.188px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tbody>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18.6875px; text-align: left;\">9.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Nama</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; height: 18.6875px; text-align: justify;\">[Nama_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18.6875px;\">10.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Tempat/tanggal lahir</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18.6875px;\">[Ttl_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18.6875px; text-align: left;\">11.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Jenis Kelamin</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; height: 18.6875px; text-align: justify;\">[Jenis_kelamin_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18.6875px;\">12.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Surat Bukti Diri</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18.6875px;\">\u{a0}</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">KTP</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18.6875px;\">[Nik_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18.6875px; text-align: left;\">\u{a0}</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">KK</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; height: 18.6875px; text-align: justify;\">[No_kk_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18.6875px;\">13.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Warga Negara</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18.6875px;\">[Warga_negara_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; height: 18.6875px; text-align: left;\">14.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Agama</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; height: 18.6875px; text-align: justify;\">[Agama_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 18.6875px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18.6875px;\">15.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18.6875px;\">Pekerjaan</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18.6875px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18.6875px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18.6875px;\">[Pekerjaan_calon_pasangan_wanitA]</td>
                </tr>
                <tr style=\"height: 36px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 36px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 36px;\">16.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 36px;\">Alamat</td>
                <td style=\"width: 4.3222%; text-align: left; height: 36px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 36px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 36px;\">[Alamat_calon_pasangan_wanitA] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18px;\">\u{a0}</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18px;\">Di:</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18px;\">\u{a0}</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">17.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18px;\">Tempat</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Form_nama_badan_peradilaN]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">18.</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18px;\">Tanggal</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Form_tanggal_pemberkatan_perkawinaN]</td>
                </tr>
                <tr style=\"height: 18px;\">
                <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 3.92927%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 26.1952%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 4.3222%; text-align: left; height: 18px;\">\u{a0}</td>
                <td style=\"width: 1.24427%; text-align: center; height: 18px;\">\u{a0}</td>
                <td style=\"width: 59.9869%; text-align: justify; height: 18px;\">\u{a0}</td>
                </tr>
                </tbody>
                </table>
                <p style=\"text-align: justify; text-indent: 30px;\">Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
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
                <td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}[Sebutan_desA] [Kode_desA]</td>
                </tr>
                <tr style=\"height: 72px;\">
                <td style=\"width: 35%; text-align: center; height: 72px;\">[qr_code]</td>
                <td style=\"width: 30%; height: 72px;\"><br /><br /><br /></td>
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
                <td style=\"width: 35%; text-align: center; height: 18px;\">\u{a0}NIP : [nip_pamong]</td>
                </tr>
                </tbody>
                </table>
                <div style=\"text-align: center;\"><br /><br /></div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratKeteranganPenghasilanIbu($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Penghasilan Ibu',
            'kode_surat'          => 'S-45',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_jumlah_penghasilan_ibu]","nama":"Jumlah Penghasilan Ibu","deskripsi":"Penghasilan Ibu dalam Rupiah","atribut":"class=\"required rupiah\"","pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Isi Keperluan","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_sekolah]","nama":"Nama Sekolah","deskripsi":"Isi Nama Sekolah","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>
            <p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>
            <p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>
            <table style=\"border-collapse: collapse; width: 100%; height: 269.297px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">1.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Nama Lengkap</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\"><strong>[NaMa_ibu]</strong></td>
            </tr>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">2.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">NIK / No. KTP</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[NiK_ibu]</td>
            </tr>
            <tr style=\"height: 25.8125px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 25.8125px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 25.8125px;\">3.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 25.8125px;\">Tempat / Tanggal Lahir</td>
            <td style=\"width: 1.24427%; text-align: center; height: 25.8125px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 25.8125px;\">[TtL_ibu]</td>
            </tr>
            <tr style=\"height: 21.8125px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 21.8125px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 21.8125px;\">4.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 21.8125px;\">Jenis Kelamin</td>
            <td style=\"width: 1.24427%; text-align: center; height: 21.8125px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 21.8125px;\">[JeNis_kelamin_ibu]</td>
            </tr>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">5.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Agama</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[AgAma_ibu]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 18px;\">6</td>
            <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pekerjaan</td>
            <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[PeKerjaan]</td>
            </tr>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">7.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Kewarganegaraan</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Warga_negarA]</td>
            </tr>
            <tr style=\"height: 43.4844px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 43.4844px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 43.4844px;\">8.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 43.4844px;\">Alamat / Tempat Tinggal</td>
            <td style=\"width: 1.24427%; text-align: center; height: 43.4844px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 43.4844px;\">[AlAmat_ibu] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">Orang yang tersebut di atas adalah benar-benar warga kami yang bertempat tinggal di [AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten] dan tercatat dalam No. KK : [No_kK] dengan NIK [NiK_ibu] Kepala Keluarga : [Kepala_kK] dan menurut sepengetahuan kami memang benar berpenghasilan rata-rata [Form_jumlah_penghasilan_ibu] / Perbulan.<br /><br /></p>
            <p>Surat Keterangan ini dibuat atas permintaan yang bersangkutan untuk keperluan anaknya untuk [Form_keperluaN] di<strong> [Form_nama_sekolaH]</strong><strong> </strong>atas nama :</p>
            <table style=\"border-collapse: collapse; width: 100%; height: 269.297px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">1.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Nama Lengkap</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\"><strong>[NAma] </strong></td>
            </tr>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">2.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">NIK / No. KTP</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Nik]</td>
            </tr>
            <tr style=\"height: 25.8125px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 25.8125px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 25.8125px;\">3.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 25.8125px;\">Tempat / Tanggal Lahir</td>
            <td style=\"width: 1.24427%; text-align: center; height: 25.8125px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 25.8125px;\">[TtL]</td>
            </tr>
            <tr style=\"height: 21.8125px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 21.8125px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 21.8125px;\">4.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 21.8125px;\">Jenis Kelamin</td>
            <td style=\"width: 1.24427%; text-align: center; height: 21.8125px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 21.8125px;\">[Jenis_kelamin]</td>
            </tr>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">5.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Agama</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[AgAma]</td>
            </tr>
            <tr>
            <td style=\"width: 4.3222%; text-align: center;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left;\">6</td>
            <td style=\"width: 30.5174%; text-align: left;\">Status</td>
            <td style=\"width: 1.24427%; text-align: center;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify;\">[Status_kawiN]</td>
            </tr>
            <tr>
            <td style=\"width: 4.3222%; text-align: center;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left;\">7.</td>
            <td style=\"width: 30.5174%; text-align: left;\">Pendidikan</td>
            <td style=\"width: 1.24427%; text-align: center;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify;\">[Pendidikan_sedanG]</td>
            </tr>
            <tr style=\"height: 18px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 18px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 18px;\">8.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pekerjaan</td>
            <td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 18px;\">[PeKerjaan]</td>
            </tr>
            <tr style=\"height: 19.75px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 19.75px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; height: 19.75px; text-align: left;\">9.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 19.75px;\">Kewarganegaraan</td>
            <td style=\"width: 1.24427%; text-align: center; height: 19.75px;\">:</td>
            <td style=\"width: 60.0524%; height: 19.75px; text-align: justify;\">[Warga_negarA]</td>
            </tr>
            <tr style=\"height: 43.4844px;\">
            <td style=\"width: 4.3222%; text-align: center; height: 43.4844px;\">\u{a0}</td>
            <td style=\"width: 3.92927%; text-align: left; height: 43.4844px;\">10.</td>
            <td style=\"width: 30.5174%; text-align: left; height: 43.4844px;\">Alamat / Tempat Tinggal</td>
            <td style=\"width: 1.24427%; text-align: center; height: 43.4844px;\">:</td>
            <td style=\"width: 60.0524%; text-align: justify; height: 43.4844px;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
            </tr>
            </tbody>
            </table>
            <p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>
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
            <div style=\"text-align: center;\"><br />[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }
}
