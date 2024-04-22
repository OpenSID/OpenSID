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
use App\Models\Config;
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
        return $hasil;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->suratSuratKuaasa($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganDomisiliNonWarga($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganIzinOrangTuaSuamiIstri($hasil, $id);
            $hasil = $hasil && $this->suratSuratKeteranganUntukNikahWargaNonMuslim($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganKematian($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganBedaIdentitasKIS($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPenghasilanIbu($hasil, $id);
            $hasil = $hasil && $this->suratPernyataanPenguasaanFisikBidangTanahSPORADIK($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganKelahiran($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganPindahPenduduk($hasil, $id);
            $hasil = $hasil && $this->suratKeteranganNikah($hasil, $id);
            // Jalankan Migrasi TinyMCE
            $hasil = $hasil && $this->migrasi_2023072451($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023070651($hasil);
        $hasil = $hasil && $this->migrasi_2023070653($hasil);
        $hasil = $hasil && $this->migrasi_2023072454($hasil);

        return $hasil && $this->migrasi_2023070652($hasil);
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

    protected function migrasi_2023070651($hasil)
    {
        DB::table('widget')->where('judul', 'Aparatur Desa')->update(['judul' => '[Pemerintah Desa]']);

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
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"number","kode":"[form_jika_beristri_istri_ke]","nama":"Jika beristri, istri ke","deskripsi":"Jika beristri, istri ke","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_anak_ke]","nama":"Anak ke","deskripsi":"Anak ke","required":"0","atribut":"min=1 max=10 class=\"\"","pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_perkawinan_ke]","nama":"Perkawinan ke","deskripsi":"Perkawinan ke","required":"0","atribut":"min=1 max=10 class=\"\"","pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_paspor]","nama":"Paspor","deskripsi":"Paspor","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kebangsaan_bagi_wna]","nama":"Kebangsaan (Bagi WNA)","deskripsi":"Kebangsaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan_ayah]","nama":"Nama Organisasi Penghayat Kepercayaan Ayah","deskripsi":"Nama Organisasi Penghayat Kepercayaan Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_telepon_ayah]","nama":"Telepon Ayah","deskripsi":"Telepon Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan_ibu]","nama":"Nama Organisasi Penghayat Kepercayaan Ibu","deskripsi":"Nama Organisasi Penghayat Kepercayaan Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_telepon_ibu]","nama":"Telepon Ibu","deskripsi":"Telepon Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"hari-tanggal","kode":"[form_hari_tanggal_menikah]","nama":"Hari, Tanggal Menikah","deskripsi":"Hari, Tanggal Menikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"time","kode":"[form_jam_menikah]","nama":"Jam Menikah","deskripsi":"Jam Menikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_pemberkatan_perkawinan]","nama":"Tanggal Pemberkatan Perkawinan","deskripsi":"Tanggal Pemberkatan Perkawinan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_agamapenghayat_kepercayaan]","nama":"Agama\/Penghayat Kepercayaan","deskripsi":"Agama\/Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan_kawin]","nama":"Nama Organisasi Penghayat Kepercayaan Kawin","deskripsi":"Nama Organisasi Penghayat Kepercayaan Kawin","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_badan_peradilan]","nama":"Nama Badan Peradilan","deskripsi":"Nama Badan Peradilan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_putusan_penetapan_pengadilan]","nama":"Nomor Putusan Penetapan Pengadilan","deskripsi":"Nomor Putusan Penetapan Pengadilan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_putusan_penetapan_pengadilan]","nama":"Tanggal Putusan Penetapan Pengadilan","deskripsi":"Tanggal Putusan Penetapan Pengadilan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pemuka_agamapghyt_kepercayaan]","nama":"Nama Pemuka Agama\/Pghyt Kepercayaan","deskripsi":"Nama Pemuka Agama\/Pghyt Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_ijin_perwakilan_bagi_wna_nomor]","nama":"Ijin Perwakilan bagi WNA \/ Nomor","deskripsi":"Ijin Perwakilan bagi WNA \/ Nomor","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_jumlah_anak_yang_telah_diakui_dan_disahkan]","nama":"Jumlah Anak Yang Telah Diakui dan Disahkan","deskripsi":"Jumlah Anak Yang Telah Diakui dan Disahkan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_pertama]","nama":"Nama Anak Pertama","deskripsi":"Nama Anak Pertama","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_pertama]","nama":"No Akta Lahir Anak Pertama","deskripsi":"No Akta Lahir Anak Pertama","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_pertama]","nama":"Tanggal Lahir Anak Pertama","deskripsi":"Tanggal Lahir Anak Pertama","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_kedua]","nama":"Nama Anak Kedua","deskripsi":"Nama Anak Kedua","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_kedua]","nama":"No Akta Lahir Anak Kedua","deskripsi":"No Akta Lahir Anak Kedua","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_kedua]","nama":"Tanggal Lahir Anak Kedua","deskripsi":"Tanggal Lahir Anak Kedua","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ketiga]","nama":"Nama Anak Ketiga","deskripsi":"Nama Anak Ketiga","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_ketiga]","nama":"No Akta Lahir Anak Ketiga","deskripsi":"No Akta Lahir Anak Ketiga","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ketiga]","nama":"Tanggal Lahir Anak Ketiga","deskripsi":"Tanggal Lahir Anak Ketiga","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ke_empat]","nama":"Nama Anak Ke Empat","deskripsi":"Nama Anak Ke Empat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_ke_empat]","nama":"No Akta Lahir Anak Ke Empat","deskripsi":"No Akta Lahir Anak Ke Empat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ke_empat]","nama":"Tanggal Lahir Anak Ke Empat","deskripsi":"Tanggal Lahir Anak Ke Empat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ke_lima]","nama":"Nama Anak Ke Lima","deskripsi":"Nama Anak Ke Lima","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_no_akta_lahir_anak_ke_lima]","nama":"No Akta Lahir Anak Ke Lima","deskripsi":"No Akta Lahir Anak Ke Lima","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ke_lima]","nama":"Tanggal Lahir Anak Ke Lima","deskripsi":"Tanggal Lahir Anak Ke Lima","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_anak_ke_enam]","nama":"Nama Anak Ke Enam","deskripsi":"Nama Anak Ke Enam","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_akta_lahir_anak_ke_enam]","nama":"No Akta Lahir Anak Ke Enam","deskripsi":"No Akta Lahir Anak Ke Enam","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_anak_ke_enam]","nama":"Tanggal Lahir Anak Ke Enam","deskripsi":"Tanggal Lahir Anak Ke Enam","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_anak_ke]","nama":"Anak ke","deskripsi":"Anak ke-","required":"0","atribut":"min=1 max=10 class=\"\"","pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_perkawinan_ke]","nama":"Perkawinan ke","deskripsi":"Perkawinan ke-","required":"0","atribut":"min=1 max=10 class=\"\"","pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_passport]","nama":"passport","deskripsi":"passport","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_kebangsaan_bagi_wna]","nama":"Kebangsaan (Bagi WNA)","deskripsi":"Kebangsaan (Bagi WNA)","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan_ayah]","nama":"Nama Organisasi Penghayat Kepercayaan Ayah","deskripsi":"Nama Organisasi Penghayat Kepercayaan Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_telepon_ayah]","nama":"Telepon Ayah","deskripsi":"Telepon Ayah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan_ibu]","nama":"Nama Organisasi Penghayat Kepercayaan Ibu","deskripsi":"Nama Organisasi Penghayat Kepercayaan Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Calon_Pasangan_Wanita","tipe":"number","kode":"[form_telepon_ibu]","nama":"Telepon Ibu","deskripsi":"Telepon Ibu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_I","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_I","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_II","tipe":"number","kode":"[form_telepon]","nama":"Telepon","deskripsi":"Telepon","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Saksi_II","tipe":"text","kode":"[form_nama_organisasi_penghayat_kepercayaan]","nama":"Nama Organisasi Penghayat Kepercayaan","deskripsi":"Nama Organisasi Penghayat Kepercayaan","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"1","status_dasar":"","kk_level":""},"data_orang_tua":"1","data_pasangan":"0","Calon_Pasangan_Wanita":{"data":1,"sex":"2","status_dasar":null,"kk_level":""},"Saksi_I":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_II":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
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

    protected function suratKeteranganIzinOrangTuaSuamiIstri($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Izin Orang Tua Suami Istri',
            'kode_surat'          => 'S-39',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"select-otomatis","kode":"[form_memberi_izin_selaku]","nama":"Memberi Izin Selaku","deskripsi":"Pilih Selaku","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_hubungan"},{"kategori":"Penerima_Izin","tipe":"text","kode":"[form_negara_tujuan]","nama":"Negara Tujuan","deskripsi":"Diisi dengan Negara yang dituju sprt: Malaysia, Korea, dll","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Penerima_Izin","tipe":"text","kode":"[form_nama_pptkis]","nama":"Nama PPTKIS","deskripsi":"*) Nama PT atau Perusahaan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Penerima_Izin","tipe":"number","kode":"[form_masa_kontrak_tahun]","nama":"Masa Kontrak (Tahun)","deskripsi":"2","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"kategori":"Penerima_Izin","tipe":"select-otomatis","kode":"[form_hubungan_dengan_penerima_izin]","nama":"Hubungan Dengan Pemberi Izin","deskripsi":"Pilih Hubungan Dengan Pemberi Izin","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_hubungan"},{"kategori":"Penerima_Izin","tipe":"select-manual","kode":"[form_status_pekerjaan_tki_tkw]","nama":"Status Pekerjaan TKI TKW","deskripsi":"Pilih Status Pekerjaan","required":"1","atribut":null,"pilihan":["Tenaga Kerja Indonesia (TKI)","Tenaga Kerja Wanita (TKW)"],"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""},"Penerima_Izin":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br><br></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan / cap jempol di bawah ini :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 118px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">3..</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Warga Negara</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">6.<br><br></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br><br></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br><br></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Saya selaku sebagai [Form_memberi_izin_selakU] dengan ini secara tulus dan ikhlas mengizinkan serta menyetujui [Form_hubungan_dengan_penerima_iziN] saya di bawah ini :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[Nama_penerima_iziN]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Ttl_penerima_iziN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Agama_penerima_iziN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Warga Negara</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Warga_negara_penerima_iziN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Pekerjaan_penerima_iziN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Alamat / Tempat Tinggal</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Alamat_penerima_iziN] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Untuk melamar pekerjaan / bekerja ke [Form_negara_tujuaN], melalui [Form_nama_pptkiS] sebagai [Form_status_pekerjaan_tki_tkW] dengan masa kontrak [Form_masa_kontrak_tahuN] tahun.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Segala akibat yang timbul di kemudian hari dari perbuatan dan penggunaan surat izin ini sepenuhnya menjadi tanggung jawab saya baik secara hukum ataupun secara moril tanpa melibatkan pihak lainnya.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Segala akibat yang timbul di kemudian hari dari pembuatan dan penggunaan surat izin ini sepenuhnya menjadi tanggung jawab saya baik secara hukum ataupun secara moril dan materi tanpa melibatkan pihak lainnya.</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"><strong>Yang Diberi Izin,</strong></td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\"><strong>Yang Memberi Izin,</strong></td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br><br><br><br></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"><strong>[Nama_penerima_iziN]</strong></td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"> </div>\r\n<div style=\"text-align: center;\">\r\n<table style=\"border-collapse: collapse; width: 100%; height: 202.4px; border-width: 0px;\" border=\"0\"><colgroup><col style=\"width: 100%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td>Mengetahui,</td>\r\n</tr>\r\n<tr>\r\n<td>[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td><br><br><br><br></td>\r\n</tr>\r\n<tr>\r\n<td><strong>[Nama_pamonG]</strong></td>\r\n</tr>\r\n<tr>\r\n<td>[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<br>[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratKeteranganDomisiliNonWarga($hasil, $id)
    {
        $data = [
            'nama'                => 'Keterangan Domisili Non Warga',
            'kode_surat'          => '041.1',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_tempat_lahir]","nama":"Tempat Lahir","deskripsi":"Masukkan tempat lahir","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir]","nama":"Tanggal Lahir","deskripsi":"Masukkan tanggal lahir","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_jenis_kelamin]","nama":"Jenis Kelamin","deskripsi":"Pilih jenis kelamin","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_sex"},{"tipe":"select-otomatis","kode":"[form_status_perkawinan]","nama":"Status Perkawinan","deskripsi":"Pilih status perkawinan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_kawin"},{"tipe":"select-otomatis","kode":"[form_agama]","nama":"Agama","deskripsi":"Pilih agama","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan]","nama":"Pekerjaan","deskripsi":"pilih pekerjaan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"textarea","kode":"[form_asal]","nama":"Asal","deskripsi":"Masukkan kota asal","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_tempat_tinggal_sekarang]","nama":"Tempat Tinggal Sekarang","deskripsi":"Masukkan tempat tinggal sekarang","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tujuan_keperluan]","nama":"Tujuan \/ Keperluan","deskripsi":"Masukkan tujuan \/ keperluan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Masukkan keterangan","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"2","individu":null}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br><br></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100.012%; height: 336.8px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 23.2px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 23.2px;\"> </td>\r\n<td style=\"width: 3.92512%; height: 23.2px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 23.2px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 23.2px;\">:</td>\r\n<td style=\"width: 59.9638%; height: 23.2px; text-align: justify;\"><strong>[Form_nama_non_wargA]</strong></td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; height: 22.4px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 59.9638%; height: 22.4px; text-align: justify;\">[Form_nik_non_wargA]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; height: 22.4px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 59.9638%; height: 22.4px; text-align: justify;\">[form_tempat_lahir], [form_tanggal_lahir]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; height: 22.4px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 59.9638%; height: 22.4px; text-align: justify;\">[form_jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; text-align: left; height: 22.4px;\">5.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">Agama</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 59.9638%; text-align: justify; height: 22.4px;\">[form_agama]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; text-align: left; height: 22.4px;\">6.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">Status</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 59.9638%; text-align: justify; height: 22.4px;\">[form_status_perkawinan]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; height: 22.4px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">Pekerjaan</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 59.9638%; height: 22.4px; text-align: justify;\">[form_pekerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.34783%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92512%; height: 22.4px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.4952%; text-align: left; height: 22.4px;\">Asal</td>\r\n<td style=\"width: 1.26812%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 59.9638%; height: 22.4px; text-align: justify;\">[form_asal]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.4px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.4px;\">Tempat Tinggal Sekarang</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.4px; text-align: justify;\">[form_tempat_tinggal_sekarang]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.4px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.4px;\">Tujuan / Keperluan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.4px; text-align: justify;\">[form_tujuan_keperluan]</td>\r\n</tr>\r\n<tr style=\"height: 22.4px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 22.4px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 22.4px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 22.4px;\">Keterangan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 22.4px;\">:</td>\r\n<td style=\"width: 60.0524%; height: 22.4px; text-align: justify;\">[form_keterangan]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dan diberikan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya dan di ketahui oleh pihak yang bertanggung jawab.<br><br></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br><br><br><br></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br>[qr_code]</div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratSuratKuaasa($hasil, $id)
    {
        $data = [
            'nama'                => 'Kuasa',
            'kode_surat'          => 'S-47',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_untuk_keperluan]","nama":"Untuk \/ Keperluan","deskripsi":"Untuk \/ Keperluan","required":"1","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""},"Penerima_Kuasa":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => '<h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
                <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br /><br /></p>
                <p style="text-align: justify; text-indent: 30px;">Yang bertanda tangan di bawah ini :</p>
                <table style="border-collapse: collapse; width: 100%; height: 171px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; height: 19px; text-align: left;">1.</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">Nama</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; height: 19px; text-align: justify;"><strong>[NAma]</strong></td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; height: 19px; text-align: left;">2.</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">NIK</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; height: 19px; text-align: justify;">[NiK]</td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; text-align: left; height: 19px;">3..</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">Tempat/tanggal lahir</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 19px;">[TtL]</td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; height: 19px; text-align: left;">4.</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">Umur</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; height: 19px; text-align: justify;">[UsiA]</td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; text-align: left; height: 19px;">5.</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">Warga Negara</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 19px;">[Warga_negarA]</td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; text-align: left; height: 19px;">6.</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">Jenis Kelamin</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 19px;">[Jenis_kelamiN]</td>
                </tr>
                <tr style="height: 19px;">
                <td style="width: 4.31655%; text-align: center; height: 19px;"> </td>
                <td style="width: 3.90545%; text-align: left; height: 19px;">7.</td>
                <td style="width: 30.5242%; text-align: left; height: 19px;">Pekerjaan</td>
                <td style="width: 1.2333%; text-align: center; height: 19px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 19px;">[PekerjaaN]</td>
                </tr>
                <tr style="height: 38px;" valign="top">
                <td style="width: 4.31655%; text-align: center; height: 38px;"> </td>
                <td style="width: 3.90545%; height: 38px; text-align: left;">8.<br /><br /></td>
                <td style="width: 30.5242%; text-align: left; height: 38px;">Tempat Tinggal<br /><br /></td>
                <td style="width: 1.2333%; text-align: center; height: 38px;">:<br /><br /></td>
                <td style="width: 60.0206%; height: 38px; text-align: justify;">[AlamaT] [Sebutan_desA] [Nama_desA], [Sebutan_kecamataN] [Nama_kecamataN], [Sebutan_kabupateN] [Nama_kabupateN]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Dengan ini memberi kuasa penuh kepada :</p>
                <table style="border-collapse: collapse; width: 100%; height: 198px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; height: 18px; text-align: left;">1.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">Nama</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; height: 18px; text-align: justify;"><strong>[NAma_penerima_kuasa]</strong></td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; text-align: left; height: 18px;">2.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">NIK</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 18px;">[Nik_penerima_kuasA]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; height: 18px; text-align: left;">3.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">Tempat/tanggal lahir</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; height: 18px; text-align: justify;">[Tempat_tgl_lahir_penerima_kuasA]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; text-align: left; height: 18px;">4.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">Umur</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 18px;">[Usia_penerima_kuasA]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; text-align: left; height: 18px;">5.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">Warga Negara</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 18px;">[Warga_negarA]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; height: 18px; text-align: left;">6.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">Jenis Kelamin</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; height: 18px; text-align: justify;">[Jenis_kelamin_penerima_kuasA]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; text-align: left; height: 18px;">7.</td>
                <td style="width: 30.5174%; text-align: left; height: 18px;">Pekerjaan</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">:</td>
                <td style="width: 59.9869%; text-align: justify; height: 18px;">[Pekerjaan_penerima_kuasA]</td>
                </tr>
                <tr style="height: 36px;" valign="top">
                <td style="width: 4.3222%; text-align: center; height: 36px;"> </td>
                <td style="width: 3.92927%; height: 36px; text-align: left;">8.</td>
                <td style="width: 30.5174%; text-align: left; height: 36px;">Tempat  tinggal</td>
                <td style="width: 1.24427%; text-align: center; height: 36px;">:</td>
                <td style="width: 59.9869%; height: 36px; text-align: justify;">[Alamat_penerima_kuasA] [Sebutan_desA] [Nama_desA], [Sebutan_kecamataN] [Nama_kecamataN], [Sebutan_kabupateN] [Nama_kabupateN]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.3222%; text-align: center; height: 18px;"> </td>
                <td style="width: 3.92927%; text-align: left; height: 18px;"> </td>
                <td style="width: 30.5174%; text-align: left; height: 18px;"> </td>
                <td style="width: 1.24427%; text-align: center; height: 18px;"> </td>
                <td style="width: 59.9869%; text-align: justify; height: 18px;"> </td>
                </tr>
                <tr style="height: 18px;">
                <td style="height: 18px; width: 38.7688%; text-align: left;" colspan="3">Untuk keperluan</td>
                <td style="width: 1.24427%; text-align: center; height: 18px;">: </td>
                <td style="width: 59.9869%; text-align: justify; height: 18px;">[Form_untuk_keperluaN]</td>
                </tr>
                   <tr style="height: 18px;">
                <td style="height: 18px; width: 38.7688%; text-align: left;" colspan="5"><p style="text-align: justify;">Demikianlah surat kuasa ini saya buat, agar dapat digunakan sebagaimana mestinya.</p>
               </td>
                </tr>
                </tbody>
                </table>
                <table style="border-collapse: collapse; width: 100%; height: 257px;" border="0">
                <tbody>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 9.62595%; height: 23.8594px;"> </td>
                <td style="width: 13.8842%; height: 23.8594px;"> </td>
                <td style="width: 10.1506%; height: 23.8594px;"> </td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;">[NaMa_desa], [TgL_surat]</td>
                </tr>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; text-align: center; height: 23.8594px;">Yang Menerima Kuasa</td>
                <td style="width: 9.62595%; height: 23.8594px;"> </td>
                <td style="width: 13.8842%; height: 23.8594px;"> </td>
                <td style="width: 10.1506%; height: 23.8594px;"> </td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;">Yang Memberi Kuasa</td>
                </tr>
                <tr style="height: 66.0781px;">
                <td style="width: 33.3333%; text-align: center; height: 66.0781px;"> </td>
                <td style="width: 9.62595%; text-align: center; height: 66.0781px;"> </td>
                <td style="width: 13.8842%; height: 66.0781px; text-align: center;"><br />
                <table style="border-collapse: collapse; width: 100.822%; height: 5px;" border="1">
                <tbody>
                <tr>
                <td style="width: 100%; text-align: center;">Materai</td>
                </tr>
                </tbody>
                </table>
                </td>
                <td style="width: 10.1506%; text-align: center; height: 66.0781px;"> </td>
                <td style="width: 32.9404%; height: 66.0781px; text-align: center;"> </td>
                </tr>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; text-align: center; height: 23.8594px;"><strong>( <span style="text-decoration: underline;">[NAma_penerima_kuasa]</span> )</strong></td>
                <td style="width: 9.62595%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 13.8842%; height: 23.8594px; text-align: center;"> </td>
                <td style="width: 10.1506%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;"><strong>( <span style="text-decoration: underline;">[NAma]</span> )</strong></td>
                </tr>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; text-align: center; height: 23.8594px;"><strong> </strong></td>
                <td style="text-align: center; width: 33.6608%; height: 23.8594px;" colspan="3">Mengetahui</td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;"><strong> </strong></td>
                </tr>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; text-align: center; height: 23.8594px;"><strong> </strong></td>
                <td style="text-align: center; width: 33.6608%; height: 23.8594px;" colspan="3">[Sebutan_kepala_desA] [Nama_desA]</td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;"><strong> </strong></td>
                </tr>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; height: 23.8594px;"> </td>
                <td style="width: 9.62595%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 13.8842%; height: 23.8594px; text-align: center;"> </td>
                <td style="width: 10.1506%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;"> </td>
                </tr>
                <tr style="height: 23.8594px;">
                <td style="width: 33.3333%; height: 23.8594px;"> </td>
                <td style="width: 9.62595%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 13.8842%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 10.1506%; text-align: center; height: 23.8594px;"> </td>
                <td style="width: 32.9404%; text-align: center; height: 23.8594px;"> </td>
                </tr>
                <tr style="height: 23.9062px;">
                <td style="width: 33.3333%; height: 23.9062px;"> </td>
                <td style="text-align: center; width: 33.6608%; height: 23.9062px;" colspan="3"><strong>[NAma_pamong]</strong></td>
                <td style="width: 32.9404%; text-align: center; height: 23.9062px;"> </td>
                </tr>
                </tbody>
                </table>
                <div style="text-align: center;"><br /><br /></div>',
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratPernyataanPenguasaanFisikBidangTanahSPORADIK($hasil, $id)
    {
        $data = [
            'nama'                => 'Pernyataan Penguasaan Fisik Bidang Tanah (SPORADIK)',
            'kode_surat'          => 'S-40',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_jalan]","nama":"Jalan","deskripsi":"Jalan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_rt_rw]","nama":"RT \/ RW","deskripsi":"RT \/ RW","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_desa_kelurahan]","nama":"Desa \/ Kelurahan","deskripsi":"Desa \/ Kelurahan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kecamatan]","nama":"Kecamatan","deskripsi":"Kecamatan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kabupaten_kota]","nama":"Kabupaten \/ Kota","deskripsi":"Kabupaten \/ Kota","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nib]","nama":"NIB","deskripsi":"NIB","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_luas_tanah_m2]","nama":"Luas Tanah (m2)","deskripsi":"Luas Tanah (m2)","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_status_tanah]","nama":"Status Tanah","deskripsi":"Status Tanah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_dipergunakan]","nama":"Dipergunakan","deskripsi":"Dipergunakan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_utara]","nama":"Batas Sebelah Utara","deskripsi":"Batas Sebelah Utara","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_timur]","nama":"Batas Sebelah Timur","deskripsi":"Batas Sebelah Timur","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_selatan]","nama":"Batas Sebelah Selatan","deskripsi":"Batas Sebelah Selatan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_batas_sebelah_barat]","nama":"Batas Sebelah Barat","deskripsi":"Batas Sebelah Barat","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tanah_diperoleh_dari_]","nama":"Tanah Diperoleh Dari ","deskripsi":"Tanah Diperoleh Dari ","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_diperoleh_sejak_tahun]","nama":"Diperoleh Sejak Tahun","deskripsi":"Sejak Tahun","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_dengan_jalan]","nama":"Dengan Jalan","deskripsi":"Dengan Jalan","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""},"data_orang_tua":"0","Saksi_I":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_II":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => null,
            'template'            => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">JUdul_surat]</span></h4>\r\n<br>\r\n<div style=\"text-align: justify;\">Yang bertanda tangan di bawah ini :</div>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 106px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Tempat &amp; Tgl. Lahir</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[UsiA]</td>\r\n</tr>\r\n<tr style=\"height: 19px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 19px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 19px;\">Pekerjaan</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 19px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 19px; text-align: justify;\">[PekerjaaN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Nomor KTP</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[NiK]</td>\r\n</tr>\r\n<tr style=\"height: 15px;\" valign=\"top\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 15px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 15px;\">Alamat</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 15px;\">:<br><br></td>\r\n<td style=\"width: 62.4754%; height: 15px; text-align: justify;\">[AlamaT] [Sebutan_desA] [Nama_desA], [Sebutan_kecamataN] [Nama_kecamataN], [Sebutan_kabupateN] [Nama_kabupateN]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: justify;\"><br>Dengan ini menyatakan bahwa saya dengan itikad baik telah menguasai sebidang tanah yang terletak :</div>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 162px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Jalan / RT</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[Form_jalaN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Dusun</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_rt_rW]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Desa/ Kelurahan</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[Form_desa_kelurahaN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Kecamatan</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_kecamataN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Kabupaten</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_kabupaten_kotA]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">NIB</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[Form_niB]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Status Tanah</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_status_tanaH]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Dipergunakan untuk</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[Form_dipergunakaN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Luas</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_luas_tanah_m2]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: justify;\"><br>Batas-Batas Tanah :</div>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 72px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Sebelah Utara</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[Form_batas_sebelah_utarA]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Sebelah Selatan</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_batas_sebelah_selataN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Sebelah Timur</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; height: 18px; text-align: justify;\">[Form_batas_sebelah_timuR]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.51866%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 31.7616%; text-align: left; height: 18px;\">Sebelah Barat</td>\r\n<td style=\"width: 1.30976%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 62.4754%; text-align: justify; height: 18px;\">[Form_batas_sebelah_baraT]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: justify;\"><br>Bidang Tanah tersebut saya peroleh dari [Form_tanah_diperoleh_dari_] tahun [Form_diperoleh_sejak_tahuN] dengan jalan [Form_jalaN] / terlampir yang sampai saat ini saya kuasai secara terus menerus dan</div>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 89.5624px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 3.0303%; height: 22.3906px; text-align: right;\">-</td>\r\n<td style=\"width: 1.77693%; text-align: left; height: 22.3906px;\"> </td>\r\n<td style=\"width: 95.1928%; height: 22.3906px; text-align: justify;\">Tidak diajukan/ menjadi jaminan hutang</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 3.0303%; height: 22.3906px; text-align: right;\">-</td>\r\n<td style=\"width: 1.77693%; text-align: left; height: 22.3906px;\"> </td>\r\n<td style=\"width: 95.1928%; height: 22.3906px; text-align: justify;\">Tidak dalam keadaan sengketa</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 3.0303%; height: 22.3906px; text-align: right;\">-</td>\r\n<td style=\"width: 1.77693%; text-align: left; height: 22.3906px;\"> </td>\r\n<td style=\"width: 95.1928%; height: 22.3906px; text-align: justify;\">Tidak merupakan tanah warisan yang belum di bagi</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 3.0303%; height: 22.3906px; text-align: right;\">-</td>\r\n<td style=\"width: 1.77693%; text-align: left; height: 22.3906px;\"> </td>\r\n<td style=\"width: 95.1928%; height: 22.3906px; text-align: justify;\">Belum bersertifikat</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 3.0303%; text-align: right;\">-</td>\r\n<td style=\"width: 1.77693%; text-align: left;\"> </td>\r\n<td style=\"width: 95.1928%; text-align: justify;\">Penggunaannya tidak pernah di ganggu gugat</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: justify;\"><br>Surat pernyataan ini saya bubuhkan cap jempol setelah saya dibacakan dan mengerti isi/ maksud, dibuat dengan sebenarnya dengan penuh tanggung jawab dan saya bersedia untuk mengangkat sumpah bila diperlukan.<br><br>Demikian dan apabila ini tidak benar, saya bersedia dituntut di hadapan pihak berwenang.<br><br>SAKSI-SAKSI</div>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 208px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; height: 18px; text-align: justify;\">[Nama_saksi_I]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Usia_saksi_I]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; height: 18px; text-align: justify;\">[Pekerjaan_saksi_I]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Alamat</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Alamat_saksi_I]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Tanda Tangan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 59.9869%; height: 18px; text-align: justify;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\">2.</td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Nama_saksi_iI]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; height: 18px; text-align: justify;\">[Usia_saksi_iI]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Pekerjaan_saksi_iI]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Alamat</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\">[Alamat_saksi_iI]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 18px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 18px;\">Tanda Tangan</td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.3222%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.92927%; text-align: left; height: 10px;\"> </td>\r\n<td style=\"width: 30.5174%; text-align: left; height: 10px;\"> </td>\r\n<td style=\"width: 1.24427%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 59.9869%; text-align: justify; height: 10px;\"> </td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 180px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">Reg No: ____________________</td>\r\n<td style=\"width: 30%; height: 18px; text-align: center;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">Tanggal : ____________________</td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">Hormat Kami,</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">Mengetahui / Membenarkan</td>\r\n<td style=\"width: 30%; height: 18px; text-align: center;\">[qr_code]</td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Sebutan_kepala_desA] [Nama_desA]</td>\r\n<td style=\"width: 30%; height: 18px; text-align: center;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 54px;\">\r\n<td style=\"width: 35%; text-align: center; height: 54px;\"> </td>\r\n<td style=\"width: 30%; height: 54px;\"> </td>\r\n<td style=\"width: 35%; height: 54px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NamA]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br><br></div>",
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function migrasi_2023070652($hasil)
    {
        DB::table('setting_aplikasi')->where('key', 'rentang_waktu_kehadiran')->update([
            'jenis'     => 'input',
            'attribute' => 'class="bilangan required" placeholder="10" min="0" type="number"',
        ]);

        return $hasil;
    }

    protected function suratKeteranganPindahPenduduk($hasil, $id)
    {
        $template = <<<HTML
                <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">[JUdul_surat]</span></h4>
                <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br><br></p>
                <p style="text-align: justify; text-indent: 30px;">Yang bertanda tangan / cap jempol di bawah ini :</p>
                <table style="border-collapse: collapse; width: 100%; height: 201.515px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 22.3906px; text-align: left;">1.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Nama Lengkap</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; height: 22.3906px; text-align: justify;"><strong>[NAma]</strong></td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 22.3906px; text-align: left;">2.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Tempat / Tanggal Lahir</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; height: 22.3906px; text-align: justify;">[TtL]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 22.3906px;">3.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Umur</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[UsiA]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 22.3906px;">4.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Kewarganegaraan</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[WArga_negara]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 22.3906px;">5.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Agama</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[AgAma]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 22.3906px; text-align: left;">6.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Jenis Kelamin</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; height: 22.3906px; text-align: justify;">[Jenis_kelamiN]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 22.3906px;">7.</td>
                <td style="width: 30.5242%; text-align: left; height: 22.3906px;">Pekerjaan</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[PeKerjaan]</td>
                </tr>
                <tr>
                <td style="width: 4.31655%; text-align: center;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left;">8.</td>
                <td style="width: 30.5242%; text-align: left;">No. KTP</td>
                <td style="width: 1.2333%; text-align: center;">:</td>
                <td style="width: 60.0206%; text-align: justify;">[NiK]</td>
                </tr>
                <tr style="height: 44.7812px;">
                <td style="width: 4.31655%; text-align: center; height: 44.7812px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 44.7812px; text-align: left;">9.<br><br></td>
                <td style="width: 30.5242%; text-align: left; height: 44.7812px;">Tempat Tinggal<br><br></td>
                <td style="width: 1.2333%; text-align: center; height: 44.7812px;">:<br><br></td>
                <td style="width: 60.0206%; height: 44.7812px; text-align: justify;">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Akan pindah dengan keterangan sebagai berikut:</p>
                <table style="border-collapse: collapse; width: 100%; height: 144px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18px; text-align: left;">10.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Alamat yang dituju</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; height: 18px; text-align: justify;">RT [Form_rt_tujuaN], RW [Form_rw_tujuaN], [Sebutan_dusun] [Form_dusun_tujuaN], [Sebutan_desa] [Form_desa_atau_kelurahan_tujuan], Kecamatan [Form_kecamatan_tujuan], [Sebutan_kabupaten] [Form_kabupaten_tujuan]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; height: 18px; text-align: left;">11.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Alasan Pindah</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; height: 18px; text-align: justify;">[Form_alasan_pindah]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 18px;">12.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Tanggal Pindah</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 18px;">[Form_tanggal_pindaH]</td>
                </tr>
                <tr style="height: 18px;">
                <td style="width: 4.31655%; text-align: center; height: 18px;">\u{a0}</td>
                <td style="width: 3.90545%; text-align: left; height: 18px;">13.</td>
                <td style="width: 30.5242%; text-align: left; height: 18px;">Jumlah Pengikut</td>
                <td style="width: 1.2333%; text-align: center; height: 18px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 18px;">[Form_jumlah_pengikuT]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;"><br>[Pengikut_pindaH]</p>
                <p style="text-align: justify; text-indent: 30px;">Surat keterangan ini diterbitkan sebagai [Form_keterangaN].</p>
                <p style="text-align: justify; text-indent: 30px;">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.</p>
                <table style="border-collapse: collapse; width: 100%;" border="0">
                <tbody>
                <tr>
                <td style="width: 35%; text-align: center;">\u{a0}</td>
                <td style="width: 30%;">\u{a0}</td>
                <td style="width: 35%; text-align: center;">[NaMa_desa], [TgL_surat]</td>
                </tr>
                <tr>
                <td style="width: 35%; text-align: center;">Pemegang Surat,</td>
                <td style="width: 30%;">\u{a0}</td>
                <td style="width: 35%; text-align: center;">[AtAs_nama]</td>
                </tr>
                <tr>
                <td style="width: 35%; text-align: center;">\u{a0}</td>
                <td style="width: 30%;"><br><br><br><br></td>
                <td style="width: 35%;">\u{a0}</td>
                </tr>
                <tr>
                <td style="width: 35%; text-align: center;">[Nama]</td>
                <td style="width: 30%;">\u{a0}</td>
                <td style="width: 35%; text-align: center;">[NaMa_pamong]</td>
                </tr>
                <tr>
                <td style="width: 35%; text-align: center;">\u{a0}</td>
                <td style="width: 30%;">\u{a0}</td>
                <td style="width: 35%; text-align: center;">[Nip_pamonG]</td>
                </tr>
                </tbody>
                </table>
                <div style="text-align: center;">\u{a0}</div>
                <div style="text-align: center;"><br>[qr_code]</div>
            HTML;
        $data = [
            'nama'                => 'Keterangan Pindah Penduduk',
            'kode_surat'          => 'S-04',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'd',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_telepon_pemohon]","nama":"Telepon Pemohon","deskripsi":"Nomor Telepon Pemohon","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-manual","kode":"[form_gunakan_format]","nama":"Gunakan Format","deskripsi":"Pilih Format Lampiran Surat","required":"1","atribut":null,"pilihan":["F-1.08 (pindah pergi)","F-1.23, F-1.25, F-1.29, F-1.34 (sesuai tujuan)","F-1.03 (pindah datang)","F-1.27, F-1.31, F-1.39 (sesuai tujuan)"],"refrensi":null},{"tipe":"select-manual","kode":"[form_jenis_permohonan]","nama":"Jenis Permohonan","deskripsi":"Pilih Jenis Permohonan","required":"0","atribut":null,"pilihan":["SURAT KETERANGAN KEPENDUDUKAN","SURAT KETERANGAN PINDAH","SURAT KETERANGAN PINDAH LUAR NEGERI (SKPLN)","SURAT KETERANGAN TEMPAT TINGGAL (SKTT)","BAGI ORANG ASING TINGGAL TERBATAS"],"refrensi":null},{"tipe":"select-manual","kode":"[form_alasan_pindah]","nama":"Alasan Pindah","deskripsi":"Pilih Alasan Pindah","required":"1","atribut":null,"pilihan":["PEKERJAAN","PENDIDIKAN","KEAMANAN","KESEHATAN","PERUMAHAN","KELUARGA","LAINNYA"],"refrensi":null},{"tipe":"select-manual","kode":"[form_klasifikasi_pindah]","nama":"Klasifikasi Pindah","deskripsi":"Pilih Klasifikasi Pindah","required":"1","atribut":null,"pilihan":["DALAM SATU DESA\/KELURAHAN","ANTAR DESA\/KELURAHAN","ANTAR KECAMATAN","ANTAR KAB\/KOTA","ANTAR PROVINSI"],"refrensi":null},{"tipe":"text","kode":"[form_alamat_tujuan]","nama":"Alamat Tujuan","deskripsi":"Alamat Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_rt_tujuan]","nama":"RT Tujuan","deskripsi":"RT Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_rw_tujuan]","nama":"RW Tujuan","deskripsi":"RW Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_dusun_tujuan]","nama":"Dusun Tujuan","deskripsi":"Dusun Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_desa_atau_kelurahan_tujuan]","nama":"Desa atau Kelurahan Tujuan","deskripsi":"Desa atau Kelurahan Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kecamatan_tujuan]","nama":"Kecamatan Tujuan","deskripsi":"Kecamatan Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kabupaten_tujuan]","nama":"Kabupaten Tujuan","deskripsi":"Kabupaten Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_provinsi_tujuan]","nama":"Provinsi Tujuan","deskripsi":"Provinsi Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kode_pos_tujuan]","nama":"Kode Pos Tujuan","deskripsi":"Kode Pos Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_telepon_tujuan]","nama":"Telepon Tujuan","deskripsi":"Telepon Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-manual","kode":"[form_jenis_kepindahan]","nama":"Jenis Kepindahan","deskripsi":"Pilih Jenis Kepindahan","required":"1","atribut":null,"pilihan":["KEP. KELUARGA","KEP. KELUARGA DAN SELURUH ANGG. KELUARGA","KEP. KELUARGA DAN SBG. ANGG. KELUARGA","ANGG. KELUARGA"],"refrensi":null},{"tipe":"select-manual","kode":"[form_status_kk_bagi_yang_tidak_pindah]","nama":"Status KK Bagi Yang Tidak Pindah","deskripsi":"Pilih Status KK Bagi Yang Tidak Pindah","required":"1","atribut":null,"pilihan":["NUMPANG KK","MEMBUAT KK BARU","TIDAK ADA ANGG. KELUARGA YANG DITINGGAL","NOMOR KK TETAP"],"refrensi":null},{"tipe":"select-manual","kode":"[form_status_kk_bagi_yang_pindah]","nama":"Status KK Bagi Yang Pindah","deskripsi":"Pilih Status KK Bagi Yang Pindah","required":"1","atribut":null,"pilihan":["NUMPANG KK","MEMBUAT KK BARU","NOMOR KK TETAP"],"refrensi":null},{"tipe":"text","kode":"[form_negara_tujuan]","nama":"Negara Tujuan","deskripsi":"Negara Tujuan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kode_negara]","nama":"Kode Negara","deskripsi":"Kode Negara","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_alamat_tujuan_luar_negeri]","nama":"Alamat Tujuan (Luar Negeri)","deskripsi":"Alamat Tujuan (Luar Negeri)","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_penanggung_jawab]","nama":"Penanggung Jawab","deskripsi":"Penanggung Jawab","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_sponsor]","nama":"Nama Sponsor","deskripsi":"Nama Sponsor","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-manual","kode":"[form_tipe_sponsor]","nama":"Tipe Sponsor","deskripsi":"Pilih Tipe Sponsor","required":"0","atribut":null,"pilihan":["ORGANISASI INTERNASIONAL","PERORANGAN","PEMERINTAH","TANPA SPONSOR","PERUSAHAAN"],"refrensi":null},{"tipe":"text","kode":"[form_alamat_sponsor]","nama":"Alamat Sponsor","deskripsi":"Alamat Sponsor","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nomor_itas_&_itap]","nama":"Nomor ITAS & ITAP","deskripsi":"Nomor ITAS & ITAP","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_itas_&_itap]","nama":"Tanggal ITAS & ITAP","deskripsi":"Tanggal ITAS & ITAP","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_pindah]","nama":"Tanggal Pindah","deskripsi":"Tanggal Pindah","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Keterangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_jumlah_pengikut]","nama":"Jumlah Pengikut","deskripsi":"Jumlah Pengikut","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""},"data_orang_tua":"0","data_pasangan":"0"}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'lampiran'            => 'F-1.03,F-1.08,F-1.25,F-1.27',
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function suratKeteranganKelahiran($hasil, $id)
    {
        $template = "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>
        <p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br><br></p>
        <p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan
            [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa
            :</p>
        <table style=\"border-collapse: collapse; width: 100%; height: 156.771px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; height: 22.3958px; text-align: left;\">1.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Hari</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; height: 22.3958px; text-align: justify;\">[Hari_kelahiranN]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; text-align: left; height: 22.3958px;\">2.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Tanggal</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; text-align: justify; height: 22.3958px;\">[Tanggal_kelahiranN]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; height: 22.3958px; text-align: left;\">3.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Pukul</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; height: 22.3958px; text-align: justify;\">[Jam_kelahiranN]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; height: 22.3958px; text-align: left;\">4.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Tempat</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; height: 22.3958px; text-align: justify;\">[Tempat_dilahirkanN]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; text-align: left; height: 22.3958px;\">5.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Telah lahir seorang anak</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; text-align: justify; height: 22.3958px;\">[NamA]</td>
                </tr>
            </tbody>
        </table>
        <p style=\"text-align: justify; text-indent: 30px;\">Dari seorang ibu:</p>
        <table style=\"border-collapse: collapse; width: 100%; height: 63px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
                <tr style=\"height: 22px;\">
                    <td style=\"width: 4.3222%; text-align: center; height: 22px;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; height: 22px; text-align: left;\">6.</td>
                    <td style=\"width: 30.5174%; text-align: left; height: 22px;\">Nama Lengkap</td>
                    <td style=\"width: 1.24427%; text-align: center; height: 22px;\">:</td>
                    <td style=\"width: 60.0524%; height: 22px; text-align: justify;\"><strong>[Nama_ibU]</strong></td>
                </tr>
                <tr style=\"height: 19px;\">
                    <td style=\"width: 4.3222%; text-align: center; height: 19px;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; text-align: left; height: 19px;\">7.</td>
                    <td style=\"width: 30.5174%; text-align: left; height: 19px;\">NIK / No KTP</td>
                    <td style=\"width: 1.24427%; text-align: center; height: 19px;\">:</td>
                    <td style=\"width: 60.0524%; text-align: justify; height: 19px;\">[Nik_ibU]</td>
                </tr>
                <tr style=\"height: 22px;\">
                    <td style=\"width: 4.3222%; text-align: center; height: 22px;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; height: 22px; text-align: left;\">8.</td>
                    <td style=\"width: 30.5174%; text-align: left; height: 22px;\">Umur</td>
                    <td style=\"width: 1.24427%; text-align: center; height: 22px;\">:</td>
                    <td style=\"width: 60.0524%; height: 22px; text-align: justify;\">[Usia_ibU]</td>
                </tr>
                <tr>
                    <td style=\"width: 4.3222%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; text-align: left;\">9.</td>
                    <td style=\"width: 30.5174%; text-align: left;\">Pekerjaan</td>
                    <td style=\"width: 1.24427%; text-align: center;\">:</td>
                    <td style=\"width: 60.0524%; text-align: justify;\">[Pekerjaan_ibU]</td>
                </tr>
                <tr>
                    <td style=\"width: 4.3222%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; text-align: left;\">10.</td>
                    <td style=\"width: 30.5174%; text-align: left;\">Alamat/Tempat Tinggal</td>
                    <td style=\"width: 1.24427%; text-align: center;\">:</td>
                    <td style=\"width: 60.0524%; text-align: justify;\">[Alamat_ibU]</td>
                </tr>
            </tbody>
        </table>
        <p style=\"text-align: justify; text-indent: 30px;\">Istri dari:</p>
        <table style=\"border-collapse: collapse; width: 100%; height: 63px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
                <tr style=\"height: 22px;\">
                    <td style=\"width: 4.3222%; text-align: center; height: 22px;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; height: 22px; text-align: left;\">11.</td>
                    <td style=\"width: 30.5174%; text-align: left; height: 22px;\">Nama Lengkap</td>
                    <td style=\"width: 1.24427%; text-align: center; height: 22px;\">:</td>
                    <td style=\"width: 60.0524%; height: 22px; text-align: justify;\"><strong>[Nama_ayaH]</strong></td>
                </tr>
                <tr style=\"height: 19px;\">
                    <td style=\"width: 4.3222%; text-align: center; height: 19px;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; text-align: left; height: 19px;\">12.</td>
                    <td style=\"width: 30.5174%; text-align: left; height: 19px;\">NIK / No KTP</td>
                    <td style=\"width: 1.24427%; text-align: center; height: 19px;\">:</td>
                    <td style=\"width: 60.0524%; text-align: justify; height: 19px;\">[Nik_ayaH]</td>
                </tr>
                <tr style=\"height: 22px;\">
                    <td style=\"width: 4.3222%; text-align: center; height: 22px;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; height: 22px; text-align: left;\">13.</td>
                    <td style=\"width: 30.5174%; text-align: left; height: 22px;\">Umur</td>
                    <td style=\"width: 1.24427%; text-align: center; height: 22px;\">:</td>
                    <td style=\"width: 60.0524%; height: 22px; text-align: justify;\">[Usia_ayaH]</td>
                </tr>
                <tr>
                    <td style=\"width: 4.3222%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; text-align: left;\">14.</td>
                    <td style=\"width: 30.5174%; text-align: left;\">Pekerjaan</td>
                    <td style=\"width: 1.24427%; text-align: center;\">:</td>
                    <td style=\"width: 60.0524%; text-align: justify;\">[Pekerjaan_ayaH]</td>
                </tr>
                <tr>
                    <td style=\"width: 4.3222%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 3.92927%; text-align: left;\">15.</td>
                    <td style=\"width: 30.5174%; text-align: left;\">Alamat/Tempat Tinggal</td>
                    <td style=\"width: 1.24427%; text-align: center;\">:</td>
                    <td style=\"width: 60.0524%; text-align: justify;\">[Alamat_ayaH]</td>
                </tr>
            </tbody>
        </table>
        <p style=\"text-align: justify; text-indent: 30px;\">Surat keterangan ini dibuat berdasarkan keterangan pelapor:</p>
        <table style=\"border-collapse: collapse; width: 100%; height: 134.375px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tbody>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; height: 22.3958px; text-align: left;\">16.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Nama Lengkap</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; height: 22.3958px; text-align: justify;\"><strong>[NAma_pelapor]</strong></td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; text-align: left; height: 22.3958px;\">17.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">NIK</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; text-align: justify; height: 22.3958px;\">[Nik_pelapoR]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; height: 22.3958px; text-align: left;\">18.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Umur</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; height: 22.3958px; text-align: justify;\">[Usia_pelapoR]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; text-align: left; height: 22.3958px;\">19.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Pekerjaan</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; text-align: justify; height: 22.3958px;\">[Pekerjaan_pelapoR]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; text-align: left; height: 22.3958px;\">20.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Alamat / Tempat Tinggal</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; text-align: justify; height: 22.3958px;\">[Alamat_pelapoR]</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"width: 4.31655%; text-align: center; height: 22.3958px;\">\u{a0}</td>
                    <td style=\"width: 3.90545%; text-align: left; height: 22.3958px;\">21.</td>
                    <td style=\"width: 30.5242%; text-align: left; height: 22.3958px;\">Hubungan pelapor dengan bayi</td>
                    <td style=\"width: 1.2333%; text-align: center; height: 22.3958px;\">:</td>
                    <td style=\"width: 60.0206%; text-align: justify; height: 22.3958px;\">[Form_hubungan_pelapor_dengan_bayI]
                    </td>
                </tr>
            </tbody>
        </table>
        <p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat
            dipergunakan sebagaimana mestinya.<br><br></p>
        <table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">
            <tbody>
                <tr>
                    <td style=\"width: 35%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 30%;\">\u{a0}</td>
                    <td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>
                </tr>
                <tr>
                    <td style=\"width: 35%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 30%;\">\u{a0}</td>
                    <td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>
                </tr>
                <tr>
                    <td style=\"width: 35%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 30%;\"><br><br><br><br></td>
                    <td style=\"width: 35%;\">\u{a0}</td>
                </tr>
                <tr>
                    <td style=\"width: 35%; text-align: center;\">\u{a0}</td>
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
        <div>\u{a0}</div>
        <div>\u{a0}</div>
        <div>\u{a0}</div>
        <div>\u{a0}</div>
        <div>\u{a0}</div>
        <div>\u{a0}</div>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 50.5832px;\" border=\"0\">
            <tbody>
                <tr style=\"height: 10.3958;\">
                    <td style=\"width: 8.50757%;\">Perihal :</td>
                    <td style=\"text-align: left; width: 40.6481%;\">Permohonan Penyelesaian</td>
                    <td style=\"width: 4.91468%;\">\u{a0}</td>
                    <td style=\"text-align: left; width: 45.9409%;\"><strong>Kepada</strong></td>
                </tr>
                <tr style=\"height: 10.3958;\">
                    <td style=\"width: 8.50757%;\">\u{a0}</td>
                    <td style=\"text-align: left; width: 40.6481%;\">Akta Kelahiran</td>
                    <td style=\"width: 4.91468%;\"><strong>Yth,</strong></td>
                    <td style=\"text-align: left; width: 45.9409%;\"><strong>Kepala Dinas Kependudukan dan Pencatatan</strong>
                    </td>
                </tr>
                <tr style=\"height: 10.3958;\">
                    <td style=\"width: 8.50757%;\">\u{a0}</td>
                    <td style=\"text-align: left; width: 40.6481%;\">\u{a0}</td>
                    <td style=\"width: 4.91468%;\">\u{a0}</td>
                    <td style=\"text-align: left; width: 45.9409%;\"><strong>Sipil [Sebutan_kabupateN] [Nama_kabupateN]</strong>
                    </td>
                </tr>
                <tr style=\"height: 10.3958;\">
                    <td style=\"width: 8.50757%;\">\u{a0}</td>
                    <td style=\"text-align: left; width: 40.6481%;\">\u{a0}</td>
                    <td style=\"width: 4.91468%;\">\u{a0}</td>
                    <td style=\"text-align: left; width: 45.9409%;\"><strong>Di [Nama_kabupateN]</strong></td>
                </tr>
            </tbody>
        </table>
        <div style=\"text-align: justify;\">Yang bertanda tangan di bawah ini :</div>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 134.375px;\" border=\"0\" cellspacing=\"0\"
            cellpadding=\"0\">
            <tbody>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.274%; text-align: left; height: 14.3958px;\">NIK</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.3192%; height: 14.3958px; text-align: justify;\">[Nik_pelapoR]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.274%; text-align: left; height: 14.3958px;\">Nama Lengkap</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.3192%; text-align: justify; height: 14.3958px;\"><strong>[NAma_pelapor]</strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.274%; text-align: left; height: 14.3958px;\">Tempat tanggal lahir / umur</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.3192%; height: 14.3958px; text-align: justify;\">[Tempat_tgl_lahir_pelapoR] /
                        [Usia_pelapoR]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.274%; text-align: left; height: 14.3958px;\">Pekerjaan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.3192%; text-align: justify; height: 14.3958px;\">[Pekerjaan_pelapoR]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.274%; text-align: left; height: 14.3958px;\">Alamat / Tempat Tinggal</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.3192%; text-align: justify; height: 14.3958px;\">[Alamat_pelapoR]</td>
                </tr>
            </tbody>
        </table>
        <div style=\"text-align: left;\"><strong>Mohon dicatatkan kelahiran dalam register Akta Kelahiran dan diterbitkan kutipan
                Akta Kelahiran :</strong></div>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 77.1875px; border: 1px solid rgb(0, 0, 0);\"
            border=\"1\">
            <colgroup>
                <col style=\"width: 3.87567%;\">
                <col style=\"width: 24.861%;\">
                <col style=\"width: 21.2689%;\">
                <col style=\"width: 16.637%;\">
                <col style=\"width: 16.637%;\">
                <col style=\"width: 16.637%;\">
            </colgroup>
            <tbody>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>No</strong></td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>Nama / Jenis Kelamin</strong></td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>Tempat Lahir</strong></td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>Tanggal Lahir</strong></td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>Anak Ke</strong></td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>No dan Tanggal Pendaftaran</strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\">1.</td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\"><strong>[NamA] </strong>/ [Jenis_kelamiN]</td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\">[Tempat_dilahirkanN]</td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\">[Tanggal_kelahiranN]</td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\">[Kelahiran_anaK]</td>
                    <td style=\"height: 14.3958px; border-color: rgb(0, 0, 0);\">\u{a0}</td>
                </tr>
            </tbody>
        </table>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 134.375px;\" border=\"0\" cellspacing=\"0\"
            cellpadding=\"0\">
            <tbody>
                <tr>
                    <td style=\"width: 33.1795%; text-align: left;\"><strong>Dari seorang ibu :</strong></td>
                    <td style=\"width: 1.41793%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 65.4137%; text-align: justify;\"><strong> </strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Nama Lengkap</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\"><strong>[Nama_ibU]</strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">NIK / No KTP</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Nik_ibU]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Tempat dan tanggal lahir / Umur</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\">[Tempat_tgl_lahir_ibU] / [Usia_ibU]
                    </td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Kewarganegaraan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Warga_negara_ibU]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Pekerjaan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Pekerjaan_ibU]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Alamat/Tempat Tinggal</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Alamat_ibU]</td>
                </tr>
            </tbody>
        </table>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 111.979px;\" border=\"0\" cellspacing=\"0\"
            cellpadding=\"0\">
            <tbody>
                <tr>
                    <td style=\"width: 33.1795%; text-align: left;\"><strong>Istri dari :</strong></td>
                    <td style=\"width: 1.41793%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 65.4137%; text-align: justify;\"><strong> </strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Nama Lengkap</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\"><strong>[Nama_ayaH]</strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">NIK / No KTP</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Nik_ayaH]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Tempat dan tanggal lahir / Umur</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\">[Tempat_tgl_lahir_ayaH] / [Usia_ayaH]
                    </td>
                </tr>
                <tr>
                    <td style=\"width: 33.1795%; text-align: left;\">Kewarganegaraan</td>
                    <td style=\"width: 1.41793%; text-align: center;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify;\">[Warga_negara_ayaH]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Pekerjaan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Pekerjaan_ayaH]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Alamat/Tempat Tinggal</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Alamat_ayaH]</td>
                </tr>
            </tbody>
        </table>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 134.375px;\" border=\"0\" cellspacing=\"0\"
            cellpadding=\"0\">
            <tbody>
                <tr>
                    <td style=\"width: 33.1795%; text-align: left;\"><strong>Saksi 1:</strong></td>
                    <td style=\"width: 1.41793%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 65.4137%; text-align: justify;\"><strong> </strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Nama Lengkap</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\"><strong>[Nama_saksi_I]</strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">NIK / No KTP</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Nik_saksi_I]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Tempat dan tanggal lahir / Umur</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\">[Tempat_tgl_lahir_saksi_I] /
                        [Usia_saksi_I]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Kewarganegaraan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Warga_negara_saksi_I]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Pekerjaan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Pekerjaan_saksi_I]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Alamat/Tempat Tinggal</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Alamat_saksi_I]</td>
                </tr>
            </tbody>
        </table>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 134.375px;\" border=\"0\" cellspacing=\"0\"
            cellpadding=\"0\">
            <tbody>
                <tr>
                    <td style=\"width: 33.1795%; text-align: left;\"><strong>Saksi 2:</strong></td>
                    <td style=\"width: 1.41793%; text-align: center;\">\u{a0}</td>
                    <td style=\"width: 65.4137%; text-align: justify;\"><strong> </strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Nama Lengkap</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\"><strong>[Nama_saksi_iI]</strong></td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">NIK / No KTP</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Nik_saksi_iI]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Tempat dan tanggal lahir / Umur</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; height: 14.3958px; text-align: justify;\">[Tempat_tgl_lahir_saksi_iI] /
                        [Usia_saksi_iI]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Kewarganegaraan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Warga_negara_saksi_iI]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Pekerjaan</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Pekerjaan_saksi_iI]</td>
                </tr>
                <tr style=\"height: 14.3958px;\">
                    <td style=\"width: 33.1795%; text-align: left; height: 14.3958px;\">Alamat/Tempat Tinggal</td>
                    <td style=\"width: 1.41793%; text-align: center; height: 14.3958px;\">:</td>
                    <td style=\"width: 65.4137%; text-align: justify; height: 14.3958px;\">[Alamat_saksi_iI]</td>
                </tr>
            </tbody>
        </table>
        <div style=\"text-align: center;\">\u{a0}</div>
        <table style=\"width: 100%; height: 43.979px;\">
            <tbody>
                <tr style=\"height: 8.3958px;\">
                    <td style=\"width: 94.4397%; height: 8.3958px;\"><span style=\"font-size: 10px;\"><strong>Terlampir persyaratan
                                sebagai berikut :</strong></span></td>
                </tr>
                <tr style=\"height: 8.3958px;\">
                    <td>
                        <div style=\"font-size: 10px;\">1. Surat keterangan kelahiran dari penolong atau dari Desa/Lurah</div>
                        <div style=\"font-size: 10px;\">2. Fotocopy KTP dan kartu keluarga orang tua</div>
                        <div style=\"font-size: 10px;\">3. Fotocopy Buku Nikah / akta perkawinan orang tua yang telah dilegalisir
                            (bagi yang memiliki)</div>
                        <div style=\"font-size: 10px;\">4. Fotocopy KTP 2 orang saksi yang masih berlaku</div>
                        <div style=\"font-size: 10px;\">5. Fotocopy Ijazah / Raport (bagi yang memiliki)</div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style=\"border-collapse: collapse; width: 99.9875%; height: 99.5833px;\">
            <colgroup>
                <col style=\"width: 32.8014%;\">
                <col style=\"width: 33.9357%;\">
                <col style=\"width: 33.274%;\">
            </colgroup>
            <tbody>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"height: 22.3958px;\">\u{a0}</td>
                    <td style=\"height: 22.3958px;\">\u{a0}</td>
                    <td style=\"text-align: center; height: 22.3958px;\">[NaMa_desa], [TgL_surat]</td>
                </tr>
                <tr style=\"height: 10px;\">
                    <td style=\"height: 54.7917px;\" rowspan=\"2\">
                        <table style=\"border-collapse: collapse; width: 100.415%; border: 1px solid rgb(0, 0, 0);\" border=\"1\">
                            <colgroup>
                                <col style=\"width: 33.6245%;\">
                                <col style=\"width: 66.2547%;\">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td style=\"text-align: center; border-color: rgb(0, 0, 0);\" rowspan=\"2\">
                                        <strong>Verifikasi</strong></td>
                                    <td style=\"text-align: left; border-color: rgb(0, 0, 0);\"><strong>Kasi</strong></td>
                                </tr>
                                <tr>
                                    <td style=\"text-align: left; border-color: rgb(0, 0, 0);\"><strong>Kabid</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style=\"height: 54.7917px;\" rowspan=\"2\">\u{a0}</td>
                    <td style=\"text-align: center; height: 10px;\">Pelapor</td>
                </tr>
                <tr style=\"height: 44.7917px;\">
                    <td style=\"height: 44.7917px;\">\u{a0}</td>
                </tr>
                <tr style=\"height: 22.3958px;\">
                    <td style=\"height: 22.3958px;\">\u{a0}</td>
                    <td style=\"height: 22.3958px;\">\u{a0}</td>
                    <td style=\"text-align: center; height: 22.3958px;\"><strong>[Nama_pelapoR]</strong></td>
                </tr>
            </tbody>
        </table>
        <div style=\"text-align: center;\">\u{a0}</div>";
        $data = [
            'nama'                => 'Keterangan Kelahiran',
            'kode_surat'          => 'S-17',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"kategori":"Pelapor","tipe":"text","kode":"[form_hubungan_pelapor_dengan_bayi]","nama":"Hubungan Pelapor dengan Bayi","deskripsi":"Hubungan Pelapor dengan Bayi","required":"0","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"1","kk_level":""},"data_orang_tua":"1","data_pasangan":"0","Pelapor":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_I":{"data":1,"sex":"","status_dasar":null,"kk_level":""},"Saksi_II":{"data":1,"sex":"","status_dasar":null,"kk_level":""}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3'],
            'lampiran'            => 'F-2.01',
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    protected function migrasi_2023070653($hasil)
    {
        return $this->db->query('ALTER TABLE login_attempts MODIFY COLUMN username VARCHAR(100) NOT NULL');
    }

    protected function migrasi_2023072451($hasil, $config_id)
    {
        $hasil = $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Program Bantuan',
            'slug'       => 'program-bantuan',
            'url'        => 'program_bantuan/clear',
            'aktif'      => 1,
            'ikon'       => 'fa-heart',
            'urut'       => 101,
            'level'      => 0,
            'hidden'     => 2,
            'ikon_kecil' => 'fa-heart',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'bantuan'])->row()->id,
        ]);

        return $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Peserta Bantuan',
            'slug'       => 'peserta-bantuan',
            'url'        => 'peserta_bantuan',
            'aktif'      => 1,
            'ikon'       => 'fa-picture-o',
            'urut'       => 102,
            'level'      => 0,
            'hidden'     => 2,
            'ikon_kecil' => 'fa-picture-o',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'bantuan'])->row()->id,
        ]);
    }

    protected function suratKeteranganNikah($hasil, $id)
    {
        $template = <<<HTML
                <h4 style="margin: 0; text-align: center;"><span style="text-decoration: underline;">SURAT PENGANTAR NUMPANG NIKAH</span></h4>
                <p style="margin: 0; text-align: center;">Nomor : [Format_nomor_suraT]<br><br></p>
                <p style="text-align: justify; text-indent: 30px;">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>
                <table style="border-collapse: collapse; width: 100%; height: 231.297px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; height: 22.3906px; text-align: left;">1.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Nama Lengkap</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; height: 22.3906px; text-align: justify;"><strong>[NAma]</strong></td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; height: 22.3906px; text-align: left;">2.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Tempat / Tanggal Lahir</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; height: 22.3906px; text-align: justify;">[TtL]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; height: 22.3906px; text-align: left;">3.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Jenis Kelamin</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; height: 22.3906px; text-align: justify;">[Jenis_kelamin]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; text-align: left; height: 22.3906px;">4.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Surat bukti diri KTP</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[NiK]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; text-align: left; height: 22.3906px;">5.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Warga Negara</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[WArga_negara]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; text-align: left; height: 22.3906px;">6.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Agama</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[AgAma]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; text-align: left; height: 22.3906px;">7.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Status Perkawinan</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[FoRm_status_kawin]</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 4.31655%; text-align: center; height: 22.3906px;">\u{a0}</td>
                <td style="width: 3.90429%; text-align: left; height: 22.3906px;">8.</td>
                <td style="width: 30.5253%; text-align: left; height: 22.3906px;">Pekerjaan</td>
                <td style="width: 1.2333%; text-align: center; height: 22.3906px;">:</td>
                <td style="width: 60.0206%; text-align: justify; height: 22.3906px;">[PeKerjaan]</td>
                </tr>
                <tr style="height: 29.7812px;">
                <td style="width: 4.31655%; text-align: center; height: 29.7812px;">\u{a0}</td>
                <td style="width: 3.90429%; height: 29.7812px; text-align: left;">9.<br><br></td>
                <td style="width: 30.5253%; text-align: left; height: 29.7812px;">Alamat / Tempat Tinggal<br><br></td>
                <td style="width: 1.2333%; text-align: center; height: 29.7812px;">:<br><br></td>
                <td style="width: 60.0206%; height: 29.7812px; text-align: justify;">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>
                </tr>
                </tbody>
                </table>
                <p style="text-align: justify; text-indent: 30px;">Nama tersebut di atas betul bermaksud akan menumpang Nikah / Kawin di :\u{a0}<strong>RT [Form_rt_kua_tujuaN] RW [Form_rw_kua_tujuaN] [FoRm_dusun_kua_tujuan] Desa [FoRm_desakelurahan_kua_tujuan] Kecamatan [FoRm_kecamatan_kua_tujuan] Kabupaten [FoRm_kabupaten_kua_tujuan]</strong></p>
                <p style="text-align: justify; text-indent: 30px;">Kepada seorang [Jenis_kelamin_pasangaN] yang bernama :</p>
                <p style="text-align: justify; text-indent: 30px;"><strong>[FOrm_nama_calon_pasangan]\u{a0}</strong>[Bin_pasangaN]\u{a0}<strong>[FOrm_nama_ayah_calon_pasangan]</strong></p>
                <p style="text-align: justify; text-indent: 30px;">Demikian Surat ini dibuat, untuk dipergunakan sebagaimana mestinya.</p>
                <table style="border-collapse: collapse; width: 100%; height: 178.5px;" border="0">
                <tbody>
                <tr style="height: 21.7656px;">
                <td style="width: 35.0099%; text-align: center; height: 21.7656px;">
                <table style="border-collapse: collapse; width: 100.353%; height: 44.7812px; border-width: 1px;" border="0"><colgroup><col style="width: 15.9175%;"><col style="width: 6.59994%;"><col style="width: 77.4522%;"></colgroup>
                <tbody>
                <tr style="height: 22.3906px;">
                <td style="height: 22.3906px; text-align: left;">Nomor</td>
                <td style="height: 22.3906px;">:</td>
                <td style="height: 22.3906px; text-align: left;">..................................................................................</td>
                </tr>
                </tbody>
                </table>
                </td>
                <td style="width: 29.9801%; height: 21.7656px;">\u{a0}</td>
                <td style="width: 35.0099%; text-align: center; height: 21.7656px;">[NaMa_desa], [TgL_surat]</td>
                </tr>
                <tr>
                <td style="width: 35.0099%; text-align: center;">
                <table style="border-collapse: collapse; width: 100.156%; border-width: 1px;" border="0"><colgroup><col style="width: 15.7277%;"><col style="width: 6.99119%;"><col style="width: 77.2781%;"></colgroup>
                <tbody>
                <tr style="height: 22.3906px;">
                <td style="height: 22.3906px; text-align: left;">Tanggal</td>
                <td style="height: 22.3906px;">:</td>
                <td style="height: 22.3906px; text-align: left;">..................................................................................</td>
                </tr>
                </tbody>
                </table>
                </td>
                <td style="width: 29.9801%;">\u{a0}</td>
                <td style="width: 35.0099%; text-align: center;">\u{a0}</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 35.0099%; text-align: center; height: 22.3906px;">Mengetahui,</td>
                <td style="width: 29.9801%; height: 22.3906px;">\u{a0}</td>
                <td style="width: 35.0099%; text-align: center; height: 22.3906px;">\u{a0}</td>
                </tr>
                <tr>
                <td style="width: 35.0099%; text-align: center;">[SeButan_camat] [NaMa_kecamatan]</td>
                <td style="width: 29.9801%;">\u{a0}</td>
                <td style="width: 35.0099%; text-align: center;">[Atas_namA]</td>
                </tr>
                <tr style="height: 89.5625px;">
                <td style="width: 35.0099%; text-align: center; height: 89.5625px;">\u{a0}</td>
                <td style="width: 29.9801%; height: 89.5625px;"><br><br><br><br></td>
                <td style="width: 35.0099%; height: 89.5625px;">\u{a0}</td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 35.0099%; text-align: center; height: 22.3906px;"><strong>[NaMa_kepala_camat]</strong></td>
                <td style="width: 29.9801%; height: 22.3906px;">\u{a0}</td>
                <td style="width: 35.0099%; text-align: center; height: 22.3906px;"><strong>[Nama_pamonG]</strong></td>
                </tr>
                <tr style="height: 22.3906px;">
                <td style="width: 35.0099%; height: 22.3906px; text-align: center;"><strong>....................................................................................</strong></td>
                <td style="width: 29.9801%; height: 22.3906px;">\u{a0}</td>
                <td style="width: 35.0099%; text-align: center; height: 22.3906px;"><strong>[SEbutan_nip_desa] : [nip_pamong]</strong></td>
                </tr>
                </tbody>
                </table>
                <div style="text-align: center;"><br>[qr_code]</div>
            HTML;

        $data = [
            'nama'                => 'Keterangan Nikah',
            'kode_surat'          => 'S-23',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"select-manual","kode":"[form_status_kawin]","nama":"Status Kawin","deskripsi":"Pilih Status Kawin","required":"1","atribut":null,"pilihan":["Jejaka","Duda","Beristri","Perawan","Janda","Bersuami"],"refrensi":null},{"tipe":"number","kode":"[form_jumlah_pasangan_terdahulu]","nama":"Jumlah Pasangan Terdahulu","deskripsi":"Jumlah Pasangan Terdahulu","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pasangan_terdahulu]","nama":"Nama Pasangan Terdahulu","deskripsi":"Nama Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_binbinti_pasangan_terdahulu]","nama":"Bin\/Binti Pasangan Terdahulu","deskripsi":"Bin\/Binti Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_ktp_pasangan_terdahulu]","nama":"No KTP Pasangan Terdahulu","deskripsi":"No KTP Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir_pasangan_terdahulu]","nama":"Tempat Lahir Pasangan Terdahulu","deskripsi":"Tempat Lahir Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_pasangan_terdahulu]","nama":"Tanggal Lahir Pasangan Terdahulu","deskripsi":"Tanggal Lahir Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_warga_negara_pasangan_terdahulu]","nama":"Warga Negara Pasangan Terdahulu","deskripsi":"Pilih Warga Negara Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"tipe":"select-otomatis","kode":"[form_agama_pasangan_terdahulu]","nama":"Agama Pasangan Terdahulu","deskripsi":"Pilih Agama Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan_pasangan_terdahulu]","nama":"Pekerjaan Pasangan Terdahulu","deskripsi":"Pilih Pekerjaan Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"text","kode":"[form_tempat_tinggal_pasangan_terdahulu]","nama":"Tempat Tinggal Pasangan Terdahulu","deskripsi":"Tempat Tinggal Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_meninggal_pasangan_terdahulu]","nama":"Tanggal Meninggal Pasangan Terdahulu","deskripsi":"Tanggal Meninggal Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_meninggal_pasangan_terdahulu]","nama":"Tempat Meninggal Pasangan Terdahulu","deskripsi":"Tempat Meninggal Pasangan Terdahulu","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_calon_pasangan]","nama":"Nama Calon Pasangan","deskripsi":"Nama Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_ktp_calon_pasangan]","nama":"No KTP Calon Pasangan","deskripsi":"No KTP Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir_calon_pasangan]","nama":"Tempat Lahir Calon Pasangan","deskripsi":"Tempat Lahir Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_calon_pasangan]","nama":"Tanggal Lahir Calon Pasangan","deskripsi":"Tanggal Lahir Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_warga_negara_calon_pasangan]","nama":"Warga Negara Calon Pasangan","deskripsi":"Pilih Warga Negara Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"tipe":"select-otomatis","kode":"[form_agama_calon_pasangan]","nama":"Agama Calon Pasangan","deskripsi":"Pilih Agama Pasangan Calon","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan_calon_pasangan]","nama":"Pekerjaan Calon Pasangan","deskripsi":"Pilih Pekerjaan Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"select-otomatis","kode":"[form_pendidikan_calon_pasangan]","nama":"Pendidikan Calon Pasangan","deskripsi":"Pilih Pendidikan Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pendidikan_kk"},{"tipe":"text","kode":"[form_tempat_tinggal_calon_pasangan]","nama":"Tempat Tinggal Calon Pasangan","deskripsi":"Tempat Tinggal Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-manual","kode":"[form_status_kawin_calon_pasangan]","nama":"Status Kawin Calon Pasangan","deskripsi":"Pilih Status Kawin Calon Pasangan","required":"1","atribut":null,"pilihan":["Jejaka","Duda","Beristri","Perawan","Janda","Bersuami"],"refrensi":null},{"tipe":"number","kode":"[form_jumlah_pasangan_terdahulu_calon_pasangan]","nama":"Jumlah Pasangan Terdahulu Calon Pasangan","deskripsi":"Jumlah Pasangan Terdahulu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_ayah_calon_pasangan]","nama":"Nama Ayah Calon Pasangan","deskripsi":"Nama Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_bin_ayah_calon_pasangan]","nama":"Bin Ayah Calon Pasangan","deskripsi":"Bin Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_ktp_ayah_calon_pasangan]","nama":"No KTP Ayah Calon Pasangan","deskripsi":"No KTP Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir_ayah_calon_pasangan]","nama":"Tempat Lahir Ayah Calon Pasangan","deskripsi":"Tempat Lahir Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_ayah_calon_pasangan]","nama":"Tanggal Lahir Ayah Calon Pasangan","deskripsi":"Tanggal Lahir Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_warga_negara_ayah_calon_pasangan]","nama":"Warga Negara Ayah Calon Pasangan","deskripsi":"Pilih Warga Negara Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"tipe":"select-otomatis","kode":"[form_agama_ayah_calon_pasangan]","nama":"Agama Ayah Calon Pasangan","deskripsi":"Pilih Agama Ayah Pasangan Calon","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan_ayah_calon_pasangan]","nama":"Pekerjaan Ayah Calon Pasangan","deskripsi":"Pilih Pekerjaan Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"text","kode":"[form_tempat_tinggal_ayah_calon_pasangan]","nama":"Tempat Tinggal Ayah Calon Pasangan","deskripsi":"Tempat Tinggal Ayah Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_ibu_calon_pasangan]","nama":"Nama Ibu Calon Pasangan","deskripsi":"Nama Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_binti_ibu_calon_pasangan]","nama":"Binti Ibu Calon Pasangan","deskripsi":"Binti Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_ktp_ibu_calon_pasangan]","nama":"No KTP Ibu Calon Pasangan","deskripsi":"No KTP Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir_ibu_calon_pasangan]","nama":"Tempat Lahir Ibu Calon Pasangan","deskripsi":"Tempat Lahir Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_ibu_calon_pasangan]","nama":"Tanggal Lahir Ibu Calon Pasangan","deskripsi":"Tanggal Lahir Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_warga_negara_ibu_calon_pasangan]","nama":"Warga Negara Ibu Calon Pasangan","deskripsi":"Pilih Warga Negara Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"tipe":"select-otomatis","kode":"[form_agama_ibu_calon_pasangan]","nama":"Agama Ibu Calon Pasangan","deskripsi":"Pilih Agama Ibu Pasangan Calon","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan_ibu_calon_pasangan]","nama":"Pekerjaan Ibu Calon Pasangan","deskripsi":"Pilih Pekerjaan Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"text","kode":"[form_tempat_tinggal_ibu_calon_pasangan]","nama":"Tempat Tinggal Ibu Calon Pasangan","deskripsi":"Tempat Tinggal Ibu Calon Pasangan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_pasangan_terdahulu_calon_pasangan]","nama":"Nama Pasangan Terdahulu Calon Pasangan","deskripsi":"Nama Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_binbinti_pasangan_terdahulu_calon_pasangan]","nama":"Bin\/Binti Pasangan Terdahulu Calon Pasangan","deskripsi":"Bin\/Binti Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_ktp_pasangan_terdahulu_calon_pasangan]","nama":"No KTP Pasangan Terdahulu Calon Pasangan","deskripsi":"No KTP Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir_pasangan_terdahulu_calon_pasangan]","nama":"Tempat Lahir Pasangan Terdahulu Calon Pasangan","deskripsi":"Tempat Lahir Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_pasangan_terdahulu_calon_pasangan]","nama":"Tanggal Lahir Pasangan Terdahulu Calon Pasangan","deskripsi":"Tanggal Lahir Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_warga_negara_pasangan_terdahulu_calon_pasangan]","nama":"Warga Negara Pasangan Terdahulu Calon Pasangan","deskripsi":"Pilih Warga Negara Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"tipe":"select-otomatis","kode":"[form_agama_pasangan_terdahulu_calon_pasangan]","nama":"Agama Pasangan Terdahulu Calon Pasangan","deskripsi":"Pilih Agama Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan_pasangan_terdahulu_calon_pasangan]","nama":"Pekerjaan Pasangan Terdahulu Calon Pasangan","deskripsi":"Pilih Pekerjaan Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"text","kode":"[form_tempat_tinggal_pasangan_terdahulu_calon_pasangan]","nama":"Tempat Tinggal Pasangan Terdahulu Calon Pasangan","deskripsi":"Tempat Tinggal Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_meninggal_pasangan_terdahulu_calon_pasangan]","nama":"Tanggal Meninggal Pasangan Terdahulu Calon Pasangan","deskripsi":"Tanggal Meninggal Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_meninggal_pasangan_terdahulu_calon_pasangan]","nama":"Tempat Meninggal Pasangan Terdahulu Calon Pasangan","deskripsi":"Tempat Meninggal Pasangan Terdahulu Calon Pasangan","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_nama_wali_nikah]","nama":"Nama Wali Nikah","deskripsi":"Nama Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_bin_wali_nikah]","nama":"Bin Wali Nikah","deskripsi":"Bin Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"number","kode":"[form_no_ktp_wali_nikah]","nama":"No KTP Wali Nikah","deskripsi":"No KTP Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_lahir_wali_nikah]","nama":"Tempat Lahir Wali Nikah","deskripsi":"Tempat Lahir Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_lahir_wali_nikah]","nama":"Tanggal Lahir Wali Nikah","deskripsi":"Tanggal Lahir Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"select-otomatis","kode":"[form_warga_negara_wali_nikah]","nama":"Warga Negara Wali Nikah","deskripsi":"Warga Negara Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_warganegara"},{"tipe":"select-otomatis","kode":"[form_agama_wali_nikah]","nama":"Agama Wali Nikah","deskripsi":"Agama Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_agama"},{"tipe":"select-otomatis","kode":"[form_pekerjaan_wali_nikah]","nama":"Pekerjaan Wali Nikah","deskripsi":"Pekerjaan Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":"tweb_penduduk_pekerjaan"},{"tipe":"text","kode":"[form_tempat_tinggal_wali_nikah]","nama":"Tempat Tinggal Wali Nikah","deskripsi":"Tempat Tinggal Wali Nikah","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_hubungan_dengan_wali]","nama":"Hubungan Dengan Wali","deskripsi":"Hubungan Dengan Wali","required":"0","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_hari_pernikahan]","nama":"Hari Pernikahan","deskripsi":"Hari Pernikahan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"date","kode":"[form_tanggal_pernikahan]","nama":"Tanggal Pernikahan","deskripsi":"Tanggal Pernikahan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"time","kode":"[form_jam_pernikahan]","nama":"Jam Pernikahan","deskripsi":"Jam Pernikahan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_tempat_pernikahan]","nama":"Tempat Pernikahan","deskripsi":"Tempat Pernikahan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_maskawin]","nama":"Maskawin","deskripsi":"Maskawin","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_rt_kua_tujuan]","nama":"RT KUA Tujuan","deskripsi":"RT KUA Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_rw_kua_tujuan]","nama":"RW KUA Tujuan","deskripsi":"RW KUA Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_dusun_kua_tujuan]","nama":"Dusun KUA Tujuan","deskripsi":"Dusun KUA Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_desakelurahan_kua_tujuan]","nama":"Desa\/Kelurahan KUA Tujuan","deskripsi":"Desa\/Kelurahan KUA Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kecamatan_kua_tujuan]","nama":"Kecamatan KUA Tujuan","deskripsi":"Kecamatan KUA Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null},{"tipe":"text","kode":"[form_kabupaten_kua_tujuan]","nama":"Kabupaten KUA Tujuan","deskripsi":"Kabupaten KUA Tujuan","required":"1","atribut":null,"pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""},"data_orang_tua":"1","data_pasangan":"0"}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'lampiran'            => 'N-1,N-2,N-4,N-5,N-6,SPS,SWN',
            'template'            => $template,
        ];

        return $hasil && $this->tambah_surat_tinymce($data, $id);
    }

    // Function Migrasi TinyMCE

    protected function migrasi_2023072454($hasil)
    {
        $id    = auth()->id ?? super_admin();
        $table = 'tweb_keluarga';

        $update['updated_by'] = [
            'type'       => 'INT',
            'constraint' => 11,
            'null'       => true,
        ];

        if ($this->db->field_exists('updated_by', $table)) {
            $this->dbforge->modify_column($table, $update);
        } else {
            $this->dbforge->add_column($table, $update);
        }

        DB::table($table)->whereNull('updated_by')->update(['updated_by' => $id]);

        return $hasil;
    }
}
