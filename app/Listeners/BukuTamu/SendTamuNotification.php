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

namespace App\Listeners\BukuTamu;

use App\Enums\JenisKelaminEnum;
use App\Events\BukuTamu\TamuSubmitted;
use App\Models\User;
use App\Notifications\BukuTamu\TamuBaru;
use Exception;
use NotificationChannels\Telegram\Telegram;

class SendTamuNotification
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
    public function handle(TamuSubmitted $event): void
    {
        // Send database notifications to users with data-tamu access
        User::status()->get()->filter(static fn (User $user) => can(akses: 'b', slugModul: 'data-tamu', user: $user))
            ->each(static function (User $user) use ($event) {
                $user->notify(new TamuBaru(tamu: $event->tamu));
            });

        // Send telegram notification if enabled
        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->sendTelegramNotification($event);
        }
    }

    /**
     * Send telegram notification.
     */
    private function sendTelegramNotification(TamuSubmitted $event): void
    {
        try {
            $pesan = '<b>Registrasi Buku Tamu Baru</b>' . "\n\n"
                . '<b>Nama:</b> ' . $event->tamu->nama . "\n"
                . '<b>Telepon:</b> ' . $event->tamu->telepon . "\n"
                . '<b>Instansi:</b> ' . $event->tamu->instansi . "\n"
                . '<b>Jenis Kelamin:</b> ' . JenisKelaminEnum::valueOf($event->tamu->jenis_kelamin) . "\n"
                . '<b>Alamat:</b> ' . $event->tamu->alamat . "\n"
                . '<b>Bertemu:</b> ' . $event->tamu->bidang . "\n"
                . '<b>Keperluan:</b> ' . $event->tamu->keperluan;

            $telegram = new Telegram(setting('telegram_token'));
            $telegram->sendMessage([
                'text'       => $pesan,
                'parse_mode' => 'HTML',
                'chat_id'    => setting('telegram_user_id'),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }
}
