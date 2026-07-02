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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Database\Seeders\DataAwal;

use App\Models\Config;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\UserGrup;
use App\Services\Install\CreateGrupAksesService;
use App\Traits\Migrator;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DataAwalSeeder extends Seeder
{
    use Migrator;

    /**
     * Config ID untuk migrasi.
     */
    public $config_id;

    public function __construct()
    {
        $this->config_id = identitas('id');
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        cache()->forget('identitas_desa');

        // Ubah config
        $this->isi_config();

        // Pengaturan Aplikasi
        $this->tambah_pengaturan_aplikasi();

        // Tambah Modul
        $this->tambah_module();

        // Grup Pengguna
        $this->tambah_grup_pengguna();

        // Pengguna
        $this->tambah_pengguna();

        // Grup Akses
        $this->tambah_grup_akses();

        // Media Sosial
        $this->tambah_media_sosial();

        // Jam Kerja
        $this->tambah_jam_kerja();

        // Jabatan
        $this->tambah_jabatan();

        // Menu Anjungan
        $this->tambah_menu_anjungan();

        // Peta - Gis Simbol
        $this->tambah_gis_simbol();

        // Syarat Surat
        $this->tambah_syarat_surat();

        // Tambah Widget
        $this->tambah_widget();

        // Template Surat
        $this->tambah_template_surat();

        // Statistik - Umur
        $this->tambah_rentang_umur();

        // Notifikasi
        $this->notifikasi();

        // Keuangan Manual
        $this->keuangan_manual();
    }

    // Tambah syarat surat pada tabel surat
    public function tambah_module()
    {
        $this->call(SettingModul::class);
    }

    protected function isi_config()
    {
        if (! identitas() || empty($kode_desa = config_item('kode_desa')) || ! cek_koneksi_internet()) {
            return;
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
                (new Config())->flushQueryCache();
                log_message('notice', 'Berhasil menggunakan kode desa dari file config');
            } else {
                log_message('error', 'Gagal menggunakan kode desa dari file config');
            }

            cache()->forget('identitas_desa');
        }
    }

    protected function tambah_grup_pengguna()
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

        $this->data_awal('user_grup', $data, false);
    }

    protected function tambah_pengguna()
    {
        $data = [
            [
                'username'          => 'admin',
                'password'          => Hash::make('sid304'), // Password default: sid304
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

        $this->data_awal('user', $data);
    }

    protected function tambah_grup_akses()
    {
        (new CreateGrupAksesService())->handle();
    }

    // Tambah pengaturan aplikasi jika tidak ada
    protected function tambah_pengaturan_aplikasi()
    {
        $this->call(\Database\Seeders\DataAwal\SettingAplikasi::class);
    }

    protected function tambah_media_sosial()
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

        $this->data_awal('media_sosial', $data, true);
    }

    protected function tambah_jam_kerja()
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

        $this->data_awal('kehadiran_jam_kerja', $data, true);
    }

    protected function tambah_jabatan()
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

        $this->data_awal('ref_jabatan', $data);
    }

    // Tambah menu anjungan
    protected function tambah_menu_anjungan()
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
                'nama'      => 'IDM 2021',
                'icon'      => 'idm.svg',
                'link'      => 'status-idm/2021',
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

        $this->data_awal('anjungan_menu', $data);
    }

    protected function tambah_gis_simbol()
    {
        $this->call(GisSimbol::class);
    }

    // Tambah syarat surat pada tabel surat
    protected function tambah_syarat_surat()
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

        $this->data_awal('ref_syarat_surat', $data);
    }

    // Tambah syarat surat pada tabel surat
    protected function tambah_widget()
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

        $this->data_awal('widget', $data);
    }

    // Tambah template Tinymce
    protected function tambah_template_surat()
    {
        $uratTinyMCE  = getSuratBawaanTinyMCE()->toArray();
        $hasQrCodeTte = Schema::hasColumn('tweb_surat_format', 'qr_code_tte');

        foreach ($uratTinyMCE as $value) {
            if (! $hasQrCodeTte) {
                unset($value['qr_code_tte']);
            }

            $this->tambah_surat_tinymce($value);
        }

    }

    // Tambah rentang umum pada tabel tweb_penduduk_umur
    protected function tambah_rentang_umur()
    {
        $this->call(RentangUmur::class);
    }

    protected function notifikasi()
    {
        $data = [
            [
                'kode'           => 'persetujuan_penggunaan',
                'judul'          => '<i class="fa fa-file-text-o text-black"></i> &nbsp;Persetujuan Penggunaan OpenSID',
                'jenis'          => 'persetujuan',
                'isi'            => '<p><b>Untuk menggunakan OpenSID, Anda dan desa Anda perlu menyetujui ketentuan berikut:</b>\n                    <ol>\n                      <li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a>.</li>\n                     <li>OpenSID gratis dan disediakan "SEBAGAIMANA ADANYA", di mana segala tanggung jawab termasuk keamanan data desa ada pada pengguna.</li>\n                       <li>Pengguna paham bahwa setiap ubahan OpenSID juga berlisensi GPL V3 yang tidak dapat dimusnahkan, dan aplikasi ubahan itu juga sumber terbuka yang bebas disebarkan oleh pihak yang menerima.</li>\n                      <li>Pengguna mengetahui, paham dan menyetujui bahwa OpenSID akan mengirim data penggunaan ke server OpenDesa secara berkala untuk tujuan menyempurnakan OpenSID, dengan pengertian bahwa data yang dikirim sama sekali tidak berisi data identitas penduduk atau data sensitif desa lainnya.</li>\n                 </ol></p>\n                 <b>Apakah Anda dan desa Anda setuju dengan ketentuan di atas?</b>',
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
                'isi'            => '<p>Kami mendeteksi bahwa Anda telah mematikan fitur tracking. Bila dimatikan, penggunaan website desa Anda tidak akan tercatat di server OpenDesa dan tidak akan menerima informasi penting yang sesekali dikirim OpenDesa.</p>\n                   <br><b>Hidupkan kembali tracking untuk mendapatkan informasi dari OpenDesa?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2020-07-30 03:37:42',
                'updated_at'     => '2020-07-30 10:37:03',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'setting/aktifkan_tracking,notif/update_pengumuman',
                'aktif'          => 0,
            ],
        ];

        $this->data_awal('notifikasi', $data);
    }

    // Keuangan Manual
    protected function keuangan_manual()
    {
        // Cek apakah tabel ada
        if (Schema::hasTable('keuangan_manual_rinci_tpl')) {
            // Truncate tabel
            DB::table('keuangan_manual_rinci_tpl')->truncate();

            // Data yang akan di-insert
            $data = [
                ['id' => 1, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.1. Hasil Usaha Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 2, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.2. Hasil Aset Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 3, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.3. Swadaya, Partisipasi dan Gotong Royong', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 4, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.4. Lain-Lain Pendapatan Asli Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 5, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.1. Dana Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 6, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.2. Bagi Hasil Pajak dan Retribusi', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 7, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.3. Alokasi Dana Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 8, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.4. Bantuan Keuangan Provinsi', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 9, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.5. Bantuan Keuangan Kabupaten/Kota', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 10, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.1. Penerimaan dari Hasil Kerjasama Antar Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 11, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.2. Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 12, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.3. Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 13, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.4. Hibah dan Sumbangan dari Pihak Ketiga', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 14, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.5. Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 15, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.6. Bunga Bank', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 16, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.9. Lain-lain Pendapatan Desa Yang Sah', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 17, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.01 BIDANG PENYELENGGARAN PEMERINTAHAN DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 18, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.02 BIDANG PELAKSANAAN PEMBANGUNAN DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 19, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.03 BIDANG PEMBINAAN KEMASYARAKATAN DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 20, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.04 BIDANG PEMBERDAYAAN MASYARAKAT DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 21, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.05 BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 22, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.1. SILPA Tahun Sebelumnya', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 23, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.2. Pencairan Dana Cadangan', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 24, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.3. Hasil Penjualan Kekayaan Desa Yang Dipisahkan', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 25, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.9. Penerimaan Pembiayaan Lainnya', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 26, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.2.1. Pembentukan Dana Cadangan', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 27, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.2.2. Penyertaan Modal Desa', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
                ['id' => 28, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.2.9. Pengeluaran Pembiayaan Lainnya', 'Nilai_Anggaran' => 0, 'Nilai_Realisasi' => 0],
            ];

            // Insert data secara batch
            DB::table('keuangan_manual_rinci_tpl')->insert($data);
        }
    }
}
