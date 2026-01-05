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

namespace App\Listeners\Pesan;

use App\Events\Pesan\PesanMasukSubmitted;
use App\Models\User;
use App\Notifications\Pesan\PesanMasuk;
use Exception;
use NotificationChannels\Telegram\Telegram;

class SendPesanMasukNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PesanMasukSubmitted $event): void
    {
        // Send notifications to users with kotak-pesan access
        User::status()->get()->filter(static fn (User $user) => can(akses: 'b', slugModul: 'kotak-pesan', user: $user))
            ->each(static function (User $user) use ($event) {
                $user->notify(new PesanMasuk(pesan: $event->pesan));
            });

        // Send telegram notification if enabled
        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->sendTelegramNotification($event);
        }
    }

    /**
     * Send telegram notification.
     */
    private function sendTelegramNotification(PesanMasukSubmitted $event): void
    {
        try {
            $telegram = new Telegram(setting('telegram_token'));
            $telegram->sendMessage([
                'text' => sprintf(
                    'Warga RT. %s atas nama %s telah mengirim pesan melalui Layanan Mandiri pada tanggal %s. Link : %s',
                    $event->penduduk->rt ?? '-',
                    $event->penduduk->nama,
                    tgl_indo2(date('Y-m-d H:i:s')),
                    APP_URL
                ),
                'parse_mode' => 'Markdown',
                'chat_id'    => setting('telegram_user_id'),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }
}
