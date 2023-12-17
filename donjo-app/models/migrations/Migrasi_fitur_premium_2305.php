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

class Migrasi_fitur_premium_2305 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2304');

        $hasil = $hasil && $this->suratPermohonanCerai($hasil);
        $hasil = $hasil && $this->migrasi_2023041251($hasil);
        $hasil = $hasil && $this->migrasi_2023041951($hasil);

        return $hasil && true;
    }

    protected function suratPermohonanCerai($hasil)
    {
        $nama_surat = 'Permohonan Cerai';

        $data = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => 'S-34',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::TIDAK,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_sebab_sebab]","nama":"Sebab - sebab","deskripsi":"Sebab - sebab","atribut":"class=\"rquired\"","pilihan":null,"refrensi":null}]',
            'form_isian'          => '{"data":"1","individu":{"sex":"","status_dasar":"","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 6.78314%;\">Nomor</td>\r\n<td style=\"width: 1.95177%; text-align: center;\">:</td>\r\n<td style=\"width: 91.2651%;\">[Format_nomor_suraT]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 6.78314%;\">Perihal</td>\r\n<td style=\"width: 1.95177%; text-align: center;\">:</td>\r\n<td style=\"width: 91.2651%;\">\r\n<h4 style=\"margin: 0px; text-align: left;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"margin: 0px; text-align: justify;\"><br />Kepada Yth<br /><br />Kepala Pengadilan Agama<br />[SeButan_kabupaten] [NaMa_kabupaten]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Dengan ini kami kirimkan dengan hormat permohonan cerai dari pasangan suami istri :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 166px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 22px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 22px;\"> </td>\r\n<td style=\"text-align: left; width: 95.6835%; height: 22px;\" colspan=\"4\">A. SUAMI</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; height: 18px; text-align: justify;\">[Nama]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">NIK</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">4.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; text-align: justify; height: 18px;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">5.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; text-align: justify; height: 18px;\">[Agama]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">6.<br /><br /></td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 70.6064%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"text-align: center; width: 100%;\" colspan=\"5\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 22px;\"> </td>\r\n<td style=\"text-align: left; width: 95.6835%; height: 22px;\" colspan=\"4\">B. ISTRI</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; height: 18px; text-align: justify;\">[Klg2_nama]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">NIK</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; height: 18px; text-align: justify;\">[Klg2_nik]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; height: 18px; text-align: justify;\">[Klg2_ttL]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">4.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; text-align: justify; height: 18px;\">[Klg2_pekerjaan]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">5.</td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 70.6064%; text-align: justify; height: 18px;\">[Klg2_agama]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">6.<br /><br /></td>\r\n<td style=\"width: 20.1439%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.02775%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 70.6064%; height: 36px; text-align: justify;\">[Klg2_alamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Adapun sebab-sebab menurut keterangan sebagai berikut :</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">[Form_sebab_sebaB]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>\r\n<p> </p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function migrasi_2023041251($hasil)
    {
        // Hapus kolom id_pertanyaan di tabel buku_kepuasan
        if ($this->db->field_exists('id_pertanyaan', 'buku_kepuasan')) {
            $data = DB::table('buku_kepuasan as k')
                ->select('k.id', 'p.pertanyaan')
                ->join('buku_pertanyaan as p', 'p.id', '=', 'k.id_pertanyaan')
                ->where('k.pertanyaan_statis', '=', '')
                ->orWhereNull('k.pertanyaan_statis')
                ->get()
                ->pluck('pertanyaan', 'id');

            if (count($data) !== 0) {
                foreach ($data as $id => $pertanyaan_statis) {
                    $batch_pertanyaan[] = [
                        'id'                => $id,
                        'pertanyaan_statis' => $pertanyaan_statis,
                    ];
                }

                if ($batch_pertanyaan) {
                    $hasil = $hasil && $this->db->update_batch('buku_kepuasan', $batch_pertanyaan, 'id');
                }
            }
        }

        // Tambahkan kolom bidang di tabel buku_tamu
        if (! $this->db->field_exists('bidang', 'buku_tamu')) {
            $hasil = $hasil && $this->dbforge->add_column('buku_tamu', [
                'bidang' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'alamat',
                ],
            ]);

            $data = DB::table('buku_tamu as t')
                ->select('t.id', 'r.nama as bidang')
                ->join('ref_jabatan as r', 'r.id', '=', 't.id_bidang')
                ->where('t.bidang', '=', '')
                ->orWhereNull('t.bidang')
                ->get()
                ->pluck('bidang', 'id');

            if (count($data) !== 0) {
                foreach ($data as $id => $bidang) {
                    $batch_bidang[] = [
                        'id'     => $id,
                        'bidang' => $bidang,
                    ];
                }

                if ($batch_bidang) {
                    $hasil = $hasil && $this->db->update_batch('buku_tamu', $batch_bidang, 'id');
                }
            }

            $hasil = $hasil && $this->dbforge->drop_column('buku_tamu', 'id_bidang');
        }

        // Tambahkan kolom keperluan di tabel buku_tamu
        if (! $this->db->field_exists('keperluan', 'buku_tamu')) {
            $hasil = $hasil && $this->dbforge->add_column('buku_tamu', [
                'keperluan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'bidang',
                ],
            ]);

            $data = DB::table('buku_tamu as t')
                ->select('t.id', 'k.keperluan')
                ->join('buku_keperluan as k', 'k.id', '=', 't.id_keperluan')
                ->where('t.keperluan', '=', '')
                ->orWhereNull('t.keperluan')
                ->get()
                ->pluck('keperluan', 'id');

            if (count($data) !== 0) {
                foreach ($data as $id => $keperluan) {
                    $batch_keperluan[] = [
                        'id'        => $id,
                        'keperluan' => $keperluan,
                    ];
                }

                if ($batch_keperluan) {
                    $hasil = $hasil && $this->db->update_batch('buku_tamu', $batch_keperluan, 'id');
                }
            }

            $hasil = $hasil && $this->dbforge->drop_column('buku_tamu', 'id_keperluan');
        }

        return $hasil;
    }

    protected function migrasi_2023041951($hasil)
    {
        $config = DB::table('config')->get();

        if ($config->count() > 0) {
            foreach ($config as $key => $value) {
                DB::table('config')
                    ->where('id', $value->id)
                    ->update([
                        'kode_desa'      => bilangan($value->kode_desa),
                        'kode_kecamatan' => bilangan($value->kode_kecamatan),
                        'kode_kabupaten' => bilangan($value->kode_kabupaten),
                        'kode_propinsi'  => bilangan($value->kode_propinsi),
                    ]);
            }
        }

        return $hasil;
    }
}
