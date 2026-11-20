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

use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_inventaris_tanah
 * @property string|null $jenis_mutasi
 * @property string $tahun_mutasi
 * @property float|null $harga_jual
 * @property string|null $sumbangkan
 * @property string $keterangan
 * @property int $visible
 * @property string $status_mutasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\InventarisTanah|null $inventaris
 * @property-read \App\Models\InventarisTanah|null $inventaris_tanah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereHargaJual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereIdInventarisTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereJenisMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereStatusMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereSumbangkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereTahunMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisTanah withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class MutasiInventarisTanah extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mutasi_inventaris_tanah';

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

    public function inventaris_tanah()
    {
        return $this->belongsTo(InventarisTanah::class, 'id_inventaris_tanah');
    }

    public function inventaris()
    {
        return $this->hasOne(InventarisTanah::class, 'id', 'id_inventaris_tanah');
    }
}
