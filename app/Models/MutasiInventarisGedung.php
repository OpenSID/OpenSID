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
 * @property int|null $id_inventaris_gedung
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
 * @property-read \App\Models\InventarisGedung|null $inventaris
 * @property-read \App\Models\InventarisGedung|null $inventaris_gedung
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereHargaJual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereIdInventarisGedung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereJenisMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereStatusMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereSumbangkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereTahunMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiInventarisGedung withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class MutasiInventarisGedung extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mutasi_inventaris_gedung';

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
    public function inventaris_gedung()
    {
        return $this->belongsTo(InventarisGedung::class, 'id_inventaris_gedung');
    }

    public function inventaris()
    {
        return $this->hasOne(InventarisGedung::class, 'id', 'id_inventaris_gedung');
    }
}
