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

use App\Enums\AktifEnum;
use App\Traits\ConfigIdNull;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int|null $config_id
 * @property string $nama
 * @property string|null $simbol
 * @property int|null $tipe
 * @property int $parrent
 * @property int $enabled
 * @property string $sumber
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Point> $children
 * @property-read int|null $children_count
 * @property-read string $path_simbol
 * @property-read string $status_label
 * @property-read Point|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point child(int $parent)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point root()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point status(?int $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point subPoint()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereParrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereSimbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereSumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Point withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Point extends BaseModel
{
    use ConfigIdNull;
    use StatusTrait;

    public const ROOT  = 0;
    public const CHILD = 2;

    public $timestamps      = false;
    public $statusColumName = 'enabled';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'point';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'simbol',
        'enabled',
        'tipe',
        'parrent',
    ];

    // append
    protected $appends = [
        'path_simbol',
    ];

    public function isLock(): bool
    {
        return $this->enabled == AktifEnum::TIDAK_AKTIF;
    }

    /**
     * Getter untuk path + simbol
     */
    public function getPathSimbolAttribute(): string
    {
        $simbol = LOKASI_SIMBOL_LOKASI . $this->attributes['simbol'];

        if (empty($this->attributes['simbol']) || ! file_exists(FCPATH . $simbol)) {
            return LOKASI_SIMBOL_LOKASI . 'default.png';
        }

        return $simbol;
    }

    /**
     * Get the parent that owns the Polygon
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Point::class, 'parrent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Point::class, 'parrent', 'id')->whereTipe(self::CHILD);
    }

    protected function scopeRoot($query)
    {
        return $query->whereTipe(self::ROOT);
    }

    protected function scopeChild($query, int $parent)
    {
        return $query->whereTipe(self::CHILD)->whereParrent($parent);
    }

    protected function scopeSubPoint($query)
    {
        return $query->whereTipe(self::CHILD);
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(AktifEnum::AKTIF);
    }
}
