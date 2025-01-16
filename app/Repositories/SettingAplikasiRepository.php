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

namespace App\Repositories;

use App\Libraries\TinyMCE;
use App\Models\Config;
use App\Models\SettingAplikasi;

class SettingAplikasiRepository
{
    protected $setting;

    public function __construct()
    {
        $this->setting = new SettingAplikasi();
    }

    /**
     * Mengambil semua data pengaturan.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->setting->orderBy('key')->get();
    }

    public function getSetting()
    {
        // Retrieve all settings (assuming it's from a database or repository)
        $settings = $this->get();

        // Apply logic to each setting
        $settings->map(function ($setting) {
            // Apply settings logic based on environment or config values
            $this->applySetting($setting);

            return $setting;
        });

        // Return settings collection, you can pluck key and value here if needed
        return $settings->pluck('value', 'key');
    }

    /**
     * Mengambil data pengaturan berdasarkan kategori.
     *
     * @param string $kategori
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByKategori($kategori)
    {
        return $this->setting->where('kategori', $kategori)->get();
    }

    /**
     * Mengambil pengaturan pertama berdasarkan key.
     *
     * @param string $key
     *
     * @return SettingAplikasi|null
     */
    public function firstByKey($key)
    {
        return $this->setting->where('key', $key)->first();
    }

    /**
     * Memperbarui pengaturan berdasarkan key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function updateWithKey($key, $value)
    {
        return $this->setting->where('key', $key)->update(['value' => $value]) > 0;
    }

    /**
     * Membersihkan cache query.
     *
     * @return void
     */
    public function flushCache()
    {
        $this->setting->flushQueryCache();
    }

    /**
     * Apply settings logic to a given setting instance.
     */
    public function applySetting(SettingAplikasi $setting)
    {
        // Set timezone
        date_default_timezone_set($setting->timezone);

        // Default values for certain keys
        $defaultValues = [
            'header_surat'             => TinyMCE::HEADER,
            'footer_surat'             => TinyMCE::FOOTER,
            'footer_surat_tte'         => TinyMCE::FOOTER_TTE,
            'link_feed'                => 'https://www.covid19.go.id/feed/',
            'anjungan_layar'           => 1,
        ];

        // Loop through the default values and apply them if setting is empty
        foreach ($defaultValues as $key => $defaultValue) {
            if ($setting->key === $key && empty($setting->value)) {
                $setting->value = $defaultValue;
            }
        }

        // Set value based on config if the setting is empty and the key matches
        $configKeys = [
            'mapbox_key'                  => 'mapbox_key',
            'google_api_key'              => 'google_api_key',
            'google_recaptcha_site_key'   => 'google_recaptcha_site_key',
            'google_recaptcha_secret_key' => 'google_recaptcha_secret_key',
            'google_recaptcha'            => 'google_recaptcha',
        ];

        foreach ($configKeys as $settingKey => $configKey) {
            if ($setting->key === $settingKey && empty($setting->value) && ! empty(config_item($configKey))) {
                $setting->value = config_item($configKey);
            }
        }

        // Apply 'layanan_opendesa_token' based on environment or config
        if ($setting->key === 'layanan_opendesa_token' && empty($setting->value)) {
            if ((ENVIRONMENT === 'development') || config_item('token_layanan')) {
                $setting->value = config_item('token_layanan');
            }
        }

        // Apply 'user_admin' from config
        if ($setting->key === 'user_admin') {
            $setting->value = config_item('user_admin');
        }

        // Apply desa names for kepala_desa and sekretaris_desa
        if ($setting->key === 'sebutan_kepala_desa' && empty($setting->value)) {
            $setting->value = kades()->nama;
        }

        if ($setting->key === 'sebutan_sekretaris_desa' && empty($setting->value)) {
            $setting->value = sekdes()->nama;
        }

        // Check if multiple desa exists
        if ($setting->key === 'multi_desa' && empty($setting->value)) {
            $setting->value = Config::count() > 1;
        }

        // Apply margins for surat and surat_dinas
        $this->applyMargins($setting);

        $setting->value = SebutanDesa($setting->value);

        return $setting;
    }

    /**
     * Apply margin logic for surat and surat_dinas settings.
     */
    private function applyMargins(SettingAplikasi $setting)
    {
        $marginKeys = ['surat_margin', 'surat_dinas_margin'];

        foreach ($marginKeys as $key) {
            if ($setting->key === $key && empty($setting->value)) {
                $margins        = json_decode($setting->value, true);
                $setting->value = json_encode([
                    "{$key}_cm_to_mm" => [
                        $margins['kiri'] * 10,
                        $margins['atas'] * 10,
                        $margins['kanan'] * 10,
                        $margins['bawah'] * 10,
                    ],
                ]);
            }
        }
    }

