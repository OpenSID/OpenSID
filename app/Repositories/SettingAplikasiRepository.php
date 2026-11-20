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

namespace App\Repositories;

use App\Libraries\TinyMCE;
use App\Models\Config;
use App\Models\Notifikasi;
use App\Models\SettingAplikasi;
use App\Rules\Traits\ValidateCloudDomainTrait;
use App\Services\OtpService;
use App\Traits\Upload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Facades\LogBatch;

class SettingAplikasiRepository
{
    use Upload;
    use ValidateCloudDomainTrait;

    protected SettingAplikasi $setting;

    /**
     * Cache in-memory hasil resolve setting (key => value) yang di-share antar
     * instance repository. Diisi lewat resolveSetting(), dipakai oleh helper
     * setting() & shim applySettingCI() sehingga semuanya mereferensi objek.
     */
    protected static ?object $resolvedSetting = null;

    /**
     * Cache in-memory daftar baris setting mentah (dulu $ci->list_setting).
     */
    protected static ?Collection $resolvedListSetting = null;

    public function __construct()
    {
        $this->setting = new SettingAplikasi();
    }

    /**
     * Shim BC untuk legacy CI3 (dipanggil dari MY_Controller::__construct()).
     *
     * Resolve logic sepenuhnya ada di resolveSetting()/buildSetting() milik
     * instance repository ini (di-bind sebagai singleton di container), method
     * ini cuma menyalin referensinya ke properti publik $ci supaya
     * controller/view CI3 lama yang masih akses $this->setting atau
     * $this->list_setting tidak perlu diubah.
     */
    public static function applySettingCI($ci): void
    {
        if ($ci->setting) {
            return;
        }

        $repository = app(static::class);

        $ci->setting      = $repository->resolveSetting();
        $ci->list_setting = $repository->resolvedListSetting();
    }

    /**
     * Mengambil object setting aplikasi (key => value), resolve dulu kalau belum pernah
     * di-resolve pada instance ini. Dipakai oleh helper setting().
     */
    public function resolveSetting(): object
    {
        return static::$resolvedSetting ??= $this->buildSetting();
    }

    /**
     * Daftar mentah baris setting (dulu $ci->list_setting).
     */
    public function resolvedListSetting(): Collection
    {
        $this->resolveSetting();

        return static::$resolvedListSetting;
    }

    /**
     * Buang cache in-memory hasil resolve. Berguna setelah update setting di
     * request yang sama, atau di antar test PHPUnit yang sengaja reuse container.
     */
    public function forgetResolvedSetting(): void
    {
        static::$resolvedSetting     = null;
        static::$resolvedListSetting = null;
    }

