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
            $hasil = $hasil && $this->suratKeteranganPenghasilanAyah($hasil, $id);
            // Jalankan Migrasi TinyMCE
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023070651($hasil);
        $hasil = $hasil && $this->migrasi_2023070653($hasil);

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

    protected function migrasi_2023070652($hasil)
    {
        DB::table('setting_aplikasi')->where('key', 'rentang_waktu_kehadiran')->update([
            'jenis'     => 'input',
            'attribute' => 'class="bilangan required" placeholder="10" min="0" type="number"',
        ]);

        return $hasil;
    }

    protected function migrasi_2023070653($hasil)
    {
        return $this->db->query('ALTER TABLE login_attempts MODIFY COLUMN username VARCHAR(100) NOT NULL');
    }

    // Function Migrasi TinyMCE
}
