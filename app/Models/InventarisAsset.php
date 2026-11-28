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
 * @property string $jenis
 * @property string|null $judul_buku
 * @property string|null $spesifikasi_buku
 * @property string|null $asal_daerah
 * @property string|null $pencipta
 * @property string|null $bahan
 * @property string|null $jenis_hewan
 * @property string|null $ukuran_hewan
 * @property string|null $jenis_tumbuhan
 * @property string|null $ukuran_tumbuhan
 * @property int $jumlah
 * @property string $tahun_pengadaan
 * @property string $asal
 * @property float $harga
 * @property string $keterangan
 * @property int $status
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\MutasiInventarisAsset|null $mutasi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset cetak($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset listKdRegister()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset reg()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset sumInventaris()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereAsalDaerah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereBahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereJenisHewan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereJenisTumbuhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereJudulBuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereKodeBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset wherePencipta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereSpesifikasiBuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereTahunPengadaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereUkuranHewan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereUkuranTumbuhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisAsset withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class InventarisAsset extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventaris_asset';

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
        return DB::table('inventaris_asset as u', 'm.id as mutasi')
            ->leftJoin('mutasi_inventaris_asset as m', 'm.id_inventaris_asset', '=', 'u.id')
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
        return $query->when(! empty($tahun), static fn ($query) => $query->whereYear('tahun_pengadaan', $tahun));
    }

    // relasi ke mutasi_inventaris_asset
    public function mutasi()
    {
        return $this->hasOne(MutasiInventarisAsset::class, 'id_inventaris_asset');
    }
}
