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

namespace Modules\Analisis\Models;

use App\Models\BaseModel;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

class AnalisisResponHasil extends BaseModel
{
    use ConfigId;

    protected $table   = 'analisis_respon_hasil';
    protected $guarded = [];
    public $timestamps = false;

    public static function preUpdate($idMaster, $pr)
    {
        $per  = $pr ?: AnalisisPeriode::where('id_master', $idMaster)->active()->value('id');
        $data = self::where('id_periode', $per)->distinct()->pluck('id_subjek');

        self::where('id_subjek', 0)->delete();
        AnalisisRespon::where('id_subjek', 0)->delete();
        self::where('id_periode', $per)->delete();

        $upx = $data->map(static function ($id_subjek) use ($per, $idMaster) {
            $akumulasi = self::where('id_subjek', $id_subjek)
                ->where('id_periode', $per)
                ->whereHas('indikator', static fn ($query) => $query->where('act_analisis', 1))
                ->sum(DB::raw('analisis_indikator.bobot * nilai'));

            return [
                'id_master'  => $idMaster,
                'akumulasi'  => $akumulasi ?: 0,
                'id_subjek'  => $id_subjek,
                'id_periode' => $per,
            ];
        })->toArray();

        if ($upx) {
            self::insert($upx);
        }
    }
}