    public static function applySettingCI($ci): void
    {        
        $settings = SettingAplikasi::orderBy('key')->get();
        $ci->list_setting = $settings;
        $ci->setting      = (object) $settings->pluck('value', 'key')
            ->map(static fn ($value, $key) => SebutanDesa($value))
            ->toArray();

        //  https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
        date_default_timezone_set($ci->setting?->timezone); // ganti ke timezone lokal

        // Ambil google api key dari desa/config/config.php kalau tidak ada di database
        if (empty($ci->setting?->mapbox_key) && ! empty(config_item('mapbox_key'))) {
            $ci->setting->mapbox_key = config_item('mapbox_key');
        }

        if (empty($ci->setting?->google_api_key) && ! empty(config_item('google_api_key'))) {
            $ci->setting->google_api_key = config_item('google_api_key');
        }

        if (empty($ci->setting?->google_recaptcha_site_key) && ! empty(config_item('google_recaptcha_site_key'))) {
            $ci->setting->google_recaptcha_site_key = config_item('google_recaptcha_site_key');
        }

        if (empty($ci->setting?->google_recaptcha_secret_key) && ! empty(config_item('google_recaptcha_secret_key'))) {
            $ci->setting->google_recaptcha_secret_key = config_item('google_recaptcha_secret_key');
        }

        if (empty($ci->setting?->google_recaptcha) && ! empty(config_item('google_recaptcha'))) {
            $ci->setting->google_recaptcha = config_item('google_recaptcha');
        }

        if (empty($ci->setting?->header_surat)) {
            $ci->setting->header_surat = TinyMCE::HEADER;
        }

        if (empty($ci->setting?->footer_surat)) {
            $ci->setting->footer_surat = TinyMCE::FOOTER;
        }

        if (empty($ci->setting?->footer_surat_tte)) {
            $ci->setting->footer_surat_tte = TinyMCE::FOOTER_TTE;
        }

        // Ganti token_layanan sesuai config untuk mempermudah development
        if ((ENVIRONMENT == 'development') || config_item('token_layanan')) {
            $ci->setting->layanan_opendesa_token = config_item('token_layanan');
        }

        $ci->setting->user_admin = config_item('user_admin');

        // Kalau folder tema ubahan tidak ditemukan, ganti dengan tema default
        $pos = strpos($ci->setting?->web_theme, 'desa/');
        if ($pos !== false) {
            $folder = FCPATH . '/desa/themes/' . substr($ci->setting?->web_theme, $pos + strlen('desa/'));
            if (! file_exists($folder)) {
                $ci->setting->web_theme = 'esensi';
            }
        }

        // Sebutan kepala desa diambil dari tabel ref_jabatan dengan jenis = 1
        // Diperlukan karena masih banyak yang menggunakan variabel ini, hapus jika tidak digunakan lagi
        $ci->setting->sebutan_kepala_desa = kades()->nama;

        // Sebutan sekretaris desa diambil dari tabel ref_jabatan dengan jenis = 2
        $ci->setting->sebutan_sekretaris_desa = sekdes()->nama;

        // Setting Multi Database untuk OpenKab
        $ci->setting->multi_desa = Config::count() > 1;

        // Feeds
        if (empty($ci->setting?->link_feed)) {
            $ci->setting->link_feed = 'https://www.covid19.go.id/feed/';
        }

        if (empty($ci->setting?->anjungan_layar)) {
            $ci->setting->anjungan_layar = 1;
        }

        if (empty($ci->setting?->sebutan_anjungan_mandiri)) {
            $ci->setting->sebutan_anjungan_mandiri = SebutanDesa('Anjungan [desa] Mandiri');
        }

        // Konversi nilai margin global dari cm ke mm
        $margins                              = json_decode($ci->setting?->surat_margin, true);
        $ci->setting->surat_margin_cm_to_mm = [
            $margins['kiri'] * 10,
            $margins['atas'] * 10,
            $margins['kanan'] * 10,
            $margins['bawah'] * 10,
        ];

        // Konversi nilai margin surat dinas global dari cm ke mm
        $margins                                    = json_decode($ci->setting?->surat_dinas_margin, true);
        $ci->setting->surat_dinas_margin_cm_to_mm = [
            $margins['kiri'] * 10,
            $margins['atas'] * 10,
            $margins['kanan'] * 10,
            $margins['bawah'] * 10,
        ];                
    }
}
