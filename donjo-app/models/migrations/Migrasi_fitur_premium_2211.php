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
use App\Libraries\TinyMCE;
use App\Models\FormatSurat;
use App\Models\KeuanganManualRinci;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2211 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2210');
        $hasil = $hasil && $this->migrasi_2022100671($hasil);
        $hasil = $hasil && $this->migrasi_2022100851($hasil);
        $hasil = $hasil && $this->migrasi_2022101571($hasil);
        $hasil = $hasil && $this->tambah_modul_gawai_layanan($hasil);
        $hasil = $hasil && $this->migrasi_2022102271($hasil);
        $hasil = $hasil && $this->migrasi_2022101371($hasil);
        $hasil = $hasil && $this->migrasi_2022101871($hasil);
        $hasil = $hasil && $this->migrasi_2022102451($hasil);
        $hasil = $hasil && $this->migrasi_2022102151($hasil);
        $hasil = $hasil && $this->migrasi_2022102671($hasil);
        $hasil = $hasil && $this->migrasi_2022103151($hasil);

        return $hasil && $this->suratTinyMCE($hasil);
    }

    protected function migrasi_2022100671($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'footer_surat_tte',
            'value'      => TinyMCE::FOOTER_TTE,
            'keterangan' => 'Footer Surat TTE',
            'kategori'   => 'format_surat',
        ]);
    }

    public function migrasi_2022100851($hasil)
    {
        // ganti jenis data untuk realisasi dan rencana keuangan
        if ($this->db->field_exists('Nilai_Anggaran', 'keuangan_manual_rinci')) {
            foreach (KeuanganManualRinci::all() as $key => $value) {
                $value->Nilai_Anggaran = (float) $value->Nilai_Anggaran;
                $value->save();
            }
            $fields = [
                'Nilai_Anggaran' => [
                    'type'       => 'decimal',
                    'constraint' => '65,2',
                    'null'       => false,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('keuangan_manual_rinci', $fields);
        }

        if ($this->db->field_exists('Nilai_Realisasi', 'keuangan_manual_rinci')) {
            foreach (KeuanganManualRinci::all() as $key => $value) {
                $value->Nilai_Realisasi = (float) $value->Nilai_Realisasi;
                $value->save();
            }
            $fields = [
                'Nilai_Realisasi' => [
                    'type'       => 'decimal',
                    'constraint' => '65,2',
                    'null'       => false,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('keuangan_manual_rinci', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022101571($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'visual_tte',
            'value'      => '0',
            'keterangan' => 'Visual Tanda Tangan TTE',
            'kategori'   => 'tte',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'visual_tte_gambar',
            'value'      => '',
            'keterangan' => 'Url Gambar Visual TTE',
            'kategori'   => 'tte',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'visual_tte_weight',
            'value'      => '100',
            'keterangan' => 'Lebar Gambar Visual TTE',
            'kategori'   => 'tte',
        ]);

        return $hasil && $this->tambah_setting([
            'key'        => 'visual_tte_height',
            'value'      => '100',
            'keterangan' => 'Tinggi Gambar Visual TTE',
            'kategori'   => 'tte',
        ]);
    }

    protected function tambah_modul_gawai_layanan($hasil)
    {
        if (! $this->db->field_exists('tipe', 'anjungan')) {
            $fields = [
                'tipe' => [
                    'type'       => 'TINYINT',
                    'default'    => 1, // 1 => anjungan, 2 => gawai layanan
                    'constraint' => 3,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('anjungan', $fields);
        }

        return $hasil && $this->tambah_modul([
            'id'         => 351,
            'modul'      => 'Gawai Layanan',
            'url'        => 'gawai_layanan',
            'aktif'      => 1,
            'ikon'       => 'fa-desktop',
            'urut'       => 3,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-desktop',
            'parent'     => 14,
        ]);
    }

    protected function migrasi_2022102271($hasil)
    {
        if (! $this->db->field_exists('deleted_at', 'log_surat')) {
            $fields = [
                'deleted_at' => [
                    'type' => 'datetime',
                    'null' => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022101371($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'anjungan_teks_berjalan',
            'value'      => '',
            'keterangan' => 'Pengaturan teks berjalan untuk anjungan',
            'kategori'   => 'anjungan',
        ]);
    }

    protected function migrasi_2022101871($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'anjungan_profil',
            'value'      => 3,
            'keterangan' => 'Pengaturan profil desa untuk anjungan', // 1 => slides; 2 => mp4; 3 => youtube
            'kategori'   => 'anjungan',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'anjungan_slide',
            'value'      => '',
            'keterangan' => 'Pengaturan profil slide untuk anjungan',
            'kategori'   => 'anjungan',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'anjungan_video',
            'value'      => '',
            'keterangan' => 'Pengaturan profil video untuk anjungan',
            'kategori'   => 'anjungan',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'anjungan_youtube',
            'value'      => 'https://www.youtube.com/embed/PuxiuH-YUF4',
            'keterangan' => 'Pengaturan profil video youtube untuk anjungan',
            'kategori'   => 'anjungan',
        ]);

        if (DB::table('setting_aplikasi')->whereKey('tampilan_anjungan')->exists()) {
            $hasil = $hasil && $this->db->where('key', 'tampilan_anjungan')
                ->update('setting_aplikasi', [
                    'kategori' => 'anjungan',
                ]);
        }

        if (DB::table('setting_aplikasi')->whereKey('tampilan_anjungan_waktu')->exists()) {
            $hasil = $hasil && $this->db->where('key', 'tampilan_anjungan_waktu')
                ->update('setting_aplikasi', [
                    'kategori' => 'anjungan',
                ]);
        }

        if (DB::table('setting_aplikasi')->whereKey('tampilan_anjungan_slider')->exists()) {
            $hasil = $hasil && $this->db->where('key', 'tampilan_anjungan_slider')
                ->update('setting_aplikasi', [
                    'kategori' => 'anjungan',
                ]);
        }

        if (DB::table('setting_aplikasi')->whereKey('tampilan_anjungan_video')->exists()) {
            $hasil = $hasil && $this->db->where('key', 'tampilan_anjungan_video')
                ->update('setting_aplikasi', [
                    'kategori' => 'anjungan',
                ]);
        }

        if (DB::table('setting_aplikasi')->whereKey('tampilan_anjungan_audio')->exists()) {
            $hasil = $hasil && $this->db->where('key', 'tampilan_anjungan_audio')
                ->update('setting_aplikasi', [
                    'kategori' => 'anjungan',
                ]);
        }

        return $hasil;
    }

    public function migrasi_2022102451($hasil)
    {
        if (DB::table('setting_aplikasi')->whereKey('format_nomor_surat')->exists()) {
            $hasil = $hasil && $this->db->where('key', 'format_nomor_surat')
                ->update('setting_aplikasi', [
                    'kategori' => 'format_surat',
                ]);
        }

        // tambhakn nomorsurat di log surat
        if (! $this->db->field_exists('format_nomor', 'tweb_surat_format')) {
            $fields = [
                'format_nomor' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'after'      => 'header',
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        return $hasil;
    }

    public function migrasi_2022102151($hasil)
    {
        if (! $this->db->field_exists('FF12', 'keuangan_ta_spp')) {
            $fields = [
                'FF12' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spp', $fields);
        }

        if (! $this->db->field_exists('FF13', 'keuangan_ta_spp')) {
            $fields = [
                'FF13' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spp', $fields);
        }

        if (! $this->db->field_exists('FF14', 'keuangan_ta_spp')) {
            $fields = [
                'FF14' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_spp', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022102671($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'anjungan_layar',
            'value'      => 1, //1: landscape; 2: potrait
            'keterangan' => 'Pengaturan jenis layar anjungan',
            'kategori'   => 'anjungan',
        ]);
    }

    protected function migrasi_2022103151($hasil)
    {
        if ($this->db->field_exists('no_id_kartu', 'program_peserta')) {
            $fields = [
                'no_id_kartu' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 60,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('program_peserta', $fields);
        }

        return $hasil;
    }

    protected function suratTinyMCE($hasil)
    {
        $hasil = $hasil && $this->suratRawTinyMCE($hasil);
        $hasil = $hasil && $this->suratKeteranganUsaha($hasil);
        $hasil = $hasil && $this->suratPengantarLaporanKehilangan($hasil);
        $hasil = $hasil && $this->suratKeteranganPengantar($hasil);
        $hasil = $hasil && $this->suratKeteranganPenduduk($hasil);
        $hasil = $hasil && $this->pengantarSuratKeteranganCatatanKepolisian($hasil);
        $hasil = $hasil && $this->suratPengantarIzinKeramaian($hasil);
        $hasil = $hasil && $this->suratKeteranganPergiKawin($hasil);
        $hasil = $hasil && $this->suratKeteranganWaliHakim($hasil);
        $hasil = $hasil && $this->suratPernyataanBelumMemilikiAktaLahir($hasil);
        $hasil = $hasil && $this->suratKeteranganDomisiliUsaha($hasil);
        $hasil = $hasil && $this->suratKeteranganJamkesos($hasil);
        $hasil = $hasil && $this->suratKeteranganJualBeli($hasil);

        return $hasil && $this->suratKeteranganKtpDalamProses($hasil);
    }

    protected function suratRawTinyMCE($hasil)
    {
        FormatSurat::where('url_surat', 'surat_raw_tinymce')
            ->update([
                'url_surat' => 'raw-tinymce',
                'template'  => "
                    <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[UsIa]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Warga Negara</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 36px; text-align: left;\">8.<br /><br /></td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 36px;\">Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0211%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Surat bukti diri</td>\r\n<td style=\"width: 1.26582%; height: 18px; text-align: center;\"> </td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KTP</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KK</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">Mohon keterangan yang akan dipegunakan untuk [Form_keperluan].</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Mulai_berlaku] s/d [Berlaku_sampai]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Golongan Darah</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[GOl_darah]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
                ",
            ]);

        return $hasil;
    }

    protected function suratKeteranganUsaha($hasil)
    {
        $nama_surat = 'Keterangan Usaha';
        $data       = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => '500',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_nama_usaha]","nama":"Nama Usaha","deskripsi":"Masukkan Nama \/ Jenis usaha","atribut":"required"},{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['13', '3'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">No. KK</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kepala Keluarga</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Kepala_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36px; text-align: left;\">7.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; height: 18px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">13.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_keperluan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">14.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Mulai_berlaku] sampai dengan [Berlaku_sampai]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut adalah benar-benar warga [Sebutan_desa] [NaMa_desa] dengan data seperti di atas, yang memiliki usaha [Form_nama_usaha].<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratPengantarLaporanKehilangan($hasil)
    {
        $nama_surat = 'Pengantar Laporan Kehilangan';

        $data = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => 'S-13',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_nama_barang]","nama":"Nama Barang","deskripsi":"Masukkan Nama Barang Yang Hilang","atribut":"required"},{"tipe":"textarea","kode":"[form_rincian]","nama":"Rincian","deskripsi":"Masukkan Rincian","atribut":"required"},{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Masukkan Keterangan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">No. KK</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kepala Keluarga</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Kepala_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36px; text-align: left;\">7.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; height: 18px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut adalah benar-benar warga [Sebutan_desa] [NaMa_desa] dengan data seperti di atas.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut telah datang kepada kami untuk melapor dan mengaku telah kehilangan [Form_nama_barang] sebagai berikut:</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 4.41932%;\"> </td>\r\n<td style=\"width: 34.5334%;\">Rincian</td>\r\n<td style=\"width: 1.2333%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify;\">[Form_rincian]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.41932%;\"> </td>\r\n<td style=\"width: 34.5334%;\">Keterangan</td>\r\n<td style=\"width: 1.2333%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify;\">[Form_keterangan]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganPengantar($hasil)
    {
        $nama_surat = 'Keterangan Pengantar';

        $data = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => 'S-01',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"},{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Masukkan Keterangan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[UsIa]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Warga Negara</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 36px; text-align: left;\">8.<br /><br /></td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 36px;\">Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0211%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Surat bukti diri</td>\r\n<td style=\"width: 1.26582%; height: 18px; text-align: center;\"> </td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KTP</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KK</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">Mohon keterangan yang akan dipegunakan untuk [Form_keperluan].</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Mulai_berlaku] s/d [Berlaku_sampai]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Golongan Darah</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[GOl_darah]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganPenduduk($hasil)
    {
        $nama_surat = 'Keterangan Penduduk';
        $data       = [
            'nama'                => $nama_surat,
            'url_surat'           => strtolower(str_replace([' ', '_'], '-', $nama_surat)),
            'kode_surat'          => 'S-02',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Umur</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[UsIa]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Warga Negara</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 36px; text-align: left;\">8.<br /><br /></td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 36px;\">Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0211%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Surat bukti diri</td>\r\n<td style=\"width: 1.26582%; height: 18px; text-align: center;\"> </td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KTP</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\"> </td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">KK</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">Mohon keterangan yang akan dipegunakan untuk [Form_keperluan].</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">[Mulai_berlaku] s/d [Berlaku_sampai]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.32489%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90295%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.4852%; text-align: left; height: 18px;\">Keterangan lain-lain</td>\r\n<td style=\"width: 1.26582%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0211%; height: 18px; text-align: justify;\">Orang tersebut di atas adalah benar-benar penduduk [SeButan_desa] kami dan ada istiadat baik.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat ini dibuat, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 324px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">Pemegang Surat</td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35.0462%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\"><strong>[NAma]</strong></td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\"> </td>\r\n<td style=\"width: 35.0462%; text-align: center; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\">No</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\">:</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; height: 18px;\">Tanggal</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\">:</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; text-align: center; height: 18px;\">Mengetahui,</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35.0462%; height: 18px;\"> </td>\r\n<td style=\"width: 30.0103%; text-align: center; height: 18px;\">Camat - [NaMa_kecamatan]</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"height: 72px; width: 35.0462%;\" rowspan=\"2\">[qr_code]</td>\r\n<td style=\"width: 30.0103%; height: 72px;\"><br /><br /><br /></td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 30.0103%; text-align: center; height: 18px;\">..............................................</td>\r\n<td style=\"width: 35.0462%; text-align: left; height: 18px;\"> </td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"> </div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function pengantarSuratKeteranganCatatanKepolisian($hasil)
    {
        $data = [
            'nama'                => 'Pengantar Surat Keterangan Catatan Kepolisian',
            'kode_surat'          => 'S-07',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Masukkan Keterangan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 243px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">No. KK</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Kepala Keluarga</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Kepala_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 22.4531px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 22.4531px;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left; height: 22.4531px;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 22.4531px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 22.4531px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 22.4531px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36.7344px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36.7344px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36.7344px; text-align: left;\">8.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36.7344px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36.7344px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36.7344px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Status</td>\r\n<td style=\"width: 1.2333%; height: 18.375px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18.375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.375px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.375px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.375px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18.4375px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18.4375px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18.4375px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18.4375px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18.4375px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18.4375px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left;\">13.</td>\r\n<td style=\"width: 30.5253%; text-align: left;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify;\">Sebagai pengantar untuk mendapatkan SKCK yang dipergunakan untuk [Form_keterangan].</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut di atas adalah benar-benar warga [Sebutan_desa] [NaMa_desa] dan menurut data kami tidak pernah terlibat perkara Polisi dan beradat istiadat baik.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sesungguhnya untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Atas_namA]</td>\r\n</tr>\r\n<tr style=\"height: 72px;\">\r\n<td style=\"width: 35%; text-align: center; height: 72px;\"> </td>\r\n<td style=\"width: 30%; height: 72px;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%; height: 72px;\"> </td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 35%; height: 18px;\"> </td>\r\n<td style=\"width: 30%; height: 18px;\"> </td>\r\n<td style=\"width: 35%; text-align: center; height: 18px;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratPengantarIzinKeramaian($hasil)
    {
        $data = [
            'nama'                => 'Pengantar Izin Keramaian',
            'kode_surat'          => 'S-12',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_jenis_acara]","nama":"Jenis Acara","deskripsi":"Masukkan Jenis Acara","atribut":"required"},{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">No. KK</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[No_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kepala Keluarga</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[Kepala_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36px; text-align: left;\">7.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; height: 18px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: left; height: 18px;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">13.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">Sebagai pengatar untuk mendapatkan Surat Izin Keramaian berupa [JeNis_acara] mulai tanggal [Mulai_berlaku] sampai dengan [Berlaku_sampai] dengan keperluan [Form_keperluan].</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut adalah benar-benar warga [Sebutan_desa] [NaMa_desa] dengan data seperti di atas.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat, untuk dipergunakan sebagaimana mestinya.</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganPergiKawin($hasil)
    {
        $data = [
            'nama'                => 'Keterangan Pergi Kawin',
            'kode_surat'          => 'S-30',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_tujuan]","nama":"Tujuan","deskripsi":"Masukkan Tujuan","atribut":"required"},{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 270px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">5.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; height: 18px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tujuan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Tujuan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">11.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_keperluan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Berlaku</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Mulai_berlaku] sampai dengan [Berlaku_sampai]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganWaliHakim($hasil)
    {
        $data = [
            'nama'                => 'Keterangan Wali Hakim',
            'kode_surat'          => 'S-32',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => null,
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 154px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">5.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang namanya tersebut di atas memang benar warga kami yang akan menikah di KUA [NaMa_kecamatan] [SeButan_kabupaten] [NaMa_kabupaten]. Berhubung orang tersebut tidak memiliki Wali Nasab, kami mohon dengan hotmat Bapak Kepala KUA [NaMa_kecamatan] supaya berkenan menjadi Wali.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratPernyataanBelumMemilikiAktaLahir($hasil)
    {
        $data = [
            'nama'                => 'Pernyataan Belum Memiliki Akta Lahir',
            'kode_surat'          => 'S-19',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => null,
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 82px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">2.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Dengan orang tua:</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 64px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Ayah</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[NaMa_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">NIK / No. KTP Ayah</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\">[Nik_ayah]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Ibu</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[NaMa_ibu]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">NIK / No. KTP Ibu</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik_ibu]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Adalah benar-benar warga [SeButan_desa] [NaMa_desa] dan belum pernah memiliki Akta Kelahiran.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganDomisiliUsaha($hasil)
    {
        $data = [
            'nama'                => 'Keterangan Domisili Usaha',
            'kode_surat'          => 'S-16',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_nama_usaha]","nama":"Nama Usaha","deskripsi":"Masukkan Nama \/ Jenis usaha","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 198px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36px; text-align: left;\">5.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; height: 18px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut di atas adalah benar-benar warga [Sebutan_desa] [NaMa_desa] yang memiliki usaha [Form_nama_usaha] di [AlamaT], [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten].<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganJamkesos($hasil)
    {
        $data = [
            'nama'                => 'Keterangan JAMKESOS',
            'kode_surat'          => 'S-15',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_no_kartu_jamkesos]","nama":"No Kartu JAMKESOS","deskripsi":"Masukkan No. Kartu JAMKESOS","atribut":"required"},{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 216px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">5.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 4.31655%; text-align: center;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left;\">Pendidikan</td>\r\n<td style=\"width: 1.2333%; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify;\">[Pendidikan_kk]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">10.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Keperluan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_keperluan]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut adalah benar-benar warga [SeButan_desa] [NaMa_desa] dengan data seperti di atas, dari keluarga kurang mampu pemegang Kartu Peserta Jamkesos No. [form_no_kartu_jamkesos].</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganJualBeli($hasil)
    {
        $data = [
            'nama'                => 'Keterangan Jual Beli',
            'kode_surat'          => 'S-05',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_jenis_barang]","nama":"Jenis Barang","deskripsi":"Masukkan Jenis Barang","atribut":"required"},{"tipe":"text","kode":"[form_rincian_barang]","nama":"Rincian Barang","deskripsi":"Masukkan Rincian Barang","atribut":"required"},{"tipe":"text","kode":"[form_identitas_pembeli]","nama":"Identitas Pembeli","deskripsi":"Masukkan Identitas Pembeli","atribut":"required"},{"tipe":"text","kode":"[form_nama_pembeli]","nama":"Nama Pembeli","deskripsi":"Masukkan Nama Pembeli","atribut":"required"},{"tipe":"text","kode":"[form_tempat_lahir_pembeli]","nama":"Tempat Lahir Pembeli","deskripsi":"Masukkan Tempat Lahir Pembeli","atribut":"required"},{"tipe":"date","kode":"[form_tanggal_lahir_pembeli]","nama":"Tanggal Lahir Pembeli","deskripsi":"Masukkan Tanggal Lahir Pembeli","atribut":"required"},{"tipe":"text","kode":"[form_jenis_kelamin_pembeli]","nama":"Jenis Kelamin Pembeli","deskripsi":"Masukkan Jenis Kelamin","atribut":"required"},{"tipe":"text","kode":"[form_pekerjaan_pembeli]","nama":"Pekerjaan Pembeli","deskripsi":"Masukkan Pekerjaan Pembeli","atribut":"required"},{"tipe":"text","kode":"[form_nama_ketua_adat]","nama":"Nama Ketua Adat","deskripsi":"Masukkan Nama Ketua Adat","atribut":"required"},{"tipe":"textarea","kode":"[form_alamat_pembeli]","nama":"Alamat Pembeli","deskripsi":"Masukkan Alamat Pembeli","atribut":"required"},{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Masukkkan Keterangan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 118px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">NIK / No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 10px;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 10px;\">[Pekerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">6.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bersangkutan hendak menjual [Form_rincian_barang]. [Form_jenis_barang] tersebut tidak dalam sengketa dengan pihak lain sehingga dapat dijual kepada pihak ke dua yaitu:</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 118px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[FOrm_nama_pembeli]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nomor Identitas</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_identitas_pembeli]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_tempat_lahir_pembeli], [FoRm_tanggal_lahir_pembeli]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">10.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_jenis_kelamin_pembelI]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">11.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Alamat / Tempat Tinggal</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_alamat_pembelI]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">12.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_pekerjaan_pembelI]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">13.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Keterangan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\">[Form_keterangan]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Mengetahui,</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Ketua Adat [NaMa_desa]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[Form_nama_ketua_adaT]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }

    protected function suratKeteranganKtpDalamProses($hasil)
    {
        $data = [
            'nama'                => 'Keterangan KTP dalam Proses',
            'kode_surat'          => 'S-08',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => null,
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 154px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">4.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">6.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Status</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Status_kawin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; height: 10px; text-align: center;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\">[WArga_negara]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Orang tersebut di atas adalah benar-benar warga [SeButan_desa] [NaMa_desa] yang saat ini Kartu Tanda Penduduk sedang dalam proses.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sesungguhnya untuk dipergunakan sebagaimana mestinya.<br /><br /></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ];

        return $hasil && $this->tambah_surat_tinymce($data);
    }
}