    protected function buildSetting(): object
    {
        $listSetting = SettingAplikasi::urut()->get();

        // Ambil setting dari global.json jika ada (override read-only, mis. instalasi cloud).
        $path = DESAPATH . 'config/global.json';

        if (is_file($path)) {
            $hash         = md5_file($path);
            $cacheKey     = 'desa.config.global.' . $hash;
            $configGlobal = Cache::rememberForever(
                $cacheKey,
                static fn () => collect(json_decode(file_get_contents($path), true))
            );

            if (isKelurahan()) {
                $configGlobal->put('sebutan_desa', 'Kelurahan');
            }

            $listSetting = $listSetting->transform(static function ($item) use ($configGlobal) {
                if (! $configGlobal->has($item->key)) {
                    return $item;
                }

                $item->value = $configGlobal->get($item->key);

                $item->attribute = json_encode(
                    array_merge(
                        json_decode($item->attribute ?? '{}', true) ?: [],
                        ['disabled' => true]
                    )
                );

                return $item;
            });
        }

        $setting = (object) $listSetting
            ->pluck('value', 'key')
            ->map(static fn ($value) => SebutanDesa($value))
            ->toArray();

        $timezone = $setting->timezone ?? config('app.timezone');

        config()->set('app.timezone', $timezone);
        date_default_timezone_set($timezone);

        // Ambil key layanan pihak ketiga: DB > config('services.*') > config_item() (legacy, fallback terakhir)
        if (empty($setting->mapbox_key)) {
            $setting->mapbox_key = $this->configOrLegacy(config('services.map.mapbox_key'), 'mapbox_key');
        }

        if (empty($setting->google_api_key)) {
            $setting->google_api_key = $this->configOrLegacy(config('services.google.api_key'), 'google_api_key');
        }

        if (empty($setting->google_recaptcha_site_key)) {
            $setting->google_recaptcha_site_key = $this->configOrLegacy(config('services.google.recaptcha.site_key'), 'google_recaptcha_site_key');
        }

        if (empty($setting->google_recaptcha_secret_key)) {
            $setting->google_recaptcha_secret_key = $this->configOrLegacy(config('services.google.recaptcha.secret_key'), 'google_recaptcha_secret_key');
        }

        if (empty($setting->google_recaptcha)) {
            $setting->google_recaptcha = $this->configOrLegacy(config('services.google.recaptcha.enabled'), 'google_recaptcha');
        }

        // Validasi google_recaptcha hanya aktif jika site key dan secret key terisi
        $setting->google_recaptcha = (bool) $setting->google_recaptcha
            && ! empty($setting->google_recaptcha_site_key)
            && ! empty($setting->google_recaptcha_secret_key);

        if (empty($setting->header_surat)) {
            $setting->header_surat = TinyMCE::HEADER;
        }

        if (empty($setting->footer_surat)) {
            $setting->footer_surat = TinyMCE::FOOTER;
        }

        if (empty($setting->footer_surat_tte)) {
            $setting->footer_surat_tte = TinyMCE::FOOTER_TTE;
        }

        // Ganti token_layanan sesuai environment untuk mempermudah development
        $tokenLayanan = $this->configOrLegacy(config('services.opendesa.token_layanan'), 'token_layanan');
        if (app()->environment('development') || $tokenLayanan) {
            $setting->layanan_opendesa_token = $tokenLayanan;
        }

        $setting->user_admin = $this->configOrLegacy(config('opensid.user_admin'), 'user_admin');

        // Sebutan pemerintah desa diambil dari Pemerintah + sebutan_desa
        $setting->sebutan_pemerintah_desa = ucwords('Pemerintah ' . $setting->sebutan_desa);

        // Sebutan kepala desa & sekretaris desa (dari tabel ref_jabatan jenis = 1 / 2)
        // Diperlukan karena masih banyak yang menggunakan variabel ini, hapus jika tidak digunakan lagi
        $setting->sebutan_kepala_desa = kades()->nama;
        $setting->sebutan_sekretaris_desa = sekdes()->nama;

        // Setting Multi Desa / Multi Database untuk OpenKab
        $setting->multi_desa = Config::count() > 1;
        $setting->multi_database = array_key_exists('opensid-gabungan', config('database.connections'));

        // Feeds
        if (empty($setting->link_feed)) {
            $setting->link_feed = 'https://www.covid19.go.id/feed/';
        }

        if (empty($setting->anjungan_layar)) {
            $setting->anjungan_layar = 1;
        }

        if (empty($setting->sebutan_anjungan_mandiri)) {
            $setting->sebutan_anjungan_mandiri = SebutanDesa('Anjungan [desa] Mandiri');
        }

        // Konversi nilai margin (global & surat dinas) dari cm ke mm
        $setting->surat_margin_cm_to_mm = $this->marginCmToMm($setting->surat_margin ?? null);
        $setting->surat_dinas_margin_cm_to_mm = $this->marginCmToMm($setting->surat_dinas_margin ?? null);

        // Kehadiran
        $setting->jarak_kehadiran = $setting->jarak_kehadiran ?? SettingAplikasi::JARAK_KEHADIRAN;

        static::$resolvedListSetting = $listSetting;

        $this->syncLaravelConfig($setting);

        return $setting;
    }

    /**
     * Sinkronisasi setting ke config Laravel (config/mail.php, config/services.php, config/captcha.php)
     * agar bisa dipakai oleh service Laravel (Mail, Notification, Captcha) tanpa harus
     * mengubah kode lama yang masih akses setting() secara langsung.
     */
    private function syncLaravelConfig(object $setting): void
    {
        if (! empty($setting->email_smtp_host)) {
            $protocol = $setting->email_protocol ?: 'smtp';
            $host     = $this->configOrLegacy(config("mail.mailers.{$protocol}.host"), 'email_smtp_host');

            if (empty($host)) {
                config([
                    'mail.default' => $protocol,
                    "mail.mailers.{$protocol}" => [
                        'transport'    => $protocol,
                        'host'         => $setting->email_smtp_host,
                        'port'         => $setting->email_smtp_port,
                        'username'     => $setting->email_smtp_user,
                        'password'     => $setting->email_smtp_pass,
                    ],
                ]);
            }
        }

        $telegramToken = $this->configOrLegacy(config('services.telegram-bot-api.token'), 'telegram_token');
        if (empty($telegramToken) && ! empty($setting->telegram_token)) {
            config(['services.telegram-bot-api.token' => $setting->telegram_token]);
        }

        $recaptchaSecret = $this->configOrLegacy(config('captcha.secret'), 'google_recaptcha_secret_key');
        if (empty($recaptchaSecret) && ! empty($setting->google_recaptcha_secret_key)) {
            config([
                'captcha' => [
                    'secret'          => $setting->google_recaptcha_secret_key,
                    'sitekey'         => $setting->google_recaptcha_site_key,
                    'options'         => [],
                    'version'         => $setting->google_recaptcha_version ?? 'v2',
                    'score_threshold' => $setting->google_recaptcha_score_threshold ?? 0.5,
                ],
            ]);
        }
    }

