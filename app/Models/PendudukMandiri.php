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

namespace App\Models;

use App\Libraries\OTP\OtpManager;
use App\Notifications\Penduduk\VerifyNotification;
use App\Services\Auth\Traits\Authorizable;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use App\Traits\StatusTrait;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukMandiri extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmailContract
{
    use ConfigId;
    use ShortcutCache;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
    use StatusTrait;

    /**
     * {@inheritDoc}
     */
    public const CREATED_AT = 'tanggal_buat';

    /**
     * {@inheritDoc}
     */
    public const UPDATED_AT = 'updated_at';

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    public $statusColumName = 'aktif';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'id_pend';

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_penduduk_mandiri';

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'penduduk',
    ];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_pend');
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->pin;
    }

    /**
     * Get email penduduk attribute.
     *
     * @return string
     */
    public function getEmailAttribute()
    {
        return $this->penduduk->email;
    }

    /**
     * Get email penduduk attribute.
     *
     * @return string
     */
    public function getTelegramAttribute()
    {
        return $this->penduduk->telegram;
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Get the telegram address where password reset links are sent.
     *
     * @return string
     */
    public function getTelegramForPasswordReset()
    {
        return $this->telegram;
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return null !== $this->penduduk->email_tgl_verifikasi;
    }

    /**
     * Determine if the user has verified their telegram.
     */
    public function hasVerifiedTelegram(): bool
    {
        return null !== $this->penduduk->telegram_tgl_verifikasi;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->penduduk()->update([
            'email_tgl_verifikasi' => $this->freshTimestamp(),
        ]);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markTelegramAsVerified()
    {
        return $this->penduduk()->update([
            'telegram_tgl_verifikasi' => $this->freshTimestamp(),
        ]);
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getTelegramForVerification()
    {
        return $this->telegram;
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyNotification('mail'));
    }

    /**
     * Send the email verification notification.
     */
    public function sendTelegramVerificationNotification(): void
    {
        $this->notify(new VerifyNotification('telegram'));
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @param mixed  $via
     */
    public function sendPasswordResetNotification($token, $via = 'mail'): void
    {
        $this->notify(new \App\Notifications\Penduduk\ResetPasswordNotification($token, $via));
    }

    public function generate_pin(): string
    {
        return strrev(random_int(100000, 999999));
    }

    public function gantiPin($id_pend, $nama, $data): array
    {
        $pin_lama      = $data['pin_lama'];
        $pin_baru1     = $data['pin_baru1'];
        $pin_baru2     = $data['pin_baru2'];
        $pilihan_kirim = $data['pilihan_kirim'];

        $otp = new OtpManager();

        if (akun_demo($id_pend)) {
            return $this->withReponse(-1, 'Tidak dapat mengubah PIN akun demo');
        }

        if ($pin_baru1 !== $pin_baru2) {
            return $this->withReponse(-1, 'Konfirmasi PIN baru tidak sesuai');
        }

        $pengguna = PendudukMandiri::where('id_pend', $id_pend)->first();
        if (! $pengguna) {
            return $this->withReponse(-1, 'Pengguna tidak ditemukan');
        }

        if (! Hash::check(bilangan($pin_lama), $pengguna->pin)) {
            return $this->withReponse(-1, 'PIN gagal diganti, <b>PIN Lama</b> yang Anda masukkan tidak sesuai');
        }

        if (Hash::check(bilangan($pin_baru2), $pengguna->pin)) {
            return $this->withReponse(-1, '<b>PIN</b> gagal diganti, Silakan ganti <b>PIN Lama</b> Anda dengan <b>PIN Baru</b>');
        }

        $pin_baru_hashed = Hash::make(bilangan($pin_baru2));

        $updateData = [
            'pin'        => $pin_baru_hashed,
            'last_login' => date('Y-m-d H:i:s'),
            'ganti_pin'  => 0,
        ];

        $logoutUrl = site_url('layanan-mandiri/keluar');

        switch ($pilihan_kirim) {
            case 'kirim_telegram':
                try {
                    $otp->driver('telegram')->kirimPinBaru($pengguna->telegram, $pin_baru2, $nama);
                    PendudukMandiri::where('id_pend', $id_pend)->update($updateData);

                    return $this->withReponse(1, 'PIN Baru sudah dikirim ke Akun Telegram Anda', $logoutUrl);
                } catch (Exception $e) {
                    logger()->error($e);

                    return $this->withReponse(-1, '<b>PIN Baru</b> gagal dikirim ke Telegram, silakan hubungi operator');
                }

                    return $this->withReponse(-1, '<b>PIN Baru</b> gagal dikirim ke Telegram, silakan hubungi operator');

            case 'kirim_email':
                try {
                    $otp->driver('email')->kirimPinBaru($pengguna->email, $pin_baru2, $nama);
                    PendudukMandiri::where('id_pend', $id_pend)->update($updateData);

                    return $this->withReponse(1, 'PIN Baru sudah dikirim ke Akun Email Anda', $logoutUrl);
                } catch (Exception $e) {
                    logger()->error($e);

                    return $this->withReponse(-1, '<b>PIN Baru</b> gagal dikirim ke Email, silakan hubungi operator');
                }

                    return $this->withReponse(-1, '<b>PIN Baru</b> gagal dikirim ke Email, silakan hubungi operator');

            default:
                PendudukMandiri::where('id_pend', $id_pend)->update($updateData);

                return $this->withReponse(
                    1,
                    'PIN berhasil diganti, silakan masuk kembali dengan Kode PIN : ' . $pin_baru2,
                    $logoutUrl
                );
        }
    }

    private function withReponse(int $status, string $pesan, ?string $aksi = null): array
    {
        $response = [
            'status' => $status,
            'pesan'  => $pesan,
        ];

        if ($aksi) {
            $response['aksi'] = $aksi;
        }

        set_session('notif', $response);

        return $response;
    }
}
