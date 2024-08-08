<?php

namespace App\Listeners;

use Exception;
use App\Models\LogLogin;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Container\Container;

class LoginAdminListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Login $login)
    {
        if ($login->guard !== 'admin') {
            return;
        }

        $this->app['ci']->session->set_userdata([
            'siteman' => 1,
            'sesi'    => $login->user->session,
            'user'    => $login->user->id,
            'nama'    => $login->user->nama,
            'grup'    => $login->user->id_grup,
            'fm_key'  => $this->setFmKey("{$login->user->id}{$login->user->id_grup}{$login->user->sesi}"),
            'isAdmin' => $login->user,
        ]);

        // hapus sesi halaman yang dituju setelah login.
        $this->app['ci']->session->unset_userdata('intended');

        $login->user->last_login = Carbon::now();
        $login->user->save();

        $logLogin = LogLogin::create([
            'username'   => $login->user->nama,
            'ip_address' => $ip = $this->app['ci']->input->ip_address(),
            'user_agent' => $this->app['ci']->input->user_agent(),
            'referer'    => $_SERVER['HTTP_REFERER'] ?? '',
            'lainnya'    => geoip_info($ip),
        ]);

        // TODO: gunakan laravel notification
        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->app['ci']->load->library('Telegram/telegram');
            $country = $logLogin->lainnya['country'] ?? ' tidak diketahui';

            if ($country != 'Indonesia') {
                try {
                    $this->app['ci']->telegram->sendMessage([
                        'text' => <<<EOD
                                Teridentifikasi login mencurigakan dari {$login->user->nama} dengan lokasi {$country}.
                            EOD,
                        'parse_mode' => 'Markdown',
                        'chat_id'    => $this->app['ci']->setting->telegram_user_id,
                    ]);
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                }
            }

            try {
                $this->app['ci']->telegram->sendMessage([
                    'text'       => sprintf('%s login Halaman Admin %s pada tanggal %s', $login->user->nama, APP_URL, tgl_indo2(date('Y-m-d H:i:s'))),
                    'parse_mode' => 'Markdown',
                    'chat_id'    => $this->app['ci']->setting->telegram_user_id,
                ]);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
    }

    private function setFmKey($key = null)
    {
        $fmHash = $key . date('Ymdhis');
        $salt   = random_int(100000, 999999);
        $salt   = strrev($salt);

        return md5($fmHash . 'OpenSID' . $salt);
    }
}