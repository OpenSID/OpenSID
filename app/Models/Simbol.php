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

use App\Enums\ImageExtensionEnum;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;
use Rennokki\QueryCache\Traits\QueryCacheable;

defined('BASEPATH') || exit('No direct script access allowed');

class Simbol extends BaseModel
{
    use ConfigId;
    use QueryCacheable;

    // forever cache
    public $cacheFor = -1;

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gis_simbol';

    protected $guarded = [
        'id',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'simbol', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $foto = LOKASI_SIMBOL_LOKASI . $model->getOriginal($file);
            if (file_exists($foto)) {
                unlink($foto);
            }
        }
    }

    /**
     * Ekspresi SQL untuk mengekstrak ekstensi file dari kolom simbol.
     *
     * Keamanan SQL Injection:
     * - Ekspresi hanya mereferensikan nama kolom internal ('simbol'), bukan input user.
     * - Nilai yang di-bind ke whereIn/whereNotIn berasal dari ImageExtensionEnum::values()
     *   yang merupakan konstanta compile-time — tidak dapat dimanipulasi dari luar.
     * - Laravel mem-binding array tersebut sebagai parameterized query secara otomatis.
     */
    private const EXT_EXPR = "LOWER(SUBSTRING_INDEX(simbol, '.', -1))";

    public function scopeImageOnly($query)
    {
        return $query->whereIn(DB::raw(self::EXT_EXPR), ImageExtensionEnum::values());
    }

    public function scopeNotImageOnly($query)
    {
        return $query->whereNotIn(DB::raw(self::EXT_EXPR), ImageExtensionEnum::values());
    }

    public static function isImageFile(string $filename): bool
    {
        return in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ImageExtensionEnum::values(), true);
    }

    protected function scopeRoot($query)
    {
        return $query->whereTipe(1);
    }
}
