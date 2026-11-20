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

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
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

        // Notifications Events
        \App\Events\Pesan\PesanMasukSubmitted::class => [
            \App\Listeners\Pesan\SendPesanMasukNotification::class,
        ],
        \App\Events\Pesan\PesanOpenDKReceived::class => [
            \App\Listeners\Pesan\SendPesanOpenDKNotification::class,
        ],
        \App\Events\Surat\PermohonanSuratSubmitted::class => [
            \App\Listeners\Surat\SendPermohonanSuratNotification::class,
        ],
        \App\Events\Surat\PermohonanSuratVerified::class => [
            \App\Listeners\Surat\SendPermohonanSuratVerificationNotification::class,
        ],
        \App\Events\Komentar\KomentarSubmitted::class => [
            \App\Listeners\Komentar\SendKomentarNotification::class,
        ],
        // Listener BukuTamu didaftarkan oleh BukuTamuServiceProvider::boot()
        \App\Events\Pengaduan\PengaduanSubmitted::class => [
            \App\Listeners\Pengaduan\SendPengaduanNotification::class,
        ],
        \App\Events\Kehadiran\PengajuanIzinSubmitted::class => [
            \App\Listeners\Kehadiran\SendPengajuanIzinNotification::class,
        ],
        \App\Events\Kehadiran\PengajuanIzinApprovalChanged::class => [
            \App\Listeners\Kehadiran\SendPengajuanIzinApprovalNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
