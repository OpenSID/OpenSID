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

use App\Traits\ConfigId;
use Illuminate\Support\Str;
use Rennokki\QueryCache\Traits\QueryCacheable;

defined('BASEPATH') || exit('No direct script access allowed');

class Theme extends BaseModel
{
    use ConfigId;
    use QueryCacheable;

    public const DEFAULT_THEME = 'esensi';
    public const PATH_SISTEM   = 'storage/app/themes/';
    public const PATH_DESA     = 'desa/themes/';

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    public $cacheFor = -1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'theme';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = [
        'full_path',
        'view_path',
        'asset_path',
    ];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'sistem' => 'integer',
        'status' => 'boolean',
        'opsi'   => 'json',
    ];

    public function getFullPathAttribute()
    {
        return $this->path;
    }

    public function getViewPathAttribute(): string
    {
        return $this->getFullPathAttribute() . '/resources/views';
    }

    public function getAssetPathAttribute(): string
    {
        return $this->getFullPathAttribute() . '/assets';
    }

    public function getConfigAttribute()
    {
        if (file_exists($path = $this->full_path . '/config.json')) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }

    public function scopeStatus($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    public function scopeSistem($query, $status = 1)
    {
        return $query->where('sistem', $status);
    }

    public function setVersiAttribute($value): void
    {
        if (empty($value)) {
            $value = VERSION;
        }

        $this->attributes['versi'] = $value;
    }

    public function getVersiAttribute(string $value): string
    {
        return 'v' . $value;
    }

    public function aktif()
    {
        $aktif = self::status()->first();

        if ($aktif && file_exists($aktif->full_path . '/composer.json')) {
            return $aktif;
        }

        self::whereIn('sistem', [0, 1])->update(['status' => 0]); // Menonaktifkan semua tema kecuali DEFAULT_THEME
        self::sistem()->where('slug', self::DEFAULT_THEME)->update(['status' => 1]); // Mengaktifkan DEFAULT_THEME

        return self::status()->first();
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $model->slug = Str::slug('desa-' . $model->nama);
        });

        static::deleting(static function ($model): void {
            deleteDir($model->full_path);

            cache()->forget('theme_active');
        });
    }
}
