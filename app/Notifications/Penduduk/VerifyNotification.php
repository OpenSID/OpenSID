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
use NotificationChannels\Telegram\TelegramMessage;

class VerifyNotification extends Notification
{
    public function __construct(protected $via)
    {
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return [$this->via];
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
        return (new MailMessage())
            ->subject('Verifikasi Alamat Email')
            ->from(identitas('email_desa'), identitas('nama_desa'))
            ->view('layanan_mandiri.auth.notifikasi_verifikasi_email', [
                'url' => $this->verificationUrl($notifiable),
            ]);
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->getTelegramForVerification())
            ->content('Hello!')
            ->line('')
            ->line('')
            ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat telegram Anda.')
            ->line('')
            ->line('Jika Anda tidak membuat akun, tidak diperlukan tindakan lebih lanjut.')
            ->line('')
            ->line('Salam,')
            ->line(identitas('nama_desa'))
            ->button('Verifikasi Alamat Telegram', $this->verificationUrl($notifiable));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     *
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $hash      = sha1($for = $this->via == 'mail' ? $notifiable->getEmailForVerification() : $notifiable->getTelegramForVerification());
        $signature = hash_hmac('sha256', $for, config_item('encryption_key'));
        $expire    = strtotime(date('Y-m-d H:i:s') . ' +60 minutes');
        $via       = $this->via === 'mail' ? 'email' : 'telegram';

        return site_url("layanan-mandiri/daftar/verifikasi/{$via}/verify/{$hash}?signature={$signature}&expires={$expire}");
    }
}
