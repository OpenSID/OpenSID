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

namespace App\Listeners;

use App\Models\LogLogin;
use Exception;
use Illuminate\Auth\Events\Login;
use Illuminate\Container\Container;
use Illuminate\Support\Carbon;
use NotificationChannels\Telegram\Telegram;

class LoginAdminListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Login $login): void
    {
        if (! in_array($login->guard, ['admin', 'admin_periksa'])) {
            return;
        }

        if ($login->guard === 'admin_periksa') {
            $this->app['ci']->session->set_userdata('periksa_data', 1);
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
            $telegram = new Telegram(setting('telegram_token'));
            $country  = $logLogin->lainnya['country'] ?? ' tidak diketahui';

            if ($country != 'Indonesia') {
                try {
                    $telegram->sendMessage([
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
                $telegram->sendMessage([
                    'text'       => sprintf('%s login Halaman Admin %s pada tanggal %s', $login->user->nama, APP_URL, tgl_indo2(date('Y-m-d H:i:s'))),
                    'parse_mode' => 'Markdown',
                    'chat_id'    => $this->app['ci']->setting->telegram_user_id,
                ]);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
    }

    private function setFmKey($key = null): string
    {
        $fmHash = $key . date('Ymdhis');
        $salt   = random_int(100000, 999999);
        $salt   = strrev($salt);

        return md5($fmHash . 'OpenSID' . $salt);
    }
}
