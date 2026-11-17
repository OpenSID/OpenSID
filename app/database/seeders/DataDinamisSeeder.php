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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Database\Seeders;

use App\Actions\GrupAkses\DefaultGrupAkses;
use App\Actions\Modul\ImportModul;
use App\Actions\Setting\ImportSetting;
use App\Models\Config;
use App\Traits\Migrator;
use Illuminate\Database\Seeder;

class DataDinamisSeeder extends Seeder
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
        $this->tambahConfig();

        // Pengaturan Aplikasi
        $this->tambahPengaturanAplikasi();

        // Grup Pengguna
        $this->tambahUserGrup();

        // Pengguna
        $this->tambahUser();

        // Tambah Modul
        $this->tambahModul();

        // Grup Akses
        $this->tambahGrupAkses();

        // Media Sosial
        $this->tambahMediaSosial();

        // Jabatan
        $this->tambahJabatan();

        // Peta - Gis Simbol
        $this->tambahGisSimbol();

        // Syarat Surat
        $this->tambahSyaratSurat();

        // Tambah Widget
        $this->tambahWidget();

        // Template Surat
        $this->tambahTemplateSurat();

        // Statistik - Umur
        $this->tambahRentangUmur();

        // Notifikasi
        $this->notifikasi();

        // Kategori
        $this->kategori();

        // Keuangan template
        $this->keuanganTemplate();

        // Shortcut
        $this->shortcut();

        // Theme
        $this->theme();

        // Klasifikasi Surat
        $this->klasifikasiSurat();
    }

    // Tambah syarat surat pada tabel surat
    public function tambahModul()
    {
        (new ImportModul())->handle();
    }

    protected function tambahConfig()
    {
        if (! identitas() || empty($kode_desa = config_item('kode_desa')) || ! cek_koneksi_internet()) {
            return;
        }

        // Ambil data desa dari tracksid
        $data_desa = get_data_desa($kode_desa);

        if (null === $data_desa) {
            logger()->error("Kode desa {$kode_desa} di desa/config/config.php tidak ditemukan di " . config_item('server_pantau'));
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
                logger()->error('Gagal menggunakan kode desa dari file config');
            }

            cache()->forget('identitas_desa');
        }
    }

    protected function tambahUserGrup()
    {
        $this->call(UserGrupSeeder::class);
    }

    protected function tambahUser()
    {
        $this->call(UserSeeder::class);
    }

    protected function tambahGrupAkses()
    {
        (new DefaultGrupAkses())->handle();
    }

    protected function tambahPengaturanAplikasi()
    {
        (new ImportSetting())->handle();
    }

    protected function tambahMediaSosial()
    {
        $this->call(MediaSosialSeeder::class);
    }

    protected function tambahJabatan()
    {
        $this->call(RefJabatanSeeder::class);
    }

    protected function tambahGisSimbol()
    {
        $this->call(GisSimbolSeeder::class);
    }

    // Tambah syarat surat pada tabel surat
    protected function tambahSyaratSurat()
    {
        $this->call(RefSyaratSeeder::class);
    }

    // Tambah syarat surat pada tabel surat
    protected function tambahWidget()
    {
        $this->call(WidgetSeeder::class);
    }

    // Tambah template Tinymce
    protected function tambahTemplateSurat()
    {
        $uratTinyMCE = getSuratBawaanTinyMCE()->toArray();

        foreach ($uratTinyMCE as $value) {
            $this->tambah_surat_tinymce($value);
        }
    }

    // Tambah rentang umum pada tabel tweb_penduduk_umur
    protected function tambahRentangUmur()
    {
        $this->call(RentangUmurSeeder::class);
    }

    protected function notifikasi()
    {
        $this->call(NotifikasiSeeder::class);
    }

    protected function keuanganTemplate()
    {
        $this->call(KeuanganTemplateSeeder::class);
    }

    protected function kategori()
    {
        $this->call(KategoriSeeder::class);
    }

    protected function shortcut()
    {
        $this->call(ShortcutSeeder::class);
    }

    protected function theme()
    {
        theme_scan();
    }

    protected function klasifikasiSurat()
    {
        $this->call(KlasifikasiSuratSeeder::class);
    }
}
