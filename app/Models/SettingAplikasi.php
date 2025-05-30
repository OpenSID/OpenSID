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
use App\Models\Galery as Galeri;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\Schema;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

defined('BASEPATH') || exit('No direct script access allowed');

class SettingAplikasi extends BaseModel
{
    use ConfigId;
    use LogsActivity;
    use QueryCacheable;

    public const WARNA_TEMA    = '#eab308';
    public const TAHUN_IDM_MIN = 2021;

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    // forever cache
    public $cacheFor = -1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_aplikasi';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'judul',
        'key',
        'value',
        'keterangan',
        'jenis',
        'option',
        'attribute',
        'kategori',
        'urut',
    ];

    protected $guarded = ['id'];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'config_id',
    ];

    /**
     * Key yang sensitif dan tidak boleh ditampilkan ketika di panggil di view.
     */
    public static array $sensitiveKeys = [
        'api_opendk_server',
        'api_opendk_key',
        'api_gform_id_script',
        'api_gform_credential',
        'api_gform_redirect_uri',
        'layanan_opendesa_token',
        'telegram_token',
        'telegram_user_id',
        'tte_api',
        'tte_username',
        'tte_password',
        'email_protocol',
        'email_smtp_host',
        'email_smtp_user',
        'email_smtp_pass',
        'email_smtp_port',
        'google_recaptcha_site_key',
        'google_recaptcha_secret_key',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'option' => 'json',
    ];

    /**
     * {@inheritDoc}
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Pengaturan Aplikasi')
            ->setDescriptionForEvent(fn ($event) => sprintf(
                'Pengaturan aplikasi %s telah di %s',
                $this->key,
                match ($event) {
                    'created' => 'dibuat',
                    'updated' => 'diubah',
                    'deleted' => 'dihapus',
                    default   => $event,
                }
            ))
            ->logAll()
            ->logOnlyDirty();
    }

    public function getOptionAttribute()
    {
        if ($this->attributes['jenis'] == 'option' && $this->attributes['key'] == 'tampilan_anjungan_slider') {
            return Galeri::whereParrent(Galeri::PARRENT)->whereEnabled(StatusEnum::YA)->pluck('nama', 'id');
        }
        if ($this->attributes['jenis'] == 'boolean') {
            return [
                1 => 'Ya',
                0 => 'Tidak',
            ];
        }

        return json_decode($this->attributes['option'], true);
    }

    public function getValueAttribute()
    {
        if ($this->attributes['jenis'] == 'select-simbol') {
            return base_url(LOKASI_SIMBOL_LOKASI . $this->attributes['value']);
        }

        return $this->attributes['value'];
    }

    public function scopeUrut($query)
    {
        return $query->orderBy(
            Schema::hasColumn('setting_aplikasi', 'urut') ? 'urut' : 'key',
            'asc'
        );
    }

    protected static function boot()
    {
        parent::boot();

        cache()->forget('setting_aplikasi');

        static::updating(static function ($model) {
            if (is_string($model->value)) {
                static::deleteFile($model, $model->value);
            }
        });

        static::deleting(static function ($model) {
            if (is_string($model->value)) {
                static::deleteFile($model, $model->value, true);
            }
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty() || $deleting) {
            if ($model->key == 'latar_website') {
                $lokasi = 'desa/pengaturan/images/';
            }

            if ($model->key == 'latar_login') {
                $lokasi = LATAR_LOGIN;
            }

            if ($model->key == 'latar_login_mandiri') {
                $lokasi = LATAR_LOGIN;
            }

            if ($model->key == 'latar_kehadiran') {
                $lokasi = LATAR_LOGIN;
            }
            if (file_exists($lokasi)) {
                unlink($lokasi . setting($model->key));
            }
        }
    }
}
