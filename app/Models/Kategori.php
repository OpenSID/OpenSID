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

use App\Traits\ConfigIdNull;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Kategori extends BaseModel
{
    use ConfigIdNull;
    use SortableTrait;

    public const ENABLE = 1;
    public const LOCK   = 0;
    public const UNLOCK = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kategori';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'kategori',
        'parrent',
        'slug',
        'enabled',
        'urut',
        'tipe',
    ];

    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => false,
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $urutTerakhir = Kategori::select(['urut'])->where(['config_id' => $model->config_id])->whereParrent($model->parrent)->orderBy('urut', 'desc')->first();
            $model->urut  = $urutTerakhir ? (int) ($urutTerakhir->urut) + 1 : 1;
        });
    }

    /**
     * Scope a query to only enable category.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEnable($query)
    {
        return $query->where('enabled', static::ENABLE);
    }

    /**
     * Scope config_id, dipisah untuk kebutuhan OpenKab.
     *
     * @return Builder
     */
    public function scopeConfigId(mixed $query)
    {
        return $query->where('config_id', identitas('id'))->orWhereNull('config_id');
    }

    protected function scopeChild($query, int $parent)
    {
        return $query->whereParrent($parent);
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(self::UNLOCK);
    }

    public function isActive(): bool
    {
        return $this->enabled == self::UNLOCK;
    }

    /**
     * Get the parent that owns the Polygon
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'parrent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Kategori::class, 'parrent', 'id');
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class, 'id_kategori');
    }

    public static function isUniqueKategori($kategori, $config_id, $id = null)
    {
        $query = Kategori::where(['kategori' => $kategori, 'config_id' => $config_id]);
        if ($id) {
            $query->where('id', '!=', $id);
        }

        return $query->count();
    }
}
