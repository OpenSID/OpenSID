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
 * @property string $kondisi_bangunan
 * @property string|null $kontruksi_bertingkat
 * @property int|null $kontruksi_beton
 * @property int $luas_bangunan
 * @property string $letak
 * @property string|null $tanggal_dokument
 * @property string|null $no_dokument
 * @property int|null $luas
 * @property string|null $status_tanah
 * @property string|null $kode_tanah
 * @property string $asal
 * @property float|null $harga
 * @property string $keterangan
 * @property int $status
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\MutasiInventarisGedung|null $mutasi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereKodeBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereKodeTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereKondisiBangunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereKontruksiBertingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereKontruksiBeton($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereLetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereLuas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereLuasBangunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereNoDokument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereStatusTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereTanggalDokument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisGedung withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class InventarisGedung extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventaris_gedung';

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
        return $this->hasOne(MutasiInventarisGedung::class, 'id_inventaris_gedung');
    }
}
