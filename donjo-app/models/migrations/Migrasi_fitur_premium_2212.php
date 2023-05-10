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
use App\Models\RefJabatan;
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
        $hasil = $hasil && $this->migrasi_2022112151($hasil);
        $hasil = $hasil && $this->migrasi_2022112351($hasil);
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
            ->update([
                'urut' => 1,
            ]);

        // Perbarui urutan pamong sekdes
        DB::table('tweb_desa_pamong')
            ->where('jabatan_id', RefJabatan::SEKDES)
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

    protected function migrasi_2022112351($hasil)
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
