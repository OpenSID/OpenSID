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

namespace App\Listeners\Surat;

use App\Enums\FirebaseEnum;
use App\Events\Surat\PermohonanSuratVerified;
use App\Models\FcmToken;
use App\Models\User;
use App\Notifications\Surat\PermohonanSuratMasuk;
use Exception;
use NotificationChannels\Telegram\Telegram;

class SendPermohonanSuratVerificationNotification
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
    public function handle(PermohonanSuratVerified $event): void
    {
        // Send database notification to user with next stage jabatan
        User::whereHas('pamong', static function ($query) use ($event) {
            if ($event->nextStage === 'verifikasi_sekdes') {
                return $query->where('jabatan_id', '=', sekdes()->id);
            }
            if ($event->nextStage === 'verifikasi_kades') {
                return $query->where('jabatan_id', '=', kades()->id);
            }
        })->first()?->notify(new PermohonanSuratMasuk($event->logSurat));

        // Send telegram and FCM notifications
        if (cek_koneksi_internet() && setting('telegram_token')) {
            $this->sendTelegramNotification($event);
            $this->sendFcmNotification($event);
        }
    }

    /**
     * Send telegram notification.
     */
    private function sendTelegramNotification(PermohonanSuratVerified $event): void
    {
        try {
            $kirimTelegram = User::whereHas('pamong', static function ($query) use ($event) {
                if ($event->nextStage === 'verifikasi_sekdes') {
                    return $query->where('jabatan_id', '=', sekdes()->id)->where('pamong_ttd', '=', '1');
                }
                if ($event->nextStage === 'verifikasi_kades') {
                    return $query->where('jabatan_id', '=', kades()->id);
                }
            })->where('notif_telegram', '=', '1')->first();

            if ($kirimTelegram !== null) {
                $telegram   = new Telegram(setting('telegram_token'));
                $kirimPesan = str_replace(
                    array_keys($event->messageData),
                    array_values($event->messageData),
                    setting('notifikasi_pengajuan_surat')
                );

                $telegram->sendMessage([
                    'chat_id'      => $kirimTelegram->id_telegram,
                    'text'         => $kirimPesan,
                    'parse_mode'   => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            ['text' => 'Lihat detail', 'url' => ci_route("keluar/periksa/{$event->logSurat->id}")],
                        ]],
                    ]),
                ]);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }

    /**
     * Send FCM notification.
     */
    private function sendFcmNotification(PermohonanSuratVerified $event): void
    {
        try {
            $allToken = FcmToken::whereHas('user.pamong', static function ($query) use ($event) {
                if ($event->nextStage === 'verifikasi_sekdes') {
                    return $query->where('jabatan_id', '=', sekdes()->id)->where('pamong_ttd', '=', '1');
                }
                if ($event->nextStage === 'verifikasi_kades') {
                    return $query->where('jabatan_id', '=', kades()->id);
                }
            })->get();

            if ($allToken->count() > 0) {
                $kirimFCM = str_replace(
                    array_keys($event->messageData),
                    array_values($event->messageData),
                    setting('notifikasi_pengajuan_surat')
                );
                $judul   = "Pengajuan Surat - {$event->messageData['[judul_surat]']}";
                $payload = "/permohonan/surat/periksa/{$event->logSurat->id}/Periksa Surat";

                $client       = new \Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
                $notification = new \Fcm\Push\Notification();

                $notification
                    ->addRecipient($allToken->pluck('token')->all())
                    ->setTitle($judul)
                    ->setBody($kirimFCM)
                    ->addData('payload', $payload);

                $client->send($notification);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }
}
