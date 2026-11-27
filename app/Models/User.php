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

use App\Enums\StatusEnum;
use App\Services\Auth\Traits\Authorizable;
use App\Traits\ConfigId;
use App\Traits\SafeSoftDeletes;
use App\Traits\ShortcutCache;
use App\Traits\StatusTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int|null $config_id
 * @property string|null $username
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $id_grup
 * @property int|null $pamong_id
 * @property string|null $email
 * @property string|null $last_login
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int|null $active
 * @property int $otp_enabled
 * @property string|null $otp_channel
 * @property string|null $otp_identifier
 * @property string|null $telegram_chat_id
 * @property string|null $nama
 * @property string $id_telegram
 * @property string|null $token
 * @property string|null $token_exp
 * @property \Illuminate\Support\Carbon|null $telegram_verified_at
 * @property int $notif_telegram
 * @property string|null $company
 * @property string|null $phone
 * @property string|null $foto
 * @property string $session
 * @property int $batasi_wilayah
 * @property array<array-key, mixed>|null $akses_wilayah
 * @property bool $two_factor_enabled
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $status_label
 * @property-read string $url_foto
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \App\Models\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OneTimePassword> $oneTimePasswords
 * @property-read int|null $one_time_passwords_count
 * @property-read \App\Models\Pamong|null $pamong
 * @property-read \App\Models\UserGrup|null $userGrup
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User status(?mixed $status = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User superAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAksesWilayah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBatasiWilayah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdGrup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotifTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePamongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelegramChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelegramVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTokenExp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutConfigId($configId = '')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use ConfigId;
    use ShortcutCache;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
    use HasOneTimePasswords;
    use StatusTrait;
    use SafeSoftDeletes;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table           = 'user';
    protected $statusColumName = 'active';

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'url_foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'    => 'datetime',
        'telegram_verified_at' => 'datetime',
        'akses_wilayah'        => 'json',
        'two_factor_enabled'   => 'boolean',
    ];

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $fotoSedang = LOKASI_USER_PICT . 'sedang_' . $model->getOriginal($file);
            $fotoKecil  = LOKASI_USER_PICT . 'kecil_' . $model->getOriginal($file);
            if (file_exists($fotoSedang)) {
                unlink($fotoSedang);
            }
            if (file_exists($fotoKecil)) {
                unlink($fotoKecil);
            }
        }
    }

    public static function syaratSandi(string $password): bool
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $password));
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::forceDeleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
    }

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }

    /**
     * {@inheritDoc}
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\Admin\ResetPasswordNotification($token));
    }

    /**
     * {@inheritDoc}
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\Admin\VerifyEmailNotification());
    }

    public function getJWTIdentifier(): void
    {
        // return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pamong()
    {
        return $this->hasOne(Pamong::class, 'pamong_id', 'pamong_id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userGrup()
    {
        return $this->hasOne(UserGrup::class, 'id', 'id_grup');
    }

    /**
     * Scope query untuk status pengguna
     *
     * @return Builder
     */
    public function scopeStatus(mixed $query, mixed $status = 1)
    {
        if ($status == '') {
            return $query;
        }

        if ($status == StatusEnum::YA) {
            $query->whereHas('userGrup', static function ($query): void {
                    $query->status(StatusEnum::YA);
            });
        }

        return $query->where('active', $status);
    }

    /**
     * Scope query untuk super admin
     *
     * Super admin tidak terikat dengan status (selalu aktif) dan hanya ada 1 untuk setiap desa
     *
     * @return Builder
     */
    public function scopeSuperAdmin(mixed $query)
    {
        return $query->where('id_grup', UserGrup::getGrupId(UserGrup::ADMINISTRATOR))->first();
    }

    /**
     * Determine if the user has verified their telegram.
     */
    public function hasVerifiedTelegram(): bool
    {
        return null !== $this->telegram_verified_at;
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function getUrlFotoAttribute(): string
    {
        return AmbilFoto($this->foto, 'kecil_', $this->sex);
    }
}
