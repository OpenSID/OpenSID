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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Menu extends BaseModel
{
    use ConfigId;
    use SortableTrait;

    // TODO:: Ganti menggunakan StatusEnum
    public const LOCK   = 0;
    public const UNLOCK = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'nama',
        'link',
        'parrent',
        'link_tipe',
        'enabled',
        'urut',

    ];

    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => false,
    ];
    protected $appends      = ['link_url'];
    private array $listMenu = [];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $urutTerakhir = Menu::select(['urut'])->whereParrent($model->parrent)->orderBy('urut', 'desc')->first();
            $model->urut  = $urutTerakhir ? (int) ($urutTerakhir->urut) + 1 : 1;
        });
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
        return $this->belongsTo(Menu::class, 'parrent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parrent', 'id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(Menu::class, 'parrent', 'id')->where('enabled', 1)->with(['childrens' => static fn ($q) => $q->select(['id', 'nama', 'parrent', 'link_tipe', 'link'])->orderBy('urut')->where('enabled', 1)]);
    }

    protected function getLinkUrlAttribute()
    {
        if ($this->attributes['link_tipe'] == 99) {
            return $this->attributes['link'];
        }
        if ($this->attributes['link_tipe'] == 88) {
            return site_url('embed?url=' . $this->attributes['link']);
        }

        return menu_slug($this->attributes['link']);
    }

    public function getSelfParents()
    {
        $result = collect([$this->toArray()]);
        $node   = $this->parent;
        if (! $node) {
            return $result;
        }

        do {
            $result->push($node->toArray());
            $node = $node->parent;
        } while ($node);

        return $result;
    }

    public function tree()
    {
        return $this->select(['id', 'nama', 'parrent', 'link_tipe', 'link'])
            ->where('parrent', 0)->where('enabled', 1)->with('childrens')
            ->orderBy('urut')
            ->get();
    }

    public function buildArray($nodes, $prefix = []): array
    {
        foreach ($nodes as $node) {
            $tmpPrefix                 = $prefix;
            $this->listMenu[$node->id] = (empty($tmpPrefix) ? '' : implode(' / ', $tmpPrefix) . ' / ') . $node->nama;
            $tmpPrefix                 = array_merge($tmpPrefix, [$node->nama]);
            if ($node->childrens) {
                $this->buildArray($node->childrens, $tmpPrefix);
            }
        }

        return $this->listMenu;
    }

    protected function scopeArtikel($query)
    {
        return $query->where('link', 'like', 'artikel/%');
    }

    protected function scopeStatistik($query)
    {
        return $query->where('link', 'like', 'statistik%');
    }

    protected function scopeLainnya($query)
    {
        return $query->whereIn('link', ['dpt', 'data-wilayah']);
    }
}
