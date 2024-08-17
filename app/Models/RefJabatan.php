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

use App\Enums\StatusEnum;
use App\Traits\Author;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class RefJabatan extends BaseModel
{
    use Author;
    use ConfigId;

    public const KADES  = 1;
    public const SEKDES = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_jabatan';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'nama',
        'jenis',
        'tupoksi',
        'created_by',
        'updated_by',
    ];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'config_id',
    ];

    // get data jabatan jenis kades
    public static function getKades()
    {
        return self::whereJenis(self::KADES)->first();
    }

    // get data jabatan jenis sekdes
    public static function getSekdes()
    {
        return self::whereJenis(self::SEKDES)->first();
    }

    // get data jabatan jenis sekdes
    public static function getKadesSekdes(): array
    {
        return [
            self::getKades()->id,
            self::getSekdes()->id,
        ];
    }

    // scope
    public function scopeUrut($query, $order = 'ASC')
    {
        return $query->orderBy(DB::raw('CASE WHEN jenis = 0 THEN 9999 ELSE jenis END'), $order);
    }

    public static function scopeNonAktif()
    {
        return self::select('id')->whereNotExists(static function ($query): void {
            $query->select(DB::raw(1))
                ->from('tweb_desa_pamong')
                ->where('tweb_desa_pamong.pamong_status', StatusEnum::YA)
                ->whereRaw('ref_jabatan.id = tweb_desa_pamong.jabatan_id');
        })->get();
    }
}
