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

use App\Traits\Author;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

class UserGrup extends BaseModel
{
    use ConfigId;
    use Author;
    use ShortcutCache;

    // UserGrup bawaan
    public const ADMINISTRATOR = 'administrator';
    public const OPERATOR      = 'operator';
    public const REDAKSI       = 'redaksi';
    public const KONTRIBUTOR   = 'kontributor';

    // Jenis UserGrup
    public const SISTEM = 1;
    public const DESA   = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_grup';

    protected $fillable = [
        'nama',
        'jenis',
        'slug',
        'status',
        'created_by',
        'updated_by',
    ];

    public static function getGrupSistem()
    {
        return self::where('jenis', self::SISTEM)->pluck('id')->toArray();
    }

    public static function getGrupId($slug)
    {
        return self::where('slug', $slug)->value('id');
    }

    public static function isAdministrator($id_grup): bool
    {
        return $id_grup == self::getGrupId(self::ADMINISTRATOR);
    }

    /**
     * Get all of the user for the UserGrup
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_grup', 'id');
    }

    /**
     * Scope query untuk status pengguna
     *
     * @return Builder
     */
    public function scopeStatus(mixed $query, mixed $status = 1)
    {
        if ($status == '') {
            return $query;
        }

        return $query->where('status', $status);
    }

    protected static function boot()
    {
        parent::boot();
    }
}
