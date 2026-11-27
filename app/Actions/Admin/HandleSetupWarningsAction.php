<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Repositories\SettingAplikasiRepository;
use Illuminate\Session\Store as SessionStore;

class HandleSetupWarningsAction
{
    public function __construct(
        protected SessionStore $session,
        protected SettingAplikasiRepository $settings,
    ) {}

    public function execute(
        string $currentController,
        bool $isProduction,
        mixed $adminUser,
    ): ?array {
        $skip = in_array($currentController, ['setting', 'pengguna', 'notif']);

        if ($skip) {
            $this->session->forget('setup_warning');

            return null;
        }

        if (! $isProduction) {
            return null;
        }

        if ($adminUser && User::superAdmin()->exists()) {
            return null;
        }

        $resolved      = $this->settings->resolveSetting();
        $emailEmpty    = empty($resolved->email_notifikasi ?? null);
        $telegramEmpty = empty($resolved->telegram_notifikasi ?? null);

        if ($emailEmpty || $telegramEmpty) {
            $warning = [
                'title'        => 'Atur Notifikasi Akun',
                'message'      => 'Anda belum mengatur email atau telegram notifikasi. Disarankan melakukan setup agar aplikasi dapat mengirim notifikasi, termasuk fitur login OTP dan reset kata sandi otomatis.',
                'icon'         => 'warning',
                'redirect_url' => url('setting#notifikasi'),
                'button_text'  => 'Atur Sekarang',
            ];

            $this->session->put('setup_warning', $warning);

            return $warning;
        }

        if (! $adminUser) {
            return null;
        }

        $emailVerified    = $adminUser->hasVerifiedEmail();
        $telegramVerified = $adminUser->hasVerifiedTelegram();

        if (! $emailVerified && ! $telegramVerified) {
            $warning = [
                'title'        => 'Verifikasi Akun Disarankan',
                'message'      => 'Belum ada email atau telegram yang terverifikasi. Silakan verifikasi minimal satu akun email atau telegram agar aplikasi dapat mengirim notifikasi, termasuk fitur login OTP dan reset kata sandi otomatis.',
                'icon'         => 'warning',
                'redirect_url' => url('pengguna'),
                'button_text'  => 'Verifikasi Sekarang',
            ];

            $this->session->put('setup_warning', $warning);

            return $warning;
        }

        return null;
    }
}
