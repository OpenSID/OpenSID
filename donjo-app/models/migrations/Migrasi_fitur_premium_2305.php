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

        if (! $this->db->field_exists('app_key', 'config')) {
            // Jalankan migrasi sebelumnya
            $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2304');
            $hasil = $hasil && $this->migrasi_2023040151($hasil);
        }

        $hasil = $hasil && $this->suratPermohonanCerai($hasil);
        $hasil = $hasil && $this->migrasi_2023041251($hasil);
        $hasil = $hasil && $this->migrasi_2023041951($hasil);
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_multidb', false);

        return $hasil && true;
    }

    protected function migrasi_2023040151($hasil)
    {
        $data_modul = [
            1   => 'home',
            2   => 'kependudukan',
            3   => 'statistik',
            4   => 'layanan-surat',
            5   => 'analisis',
            6   => 'bantuan',
            7   => 'pertanahan',
            8   => 'pengaturan-peta',
            9   => 'pemetaan',
            10  => 'hubung-warga',
            11  => 'pengaturan',
            13  => 'admin-web',
            14  => 'layanan-mandiri',
            15  => 'sekretariat',
            17  => 'identitas-desa',
            18  => 'pemerintah-desa',
            20  => 'wilayah-administratif',
            21  => 'penduduk',
            22  => 'keluarga',
            23  => 'rumah-tangga',
            24  => 'kelompok',
            25  => 'data-suplemen',
            26  => 'calon-pemilih',
            27  => 'statistik-kependudukan',
            28  => 'laporan-bulanan',
            29  => 'laporan-kelompok-rentan',
            30  => 'pengaturan-surat',
            31  => 'cetak-surat',
            32  => 'arsip-layanan',
            39  => 'kirim-pesan',
            40  => 'daftar-kontak',
            42  => 'modul',
            43  => 'aplikasi',
            44  => 'pengguna',
            45  => 'database',
            46  => 'info-sistem',
            47  => 'artikel',
            48  => 'widget',
            49  => 'menu',
            50  => 'komentar',
            51  => 'galeri',
            52  => 'informasi-publik',
            53  => 'media-sosial',
            54  => 'slider',
            55  => 'kotak-pesan',
            56  => 'pendaftar-layanan-mandiri',
            57  => 'surat-masuk',
            58  => 'surat-keluar',
            61  => 'inventaris',
            62  => 'peta',
            63  => 'klasifikasi-surat',
            64  => 'teks-berjalan',
            65  => 'kategori',
            66  => 'log-penduduk',
            67  => 'analisis-kategori',
            68  => 'analisis-indikator',
            69  => 'analisis-klasifikasi',
            70  => 'analisis-periode',
            71  => 'analisis-respon',
            72  => 'analisis-laporan',
            73  => 'analisis-statistik-jawaban',
            75  => 'api-inventaris-asset',
            76  => 'api-inventaris-gedung',
            77  => 'api-inventaris-gedung-1',
            78  => 'api-inventaris-jalan',
            79  => 'api-inventaris-kontruksi',
            80  => 'api-inventaris-peralatan',
            81  => 'api-inventaris-tanah',
            82  => 'inventaris-asset',
            83  => 'inventaris-gedung',
            84  => 'inventaris-jalan',
            85  => 'inventaris-kontruksi',
            86  => 'inventaris-peralatan',
            87  => 'laporan-inventaris',
            88  => 'plan',
            89  => 'point',
            90  => 'garis',
            91  => 'line',
            92  => 'area',
            93  => 'polygon',
            94  => 'kategori-kelompok',
            95  => 'produk-hukum',
            96  => 'informasi-publik-1',
            97  => 'daftar-persyaratan',
            98  => 'permohonan-surat',
            101 => 'status-desa',
            102 => 'pengaturan-grup',
            110 => 'master-analisis',
            111 => 'pengaturan-analisis',
            200 => 'info-desa',
            201 => 'keuangan',
            202 => 'impor-data',
            203 => 'laporan',
            205 => 'pengunjung',
            206 => 'kesehatan',
            207 => 'pendataan',
            208 => 'pemantauan',
            209 => 'input-data',
            210 => 'laporan-manual',
            211 => 'pengaturan-web',
            212 => 'qr-code',
            213 => 'daftar-persil',
            214 => 'c-desa',
            220 => 'pembangunan',
            301 => 'buku-administrasi-desa',
            302 => 'administrasi-umum',
            303 => 'administrasi-penduduk',
            304 => 'administrasi-keuangan',
            305 => 'administrasi-pembangunan',
            310 => 'buku-eskpedisi',
            311 => 'buku-lembaran-dan-berita-desa',
            312 => 'anjungan',
            313 => 'layanan-pelanggan',
            314 => 'pengaturan-layanan-mandiri',
            315 => 'buku-mutasi-penduduk',
            316 => 'buku-rekapitulasi-jumlah-penduduk',
            317 => 'buku-penduduk-sementara',
            318 => 'buku-ktp-dan-kk',
            319 => 'buku-tanah-kas-desa',
            320 => 'buku-tanah-di-desa',
            321 => 'pendapat',
            322 => 'buku-inventaris-dan-kekayaan-desa',
            323 => 'buku-rencana-kerja-pembangunan',
            324 => 'lapak',
            325 => 'laporan-apbdes',
            326 => 'sinkronisasi',
            327 => 'lembaga-desa',
            328 => 'kategori-lembaga',
            329 => 'bumindes-kegiatan-pembangunan',
            330 => 'laporan-penduduk',
            331 => 'pendaftaran-kerjasama',
            332 => 'bumindes-kader',
            333 => 'bumindes-hasil-pembangunan',
            334 => 'pengaduan',
            335 => 'vaksin',
            336 => 'arsip-desa',
            337 => 'kehadiran',
            339 => 'jam-kerja',
            340 => 'hari-libur',
            341 => 'rekapitulasi',
            342 => 'kehadiran-pengaduan',
            343 => 'opendk',
            344 => 'pesan',
            345 => 'grup-kontak',
            346 => 'stunting',
            347 => 'daftar-anjungan',
            348 => 'anjungan-menu',
            349 => 'pengaturan-anjungan',
            350 => 'alasan-keluar',
            351 => 'gawai-layanan',
            352 => 'satu-data',
            353 => 'dtks',
            354 => 'buku-tamu',
            355 => 'data-tamu',
            356 => 'data-kepuasan',
            357 => 'data-pertanyaan',
            358 => 'data-keperluan',
            359 => 'optimasi-gambar',
        ];

        foreach ($data_modul as $id => $slug) {
            $this->db->where('id', $id)->update('setting_modul', ['slug' => $slug]);
        }

        return $hasil;
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
                        'kode_desa'      => $value->kode_desa ? bilangan($value->kode_desa) : '',
                        'kode_kecamatan' => $value->kode_kecamatan ? bilangan($value->kode_kecamatan) : '',
                        'kode_kabupaten' => $value->kode_kabupaten ? bilangan($value->kode_kabupaten) : '',
                        'kode_propinsi'  => $value->kode_propinsi ? bilangan($value->kode_propinsi) : '',
                    ]);
            }
        }

        return $hasil;
    }
}
