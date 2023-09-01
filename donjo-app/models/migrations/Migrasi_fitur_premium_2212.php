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

use App\Enums\HubunganRTMEnum;
use App\Enums\StatusEnum;
use App\Models\FormatSurat;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2212 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2211');
        $hasil = $hasil && $this->migrasi_2022110171($hasil);
        $hasil = $hasil && $this->migrasi_2022110771($hasil);
        $hasil = $hasil && $this->migrasiPengaturanAplikasi($hasil);
        $hasil = $hasil && $this->migrasi_2022110951($hasil);

        // Modul DTKS
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_dtks');
        $hasil = $hasil && $this->migrasi_2022111653($hasil);
        $hasil = $hasil && $this->migrasi_2022111654($hasil);
        $hasil = $hasil && $this->migrasi_2022111751($hasil);
        $hasil = $hasil && $this->migrasi_2022112071($hasil);
        $hasil = $hasil && $this->migrasi_2022112151($hasil);
        $hasil = $hasil && $this->suratKeteranganKurangMampu($hasil);
        $hasil = $hasil && $this->suratKeteranganBedaIdentitas($hasil);
        $hasil = $hasil && $this->migrasi_2022112851($hasil);
        $hasil = $hasil && $this->migrasi_2022112971($hasil);
        $hasil = $hasil && $this->migrasi_2022113052($hasil);

        return $hasil && true;
    }

    protected function migrasi_2022110171($hasil)
    {
        if (! $this->db->field_exists('premium', 'migrasi')) {
            $fields = [
                'premium' => [
                    'type' => 'text',
                    'null' => true,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('migrasi', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022110771($hasil)
    {
        // Buat ulang view keluarga_aktif
        return $hasil && $this->db->query('CREATE OR REPLACE VIEW keluarga_aktif AS SELECT k.* FROM tweb_keluarga k LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id WHERE p.status_dasar = 1');
    }

    protected function migrasiPengaturanAplikasi($hasil)
    {
        // Pengaturan aplikasi jenis text
        DB::table('setting_aplikasi')->whereNull('jenis')->orWhere('jenis', '=', '')->update(['jenis' => 'text']);

        // Pengaturan aplikasi kategori sistem
        DB::table('setting_aplikasi')->whereNull('kategori')->orWhere('kategori', '=', '')->update(['kategori' => 'sistem']);

        // Tambah kolom judul
        if (! $this->db->field_exists('judul', 'setting_aplikasi')) {
            $fields = [
                'judul' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'id',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('setting_aplikasi', $fields);

            // Tambahkan data judul
            $daftar_pengaturan = DB::table('setting_aplikasi')->whereNull('judul')->get();
            if ($daftar_pengaturan) {
                foreach ($daftar_pengaturan as $pengaturan) {
                    if ($pengaturan->key === 'tahun_idm') {
                        $judul = 'Tahun IDM';
                    } elseif ($pengaturan->key === 'ip_adress_kehadiran') {
                        $judul = 'IP Adress Kehadiran';
                    } elseif ($pengaturan->key === 'aktifkan_sms') {
                        $judul = 'Aktifkan SMS';
                    } elseif ($pengaturan->key === 'kode_desa_bps') {
                        $judul = 'Kode Desa BPS';
                    } elseif ($pengaturan->key === 'sebutan_nip_desa') {
                        $judul = 'Sebutan NIP Desa';
                    } elseif ($pengaturan->key === 'statistik_chart_3d') {
                        $judul = 'Statistik Chart 3D';
                    } elseif ($pengaturan->key === 'covid_rss') {
                        $judul = 'Covid RSS';
                    } elseif ($pengaturan->key === 'pesan_singkat_wa') {
                        $judul = 'Pesan Singkat WA';
                    } elseif ($pengaturan->key === 'mac_adress_kehadiran') {
                        $judul = 'MAC Adress Kehadiran';
                    } elseif ($pengaturan->key === 'aktifkan_sms') {
                        $judul = 'Aktifkan SMS';
                    } else {
                        $judul = ucwords(str_replace('_', ' ', $pengaturan->key));
                    }

                    if (preg_match('/tte/i', $judul)) {
                        $judul = str_replace('Tte', 'TTE', $judul);
                    }

                    DB::table('setting_aplikasi')->where('id', $pengaturan->id)->update(['judul' => $judul]);
                }
            }
        }

        // Tambah kolom option
        if (! $this->db->field_exists('option', 'setting_aplikasi')) {
            $fields = [
                'option' => [
                    'type'  => 'TEXT',
                    'null'  => true,
                    'after' => 'jenis',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('setting_aplikasi', $fields);
        }

        // Tambah kolom attribute
        if (! $this->db->field_exists('attribute', 'setting_aplikasi')) {
            $fields = [
                'attribute' => [
                    'type'  => 'TEXT',
                    'null'  => true,
                    'after' => 'option',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('setting_aplikasi', $fields);
        }

        // offline_mode
        DB::table('setting_aplikasi')
            ->where('key', '=', 'offline_mode')
            ->update([
                'option' => json_encode([
                    '0' => 'Web bisa diakses publik',
                    '1' => 'Web hanya bisa diakses petugas web',
                    '2' => 'Web non-aktif sama sekali',
                ]),
                'jenis'    => 'option',
                'kategori' => 'web',
            ]);

        // jenis_peta
        DB::table('setting_aplikasi')
            ->where('key', '=', 'jenis_peta')
            ->update([
                'option' => json_encode([
                    '1' => 'OpenStreetMap',
                    '2' => 'OpenStreetMap H.O.T',
                    '3' => 'Mapbox Streets',
                    '4' => 'Mapbox Satellite',
                    '5' => 'Mapbox Satellite-Street',
                ]),
                'jenis'    => 'option',
                'kategori' => 'peta',
            ]);

        // penomoran_surat
        DB::table('setting_aplikasi')
            ->where('key', '=', 'penomoran_surat')
            ->update([
                'option' => json_encode([
                    '1' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan',
                    '2' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama',
                    '3' => 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar',
                ]),
                'jenis'    => 'option',
                'kategori' => 'sistem',
            ]);

        // timezone
        DB::table('setting_aplikasi')
            ->where('key', '=', 'timezone')
            ->update([
                'option' => json_encode([
                    'Asia/Jakarta'  => 'Asia/Jakarta',
                    'Asia/Makassar' => 'Asia/Makassar',
                    'Asia/Jayapura' => 'Asia/Jayapura',
                ]),
                'jenis'    => 'option',
                'kategori' => 'sistem',
            ]);

        // sumber_gambar_slider
        DB::table('setting_aplikasi')
            ->where('key', '=', 'sumber_gambar_slider')
            ->update([
                'option' => json_encode([
                    '1' => 'Gambar utama artikel terbaru',
                    '2' => 'Gambar utama artikel terbaru yang masuk ke slider atas',
                    '3' => 'Gambar dalam album galeri yang dimasukkan ke slider',
                ]),
                'jenis'    => 'option',
                'kategori' => 'web',
            ]);

        // tampilan_anjungan
        DB::table('setting_aplikasi')
            ->where('key', '=', 'tampilan_anjungan')
            ->update([
                'option' => json_encode([
                    '0' => 'Tidak Aktif',
                    '1' => 'Slider',
                    '2' => 'Video',
                ]),
                'jenis'    => 'option',
                'kategori' => 'anjungan',
            ]);

        // warna_tema_admin
        DB::table('setting_aplikasi')
            ->where('key', '=', 'warna_tema_admin')
            ->update([
                'option' => json_encode([
                    'skin-blue'         => 'Biru',
                    'skin-blue-light'   => 'Biru Terang',
                    'skin-black'        => 'Hitam',
                    'skin-black-light'  => 'Hitam Terang',
                    'skin-red'          => 'Merah',
                    'skin-red-light'    => 'Merah Terang',
                    'skin-yellow'       => 'Kuning',
                    'skin-yellow-light' => 'Kuning Terang',
                    'skin-purple'       => 'Ungu',
                    'skin-purple-light' => 'Ungu Terang',
                    'skin-green'        => 'Hijau',
                    'skin-green-light'  => 'Hijau Terang',
                ]),
                'jenis'    => 'option',
                'kategori' => 'sistem',
            ]);

        // tampilan_anjungan_slider
        DB::table('setting_aplikasi')
            ->where('key', '=', 'tampilan_anjungan_slider')
            ->update([
                'jenis'    => 'option',
                'option'   => null,
                'kategori' => 'anjungan',
            ]);

        // web_theme
        DB::table('setting_aplikasi')
            ->where('key', '=', 'web_theme')
            ->update([
                'jenis'    => 'option',
                'option'   => null,
                'kategori' => 'web',
            ]);

        // Sesuaikan kategori
        DB::table('setting_aplikasi')
            ->whereIn('key', [
                'tte',
                'tte_api',
                'tte_username',
                'tte_password',
                'visual_tte',
                'visual_tte_gambar',
                'visual_tte_weight',
                'visual_tte_height',
            ])
            ->update([
                'kategori' => 'tte',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'penggunaan_server')
            ->update([
                'kategori' => 'hidden',
            ]);

        // Tambahkan validasi untuk pengaturan berikut
        DB::table('setting_aplikasi')
            ->where('key', 'banyak_foto_tiap_produk')
            ->update([
                'attribute' => 'class="" max="5"',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'current_version')
            ->update([
                'attribute' => 'class="" disabled',
                'kategori'  => 'sistem',
            ]);

        // Ganti jenis = int menjadi jenis = text dengan atribut class="int"
        DB::table('setting_aplikasi')
            ->where('jenis', 'int')
            ->update([
                'jenis'     => 'text',
                'attribute' => 'class="int"',
            ]);

        // Sesuaikan attribute untuk modul kehadiran
        DB::table('setting_aplikasi')
            ->where('key', 'mac_adress_kehadiran')
            ->update([
                'attribute' => 'class="mac_address" placeholder="00:1B:44:11:3A:B7"',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'ip_adress_kehadiran')
            ->update([
                'attribute' => 'class="ip_address" placeholder="127.0.0.1"',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'id_pengunjung_kehadiran')
            ->update([
                'attribute' => 'class="alfanumerik" placeholder="ad02c373c2a8745d108aff863712fe92"',
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'id_pengunjung_kehadiran')
            ->update([
                'attribute' => 'class="alfanumerik" placeholder="ad02c373c2a8745d108aff863712fe92"',
            ]);

        // Ganti semua jenis option-kode dan option-value menjadi option
        DB::table('setting_aplikasi')
            ->whereIn('jenis', [
                'option-kode',
                'option-value',
            ])
            ->update([
                'jenis' => 'option',
            ]);

        if ($this->db->table_exists('setting_aplikasi_options')) {
            // Hapus tabel setting aplikasi options
            $hasil = $hasil && $this->dbforge->drop_table('setting_aplikasi_options');
        }

        return $hasil;
    }

    protected function migrasi_2022110951($hasil)
    {
        if (! $this->db->field_exists('satuan_waktu', 'pembangunan')) {
            $hasil = $hasil && $this->dbforge->add_column('pembangunan', [
                'satuan_waktu' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => '3',
                    'after'      => 'waktu',
                    'comment'    => '1 = Hari, 2 = Minggu, 3 = Bulan, 4 = Tahun',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2022111653($hasil)
    {
        if (! $this->db->field_exists('ip_address', 'pengaduan')) {
            $hasil = $hasil && $this->dbforge->add_column('pengaduan', [
                'ip_address' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                    'after'      => 'foto',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2022111654($hasil)
    {
        // Perbarui urutan pamong kades
        DB::table('tweb_desa_pamong')
            ->where('jabatan_id', RefJabatan::KADES)
            ->orWhere('jabatan_id', kades()->id)
            ->update([
                'urut' => 1,
            ]);

        // Perbarui urutan pamong sekdes
        DB::table('tweb_desa_pamong')
            ->where('jabatan_id', RefJabatan::SEKDES)
            ->orWhere('jabatan_id', sekdes()->id)
            ->update([
                'urut' => 2,
            ]);

        return $hasil;
    }

    protected function migrasi_2022111751($hasil)
    {
        // Pindahkan pengaturan Font dari menggunakan Enum ke pengaturan option
        DB::table('setting_aplikasi')
            ->where('key', 'font_surat')
            ->whereNull('option')
            ->update([
                'option' => json_encode([
                    'Andale Mono',
                    'Arial',
                    'Arial Black',
                    'Bookman Old Style',
                    'Comic Sans MS',
                    'Courier New',
                    'Georgia',
                    'Helvetica',
                    'Impact',
                    'Tahoma',
                    'Times New Roman',
                    'Trebuchet MS',
                    'Verdana',
                ]),
                'jenis'    => 'option',
                'kategori' => 'format_surat',
            ]);

        return $hasil;
    }

    protected function migrasi_2022112071($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Sebutan Pemerintah Desa',
            'key'        => 'sebutan_pemerintah_desa',
            'value'      => 'Pemerintah ' . ucwords(SettingAplikasi::where('key', 'sebutan_desa')->first()->value),
            'keterangan' => 'Sebutan Pemerintah Desa',
            'kategori'   => 'Pemerintah Desa',
        ]);

        return $hasil && $this->ubah_modul(18, [
            'modul' => '[Pemerintah Desa]',
        ]);
    }

    protected function migrasi_2022112151($hasil)
    {
        // Perbaiki data penduduk untuk data kepala rtm berdasarkan data tweb_rtm
        $daftar_rtm = DB::table('tweb_rtm')->get(['nik_kepala', 'no_kk']);

        if ($daftar_rtm) {
            foreach ($daftar_rtm as $key => $value) {
                DB::table('tweb_penduduk')
                    ->where('id', '=', $value->nik_kepala)
                    ->update([
                        'id_rtm'    => $value->no_kk,
                        'rtm_level' => HubunganRTMEnum::KEPALA_RUMAH_TANGGA,
                    ]);
            }
        }

        return $hasil;
    }

    protected function suratKeteranganKurangMampu($hasil)
    {
        return $hasil && $this->tambah_surat_tinymce([
            'nama'                => 'Keterangan Kurang Mampu',
            'kode_surat'          => 'S-11',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"textarea","kode":"[form_keperluan]","nama":"Keperluan","deskripsi":"Masukkan Keperluan","atribut":"required"}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1","kk_level":"1"}}',
            'mandiri'             => StatusEnum::YA,
            'syarat_surat'        => ['2', '3', '1'],
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 172px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left; height: 18px;\">2.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">ID BDT</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Id_bdT]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">3.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">No. KTP</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Nik]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Ttl]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 18px; text-align: left;\">5.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left; height: 18px;\">6.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Kewarganegaraan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[WArga_negara]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left; height: 18px;\">7.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90429%; text-align: left; height: 10px;\">8.</td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 10px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 10px;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90429%; height: 36px; text-align: left;\">7.<br /><br /></td>\r\n<td style=\"width: 30.5253%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Bahwa yang tersebut namanya di atas, sepanjang pengetahuan dan penelitian kami hingga saat dikeluarkannya surat keterangan ini memang benar Keluarga yang KURANG MAMPU dan tidak memiliki pengahasilan tetap.<br /><br /></p>\r\n<p style=\"text-indent: 30px; text-align: center;\"><span style=\"text-decoration: underline;\"><strong>DAFTAR TANGGUNGAN KELUARGA<br /></strong></span></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 138px;\" border=\"1\">\r\n<tbody>\r\n<tr style=\"height: 12px;\">\r\n<td style=\"width: 6.37205%; text-align: center; height: 12px;\"><span style=\"font-size: 10pt;\"><strong>NO.</strong></span></td>\r\n<td style=\"width: 20.8633%; text-align: center; height: 12px;\"><span style=\"font-size: 10pt;\"><strong>NIK</strong></span></td>\r\n<td style=\"width: 19.63%; text-align: center; height: 12px;\"><span style=\"font-size: 10pt;\"><strong>NAMA</strong></span></td>\r\n<td style=\"width: 13.4639%; text-align: center; height: 12px;\"><span style=\"font-size: 10pt;\"><strong>L / P</strong></span></td>\r\n<td style=\"width: 24.7683%; text-align: center; height: 12px;\"><span style=\"font-size: 10pt;\"><strong>TEMPAT TANGGAL LAHIR</strong></span></td>\r\n<td style=\"width: 14.7996%; text-align: center; height: 12px;\"><span style=\"font-size: 10pt;\"><strong>SHDK</strong></span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg1_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg1_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg1_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg1_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg1_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg1_hubungan_kk]</span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg2_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg2_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg2_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg2_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg2_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg2_hubungan_kk]</span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg3_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg3_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg3_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg3_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg3_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg3_hubungan_kk]</span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg4_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg4_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg4_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg4_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg4_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg4_hubungan_kk]</span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg5_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg5_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg5_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg5_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg5_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg5_hubungan_kk]</span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg6_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg6_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg6_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg6_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg6_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg6_hubungan_kk]</span></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 6.37205%; height: 18px; text-align: center;\"><span style=\"font-size: 10pt;\">[KLg7_no]</span></td>\r\n<td style=\"width: 20.8633%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg7_nik]</span></td>\r\n<td style=\"width: 19.63%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg7_nama]</span></td>\r\n<td style=\"width: 13.4639%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg7_jenis_kelamin]</span></td>\r\n<td style=\"width: 24.7683%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg7_ttl]</span></td>\r\n<td style=\"width: 14.7996%; height: 18px;\"><span style=\"font-size: 10pt;\">[KLg7_hubungan_kk]</span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-indent: 30px;\">Surat Keterangan ini dibuat untuk keperluan : <span style=\"text-decoration: underline;\"><strong>[Form_keperluaN]</strong></span></p>\r\n<p style=\"text-indent: 30px;\">Demikian surat keterangan ini dibuat dengan sebenarnya,  untuk dapat dipergunakan sebagaimana mestinya.</p>\r\n<p> </p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">Pemegang Surat</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_nama]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\">[NAma]</td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ]);
    }

    protected function suratKeteranganBedaIdentitas($hasil)
    {
        return $hasil && $this->tambah_surat_tinymce([
            'nama'                => 'Keterangan Beda Identitas',
            'kode_surat'          => '471.1',
            'masa_berlaku'        => 1,
            'satuan_masa_berlaku' => 'M',
            'orientasi'           => 'Potrait',
            'ukuran'              => 'F4',
            'margin'              => '{"kiri":1.78,"atas":0.63,"kanan":1.78,"bawah":1.37}',
            'qr_code'             => StatusEnum::YA,
            'kode_isian'          => '[{"tipe":"text","kode":"[form_kartu]","nama":"Kartu","deskripsi":"Masukkan Identitas dalam (nama kartu)","atribut":"class=\"required\""},{"tipe":"text","kode":"[form_no_identitas]","nama":"No Identitas","deskripsi":"Masukkan Nomer Identitas","atribut":"class=\"required\""},{"tipe":"text","kode":"[form_nama]","nama":"Nama","deskripsi":"Masukkan Nama","atribut":"class=\"required\""},{"tipe":"text","kode":"[form_tempat_lahir]","nama":"Tempat Lahir","deskripsi":"Masukkan Tempat Lahir","atribut":"class=\"required\""},{"tipe":"date","kode":"[form_tanggal_lahir]","nama":"Tanggal Lahir","deskripsi":"Masukkan Tanggal Lahir","atribut":"class=\"required\""},{"tipe":"textarea","kode":"[form_alamat_tempat_tinggal]","nama":"Alamat \/ Tempat Tinggal","deskripsi":"Masukkan Alamat \/ Tempat Tinggal","atribut":"class=\"required\""},{"tipe":"text","kode":"[form_jenis_kelamin]","nama":"Jenis Kelamin","deskripsi":"Masukkan Jenis Kelamin","atribut":"class=\"required\""},{"tipe":"text","kode":"[form_agama]","nama":"Agama","deskripsi":"Masukkan Agama","atribut":"class=\"required\""},{"tipe":"text","kode":"[form_pekerjaan]","nama":"Pekerjaan","deskripsi":"Masukkan Pekerjaan","atribut":"class=\"required\""},{"tipe":"textarea","kode":"[form_perbedaan]","nama":"Perbedaan","deskripsi":"Masukkan Perbedaan","atribut":"class=\"required\""},{"tipe":"textarea","kode":"[form_keterangan]","nama":"Keterangan","deskripsi":"Masukkan Keterangan","atribut":"class=\"required\""}]',
            'form_isian'          => '{"individu":{"sex":"","status_dasar":"1","kk_level":""}}',
            'mandiri'             => StatusEnum::TIDAK,
            'syarat_surat'        => null,
            'template'            => "
                <h4 style=\"margin: 0; text-align: center;\"><span style=\"text-decoration: underline;\">[JUdul_surat]</span></h4>\r\n<p style=\"margin: 0; text-align: center;\">Nomor : [Format_nomor_suraT]<br /><br /></p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Yang bertanda tangan di bawah ini [JaBatan] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten], Provinsi [NaMa_provinsi] menerangkan dengan sebenarnya bahwa :</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\"><strong>I.  Identitas dalam KK</strong></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 118px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 10px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 10px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 10px; text-align: left;\">1.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 10px;\">Nama Lengkap</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 10px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 10px; text-align: justify;\"><strong>[NAma]</strong></td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">2.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[TtL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">3..</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[AgAma]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">4.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Jenis_kelamin]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">5.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[PeKerjaan]</td>\r\n</tr>\r\n<tr style=\"height: 36px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 36px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 36px; text-align: left;\">6.<br /><br /></td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 36px;\">Alamat / Tempat Tinggal<br /><br /></td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 36px;\">:<br /><br /></td>\r\n<td style=\"width: 60.0206%; height: 36px; text-align: justify;\">[AlamaT] [Sebutan_desa] [NaMa_desa], Kecamatan [NaMa_kecamatan], [SeButan_kabupaten] [NaMa_kabupaten]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\"><strong>Ii. Identitas dalam [Form_kartU]</strong></p>\r\n<table style=\"border-collapse: collapse; width: 100%; height: 144px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">7.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">No. Identitas</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_no_identitaS]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">8.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Nama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_namA]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">9.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Tempat / Tanggal Lahir</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_tempat_lahiR], [Form_tanggal_lahiR]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">10.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Jenis Kelamin</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_jenis_kelamiN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">11.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Alamat / Tempat Tinggal</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_alamat_tempat_tinggaL]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">12.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Agama</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_agamA]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; text-align: left; height: 18px;\">13.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Pekerjaan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; text-align: justify; height: 18px;\">[Form_pekerjaaN]</td>\r\n</tr>\r\n<tr style=\"height: 18px;\">\r\n<td style=\"width: 4.31655%; text-align: center; height: 18px;\"> </td>\r\n<td style=\"width: 3.90545%; height: 18px; text-align: left;\">14.</td>\r\n<td style=\"width: 30.5242%; text-align: left; height: 18px;\">Keterangan</td>\r\n<td style=\"width: 1.2333%; text-align: center; height: 18px;\">:</td>\r\n<td style=\"width: 60.0206%; height: 18px; text-align: justify;\">[Form_keterangaN]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Adalah benar-benar warga [SeButan_desa] [NaMa_desa] dan merupakan orang yang sama namun terdapat perbedaan [Form_perbedaaN] seperti tersebut di atas. Adapun data yang benar dan dipakai seperti yang tercantum di Kartu Keluarga (KK).</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\">Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>\r\n<p style=\"text-align: justify; text-indent: 30px;\"> </p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[NaMa_desa], [TgL_surat]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Atas_namA]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"><br /><br /><br /><br /></td>\r\n<td style=\"width: 35%;\"> </td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%; text-align: center;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[Nama_pamonG]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 35%;\"> </td>\r\n<td style=\"width: 30%;\"> </td>\r\n<td style=\"width: 35%; text-align: center;\">[SEbutan_nip_desa] : [nip_pamong]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div style=\"text-align: center;\"><br />[qr_code]</div>
            ",
        ]);
    }

    protected function migrasi_2022112851($hasil)
    {
        if ($this->db->where('nama', 'SK Kades')->get('ref_dokumen')->row()) {
            $hasil = $hasil && $this->db->update(
                'ref_dokumen',
                ['nama' => 'Keputusan Kades'],
                ['id'   => 2]
            );
        }

        return $hasil;
    }

    protected function migrasi_2022112971($hasil)
    {
        // Sesuaikan attribut lama
        $surat_tinymce = DB::table('tweb_surat_format')
            ->whereIn('jenis', FormatSurat::TINYMCE)
            ->whereNotNull('kode_isian')
            ->pluck('kode_isian', 'id');
        if ($surat_tinymce) {
            foreach ($surat_tinymce as $id => $kode_isian) {
                DB::table('tweb_surat_format')
                    ->where('id', $id)
                    ->update([
                        'kode_isian' => str_replace('"required"', '"class=\"required\""', $kode_isian),
                    ]);
            }
        }

        return $hasil;
    }

    protected function migrasi_2022113052($hasil)
    {
        $tables = $this->db
            ->query("SELECT TABLE_NAME, TABLE_COLLATION FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->db->database}' AND TABLE_COLLATION != 'utf8_general_ci'")
            ->result_array();

        if ($tables) {
            foreach ($tables as $tbl) {
                if ($this->db->table_exists($tbl['TABLE_NAME'])) {
                    $hasil = $hasil && $this->db->query("ALTER TABLE {$tbl['TABLE_NAME']} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
                }
            }
        }

        return $hasil;
    }
}
