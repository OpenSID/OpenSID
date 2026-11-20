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
use App\Models\Galery as Galeri;
use App\Traits\ConfigId;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\Activitylog\ActivityLogStatus;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string|null $judul
 * @property string|null $key
 * @property string|null $value
 * @property int|null $urut
 * @property string|null $keterangan
 * @property string|null $jenis
 * @property array<array-key, mixed>|null $option
 * @property string|null $attribute
 * @property string|null $kategori
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi urut()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereUrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingAplikasi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class SettingAplikasi extends BaseModel
{
    use ConfigId;
    use LogsActivity;
    use QueryCacheable;

    public const WARNA_TEMA      = '#eab308';
    public const TAHUN_IDM_MIN   = 2021;
    public const JARAK_KEHADIRAN = 50; // dalam meter

    // forever cache
    public $cacheFor = -1;

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
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_aplikasi';

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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'option' => 'json',
    ];

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty() || $deleting) {
            $lokasi = null;

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

    protected static function boot()
    {
        parent::boot();

        cache()->forget('setting_aplikasi');

        // Buang cache in-memory resolveSetting() pada setiap perubahan data
        // (update/delete langsung lewat model) agar setting() tidak membaca
        // nilai usang dalam request yang sama.
        static::updated(static function (): void {
            app(\App\Repositories\SettingAplikasiRepository::class)->forgetResolvedSetting();
        });

        static::deleted(static function (): void {
            app(\App\Repositories\SettingAplikasiRepository::class)->forgetResolvedSetting();
        });

        static::updating(static function ($model): void {
            if (is_string($model->value)) {
                static::deleteFile($model, $model->value);
            }
        });

        static::deleting(static function ($model): void {
            if (is_string($model->value)) {
                static::deleteFile($model, $model->value, true);
            }
        });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        logger()->warning(sprintf('Tabel log_activity tidak tersedia, log aktivitas tidak akan dicatat pada: %s', self::class));

        $this->disableLogging();
        app(ActivityLogStatus::class)->disable();
    }

    /**
     * {@inheritDoc}
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Pengaturan Aplikasi')
            ->setDescriptionForEvent(fn ($event): string => sprintf(
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
        if ($this->attributes['key'] == 'apbdes_tahun') {
            return Keuangan::tahunAnggaran()->pluck('tahun', 'tahun')->toArray();
        }

        if ($this->attributes['jenis'] == 'option' && $this->attributes['key'] == 'tampilan_anjungan_slider') {
            return Galeri::whereParrent(Galeri::PARRENT)->whereEnabled(StatusEnum::YA)->pluck('nama', 'id');
        }
        if ($this->attributes['jenis'] == 'boolean') {
            return [
                1 => 'Ya',
                0 => 'Tidak',
            ];
        }

        return json_decode((string) $this->attributes['option'], true);
    }

    public function getValueAttribute()
    {
        $jenis = $this->attributes['jenis'] ?? '';
        $value = $this->attributes['value'] ?? null;

        if ($jenis == 'select-simbol') {
            return default_file(LOKASI_SIMBOL_LOKASI . $value);
        }

        if ($jenis == 'input-number' && is_numeric($value)) {
            return $value + 0;
        }

        return $value;
    }

    public function scopeUrut($query)
    {
        return $query->orderBy(
            'urut',
            'asc'
        );
    }
}
