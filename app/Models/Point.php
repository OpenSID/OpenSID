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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

class Point extends BaseModel
{
    use ConfigId;

    public const LOCK   = 1;
    public const UNLOCK = 2;
    public const ROOT   = 0;
    public const CHILD  = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'point';

    public $timestamps = false;

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
        return $query->whereEnabled(self::UNLOCK);
    }

    public function isLock(): bool
    {
        return $this->enabled == self::LOCK;
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
}
