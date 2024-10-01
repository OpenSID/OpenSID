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

use App\Models\Config;
use App\Models\Modul;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\UserGrup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_awal extends MY_Model
{
    public function up()
    {
        $hasil = true;

        cache()->forget('identitas_desa');

        // Ubah config
        $hasil = $hasil && $this->isi_config($hasil);

        // Pengaturan Aplikasi
        $hasil = $hasil && $this->tambah_pengaturan_aplikasi($hasil);

        // Tambah Modul
        $hasil = $hasil && $this->tambah_modul($hasil);

        // Grup Pengguna
        $hasil = $hasil && $this->tambah_grup_pengguna($hasil);

        // Pengguna
        $hasil = $hasil && $this->tambah_pengguna($hasil);

        // Grup Akses
        $hasil = $hasil && $this->tambah_grup_akses($hasil);

        // Media Sosial
        $hasil = $hasil && $this->tambah_media_sosial($hasil);

        // Jam Kerja
        $hasil = $hasil && $this->tambah_jam_kerja($hasil);

        // Jabatan
        $hasil = $hasil && $this->tambah_jabatan($hasil);

        // Klasifikasi Surat
        // $hasil = $hasil && $this->tambah_klasifikasi_surat($hasil);

        // Menu Anjungan
        $hasil = $hasil && $this->tambah_menu_anjungan($hasil);

        // Peta - Gis Simbol
        $hasil = $hasil && $this->tambah_gis_simbol($hasil);

        // Syarat Surat
        $hasil = $hasil && $this->tambah_syarat_surat($hasil);

        // Tambah Widget
        $hasil = $hasil && $this->tambah_widget($hasil);

        // Template Surat
        $hasil = $hasil && $this->tambah_template_surat($hasil);

        // Statistik - Umur
        $hasil = $hasil && $this->tambah_rentang_umur($hasil);

        // Master Analisis
        $hasil = $hasil && $this->impor_data_awal_analisis($hasil);

        // Notifikasi
        $hasil = $hasil && $this->notifikasi($hasil);

        // Keuangan Manual
        return $hasil && $this->keuangan_manual($hasil);
    }

    protected function isi_config($hasil)
    {
        if (! identitas() || empty($kode_desa = config_item('kode_desa')) || ! cek_koneksi_internet()) {
            return $hasil;
        }

        // Ambil data desa dari tracksid
        $data_desa = get_data_desa($kode_desa);

        if (null === $data_desa) {
            log_message('error', "Kode desa {$kode_desa} di desa/config/config.php tidak ditemukan di " . config_item('server_pantau'));
        } else {
            $desa = $data_desa;
            $data = [
                'nama_desa'         => nama_desa($desa->nama_desa),
                'kode_desa'         => bilangan($kode_desa),
                'nama_kecamatan'    => nama_terbatas($desa->nama_kec),
                'kode_kecamatan'    => bilangan($desa->kode_kec),
                'nama_kabupaten'    => ucwords(hapus_kab_kota(nama_terbatas($desa->nama_kab))),
                'kode_kabupaten'    => bilangan($desa->kode_kab),
                'nama_propinsi'     => ucwords(nama_terbatas($desa->nama_prov)),
                'kode_propinsi'     => bilangan($desa->kode_prov),
                'nama_kepala_camat' => '',
                'nip_kepala_camat'  => '',
            ];

            if (Config::appKey()->update($data)) {
                log_message('notice', 'Berhasil menggunakan kode desa dari file config');
            } else {
                log_message('error', 'Gagal menggunakan kode desa dari file config');
            }

            cache()->forget('identitas_desa');
        }

        return $hasil;
    }

    protected function tambah_grup_pengguna($hasil)
    {
        $data = [
            [
                'nama'       => 'Administrator',
                'slug'       => 'administrator',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Operator',
                'slug'       => 'operator',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Redaksi',
                'slug'       => 'redaksi',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Kontributor',
                'slug'       => 'kontributor',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Satgas Covid-19',
                'slug'       => 'satgas-covid-19',
                'jenis'      => 2,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
        ];

        return $hasil && $this->data_awal('user_grup', $data, false);
    }

    protected function tambah_pengguna($hasil)
    {
        $data = [
            [
                'username'          => 'admin',
                'password'          => '$2y$10$CfFhuvLXa3RNotqOPYyW2.JujLbAbZ4YO0PtxIRBz4QDLP0/pfH6.',
                'id_grup'           => UserGrup::where('nama', 'Administrator')->first()->id,
                'email'             => null,
                'id_telegram'       => '0',
                'last_login'        => '2022-02-28 19:55:01',
                'email_verified_at' => null,
                'active'            => 1,
                'nama'              => 'Administrator',
                'company'           => 'OpenDesa',
                'phone'             => null,
                'foto'              => 'kuser.png',
                'session'           => md5(now()),
            ],
        ];

        return $hasil && $this->data_awal('user', $data);
    }

    protected function tambah_grup_akses($hasil)
    {
        $this->load->model('seeders/dataAwal/GrupAkses', 'grupAkses');
        $data = $this->grupAkses->getData();

        foreach ($data as $row) {
            $dataInsert = [
                'config_id' => $this->config_id,
                'id_grup'   => UserGrup::where('nama', $row['grup'])->first()->id,
                'id_modul'  => Modul::when($row['slug'] == 'klasfikasi-surat', static function ($query): void {
                    // perubahan modul 'klasfikasi-surat' menjadi 'klasifikasi-surat'
                    // membuat migrasi selanjutnya tidak berjalan, gunakan query
                    // untuk mencari 'klasfikasi-surat' atau 'klasifikasi-surat'
                    $query->where('slug', 'klasfikasi-surat')->orWhere('slug', 'klasifikasi-surat');
                }, static function ($query) use ($row): void {
                    // default query
                    $query->where('slug', $row['slug']);
                })
                    ->first()
                    ->id,
                'akses' => $row['akses'],
            ];
            if (empty($dataInsert['id_modul'])) {
                log_message('error', 'id_modul_null -- ' . json_encode($row));

                continue;
            }
            $hasil = $hasil && DB::table('grup_akses')->insert($dataInsert);
        }

        return $hasil;
    }

    // Tambah pengaturan aplikasi jika tidak ada
    protected function tambah_pengaturan_aplikasi($hasil)
    {
        $this->load->model('seeders/dataAwal/SettingAplikasi', 'settingAplikasi');
        $data = $this->settingAplikasi->getData();

        $hasil = $this->data_awal('setting_aplikasi', $data, true);

        // Hapus cache menu navigasi
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    protected function tambah_media_sosial($hasil)
    {
        $data = [
            [
                'gambar'  => 'fb.png',
                'link'    => null,
                'nama'    => 'Facebook',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'twt.png',
                'link'    => null,
                'nama'    => 'Twitter',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'yb.png',
                'link'    => null,
                'nama'    => 'YouTube',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'ins.png',
                'link'    => null,
                'nama'    => 'Instagram',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'wa.png',
                'link'    => null,
                'nama'    => 'WhatsApp',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'tg.png',
                'link'    => null,
                'nama'    => 'Telegram',
                'tipe'    => 1,
                'enabled' => 2,
            ],
        ];

        return $hasil && $this->data_awal('media_sosial', $data, true);
    }

    protected function tambah_jam_kerja($hasil)
    {
        $data = [
            [
                'nama_hari'  => 'Senin',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 1,
            ],
            [
                'nama_hari'  => 'Selasa',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 1,
            ],
            [
                'nama_hari'  => 'Rabu',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 1,
            ],
            [
                'nama_hari'  => 'Kamis',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 1,
            ],
            [
                'nama_hari'  => 'Jumat',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 1,
            ],
            [
                'nama_hari'  => 'Sabtu',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 0,
            ],
            [
                'nama_hari'  => 'Minggu',
                'jam_masuk'  => '08:00:00',
                'jam_keluar' => '16:00:00',
                'status'     => 0,
            ],
        ];

        return $hasil && $this->data_awal('kehadiran_jam_kerja', $data, true);
    }

    protected function tambah_jabatan($hasil)
    {
        $data = [
            [
                'nama'  => 'Kepala ' . ucwords(SettingAplikasi::where('key', 'sebutan_desa')->first()->value ?? 'desa'),
                'jenis' => RefJabatan::KADES,
            ],
            [
                'nama'  => 'Sekretaris',
                'jenis' => RefJabatan::SEKDES,
            ],
        ];

        return $hasil && $this->data_awal('ref_jabatan', $data);
    }

    protected function tambah_klasifikasi_surat($hasil)
    {
        $this->load->model('seeders/dataAwal/KlasifikasiSurat', 'klasifikasiSurat');
        $data = $this->klasifikasiSurat->getData();

        return $hasil && $this->data_awal('klasifikasi_surat', $data);
    }

    // Tambah menu anjungan
    protected function tambah_menu_anjungan($hasil)
    {
        $data = [
            [
                'nama'      => 'Peta Desa',
                'icon'      => 'peta.svg',
                'link'      => 'peta',
                'link_tipe' => 5,
                'urut'      => 1,
                'status'    => 1,
            ],
            [
                'nama'      => 'Informasi Pubik',
                'icon'      => 'protected.svg',
                'link'      => 'informasi_publik',
                'link_tipe' => 5,
                'urut'      => 2,
                'status'    => 1,
            ],
            [
                'nama'      => 'Data Pekerjaan',
                'icon'      => 'statistik.svg',
                'link'      => 'statistik/1',
                'link_tipe' => 2,
                'urut'      => 3,
                'status'    => 1,
            ],
            [
                'nama'      => 'Layanan Mandiri',
                'icon'      => 'mandiri.svg',
                'link'      => 'layanan-mandiri/beranda',
                'link_tipe' => 5,
                'urut'      => 4,
                'status'    => 1,
            ],
            [
                'nama'      => 'Lapak',
                'icon'      => 'lapak.svg',
                'link'      => 'lapak',
                'link_tipe' => 5,
                'urut'      => 5,
                'status'    => 1,
            ],
            [
                'nama'      => 'Keuangan',
                'icon'      => 'keuangan.svg',
                'link'      => 'artikel/100',
                'link_tipe' => 6,
                'urut'      => 6,
                'status'    => 1,
            ],
            [
                'nama'      => 'IDM 2020',
                'icon'      => 'idm.svg',
                'link'      => 'status-idm/2022',
                'link_tipe' => 10,
                'urut'      => 7,
                'status'    => 1,
            ],
        ];

        $from  = FCPATH . LOKASI_ICON_MENU_ANJUNGAN_DEFAULT . 'contoh/';
        $to    = FCPATH . LOKASI_ICON_MENU_ANJUNGAN;
        $files = array_filter(glob("{$from}*"), 'is_file');

        foreach ($files as $file) {
            copy($file, $to . basename($file));
        }

        return $hasil && $this->data_awal('anjungan_menu', $data);
    }

    protected function tambah_gis_simbol($hasil)
    {
        $this->load->model('seeders/dataAwal/GisSimbol', 'gisSimbol');
        $data = $this->gisSimbol->getData();

        return $hasil && $this->data_awal('gis_simbol', $data);
    }

    // Tambah syarat surat pada tabel surat
    protected function tambah_syarat_surat($hasil)
    {
        $data = [
            [
                'ref_syarat_nama' => 'Surat Pengantar RT/RW',
            ],
            [
                'ref_syarat_nama' => 'Fotokopi KK',
            ],
            [
                'ref_syarat_nama' => 'Fotokopi KTP',
            ],
            [
                'ref_syarat_nama' => 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan',
            ],
            [
                'ref_syarat_nama' => 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak',
            ],
            [
                'ref_syarat_nama' => 'Surat Pindah Datang dari tempat asal',
            ],
            [
                'ref_syarat_nama' => 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter',
            ],
            [
                'ref_syarat_nama' => 'Surat Keterangan Cerai',
            ],
            [
                'ref_syarat_nama' => 'Fotokopi Ijasah Terakhir',
            ],
            [
                'ref_syarat_nama' => 'SK. PNS/KARIP/SK. TNI – POLRI',
            ],
            [
                'ref_syarat_nama' => 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan',
            ],
            [
                'ref_syarat_nama' => 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)',
            ],
        ];

        return $hasil && $this->data_awal('ref_syarat_surat', $data);
    }

    // Tambah syarat surat pada tabel surat
    protected function tambah_widget($hasil)
    {
        $data = [
            [
                'isi'          => '<p><iframe src="https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="100%"></iframe></p> ',
                'enabled'      => 2,
                'judul'        => 'Peta Desa',
                'jenis_widget' => 3,
                'urut'         => 1,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'agenda.php',
                'enabled'      => 1,
                'judul'        => 'Agenda',
                'jenis_widget' => 1,
                'urut'         => 6,
                'form_admin'   => 'web/tab/1000',
                'setting'      => '',
            ],
            [
                'isi'          => 'galeri.php',
                'enabled'      => 1,
                'judul'        => 'Galeri',
                'jenis_widget' => 1,
                'urut'         => 8,
                'form_admin'   => 'gallery',
                'setting'      => '',
            ],
            [
                'isi'          => 'statistik.php',
                'enabled'      => 1,
                'judul'        => 'Statistik',
                'jenis_widget' => 1,
                'urut'         => 4,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'komentar.php',
                'enabled'      => 1,
                'judul'        => 'Komentar',
                'jenis_widget' => 1,
                'urut'         => 10,
                'form_admin'   => 'komentar',
                'setting'      => '',
            ],
            [
                'isi'          => 'media_sosial.php',
                'enabled'      => 1,
                'judul'        => 'Media Sosial',
                'jenis_widget' => 1,
                'urut'         => 11,
                'form_admin'   => 'sosmed',
                'setting'      => '',
            ],
            [
                'isi'          => 'peta_lokasi_kantor.php',
                'enabled'      => 1,
                'judul'        => 'Peta Lokasi Kantor',
                'jenis_widget' => 1,
                'urut'         => 13,
                'form_admin'   => 'identitas_desa/maps/kantor',
                'setting'      => '',
            ],
            [
                'isi'          => 'statistik_pengunjung.php',
                'enabled'      => 1,
                'judul'        => 'Statistik Pengunjung',
                'jenis_widget' => 1,
                'urut'         => 14,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'arsip_artikel.php',
                'enabled'      => 1,
                'judul'        => 'Arsip Artikel',
                'jenis_widget' => 1,
                'urut'         => 5,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'aparatur_desa.php',
                'enabled'      => 1,
                'judul'        => 'Aparatur Desa',
                'jenis_widget' => 1,
                'urut'         => 9,
                'form_admin'   => 'web_widget/admin/aparatur_desa',
                'setting'      => '{"overlay":"1"}',
            ],
            [
                'isi'          => 'sinergi_program.php',
                'enabled'      => 1,
                'judul'        => 'Sinergi Program',
                'jenis_widget' => 1,
                'urut'         => 7,
                'form_admin'   => 'web_widget/admin/sinergi_program',
                'setting'      => '[]',
            ],
            [
                'isi'          => 'menu_kategori.php',
                'enabled'      => 1,
                'judul'        => 'Menu Kategori',
                'jenis_widget' => 1,
                'urut'         => 2,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'peta_wilayah_desa.php',
                'enabled'      => 1,
                'judul'        => 'Peta Wilayah Desa',
                'jenis_widget' => 1,
                'urut'         => 12,
                'form_admin'   => 'identitas_desa/maps/wilayah',
                'setting'      => '',
            ],
            [
                'isi'          => 'keuangan.php',
                'enabled'      => 1,
                'judul'        => 'Keuangan',
                'jenis_widget' => 1,
                'urut'         => 15,
                'form_admin'   => 'keuangan/impor_data',
                'setting'      => '',
            ],
        ];

        return $hasil && $this->data_awal('widget', $data);
    }

    // Tambah template Tinymce
    protected function tambah_template_surat($hasil)
    {
        $uratTinyMCE = getSuratBawaanTinyMCE()->toArray();

        foreach ($uratTinyMCE as $value) {
            $hasil = $hasil && $this->tambah_surat_tinymce($value);
        }

        return $hasil;
    }

    // Tambah rentang umum pada tabel tweb_penduduk_umur
    protected function tambah_rentang_umur($hasil)
    {
        $this->load->model('seeders/dataAwal/RentangUmur', 'rentangUmur');
        $data = $this->rentangUmur->getData();

        return $hasil && $this->data_awal('tweb_penduduk_umur', $data);
    }

    // Tambah syarat surat pada tabel surat
    public function tambah_modul($hasil): bool
    {
        $this->load->model('seeders/dataAwal/SettingModul', 'settingModul');
        $data   = $this->settingModul->getData();
        $parent = [
            '2'   => 'kependudukan',
            '3'   => 'statistik',
            '4'   => 'layanan-surat',
            '5'   => 'analisis',
            '6'   => 'bantuan',
            '7'   => 'pertanahan',
            '9'   => 'pemetaan',
            '10'  => 'hubung-warga',
            '11'  => 'pengaturan',
            '13'  => 'admin-web',
            '14'  => 'layanan-mandiri',
            '15'  => 'sekretariat',
            '200' => 'info-desa',
            '201' => 'keuangan',
            '206' => 'kesehatan',
            '220' => 'pembangunan',
            '301' => 'buku-administrasi-desa',
            '312' => 'anjungan',
            '324' => 'lapak',
            '334' => 'pengaduan',
            '337' => 'kehadiran',
            '343' => 'opendk',
            '352' => 'satu-data',
            '354' => 'buku-tamu',
        ];
        // jika parent belum ada maka tambahkan dulu
        $cekParent = DB::table('setting_modul')->where(['slug' => 'kependudukan', 'config_id' => $this->config_id])->count();
        if (! $cekParent) {
            $slugParent = implode("','", $parent);
            DB::statement("
                insert into setting_modul (config_id, modul, slug, url, aktif, ikon, urut, `level`, hidden , ikon_kecil , parent)
                select {$this->config_id}, modul, slug, url, aktif, ikon, urut, `level`, hidden , ikon_kecil , parent  from setting_modul where config_id = 1 and slug in ('{$slugParent}')
            ");
        }
        $hasil = $hasil && $this->data_awal('setting_modul', $data);

        foreach ($parent as $key => $value) {
            DB::table('setting_modul')->where('id', $key)->update(['slug' => $value]);

            // Cari parent_id
            $parent_id = DB::table('setting_modul')->where('config_id', $this->config_id)->where('slug', $value)->value('id');

            // Update parent submodul
            DB::table('setting_modul')->where('config_id', $this->config_id)->where('parent', $key)->update(['parent' => $parent_id]);
        }

        return $hasil;
    }

    protected function impor_data_awal_analisis($hasil)
    {
        $this->load->model('database_model');
        $this->database_model->impor_data_awal_analisis();

        return $hasil;
    }

    protected function notifikasi($hasil)
    {
        $data = [
            [
                'kode'           => 'persetujuan_penggunaan',
                'judul'          => '<i class="fa fa-file-text-o text-black"></i> &nbsp;Persetujuan Penggunaan OpenSID',
                'jenis'          => 'persetujuan',
                'isi'            => '<p><b>Untuk menggunakan OpenSID, anda dan desa anda perlu menyetujui ketentuan berikut:</b>\n                    <ol>\n                      <li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a>.</li>\n                     <li>OpenSID gratis dan disediakan "SEBAGAIMANA ADANYA", di mana segala tanggung jawab termasuk keamanan data desa ada pada pengguna.</li>\n                       <li>Pengguna paham bahwa setiap ubahan OpenSID juga berlisensi GPL V3 yang tidak dapat dimusnahkan, dan aplikasi ubahan itu juga sumber terbuka yang bebas disebarkan oleh pihak yang menerima.</li>\n                      <li>Pengguna mengetahui, paham dan menyetujui bahwa OpenSID akan mengirim data penggunaan ke server OpenDesa secara berkala untuk tujuan menyempurnakan OpenSID, dengan pengertian bahwa data yang dikirim sama sekali tidak berisi data identitas penduduk atau data sensitif desa lainnya.</li>\n                 </ol></p>\n                 <b>Apakah anda dan desa anda setuju dengan ketentuan di atas?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2022-03-01 04:16:23',
                'updated_at'     => '2021-12-01 04:16:23',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'notif/update_pengumuman,siteman/logout',
                'aktif'          => 1,
            ],
            [
                'kode'           => 'tracking_off',
                'judul'          => '<i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Peringatan Tracking Off',
                'jenis'          => 'peringatan',
                'isi'            => '<p>Kami mendeteksi bahwa anda telah mematikan fitur tracking. Bila dimatikan, penggunaan website desa anda tidak akan tercatat di server OpenDesa dan tidak akan menerima informasi penting yang sesekali dikirim OpenDesa.</p>\n                   <br><b>Hidupkan kembali tracking untuk mendapatkan informasi dari OpenDesa?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2020-07-30 03:37:42',
                'updated_at'     => '2020-07-30 10:37:03',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'setting/aktifkan_tracking,notif/update_pengumuman',
                'aktif'          => 0,
            ],
        ];

        return $hasil && $this->data_awal('notifikasi', $data);
    }

    // Keuangan Manual
    protected function keuangan_manual($hasil)
    {
        //insert keuangan_manual_rinci_tpl
        $this->db->truncate('keuangan_manual_rinci_tpl');
        $query = "INSERT INTO `keuangan_manual_rinci_tpl` (`id`, `Tahun`, `Kd_Akun`, `Kd_Keg`, `Kd_Rincian`, `Nilai_Anggaran`, `Nilai_Realisasi`) VALUES
            (1, '2020', '4.PENDAPATAN', '', '4.1.1. Hasil Usaha Desa', '0', '0'),
            (2, '2020', '4.PENDAPATAN', '', '4.1.2. Hasil Aset Desa', '0', '0'),
            (3, '2020', '4.PENDAPATAN', '', '4.1.3. Swadaya, Partisipasi dan Gotong Royong', '0', '0'),
            (4, '2020', '4.PENDAPATAN', '', '4.1.4. Lain-Lain Pendapatan Asli Desa', '0', '0'),
            (5, '2020', '4.PENDAPATAN', '', '4.2.1. Dana Desa', '0', '0'),
            (6, '2020', '4.PENDAPATAN', '', '4.2.2. Bagi Hasil Pajak dan Retribusi', '0', '0'),
            (7, '2020', '4.PENDAPATAN', '', '4.2.3. Alokasi Dana Desa', '0', '0'),
            (8, '2020', '4.PENDAPATAN', '', '4.2.4. Bantuan Keuangan Provinsi', '0', '0'),
            (9, '2020', '4.PENDAPATAN', '', '4.2.5. Bantuan Keuangan Kabupaten/Kota', '0', '0'),
            (10, '2020', '4.PENDAPATAN', '', '4.3.1. Penerimaan dari Hasil Kerjasama Antar Desa', '0', '0'),
            (11, '2020', '4.PENDAPATAN', '', '4.3.2. Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga', '0', '0'),
            (12, '2020', '4.PENDAPATAN', '', '4.3.3. Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa', '0', '0'),
            (13, '2020', '4.PENDAPATAN', '', '4.3.4. Hibah dan Sumbangan dari Pihak Ketiga', '0', '0'),
            (14, '2020', '4.PENDAPATAN', '', '4.3.5. Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya', '0', '0'),
            (15, '2020', '4.PENDAPATAN', '', '4.3.6. Bunga Bank', '0', '0'),
            (16, '2020', '4.PENDAPATAN', '', '4.3.9. Lain-lain Pendapatan Desa Yang Sah', '0', '0'),
            (17, '2020', '5.BELANJA', '00.0000.01 BIDANG PENYELENGGARAN PEMERINTAHAN DESA', '5.0.0', '0', '0'),
            (18, '2020', '5.BELANJA', '00.0000.02 BIDANG PELAKSANAAN PEMBANGUNAN DESA', '5.0.0', '0', '0'),
            (19, '2020', '5.BELANJA', '00.0000.03 BIDANG PEMBINAAN KEMASYARAKATAN DESA', '5.0.0', '0', '0'),
            (20, '2020', '5.BELANJA', '00.0000.04 BIDANG PEMBERDAYAAN MASYARAKAT DESA', '5.0.0', '0', '0'),
            (21, '2020', '5.BELANJA', '00.0000.05 BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA', '5.0.0', '0', '0'),
            (22, '2020', '6.PEMBIAYAAN', '', '6.1.1. SILPA Tahun Sebelumnya', '0', '0'),
            (23, '2020', '6.PEMBIAYAAN', '', '6.1.2. Pencairan Dana Cadangan', '0', '0'),
            (24, '2020', '6.PEMBIAYAAN', '', '6.1.3. Hasil Penjualan Kekayaan Desa Yang Dipisahkan', '0', '0'),
            (25, '2020', '6.PEMBIAYAAN', '', '6.1.9. Penerimaan Pembiayaan Lainnya', '0', '0'),
            (26, '2020', '6.PEMBIAYAAN', '', '6.2.1. Pembentukan Dana Cadangan', '0', '0'),
            (27, '2020', '6.PEMBIAYAAN', '', '6.2.2. Penyertaan Modal Desa', '0', '0'),
            (28, '2020', '6.PEMBIAYAAN', '', '6.2.9. Pengeluaran Pembiayaan Lainnya', '0', '0')";

        $this->db->query($query);

        return true;
    }
}
