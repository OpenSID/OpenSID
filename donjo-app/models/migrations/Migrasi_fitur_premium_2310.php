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
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2310 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2309', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && $this->migrasi_2023101252($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023091851($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023092571($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023092951($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023092652($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_23090451($hasil);
        $hasil = $hasil && $this->migrasi_23090651($hasil);

        return $hasil && $this->migrasi_2023100351($hasil);
    }

    protected function migrasi_23090451($hasil)
    {
        $this->db->where('status', '2')->update('teks_berjalan', ['status' => '0']);
        $this->dbforge->modify_column('teks_berjalan', [
            'status' => [
                'type'       => 'TINYINT',
                'null'       => false,
                'constraint' => 1,
                'default'    => StatusEnum::TIDAK,
            ],
        ]);

        return $hasil;
    }

    protected function migrasi_23090651($hasil)
    {
        $table = 'artikel';

        $this->dbforge->modify_column($table, [
            'headline' => [
                'type'       => 'TINYINT',
                'null'       => false,
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);

        $slider['slider'] = [
            'type'       => 'TINYINT',
            'null'       => false,
            'constraint' => 1,
            'default'    => 0,
        ];

        if ($this->db->field_exists('slider', $table)) {
            $this->dbforge->modify_column($table, $slider);
        } else {
            $this->dbforge->add_column($table, $slider);
        }

        $this->db->where('headline', '3')->update($table, ['headline' => '0', 'slider' => '1']);
        $this->db->where('headline', '2')->update($table, ['headline' => '1', 'slider' => '1']);

        return $hasil;
    }

    protected function migrasi_2023091851($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Link Feed',
            'key'        => 'link_feed',
            'value'      => 'https://www.covid19.go.id/feed/',
            'keterangan' => 'Alamat Feed yang digunakan <code>(contoh: https://www.covid19.go.id/feed/)</code>',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'conf_web',
        ], $id);
    }

    protected function migrasi_2023091951($hasil)
    {
        DB::table('tweb_surat_format')->where('url_surat', 'surat-keterangan-pergi-kawin')->update([
            'template' => "<h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">5.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; height: 18px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tujuan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_tujuan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_keperluan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Mulai_berlaku] sampai dengan [Berlaku_sampai]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>",
        ]);

        return $hasil;
    }

    protected function migrasi_2023092571($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Kode Isian data kosong',
            'key'        => 'ganti_data_kosong',
            'value'      => '-',
            'keterangan' => 'Bawaan jika kode isian memiliki data kosong',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat',
        ], $id);
    }

    protected function migrasi_2023092951($hasil, $id)
    {
        foreach ([
            'tampilan_anjungan',
            'tampilan_anjungan_audio',
            'tampilan_anjungan_slider',
            'tampilan_anjungan_waktu',
        ] as $value) {
            $hasil = $hasil && $this->db
                ->where('config_id', $id)
                ->where('key', $value)
                ->where('kategori !=', 'anjungan')
                ->update('setting_aplikasi', ['kategori' => 'anjungan']);
        }

        return $hasil;
    }

    protected function migrasi_2023092652($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Telegram Notifikasi',
            'key'        => 'telegram_notifikasi',
            'value'      => setting('telegram_notifikasi') === null && ! empty(setting('telegram_user_id')) && ! empty(setting('telegram_token')) ? 1 : 0,
            'keterangan' => 'Aktif atau nonaktifkan notifikasi telegram',
            'jenis'      => 'boolean',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ], $id);
    }

    protected function migrasi_2023100351($hasil)
    {
        $surat = DB::table('tweb_surat_format')->where('syarat_surat', '!=', null)->get();

        foreach ($surat as $data_surat) {
            $cart = [];

            foreach (json_decode($data_surat->syarat_surat, null) as $row) {
                $cart[] = $this->cek_syarat_surat($row, $data_surat->config_id);
            }
            DB::table('tweb_surat_format')->where('id', $data_surat->id)->update(['syarat_surat' => json_encode($cart, JSON_THROW_ON_ERROR)]);
        }

        return $hasil;
    }

    protected function cek_syarat_surat($ref_syarat_id, $config_id)
    {
        $syarat_surat = [
            '1'  => 'Surat Pengantar RT/RW',
            '2'  => 'Fotokopi KK',
            '3'  => 'Fotokopi KTP',
            '4'  => 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan',
            '5'  => 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak',
            '6'  => 'Surat Pindah Datang dari tempat asal',
            '7'  => 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter',
            '8'  => 'Surat Keterangan Cerai',
            '9'  => 'Fotokopi Ijasah Terakhir',
            '10' => 'SK. PNS/KARIP/SK. TNI – POLRI',
            '11' => 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan',
            '12' => 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)',
        ];

        $nama_awal = $syarat_surat[$ref_syarat_id];

        $ambil_syarat_surat = DB::table('ref_syarat_surat')->where('ref_syarat_nama', $nama_awal)->where('config_id', $config_id)->get();

        return $ambil_syarat_surat[0]->ref_syarat_id;
    }

    protected function migrasi_2023101251($hasil)
    {
        $query = 'update config set kode_desa = SUBSTRING(kode_desa,1, 10), kode_kecamatan = SUBSTRING(kode_kecamatan,1, 6), kode_kabupaten = SUBSTRING(kode_kabupaten,1, 4), kode_propinsi = SUBSTRING(kode_propinsi,1, 2) ';
        DB::statement($query);

        return $hasil;
    }

    protected function migrasi_2023101252($hasil)
    {
        $hasil = $hasil && $this->migrasi_2023101251($hasil);

        $queryKodeDesa      = 'alter table config MODIFY COLUMN kode_desa varchar(10)';
        $queryKodeKecamatan = 'alter table config MODIFY COLUMN kode_kecamatan varchar(6)';
        $queryKodeKabupaten = 'alter table config MODIFY COLUMN kode_kabupaten varchar(4)';
        $queryKodePropinsi  = 'alter table config MODIFY COLUMN kode_propinsi varchar(2)';
        DB::statement($queryKodeDesa);
        DB::statement($queryKodeKecamatan);
        DB::statement($queryKodeKabupaten);
        DB::statement($queryKodePropinsi);

        return $hasil;
    }
}
