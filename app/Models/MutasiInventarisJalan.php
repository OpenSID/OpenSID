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
 * @property int|null $id_inventaris_jalan
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
 * @property-read \App\Models\InventarisJalan|null $inventaris
 * @property-read \App\Models\InventarisJalan|null $inventaris_jalan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereHargaJual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereIdInventarisJalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereJenisMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereStatusMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereSumbangkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereTahunMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisJalan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class MutasiInventarisJalan extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mutasi_inventaris_jalan';

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
    public function inventaris_jalan()
    {
        return $this->belongsTo(InventarisJalan::class, 'id_inventaris_jalan');
    }

    public function inventaris()
    {
        return $this->hasOne(InventarisJalan::class, 'id', 'id_inventaris_jalan');
    }
}
