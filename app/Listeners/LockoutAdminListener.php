<?php

namespace App\Listeners;

use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Container\Container;

class LockoutAdminListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Lockout $lockout)
    {
        if ($this->app['auth']->getDefaultDriver() !== 'admin') {
            return;
        }

        // TODO: gunakan laravel notification
        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->app['ci']->load->library('Telegram/telegram');

            try {
                $this->app['ci']->telegram->sendMessage([
                    'text' => <<<EOD
                            Percobaan login gagal sebanyak 3 kali dengan input nama pengguna {$lockout->request?->username} dan IP Address {$lockout->request->ip()}.
                        EOD,
                    'parse_mode' => 'Markdown',
                    'chat_id'    => $this->app['ci']->setting->telegram_user_id,
                ]);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
    }
}