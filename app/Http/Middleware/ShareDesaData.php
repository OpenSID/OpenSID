<?php

namespace App\Http\Middleware;

use App\Libraries\UserAgent;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\UserGrup;
use App\Repositories\SettingAplikasiRepository;
use App\Services\Kapabilitas\PerbaruiLangganan;
use App\Services\NotificationService;
use App\Services\Pengumuman\SumberPengumuman;
use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareDesaData
{
    public function __construct(
        protected SettingAplikasiRepository $settings,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $setting     = $this->settings->resolveSetting();
        $listSetting = $this->settings->resolvedListSetting();
        $desa        = identitas();

        $currentController = explode('.', $request->route()?->getName() ?? '')[0];

        app(PerbaruiLangganan::class)->refresh();

        $status_langganan = collect(app(SumberPengumuman::class)->pengumuman())->firstWhere('jenis', 'langganan');
        $user             = auth('admin')->user();

        View::share([
            'desa'        => $desa,
            'setting'     => $setting,
            'auth'        => $user,
            'session'     => new \Illuminate\Support\Optional(session()),
            'token_name'  => '_token',
            'token_value' => csrf_token(),
            'notif'       => [
                'langganan'  => $status_langganan,
                'pengumuman' => self::cekPengumuman($user),
            ],
            'notif_categories'     => $user ? NotificationService::getCategories() : [],
            'notif_counts'         => $user ? NotificationService::getNotificationCounts($user) : [],
            'notif_list'           => $user ? NotificationService::getRecentNotifications($user, 10) : [],
            'list_setting'         => $listSetting,
            'perbaharui_langganan' => self::perbaharuiLangganan(),
            'module_name'          => SebutanDesa($listSetting->firstWhere('slug', View::shared('sub_modul_ini') ?? View::shared('modul_ini'))->modul ?? null),
            'is_mobile'            => (new UserAgent())->is_mobile(),
        ]);

        View::composer('*', function ($view) use ($setting): void {
            foreach (SettingService::sensitiveKeys() as $key) {
                unset($setting->{$key});
            }
        });

        return $next($request);
    }

    private static function cekPengumuman(?User $user): ?array
    {
        if (app()->environment(['development', 'testing'])) {
            return null;
        }

        if (! $user) {
            return null;
        }

        $grupId    = $user->id_grup;
        $adminGrup = UserGrup::where('slug', UserGrup::ADMINISTRATOR)->first();

        if (! $adminGrup || $grupId != $adminGrup->id) {
            return null;
        }

        $pengumuman = null;
        $notifikasi = Notifikasi::semua();

        foreach ($notifikasi as $notif) {
            $pengumuman = Notifikasi::convert($notif);
            if (($notif['jenis'] ?? '') === 'persetujuan') {
                break;
            }
        }

        return $pengumuman;
    }

    private static function perbaharuiLangganan(): ?bool
    {
        if (is_demo_mode()) {
            return null;
        }

        // Periksa apakah cache status_langganan sudah kedaluwarsa (> 30 hari) atau tidak ada.
        // Gunakan cache Laravel sebagai pengganti CI cache file.
        $cached   = cache()->store('subscription')->get('status_langganan');

        if ($cached === null) {
            return true;
        }

        return null;
    }
}
