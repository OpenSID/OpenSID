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
use App\Models\Notifikasi;
use App\Models\SettingAplikasi;
use App\Traits\Upload;

class SettingAplikasiRepository
{
    use Upload;

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
     * @param string $column
     *
     * @return bool
     */
    public function updateWithKey($key, $value)
    {
        $data        = $this->setting->where('key', $key)->first();
        $data->value = $value;
        $data->save();

        $this->flushCache();

        return true;
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

    public function updateSetting($data)
    {
        $hasil = true;

        foreach ($data as $key => $value) {
            // Update setting yang diubah
            if (setting($key) != $value) {
                if (in_array($key, ['current_version', 'warna_tema', 'lock_theme'])) {
                    continue;
                }

                $value = is_array($value) ? $value : strip_tags($value);
                // update password jika terisi saja
                if ($key == 'email_smtp_pass' && $value === '') {
                    continue;
                }

                if ($key == 'tampilkan_pendaftaran' && $value == 1) {
                    if (setting('email_notifikasi') == 0 || setting('telegram_notifikasi') == 0) {
                        $value = 0;
                        $hasil = false;
                        set_session('flash_error_msg', 'Untuk menampilkan pendaftaran, notifikasi harus mengaktifkan pengaturan notifikasi email dan telegram');
                    }
                }

                if ($key == 'ip_adress_kehadiran' || $key == 'mac_adress_kehadiran') {
                    $value = trim($value);
                }

                if ($key == 'id_pengunjung_kehadiran') {
                    $value = alfanumerik(trim($value));
                }

                if (is_array($post = request()->get($key))) {
                    if (in_array('-', $post)) {
                        unset($post[0]);
                    }
                    $value = json_encode($post, JSON_THROW_ON_ERROR);
                }

                $hasil = $hasil && $this->updateWithKey($key, $value);
                if ($key == 'tte' && $value == 1) {
                    $this->updateWithKey('verifikasi_kades', $value); // jika tte aktif, aktifkan juga verifikasi kades
                }
                // $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $hasil = $hasil && $this->notifikasiTracker($value);
                }
            }
        }
        // model seperti di atas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        $this->flushCache();

        return $hasil;
    }

    private function notifikasiTracker($value): bool
    {
        if ($value == 0) {
            // Notifikasi tracker dimatikan
            $notif = [
                'updated_at'     => date('Y-m-d H:i:s'),
                'tgl_berikutnya' => date('Y-m-d H:i:s'),
                'aktif'          => 1,
            ];
        } else {
            // Matikan notifikasi tracker yg sdh aktif
            $notif = [
                'updated_at' => date('Y-m-d H:i:s'),
                'aktif'      => 0,
            ];
        }
        Notifikasi::where('kode', 'tracking_off')->update($notif);

        return true;
    }

    public static function applySettingCI($ci): void
    {
        if ($ci->setting) {
            return;
        }

        $ci->list_setting = SettingAplikasi::orderBy('key')->get();
        $ci->setting      = (object) $ci->list_setting->pluck('value', 'key')
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

        // Sebutan kepala desa diambil dari tabel ref_jabatan dengan jenis = 1
        // Diperlukan karena masih banyak yang menggunakan variabel ini, hapus jika tidak digunakan lagi
        $ci->setting->sebutan_kepala_desa = kades()->nama;

        // Sebutan sekretaris desa diambil dari tabel ref_jabatan dengan jenis = 2
        $ci->setting->sebutan_sekretaris_desa = sekdes()->nama;

        // Setting Multi Desa untuk OpenKab
        $ci->setting->multi_desa = Config::count() > 1;

        // Setting Multi Database untuk OpenKab
        $ci->setting->multi_database = count(config('database.connections')) >= 2;

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
        $margins                            = json_decode($ci->setting?->surat_margin, true);
        $ci->setting->surat_margin_cm_to_mm = [
            $margins['kiri'] * 10,
            $margins['atas'] * 10,
            $margins['kanan'] * 10,
            $margins['bawah'] * 10,
        ];

        // Konversi nilai margin surat dinas global dari cm ke mm
        $margins                                  = json_decode($ci->setting?->surat_dinas_margin, true);
        $ci->setting->surat_dinas_margin_cm_to_mm = [
            $margins['kiri'] * 10,
            $margins['atas'] * 10,
            $margins['kanan'] * 10,
            $margins['bawah'] * 10,
        ];
    }
}
