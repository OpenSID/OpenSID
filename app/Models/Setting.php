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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $modul
 * @property string|null $slug
 * @property string $url
 * @property int $aktif
 * @property string|null $ikon
 * @property int|null $urut
 * @property int $level
 * @property int $hidden
 * @property string|null $ikon_kecil
 * @property int|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereIkon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereIkonKecil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereModul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Setting extends BaseModel
{
    use ConfigId;

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
    protected $guarded = ['id'];


    // booted update remove cache
    protected static function booted()
    {
        static::boot();

        static::saved(static function (): void {
            Cache::forget('settings_modules');
        });

        static::deleted(static function (): void {
            Cache::forget('settings_modules');
        });
    }
}
