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

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Auth\Events\Registered::class => [
            \App\Listeners\Penduduk\SendEmailVerificationNotification::class,
            \App\Listeners\Penduduk\SendTelegramVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\Attempting::class    => [],
        \Illuminate\Auth\Events\Authenticated::class => [],
        \Illuminate\Auth\Events\Login::class         => [
            \App\Listeners\LoginAdminListener::class,
            \App\Listeners\LoginPendudukListener::class,
            \App\Listeners\LoginPerangkatListener::class,
        ],
        \Illuminate\Auth\Events\Failed::class => [
            \App\Listeners\FailedAdminListener::class,
        ],
        \Illuminate\Auth\Events\Validated::class => [],
        \Illuminate\Auth\Events\Verified::class  => [],
        \Illuminate\Auth\Events\Logout::class    => [
            \App\Listeners\LogoutAdminListener::class,
        ],
        \Illuminate\Auth\Events\CurrentDeviceLogout::class => [],
        \Illuminate\Auth\Events\OtherDeviceLogout::class   => [],
        \Illuminate\Auth\Events\Lockout::class             => [
            \App\Listeners\LockoutAdminListener::class,
        ],
        \Illuminate\Auth\Events\PasswordReset::class => [],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * {@inheritDoc}
     */
    public function register(): void
    {

    }

    /**
     * Register the application's event listeners.
     */
    public function boot(): void
    {
        $this->callAfterResolving('events', function (Dispatcher $events): void {
            foreach ($this->listens() as $event => $listeners) {
                foreach ($listeners as $listener) {
                    $events->listen($event, $listener);
                }
            }

            foreach ($this->subscribe as $subscriber) {
                $events->subscribe($subscriber);
            }
        });
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }
}
