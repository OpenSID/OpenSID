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

namespace App\Notifications\Penduduk;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use NotificationChannels\Telegram\TelegramMessage;

class PermohonanSuratNotification extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        if (null !== $notifiable->email) {
            return ['mail'];
        }

        if (null !== $notifiable->telegram) {
            return ['telegram'];
        }

        return [];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $waktuCetak = Carbon::now();

        return (new MailMessage())
            ->subject('Pemberitahuan Pencetakan Surat')
            ->view('anjungan::notification.notifikasi_permohonan_surat', [
                'waktuCetak' => $waktuCetak,
            ]);
    }

    /**
     * Build the telegram representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toTelegram($notifiable)
    {
        $waktuCetak = Carbon::now();

        return TelegramMessage::create()
            ->to($notifiable->telegram)
            ->content('📄 *Pemberitahuan Pencetakan Surat*')
            ->line("Ada yang mencetak surat Anda melalui *Anjungan Mandiri* di Kantor Desa pada *{$waktuCetak->isoFormat('dddd, D MMMM Y [pukul] HH:mm')}*.")
            ->line('Pencetakan dilakukan tanpa akun (_guest_).')
            ->line('⚠️ *Jika bukan Anda yang mencetak surat tersebut, segera laporkan ke Kantor Desa untuk ditindaklanjuti.*');
    }
}
