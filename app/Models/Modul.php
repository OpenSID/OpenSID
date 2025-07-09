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

defined('BASEPATH') || exit('No direct script access allowed');

class Modul extends BaseModel
{
    use ConfigId;

    public const PARENT       = 0;
    public const LOCK         = 2;
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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_modul';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

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
                    ->when(config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO), static function ($query) {
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

    protected function getRawAktifAttribute($value)
    {
        return $this->attributes['aktif'];
    }

    public static function listIcon()
    {
        $list_icon = [];

        $file = FCPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);

            return array_map(static fn ($a): string => explode(':', $a)[0], $list_icon);
        }

        return false;
    }

    public function isLock(): bool
    {
        return $this->attributes['aktif'] == self::LOCK;
    }
}
