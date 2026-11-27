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
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $nama_barang
 * @property string $kode_barang
 * @property string $register
 * @property string $kontruksi
 * @property int $panjang
 * @property int $lebar
 * @property int $luas
 * @property string|null $letak
 * @property string $tanggal_dokument
 * @property string|null $no_dokument
 * @property string|null $status_tanah
 * @property string|null $kode_tanah
 * @property string $kondisi
 * @property string $asal
 * @property float $harga
 * @property string $keterangan
 * @property int $status
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\MutasiInventarisJalan|null $mutasi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan cetak($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan listKdRegister()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan reg()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan sumInventaris()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereKodeBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereKodeTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereKontruksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereLebar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereLetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereLuas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereNoDokument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan wherePanjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereStatusTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereTanggalDokument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisJalan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
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

    public static function listInventaris()
    {
        return DB::table('inventaris_jalan as u', 'm.id as mutasi')
            ->leftJoin('mutasi_inventaris_jalan as m', 'm.id_inventaris_jalan', '=', 'u.id')
            ->where('u.visible', 1)
            ->get();
    }

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