    /**
     * @return array{0: float|int, 1: float|int, 2: float|int, 3: float|int}
     */
    private function marginCmToMm(?string $json): array
    {
        $margins = json_decode($json ?? '', true) ?: [];

        return [
            ($margins['kiri'] ?? 0) * 10,
            ($margins['atas'] ?? 0) * 10,
            ($margins['kanan'] ?? 0) * 10,
            ($margins['bawah'] ?? 0) * 10,
        ];
    }

    /**
     * Ambil nilai config Laravel-native (config/service.php) sebagai sumber utama,
     * config_item() (desa/config/config.php, CI3) hanya dipakai sebagai fallback
     * terakhir untuk instalasi lama yang belum memindahkan nilainya ke .env
     */
    private function configOrLegacy(mixed $configValue, string $legacyKey): mixed
    {
        if (! empty($configValue)) {
            return $configValue;
        }

        if (! function_exists('config_item')) {
            return null;
        }

        return config_item($legacyKey);
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
     * @param string $column
     */
    public function updateWithKey($key, mixed $value): bool
    {
        $data = $this->firstByKey($key);

        if (! $data) {
            return false;
        }

        // Validasi value untuk key token layanan
        if ($this->isKeyTokenLayanan($key)) {
            $value = $this->validateTokenLayananValue($key, $value);
        }

        $data->value = $value;
        $data->save();

        $this->flushCache();
        $this->forgetResolvedSetting();

        return true;
    }

    /**
     * Cek apakah key adalah token layanan yang perlu validasi khusus.
     */
    private function isKeyTokenLayanan(string $key): bool
    {
        $tokenKeys = [
            'layanan_opendesa_token',
            'mapbox_key',
            'google_api_key',
            'google_recaptcha_site_key',
            'google_recaptcha_secret_key',
        ];

        return in_array($key, $tokenKeys, true);
    }

    /**
     * Validasi value untuk token layanan.
     *
     * @param  string  $key  Key setting
     * @param  mixed  $value  Value yang akan divalidasi
     * @return mixed Value yang sudah divalidasi
     */
    private function validateTokenLayananValue(string $key, mixed $value): mixed
    {
        // Untuk layanan_opendesa_token, validasi dilakukan oleh LayananTokenRepository
        // Method ini hanya untuk validasi dasar format token lainnya
        if ($key === 'layanan_opendesa_token') {
            return $value;
        }

        // Validasi dasar: pastikan value adalah string dan tidak kosong
        if (is_string($value) && trim($value) !== '') {
            // Sanitasi input untuk mencegah XSS
            $value = strip_tags($value);
        }

        return $value;
    }

    /**
     * Membersihkan cache query.
     */
    public function flushCache(): void
    {
        $this->setting->flushQueryCache();
    }

    public function updateSetting($data)
    {
        $hasil = true;

        LogBatch::startBatch();

        foreach ($data as $key => $value) {
            // Update setting yang diubah
            if (setting($key) != $value) {
                if (in_array($key, ['version_build_script', 'current_version', 'warna_tema', 'lock_theme'])) {
                    continue;
                }

                $value = is_array($value) ? $value : strip_tags((string) $value);
                // update password jika terisi saja
                if ($key == 'email_smtp_pass' && $value === '') {
                    continue;
                }

                if ($key == 'tampilkan_pendaftaran' && $value == 1 && (setting('email_notifikasi') == 0 || setting('telegram_notifikasi') == 0)) {
                    $value = 0;
                    $hasil = false;
                    session()->flash('flash_error_msg', 'Untuk menampilkan pendaftaran, notifikasi harus mengaktifkan pengaturan notifikasi email dan telegram');
                }

                if ($key == 'google_recaptcha' && $value == 1 && (empty($data['google_recaptcha_site_key']) || empty($data['google_recaptcha_secret_key']))) {
                    $value = 0;
                    $hasil = false;
                    session()->flash('flash_error_msg', 'Untuk mengaktifkan Google reCAPTCHA, Site Key dan Secret Key harus diisi');
                }

                // Terapkan perlindungan SSRF lokal untuk api_opendk_server via trait
                if ($key == 'api_opendk_server' && ! empty($value)) {
                    $this->validateDomain(
                        data: [$key => $value],
                        requireCloudWhitelist: false,
                        attribute: $key,
                        redirectUrl: url('sinkronisasi#tab_buat_key')
                    );
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
                // Jika 'login_otp' diatur ke '0' (Tidak), nonaktifkan OTP untuk semua pengguna.
                if ($key == 'login_otp' && $value == '0') {
                    (new OtpService())->deactivateForAllUsers();
                }

                // $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $hasil = $hasil && $this->notifikasiTracker($value);
                }
            }
        }

        LogBatch::endBatch();

        // model seperti di atas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        $this->flushCache();

        return $hasil;
    }

    private function notifikasiTracker(string|array|int|null|bool $value): bool
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
}
