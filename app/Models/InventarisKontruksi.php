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
 * @property string $kondisi_bangunan
 * @property string $kontruksi_bertingkat
 * @property int|null $kontruksi_beton
 * @property int $luas_bangunan
 * @property string $letak
 * @property string|null $tanggal_dokument
 * @property string|null $no_dokument
 * @property string|null $tanggal
 * @property string|null $status_tanah
 * @property string|null $kode_tanah
 * @property string $asal
 * @property float $harga
 * @property string $keterangan
 * @property int $status
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereKodeTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereKondisiBangunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereKontruksiBertingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereKontruksiBeton($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereLetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereLuasBangunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereNoDokument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereStatusTanah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereTanggalDokument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventarisKontruksi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class InventarisKontruksi extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventaris_kontruksi';

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
}
