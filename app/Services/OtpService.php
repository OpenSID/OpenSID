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

use App\Mail\OtpMail;
use App\Models\OtpToken;
use App\Models\User;
use Carbon\Carbon;
use CI_Session;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Generate OTP code (6 digit)
     */
    public function generateOtpCode(): int
    {
        return random_int(100000, 999999);
    }

    /**
     * Generate and save OTP token
     *
     * @param string $channel (email|telegram)
     * @param string $purpose (activation|login)
     *
     * @return array ['token' => OtpToken, 'otp' => int]
     */
    public function generateAndSave(User $user, string $channel, string $identifier, string $purpose = 'login'): array
    {
        // Generate OTP code
        $otp = $this->generateOtpCode();

        // Hash the OTP
        $tokenHash = Hash::make($otp);

        // Delete old tokens for this user and purpose
        OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->delete();

        // Create new token
        $expiryMinutes = setting('otp_expiry_minutes');
        $token         = OtpToken::create([
            'user_id'    => $user->id,
            'token_hash' => $tokenHash,
            'channel'    => $channel,
            'identifier' => $identifier,
            'purpose'    => $purpose,
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
            'attempts'   => 0,
        ]);

        return [
            'token' => $token,
            'otp'   => $otp,
        ];
    }

    /**
     * Send OTP via email
     */
    public function sendViaEmail(string $email, int $otp, string $purpose = 'login'): bool
    {
        try {
            Mail::to($email)->send(new OtpMail($otp, $purpose));

            return true;
        } catch (Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Send OTP via Telegram
     */
    public function sendViaTelegram(string $chatId, int $otp, string $purpose = 'login'): bool
    {
        try {
            $botToken = setting('telegram_token');

            if (empty($botToken)) {
                Log::warning('Telegram bot token not configured');

                return false;
            }

            $message = $this->formatTelegramMessage($otp, $purpose);

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Failed to send OTP via Telegram: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Generate and send OTP
     */
    public function generateAndSend(User $user, string $channel, string $identifier, string $purpose = 'login'): array
    {
        $result = $this->generateAndSave($user, $channel, $identifier, $purpose);
        $otp    = $result['otp'];

        $sent = false;
        if ($channel === 'email') {
            $sent = $this->sendViaEmail($identifier, $otp, $purpose);
        } elseif ($channel === 'telegram') {
            $sent = $this->sendViaTelegram($identifier, $otp, $purpose);
        }

        return [
            'token' => $result['token'],
            'sent'  => $sent,
        ];
    }

    /**
     * Verify OTP
     */
    public function verify(User $user, string $otp, string $purpose = 'login'): array
    {
        // First check if token exists at all (including expired/max attempts)
        $token = OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->first();

        if (! $token) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ];
        }

        // Check if expired
        if ($token->isExpired()) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ];
        }

        if (! Hash::check($otp, $token->token_hash)) {
            $token->incrementAttempts();

            // Pindahkan pengecekan maksimal percobaan ke sini, setelah attempts ditingkatkan
            if ($token->hasMaxAttempts()) {
                // Hapus token karena sudah tidak bisa digunakan lagi
                $token->delete();

                return [
                    'success' => false,
                    'message' => 'Maksimal percobaan telah tercapai. Silakan minta kode baru.',
                    'reason'  => 'max_attempts',
                ];
            }

            $maxTrials         = (int) setting('otp_max_trials');
            $remainingAttempts = $maxTrials - $token->attempts;

            // Provide specific message based on purpose
            $message = "Kode OTP salah. Sisa percobaan: {$remainingAttempts}";
            if ($purpose === '2fa_login') {
                $message = "Kode 2FA salah. Sisa percobaan: {$remainingAttempts}";
            }

            return [
                'success' => false,
                'message' => $message,
            ];
        }

        // OTP is valid, delete the token
        $token->delete();

        // Provide specific success message based on purpose
        $message = 'Kode OTP berhasil diverifikasi';
        if ($purpose === '2fa_login') {
            $message = 'Kode 2FA berhasil diverifikasi';
        }

        return [
            'success' => true,
            'message' => $message,
        ];
    }

    /**
     * Resend OTP based on purpose and session data.
     */
    public function resend(string $purpose, CI_Session $session): array
    {
        if ($purpose === 'activation') {
            $sessionKey  = 'otp_activation';
            $sessionData = $session->userdata($sessionKey);

            if (! $sessionData) {
                return ['success' => false, 'message' => 'Sesi aktivasi tidak ditemukan.'];
            }

            $user       = Auth::user();
            $channel    = $sessionData['channel'];
            $identifier = $sessionData['identifier'];

        } elseif ($purpose === 'login') {
            $sessionKey  = 'otp_login';
            $sessionData = $session->userdata($sessionKey);

            if (! $sessionData) {
                return ['success' => false, 'message' => 'Sesi login tidak ditemukan.'];
            }

            $user = User::find($sessionData['user_id']);
            if (! $user) {
                return ['success' => false, 'message' => 'Pengguna tidak ditemukan.'];
            }

            $channel    = $user->otp_channel;
            $identifier = $user->otp_identifier;

        } else {
            return ['success' => false, 'message' => 'Tujuan OTP tidak valid.'];
        }

        // Generate and send the new OTP
        $result = $this->generateAndSend(
            $user,
            $channel,
            $identifier,
            $purpose
        );

        if ($result['sent']) {
            // Update the sent_at timestamp in the session
            $sessionData['sent_at']    = Carbon::now()->timestamp;
            $sessionData['expires_at'] = Carbon::now()->addMinutes(setting('otp_expiry_minutes'))->timestamp;
            $session->set_userdata($sessionKey, $sessionData);

            return [
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim ke ' . ($channel === 'email' ? 'email' : 'Telegram') . ' Anda.',
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal mengirim ulang kode OTP. Silakan coba lagi nanti.',
        ];
    }

    /**
     * Cleanup expired tokens
     *
     * @return int Number of deleted tokens
     */
    public function cleanupExpired(): int
    {
        return OtpToken::where('expires_at', '<', now())->delete();
    }

    /**
     * Verify Telegram chat ID
     */
    public function verifyTelegramChatId(string $chatId): bool
    {
        try {
            $botToken = setting('telegram_token');

            if (empty($botToken)) {
                return false;
            }

            $response = Http::post("https://api.telegram.org/bot{$botToken}/getChat", [
                'chat_id' => $chatId,
            ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Failed to verify Telegram chat ID: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Deactivate OTP for all users.
     */
    public function deactivateForAllUsers(): bool
    {
        // Hapus sesi aktivasi OTP yang mungkin sedang berjalan
        ci()->session->unset_userdata('otp_activation');

        return true;
    }

    /**
     * Get Telegram bot username from token.
     */
    public function getBotUsername(): ?string
    {
        try {
            $botToken = setting('telegram_token');

            if (empty($botToken)) {
                return null;
            }

            $response = Http::timeout(5)->get("https://api.telegram.org/bot{$botToken}/getMe");

            if (! $response->successful()) {
                // Lemparkan exception jika permintaan tidak berhasil (misalnya, token tidak valid)
                throw new Exception('Gagal menghubungi API  periksa token dan chat_id anda: ' . $response->body());
            }

            return $response->json('result.username');
        } catch (Exception $e) {
            Log::error('Failed to get Telegram bot username: ' . $e->getMessage());

            throw $e; // Lemparkan kembali exception
        }
    }

    /**
     * Format Telegram message
     */
    private function formatTelegramMessage(int $otp, string $purpose): string
    {
        $appName = ucwords(setting('sebutan_desa')) . ' ' . identitas('nama_desa');

        switch ($purpose) {
            case 'activation':
                $purposeText = 'Aktivasi OTP';
                break;

            case '2fa_activation':
                $purposeText = 'Aktivasi 2FA';
                break;

            case '2fa_login':
                $purposeText = 'Login 2FA';
                break;

            default:
                $purposeText = 'Login';
                break;
        }

        return "🔐 <b>{$appName} - {$purposeText}</b>\n\n" .
            "Kode OTP Anda: <code>{$otp}</code>\n\n" .
            '⏰ Berlaku selama ' . setting('otp_expiry_minutes') . " menit\n" .
            "🔒 Jangan bagikan kode ini kepada siapa pun\n\n" .
            '<i>Jika Anda tidak meminta kode ini, abaikan pesan ini.</i>';
    }
}
