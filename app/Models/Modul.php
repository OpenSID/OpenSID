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

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $modul
 * @property string|null $slug
 * @property string $url
 * @property bool $aktif
 * @property string|null $ikon
 * @property int|null $urut
 * @property int $level
 * @property int $hidden
 * @property string|null $ikon_kecil
 * @property Modul|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Modul> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Modul> $childrens
 * @property-read int|null $childrens_count
 * @property-read mixed $raw_aktif
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul isActive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul isChild()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul isNonActive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul isParent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul isShow()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul status($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereIkon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereIkonKecil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereModul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul whereUrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modul withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Modul extends BaseModel
{
    use ConfigId;

    public const PARENT       = 0;
    public const LOCK         = 0;
    public const UNLOCK       = 1;
    public const SHOW         = 0;
    public const SHOW_S       = 1;
    public const HIDDEN       = 2;
    public const SELALU_AKTIF = ['beranda', 'notif', 'pengguna'];

    // default modul
    public const DEFAULT_MODUL = [
        'beranda' => [
            'modul' => 'Beranda',
            'slug'  => 'beranda',
            'url'   => 'beranda',
            'ikon'  => 'fa-home',
        ],
    ];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_modul';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'aktif' => 'boolean',
    ];

    protected $appends = [
        'raw_aktif',
    ];

    public static function listIcon()
    {
        $list_icon = [];

        $file = PUBLICPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);

            return array_map(static fn ($a): string => explode(':', $a)[0], $list_icon);
        }

        return false;
    }

    protected static function booted()
    {
        static::creating(function ($item) {

            if (! $item->urut) {
                $maxUrut = ($item->parent == self::PARENT)
                    ? self::max('urut')
                    : self::where('parent', $item->parent)->max('urut');

                $item->urut = ($maxUrut ?? 0) + 1;
            }
        });
    }

    /**
     * Scope query untuk aktif
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeStatus($query, $value = 1)
    {
        return $query->where('aktif', $value);
    }

    public function scopeIsActive($query)
    {
        return $query->where('aktif', self::UNLOCK);
    }

    public function scopeIsNonActive($query)
    {
        return $query->where('aktif', self::LOCK);
    }

    public function scopeIsShow($query)
    {
        return $query->whereIn('hidden', [self::SHOW, self::SHOW_S]);
    }

    public function scopeIsParent($query)
    {
        return $query->where('parent', self::PARENT);
    }

    public function scopeIsChild($query)
    {
        return $query->where('parent', '!=', 0);
    }

    /**
     * Get the parent that owns the Polygon
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Modul::class, 'parent', 'id');
    }

    /**
     * Get all of the children for the Modul
     */
    public function children(): HasMany
    {
        return $this->hasMany(Modul::class, 'parent', 'id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(Modul::class, 'parent', 'id')->with(['childrens' => static fn ($q) => $q->select(['id', 'modul', 'parent', 'url', 'ikon'])]);
    }

    public function tree($grupId)
    {
        $superAdmin = is_super_admin();

        $modul = $this->with(['childrens' => static function ($q) use ($grupId, $superAdmin) {
                $q->select(['id', 'parent', 'modul', 'slug', 'url', 'ikon'])
                    ->when(! UserGrup::isAdministrator($grupId), static function ($query) use ($grupId) {
                        $query->whereIn('id', static function ($query) use ($grupId) {
                            $query->select('id_modul')->from('grup_akses')->where('id_grup', $grupId);
                        });
                    })
                    ->when(is_demo_mode(), static function ($query) {
                        $query->whereNotIn('slug', ['layanan-pelanggan', 'pendaftaran-kerjasama']);
                    })
                    ->isChild()->isShow()
                    ->when(! $superAdmin, static function ($query) {
                        $query->isActive();
                    })
                    ->orderBy('urut');
            }])
            ->select(['id', 'parent', 'modul', 'slug', 'url', 'ikon'])
            ->when(! UserGrup::isAdministrator($grupId), static function ($query) use ($grupId) {
                    $query->whereIn('id', static function ($query) use ($grupId) {
                        $query->select('id_modul')->from('grup_akses')->where('id_grup', $grupId);
                    });
                })
            ->isParent()->isShow()
            ->when(! $superAdmin, static function ($query) {
                $query->isActive();
            })
            ->orderBy('urut')
            ->get();

        return collect(self::DEFAULT_MODUL)->merge($modul)
            ->map(static function ($item) {
                $item['modul'] = SebutanDesa($item['modul']);
                if (isset($item['childrens'])) {
                    $item['childrens'] = $item['childrens']->map(static function ($child) {
                        $child['modul'] = SebutanDesa($child['modul']);

                        return $child;
                    });
                }

                return $item;
            })
            ->values();
    }

    public function isLock(): bool
    {
        return $this->attributes['aktif'] == self::LOCK;
    }

    protected function getRawAktifAttribute($value)
    {
        return $this->attributes['aktif'] ?? false;
    }
}
