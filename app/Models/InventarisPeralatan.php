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
 * @property string $nama_barang
 * @property string $kode_barang
 * @property string $register
 * @property string|null $merk
 * @property string|null $ukuran
 * @property string|null $bahan
 * @property string $tahun_pengadaan
 * @property string|null $no_pabrik
 * @property string|null $no_rangka
 * @property string|null $no_mesin
 * @property string|null $no_polisi
 * @property string|null $no_bpkb
 * @property string $asal
 * @property float $harga
 * @property string $keterangan
 * @property int $status
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\MutasiInventarisPeralatan|null $mutasi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereBahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereKodeBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereMerk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereNoBpkb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereNoMesin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereNoPabrik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereNoPolisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereNoRangka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereTahunPengadaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereUkuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisPeralatan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class InventarisPeralatan extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventaris_peralatan';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

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

    public function mutasi()
    {
        return $this->hasOne(MutasiInventarisPeralatan::class, 'id_inventaris_peralatan');
    }
}
