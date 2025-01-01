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
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class InventarisJalan extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventaris_jalan';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'config_id',
    ];

    public function scopeVisible($query, $value = 1)
    {
        return $query->where('visible', $value);
    }

    public function scopeAktif($query)
    {
        return $query->visible();
    }

    public function scopeReg($query)
    {
        return $query->count();
    }

    public function scopeListKdRegister()
    {
        return $this->select('register')->get();
    }

    public function scopeSumInventaris()
    {
        return $this->aktif()->sum('harga');
    }

    public static function listInventaris()
    {
        return DB::table('inventaris_jalan as u', 'm.id as mutasi')
            ->leftJoin('mutasi_inventaris_jalan as m', 'm.id_inventaris_jalan', '=', 'u.id')
            ->where('u.visible', 1)
            ->get();
    }

    public function scopeCetak($query, $tahun = null)
    {
        return $query->when(! empty($tahun), static fn ($query) => $query->whereYear('tanggal_dokument', $tahun));
    }

    // relasi ke mutasi_inventaris_jalan
    public function mutasi()
    {
        return $this->hasOne(MutasiInventarisJalan::class, 'id_inventaris_jalan');
    }
}
