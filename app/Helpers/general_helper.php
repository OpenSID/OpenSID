<?php

use App\Models\Modul;
use App\Repositories\SettingAplikasiRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

if (! function_exists('setting')) {
    /**
     * Mengambil nilai dari pengaturan aplikasi.
     *
     * @param mixed|null $key
     * @param mixed|null $value
     *
     * @return mixed|null
     */
    function setting($key = null, $value = null)
    {
        $setting = app(SettingAplikasiRepository::class)->resolveSetting();

        if (null === $key) {
            return $setting;
        }

        if (null === $value) {
            return $setting->{$key} ?? null;
        }

        return $setting->{$key} = $value;
    }
}

if (! function_exists('can')) {
    /**
     * Cek akses user
     *
     * @param string|null $akses
     * @param string|null $slugModul
     * @param bool        $adminOnly
     * @param mixed       $demoOnly
     *
     * @return bool
     */
    function can($akses = null, $slugModul = null, $adminOnly = false, $demoOnly = false, ?Authenticatable $user = null): bool
    {
        if (null === $slugModul) {
            $slugModul = view()->shared('akses_modul') ?? (view()->shared('sub_modul_ini') ?? view()->shared('modul_ini'));
        }

        if (null !== $user) {
            return Gate::forUser($user)->allows("{$slugModul}:{$akses}", [$akses, $slugModul, $adminOnly, $demoOnly]);
        }

        return Gate::allows("{$slugModul}:{$akses}", [$akses, $slugModul, $adminOnly, $demoOnly]);
    }
}

if (! function_exists('isCan')) {
    /**
     * Cek akses user
     *
     * @param string|null $akses
     * @param string|null $slugModul
     * @param bool        $adminOnly
     * @param mixed       $demoOnly
     */
    function isCan($akses = null, $slugModul = null, $adminOnly = false, $demoOnly = false, ?Authenticatable $user = null): void
    {
        if (! can('b', $slugModul, $adminOnly, $demoOnly, $user) || ! can($akses, $slugModul, $adminOnly, $demoOnly, $user)) {
            $pesan = 'Anda tidak memiliki akses untuk halaman tersebut!';

            $redirect = new RedirectResponse(url('beranda'));
            $redirect->setSession(app('session.store'));
            $redirect->setRequest(request());

            throw new HttpResponseException($redirect->with('error', $pesan));
        }
    }
}

if (! function_exists('folder')) {
    /**
     * Membuat folder jika belum ada, dan menambahkan file .htaccess dan index.html
     *
     * @param string|null $folder
     * @param int         $permissions
     * @param string|null $htaccess
     * @param array       $extra
     */
    function folder($folder = null, $permissions = 0755, $htaccess = null, array $extra = []): bool
    {
        if (empty($folder)) {
            return false;
        }

        $folderPath = base_path($folder);

        $hasil = is_dir($folderPath) || mkdir($folderPath, $permissions, true);

        if ($hasil) {
            if ($htaccess !== null) {
                $htaccessPath = $folderPath . DIRECTORY_SEPARATOR . '.htaccess';
                if (! File::exists($htaccessPath)) {
                    File::put($htaccessPath, config('installer.' . $htaccess));
                }
            }

            $indexPath = $folderPath . DIRECTORY_SEPARATOR . 'index.html';
            if (! File::exists($indexPath)) {
                File::put($indexPath, config('installer.index_html'));
            }

            foreach ($extra as $value) {
                $file    = realpath($value);
                $newfile = realpath($folderPath) . DIRECTORY_SEPARATOR . basename($value);

                if ($file) {
                    copy($file, $newfile);
                }
            }

            return true;
        }

        return false;
    }
}

if (! function_exists('folder_desa')) {
    /**
     * Membuat folder desa dan menyalin file konfigurasi yang diperlukan.
     */
    function folder_desa(): bool
    {
        $list_folder = array_merge(config('installer.desa', []), config('installer.lainnya', []));

        foreach ($list_folder as $folder => $lainnya) {
            folder($folder, $lainnya[0], $lainnya[1] ?? null, $lainnya[2] ?? []);
        }

        $files = [
            base_path('desa/config/config.php')                      => 'installer.config',
            base_path('desa/config/database.php')                    => 'installer.database',
            base_path('desa/pengaturan/siteman/siteman.css')         => 'installer.siteman_css',
            base_path('desa/pengaturan/siteman/siteman_mandiri.css') => 'installer.siteman_mandiri_css',
            base_path('desa/app_key')                                => null,
        ];

        foreach ($files as $path => $configKey) {
            if (! File::exists($path)) {
                File::put($path, $configKey ? config($configKey) : set_app_key());
            }
        }

        config()->set('app.key', get_app_key());

        return true;
    }
}

if (! function_exists('kirim_versi_opensid')) {
    /**
     * Kirim versi Opensid ke server pantau.
     *
     * @param string $kode_desa
     */
    function kirim_versi_opensid($kode_desa): void
    {
        if (! config('opensid.demo_mode') && ! empty($kode_desa) && app()->environment('production')) {
            $versi = AmbilVersi();
            if ($versi != cache()->get('versi_app_cache')) {
                try {
                    $client = new Client();
                    $client->post(config('services.opendesa.server_layanan') . '/api/v1/pelanggan/catat-versi', [
                        'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                        'form_params' => [
                            'kode_desa' => kode_wilayah($kode_desa),
                            'versi'     => $versi,
                        ],
                    ])->getBody();

                    cache()->put('versi_app_cache', $versi);
                } catch (Exception $e) {
                    logger()->error($e);
                }
            }
        }
    }
}

if (! function_exists('admin_menu')) {
    /**
     * admin_menu untuk menampilkan menu admin yang aktif.
     *
     * @return mixed
     */
    function admin_menu()
    {
        $grupId = auth('admin')->user()?->id_grup;

        return cache()->rememberForever("{$grupId}_admin_menu", static fn () => (new Modul())->tree($grupId)->toArray());
    }
}

if (! function_exists('parsedown')) {
    /**
     * Parsedown.
     *
     * @param string|null $params
     *
     * @return \Parsedown|string
     */
    function parsedown($params = null)
    {
        $parsedown = new \Parsedown();

        if (null !== $params) {
            return $parsedown->text(file_get_contents(base_path($params)));
        }

        return $parsedown;
    }
}

if (! function_exists('calculate_days')) {
    /**
     * Calculate minute between 2 date.
     *
     * @return int
     */
    function calculate_days(string $dateStart, string $format = 'Y-m-d')
    {
        return abs(Carbon::createFromFormat($format, $dateStart)->getTimestamp() - Carbon::now()->getTimestamp()) / (60 * 60 * 24);
    }
}

if (! function_exists('calculate_date_intervals')) {
    /**
     * Calculate list dates interval to minutes.
     *
     * @return int
     */
    function calculate_date_intervals(array $date)
    {
        $reference = Carbon::now();
        $endTime   = clone $reference;

        foreach ($date as $dateInterval) {
            if (empty($dateInterval)) {
                continue;
            }
            $endTime = $endTime->add(DateInterval::createFromDateString(calculate_days($dateInterval) . 'days'));
        }

        return $reference->diff($endTime)->days;
    }
}

if (! function_exists('unserialize_flip')) {
    /**
     * Unserialize string lalu balik key <-> value.
     *
     * @param string|null $str
     *
     * @return array
     */
    function unserialize_flip($str): array
    {
        $arr = @unserialize((string) $str, ['allowed_classes' => false]);

        return is_array($arr) ? array_flip($arr) : [];
    }
}
