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

namespace App\Providers;

use App\Models\GrupAkses;
use App\Models\Modul;
use App\Services\Auth\PendudukMandiriProvider;
use App\Services\Auth\SessionGuard;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->bootExtendGuard();
        $this->bootPendudukMandiriProvider();
        $this->bootGateAccess();
        $this->registerPolicies();
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function register()
    {
        $this->booting(function () {
            $this->registerPolicies();
        });

        $this->registerMd5Hasher();
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Get the policies defined on the provider.
     *
     * @return array<class-string, class-string>
     */
    public function policies()
    {
        return $this->policies;
    }

    protected function bootExtendGuard()
    {
        $this->app['auth']->extend('session', function ($app, $name, $config) {
            $provider = $app['auth']->createUserProvider($config['provider'] ?? null);

            $guard = new SessionGuard(
                name: $name,
                provider: $provider,
                session: $app['session.store'],
            );

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($this->app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($this->app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            if (isset($config['remember'])) {
                $guard->setRememberDuration($config['remember']);
            }

            return $guard;
        });
    }

    protected function bootPendudukMandiriProvider()
    {
        $this->app['auth']->provider(PendudukMandiriProvider::class, static function ($app, $config) {
            return new PendudukMandiriProvider(
                $app['hash']->driver('md5'),
                $config['model'],
                $config['belongsTo']
            );
        });
    }

    protected function registerMd5Hasher()
    {
        $this->app['hash']->extend('md5', function () {
            return new class () implements \Illuminate\Contracts\Hashing\Hasher {
                /**
                 * {@inheritDoc}
                 */
                public function info($hashedValue)
                {
                    return array_merge(
                        password_get_info($hashedValue),
                        ['algo' => 'md5', 'algoName' => 'md5']
                    );
                }

                /**
                 * {@inheritDoc}
                 *
                 * @see https://github.com/OpenSID/OpenSID/blob/master/donjo-app/helpers/donjolib_helper.php#L492-L499
                 */
                public function make($value, array $options = [])
                {
                    try {
                        if (! is_numeric($value) || strlen($value) != 6) {
                            throw new InvalidArgumentException('Value must be a 6-digit number');
                        }

                        $value = strrev($value);
                        $value *= 77;
                        $value .= '!#@$#%';

                        return md5($value);
                    } catch (Exception $e) {
                        throw new Exception(sprintf(
                            'Error processing value: %s. [%s].',
                            $e->getMessage(),
                            self::class
                        ), 400);
                    }
                }

                /**
                 * {@inheritDoc}
                 */
                public function check($value, $hashedValue, array $options = [])
                {
                    if (! is_numeric($value) || strlen($value) != 6) {
                        return false;
                    }

                    return hash_equals($this->make($value), $hashedValue);
                }

                /**
                 * {@inheritDoc}
                 */
                public function needsRehash($hashedValue, array $options = [])
                {
                    throw new Exception(sprintf(
                        'This password md5 does not implement needsRehash. [%s].',
                        self::class
                    ));
                }
            };
        });
    }

    protected function bootGateAccess()
    {
        Gate::before(function ($user, $ability, $arguments) {
            [$akses, $slugModul, $adminOnly, $demoOnly] = $arguments;

            // Early return for demo-only mode
            if ($demoOnly && config_item('demo_mode')) {
                return false;
            }

            // Grant access to the default module directly
            if ($slugModul === Modul::DEFAULT_MODUL['beranda']['slug']) {
                return true;
            }

            // Admin-only check
            if ($adminOnly && $user->id != super_admin()) {
                return false;
            }

            // Cache the user group access data, caching it by group ID
            $accessData = cache()->remember("akses_grup_{$user->id_grup}", 604800, fn () => $this->getUserGroupAccessData($user->id_grup));

            collect($accessData)->each(static function ($data, $modul) {
                Gate::define("{$modul}:baca", static fn () => $data['baca']);
                Gate::define("{$modul}:ubah", static fn () => $data['ubah']);
                Gate::define("{$modul}:hapus", static fn () => $data['hapus']);
                Gate::define("{$modul}:b", static fn () => $data['baca']);
                Gate::define("{$modul}:u", static fn () => $data['ubah']);
                Gate::define("{$modul}:h", static fn () => $data['hapus']);
            });
        });
    }

    /**
     * Retrieve and structure user group access data.
     *
     * @param int $grupId
     *
     * @return array
     */
    protected function getUserGroupAccessData($grupId)
    {
        $grupAkses = GrupAkses::leftJoin('setting_modul as s1', 'grup_akses.id_modul', '=', 's1.id')
            ->leftJoin('setting_modul as s2', 's1.parent', '=', 's2.id')
            ->where('id_grup', $grupId)
            ->select('grup_akses.*', 's1.slug as slug', 's2.slug as parent_slug')
            ->get();

        return $grupAkses->mapWithKeys(static function ($item) use ($grupAkses) {
            $item->akses = $grupAkses->where('parent_slug', $item->slug)->where('akses', '>', 0)->count() > 0 ? 7 : $item->akses;

            return [
                $item->slug => [
                    'id_modul'    => $item->id_modul,
                    'parent_slug' => $item->parent_slug,
                    'id_grup'     => $item->id_grup,
                    'akses'       => $item->akses,
                    'baca'        => $item->akses >= 1,
                    'ubah'        => $item->akses >= 3,
                    'hapus'       => $item->akses >= 7,
                ],
            ];
        })->toArray();
    }
}
