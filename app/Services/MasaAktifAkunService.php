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
     * Menonaktifkan akun pengguna yang tidak aktif dan mengirim notifikasi.
     *
     * @return array Hasil proses (jumlah akun dinonaktifkan, pesan)
     */
    public function deactivateInactiveAccounts(): array
    {
        $masaTidakAktifHari = (int) setting('masa_akun_tidak_aktif');
        if ($masaTidakAktifHari <= 0) {
            return ['success' => false, 'message' => 'Pengaturan masa akun tidak aktif tidak valid.'];
        }

        $tanggalBatas = Carbon::now()->subDays($masaTidakAktifHari);

        // Ambil pengguna yang aktif tetapi last login nya null.
        $nullLastLoginUsers = User::where('active', AktifEnum::AKTIF) // Hanya targetkan pengguna yang masih aktif
            ->where('id', '!=', super_admin()) // Jangan pilih super admin
            ->whereNull('last_login')
            ->update(['last_login' => Carbon::now()]);

        // Ambil pengguna yang aktif tetapi tidak login dalam rentang waktu yang ditentukan.
        $inactiveUsers = User::where('active', AktifEnum::AKTIF) // Hanya targetkan pengguna yang masih aktif
            ->where('id', '!=', super_admin()) // Jangan nonaktifkan super admin
            ->whereNotNull('last_login')
            ->where('last_login', '<', $tanggalBatas) // Sudah lama tidak login
            ->get();

        $deactivatedCount = 0;

        foreach ($inactiveUsers as $user) {
            $user->active = AktifEnum::TIDAK_AKTIF;
            $user->save();
            $deactivatedCount++;

            $this->sendAccountActivatedNotification($user);
        }

        if ($deactivatedCount > 0) {
            logger()->info("Berhasil menonaktifkan {$deactivatedCount} akun tidak aktif.");

            return ['success' => true, 'count' => $deactivatedCount, 'message' => "Berhasil menonaktifkan {$deactivatedCount} akun tidak aktif."];
        }

        return ['success' => false, 'message' => 'Tidak ada akun yang dinonaktifkan karena semua akun aktif dalam rentang waktu yang ditentukan.'];
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
