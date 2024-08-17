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

class Polygon extends BaseModel
{
    use ConfigId;

    public const LOCK        = 1;
    public const UNLOCK      = 2;
    public const POLYGON     = 0;
    public const SUB_POLYGON = 2;

    /**
     * {@inheritDoc}
     */
    protected $table = 'polygon';

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    public $timestamps  = false;
    protected $fillable = [
        'config_id',
        'nama',
        'simbol',
        'color',
        'enabled',
        'tipe',
        'parrent',
    ];

    // append parent_id
    protected $appends = ['parrent_id'];

    // TODO: Perbaiki struktur tabel untuk mengenali utama dan subnya
    // Harusnya jika parent = null maka dia utama
    // Jika parent = id dari polygon lain maka dia sub
    // Tambahan field parent_id untuk menyederhanakan tipe dan parrent, setelah data dipindahkan ke parent_id kolom tipe dan parrent bisa dihapus
    public function getParrentIdAttribute()
    {
        return $this->attributes['tipe'] == 0 ? null : $this->attributes['parrent'];
    }

    protected function scopeRoot($query)
    {
        return $query->whereTipe(self::POLYGON);
    }

    protected function scopeChild($query, int $parent)
    {
        return $query->whereTipe(self::SUB_POLYGON)->whereParrent($parent);
    }

    protected function scopeSubPolygon($query)
    {
        return $query->whereTipe(self::SUB_POLYGON);
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(self::UNLOCK);
    }

    /**
     * Get the parent that owns the Polygon
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Polygon::class, 'parrent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Polygon::class, 'parrent', 'id')->whereTipe(self::SUB_POLYGON);
    }
}
