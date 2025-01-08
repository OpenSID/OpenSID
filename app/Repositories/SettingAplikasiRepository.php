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
            'sebutan_anjungan_mandiri' => SebutanDesa('Anjungan [desa] Mandiri'),
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

        // Apply theme setting with fallback
        if ($setting->key === 'web_theme' && empty($setting->value)) {
            $pos = strpos($setting->value, 'desa/');
            if ($pos !== false) {
                $folder = FCPATH . '/desa/themes/' . substr($setting->value, $pos + strlen('desa/'));
                if (! file_exists($folder)) {
                    $setting->value = 'esensi';
                }
            }
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

        SebutanDesa($setting->value);

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
}
