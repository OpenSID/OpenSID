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
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property int|null                                                            $activities_count
 * @property string|null                                                         $attribute
 * @property int                                                                 $config_id
 * @property int                                                                 $id
 * @property string|null                                                         $jenis
 * @property string|null                                                         $judul
 * @property string|null                                                         $kategori
 * @property string|null                                                         $keterangan
 * @property string|null                                                         $key
 * @property array<array-key, mixed>|null                                        $option
 * @property int|null                                                            $urut
 * @property string|null                                                         $value
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
        'sso_callback_secret',
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
            if ($lokasi && ($currentVal = setting($model->key))) {
                $targetFile = $lokasi . $currentVal;
                if (is_file($targetFile)) {
                    unlink($targetFile);
                }
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
        return json_decode((string) ($this->attributes['option'] ?? ''), true);
    }

    public function toComponentData(): array
    {
        $attrData = [];
        $rawAttribute = $this->attribute;

        $isJson = false;
        if (is_string($rawAttribute) && !empty($rawAttribute)) {
            $decoded = json_decode($rawAttribute, true);
            if (is_array($decoded)) {
                $attrData = $decoded;
                $isJson = true;
            } else {
                if (preg_match('/class=["\']([^"\']+)["\']/', $rawAttribute, $matches)) {
                    $attrData['class'] = $matches[1];
                }
                if (str_contains($rawAttribute, 'readonly')) {
                    $attrData['readonly'] = 'readonly';
                }
                if (str_contains($rawAttribute, 'disabled')) {
                    $attrData['disabled'] = 'disabled';
                }
            }
        }

        $originalAttrData = $attrData;

        $class = $attrData['class'] ?? '';
        unset($attrData['class']);

        $requiredIf = $attrData['required_if'] ?? null;
        unset($attrData['required_if']);

        $readonly = !empty($attrData['readonly']) || (! $isJson && is_string($rawAttribute) && str_contains($rawAttribute, 'readonly')) ? 'readonly' : '';
        $disabled = !empty($attrData['disabled']) || (! $isJson && is_string($rawAttribute) && str_contains($rawAttribute, 'disabled')) ? 'disabled' : '';
        unset($attrData['readonly'], $attrData['disabled']);

        // Filter atribut internal / konfigurasi komponen agar tidak bocor ke markup HTML
        unset($attrData['multiple'], $attrData['where']);

        $scalarAttrs = array_filter(
            $attrData,
            static fn ($v) => !is_array($v) && !is_object($v) && !is_bool($v)
        );

        $htmlAttributes = implode(
            ' ',
            array_map(
                static fn ($k, $v) => $k . '="' . htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8') . '"',
                array_keys($scalarAttrs),
                $scalarAttrs,
            ),
        );

        return [
            'type'        => $this->jenis ?: 'input-text',
            'key'         => $this->key,
            'default'     => $this->value,
            'class'       => $class,
            'readonly'    => $readonly,
            'disabled'    => $disabled,
            'attributes'  => $htmlAttributes,
            'option'      => $this->option,
            'required_if' => $requiredIf,
            'raw_attr'    => $originalAttrData,
        ];
    }

    public function getValueAttribute()
    {
        $jenis = $this->attributes['jenis'] ?? '';
        $value = $this->attributes['value'] ?? null;

        $isSimbol = $jenis === 'select-simbol';

        if ($isSimbol) {
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
