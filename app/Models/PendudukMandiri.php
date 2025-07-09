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

namespace App\Models;

use App\Notifications\Penduduk\VerifyNotification;
use App\Services\Auth\Traits\Authorizable;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Notifications\Notifiable;

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
    protected $primaryKey = 'id_pend';

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_penduduk_mandiri';

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

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
     * Scope query untuk aktif
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatus($query, mixed $value = 1)
    {
        return $query->where('aktif', $value);
    }

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
        $ganti    = $data;
        $pin_lama = hash_pin(bilangan($ganti['pin_lama']));
        hash_pin(bilangan($ganti['pin_baru1']));
        $pin_baru2 = hash_pin(bilangan($ganti['pin_baru2']));

        $pilihan_kirim = $ganti['pilihan_kirim'];

        // Ganti password
        $pin = PendudukMandiri::where('id_pend', $id_pend)->first()->pin;

        $data = [
            'id_pend'    => $id_pend,
            'pin'        => $pin_baru2,
            'last_login' => date('Y-m-d H:i:s', NOW()),
            'ganti_pin'  => 0,
        ];

        switch (true) {
            case akun_demo($id_pend):
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Tidak dapat mengubah PIN akun demo',
                ];
                break;

            case $pin_lama != $pin:
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'PIN gagal diganti, <b>PIN Lama</b> yang anda masukkan tidak sesuai',
                ];
                break;

            case $pin_baru2 == $pin:
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => '<b>PIN</b> gagal diganti, Silahkan ganti <b>PIN Lama</b> anda dengan <b>PIN Baru</b> ',
                ];
                break;

            case $pilihan_kirim == 'kirim_telegram':
                if ($this->kirimTelegram(['id_pend' => $id_pend, 'pin' => $ganti['pin_baru2'], 'nama' => $nama])) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'aksi'   => site_url('layanan-mandiri/keluar'),
                        'pesan'  => 'PIN Baru sudah dikirim ke Akun Telegram Anda',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => '<b>PIN Baru</b> gagal dikirim ke Telegram, silahkan hubungi operator',
                    ];
                }
                break;

            case $pilihan_kirim == 'kirim_email':
                if ($this->kirimEmail(['id_pend' => $id_pend, 'pin' => $ganti['pin_baru2'], 'nama' => $nama])) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'aksi'   => site_url('layanan-mandiri/keluar'),
                        'pesan'  => 'PIN Baru sudah dikirim ke Akun Email Anda',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => '<b>PIN Baru</b> gagal dikirim ke Email, silahkan hubungi operator',
                    ];
                }
                break;

            default:
                PendudukMandiri::where('id_pend', $id_pend)->update($data);

                $respon = [
                    'status' => 1, // Notif berhasil
                    'aksi'   => site_url('layanan-mandiri/keluar'),
                    'pesan'  => 'PIN berhasil diganti, silahkan masuk kembali dengan Kode PIN : ' . $ganti['pin_baru2'],
                ];
                break;
        }

        set_session('notif', $respon);

        return $respon;
    }
}
