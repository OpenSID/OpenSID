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
use App\Traits\ConfigId;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $nama
 * @property string|null $simbol
 * @property string|null $color
 * @property int|null $tipe
 * @property int|null $tebal
 * @property string|null $jenis
 * @property int|null $parrent
 * @property int $enabled
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Line> $children
 * @property-read int|null $children_count
 * @property-read string $status_label
 * @property-read Line|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line child(int $parent)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line root()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line status(?int $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line subLine()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereParrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereSimbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereTebal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Line withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Line extends BaseModel
{
    use ConfigId;
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
    protected $table = 'line';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'simbol',
        'color',
        'enabled',
        'tipe',
        'tebal',
        'jenis',
        'parrent',
    ];

    /**
     * Get the parent that owns the Line
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Line::class, 'parrent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Line::class, 'parrent', 'id')->whereTipe(self::CHILD);
    }

    protected function scopeRoot($query)
    {
        return $query->whereTipe(self::ROOT);
    }

    protected function scopeChild($query, int $parent)
    {
        return $query->whereTipe(self::CHILD)->whereParrent($parent);
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(AktifEnum::AKTIF);
    }

    protected function scopeSubLine($query)
    {
        return $query->whereTipe(self::CHILD);
    }
}
