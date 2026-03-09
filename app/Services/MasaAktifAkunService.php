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

namespace App\Services;

use App\Enums\AktifEnum;
use App\Mail\MasaAktifAkunMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MasaAktifAkunService
{
    /**
     * Send account activated notification via email and/or Telegram.
     *
     * @param User $user               The user whose account was activated.
     * @param int  $masaTidakAktifHari Batas hari tidak aktif.
     *
     * @return array An array indicating success for email and telegram.
     */
    public function sendAccountActivatedNotification(User $user): array
    {
        $emailSent    = false;
        $telegramSent = false;

        $this->logActivity($user);

        // Utamakan notifikasi Telegram
        if (! empty($user->id_telegram) && setting('telegram_notifikasi')) {
            $message      = $this->formatTelegramMessage($user);
            $telegramSent = $this->sendTelegramMessage($user->id_telegram, $message);
        }
        // Jika Telegram tidak aktif/tersedia, baru kirim email
        elseif (! empty($user->email) && setting('email_notifikasi')) {
            try {
                Mail::to($user->email)->send(new MasaAktifAkunMail($user));
                $emailSent = true;
            } catch (Exception $e) {
                Log::error('Failed to send account activated email: ' . $e->getMessage());
            }
        }

        return [
            'email_sent'    => $emailSent,
            'telegram_sent' => $telegramSent,
        ];
    }

    /**
     * Memeriksa apakah akun pengguna sudah tidak aktif terlalu lama saat login.
     * Jika ya, nonaktifkan akun dan kembalikan pesan error.
     *
     * @param User $user Pengguna yang sedang mencoba login.
     *
     * @return string|null Pesan error jika akun dinonaktifkan, null jika akun masih aktif.
     */
    public function checkAndDeactivateIfInactive(User $user): ?string
    {
        if (! setting('masa_akun_pengguna')) {
            return null;
        }

        if ($user->id == super_admin()) {
            return null;
        }

        $masaTidakAktifHari = (int) setting('masa_akun_tidak_aktif');
        if ($masaTidakAktifHari <= 0) {
            return null;
        }

        // Akun belum pernah login — set last_login ke sekarang agar timer mulai berjalan
        if ($user->last_login === null) {
            $user->last_login = Carbon::now();
            $user->save();

            return null;
        }

        $tanggalBatas = Carbon::now()->subDays($masaTidakAktifHari);
        $lastLoginDate = Carbon::parse($user->last_login); // Ensure it's a Carbon instance

        if ($lastLoginDate->lessThan($tanggalBatas)) {
            $user->active = AktifEnum::TIDAK_AKTIF;
            $user->save();

            $this->sendAccountActivatedNotification($user);

            logger()->notice("Akun pengguna '{$user->nama}' ({$user->username}) dinonaktifkan saat login karena tidak aktif selama lebih dari {$masaTidakAktifHari} hari.");

            return "Login gagal. Akun Anda telah dinonaktifkan karena tidak digunakan selama lebih dari {$masaTidakAktifHari} hari. Silakan hubungi administrator untuk mengaktifkan kembali.";
        }

        return null;
    }

    /**
     * Generic method to send a Telegram message.
     */
    private function sendTelegramMessage(string $chatId, string $message): bool
    {
        $botToken = setting('telegram_token');
        if (empty($botToken)) {
            Log::warning('Telegram bot token not configured');

            return false;
        }

        $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ]);

        return $response->successful();
    }

    /**
     * Format Telegram message for account activated notification.
     */
    private function formatTelegramMessage(User $user): string
    {
        $masaTidakAktifHari = (int) setting('masa_akun_tidak_aktif');
        $appName            = ucwords(setting('sebutan_desa')) . ' ' . identitas('nama_desa');
        $status             = $user->active == 1 ? 'berhasil diaktifkan' : "dinonaktifkan secara tidak aktif selama lebih dari {$masaTidakAktifHari} hari ";
        $dapatLogin         = $user->active == 1 ? 'dapat' : 'tidak dapat';
        $icon               = $user->active == 1 ? '✅' : '🔒';

        return "{$icon} <b>{$appName} - Akun {$status}</b>\n\n" .
               "Halo {$user->nama},\n\nAkun Anda di {$appName} telah {$status}.\nAnda sekarang {$dapatLogin} login menggunakan kredensial Anda.\n\nTerima kasih.";
    }

    /**
     * Catat aktivitas aktivasi/deaktivasi akun.
     */
    private function logActivity(User $user): void
    {
        $status  = $user->active == 1 ? 'diaktifkan' : 'dinonaktifkan';
        $event   = $user->active == 1 ? 'Aktif' : 'Nonaktif';
        $message = "Akun pengguna '{$user->nama}' ({$user->username}) telah {$status}.";

        // TODO: Ganti dengan helper log aktivitas OpenSID jika tersedia, contoh: log_activity($message);
        activity()
            ->causedBy(auth()->id) // Jika dijalankan oleh sistem, penyebabnya adalah user itu sendiri
            ->performedOn($user)
            ->inLog('Akun')
            ->event($event)
            ->withProperties([
                'username' => $user->username,
                'nama'     => $user->nama,
            ])
            ->log($message);
    }
}
