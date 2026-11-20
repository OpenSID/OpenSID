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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_cdesa_masuk
 * @property int|null $cdesa_keluar
 * @property int|null $jenis_mutasi
 * @property string|null $tanggal_mutasi
 * @property string|null $keterangan
 * @property int $id_persil
 * @property int|null $no_bidang_persil
 * @property numeric|null $luas
 * @property string|null $no_objek_pajak
 * @property string|null $path
 * @property int|null $id_peta
 * @property-read \App\Models\Persil|null $persil
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereCdesaKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereIdCdesaMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereIdPersil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereIdPeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereJenisMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereLuas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereNoBidangPersil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereNoObjekPajak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi whereTanggalMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdesaMutasi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class CdesaMutasi extends BaseModel
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
    protected $table = 'mutasi_cdesa';

    public function persil()
    {
        return $this->belongsTo(Persil::class, 'persil_id', 'persil_id');
    }
}
