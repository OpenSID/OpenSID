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

use App\Enums\StatusEnum;
use App\Services\Auth\Traits\Authorizable;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

defined('BASEPATH') || exit('No direct script access allowed');

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use ConfigId;
    use ShortcutCache;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;

    protected $table = 'user';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

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
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\Admin\ResetPasswordNotification($token));
    }

    /**
     * {@inheritDoc}
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\Admin\VerifyEmailNotification());
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function pamong()
    {
        return $this->hasOne(Pamong::class, 'pamong_id', 'pamong_id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
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
}
