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
 * @property int|null $id_inventaris_peralatan
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
 * @property-read \App\Models\InventarisPeralatan|null $inventaris
 * @property-read \App\Models\InventarisPeralatan|null $inventaris_peralatan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereHargaJual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereIdInventarisPeralatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereJenisMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereStatusMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereSumbangkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereTahunMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisPeralatan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class MutasiInventarisPeralatan extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mutasi_inventaris_peralatan';

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

    // relasi ke inventaris jalan
    public function inventaris_peralatan()
    {
        return $this->belongsTo(InventarisPeralatan::class, 'id_inventaris_peralatan');
    }

    public function inventaris()
    {
        return $this->hasOne(InventarisPeralatan::class, 'id', 'id_inventaris_peralatan');
    }
}
