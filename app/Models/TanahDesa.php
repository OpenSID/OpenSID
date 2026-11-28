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
 * @property int|null $id_penduduk
 * @property numeric|null $nik
 * @property string|null $jenis_pemilik
 * @property string $nama_pemilik_asal
 * @property int $luas
 * @property int|null $hak_milik
 * @property int|null $hak_guna_bangunan
 * @property int|null $hak_pakai
 * @property int|null $hak_guna_usaha
 * @property int|null $hak_pengelolaan
 * @property int|null $hak_milik_adat
 * @property int|null $hak_verponding
 * @property int|null $tanah_negara
 * @property int|null $perumahan
 * @property int|null $perdagangan_jasa
 * @property int|null $perkantoran
 * @property int|null $industri
 * @property int|null $fasilitas_umum
 * @property int|null $sawah
 * @property int|null $tegalan
 * @property int|null $perkebunan
 * @property int|null $peternakan_perikanan
 * @property int|null $hutan_belukar
 * @property int|null $hutan_lebat_lindung
 * @property int|null $tanah_kosong
 * @property int|null $lain
 * @property string $mutasi
 * @property string $keterangan
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\Penduduk|null $penduduk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereFasilitasUmum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakGunaBangunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakGunaUsaha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakMilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakMilikAdat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakPakai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakPengelolaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHakVerponding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHutanBelukar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereHutanLebatLindung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereIdPenduduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereIndustri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereJenisPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereLuas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereNamaPemilikAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa wherePerdaganganJasa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa wherePerkantoran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa wherePerkebunan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa wherePerumahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa wherePeternakanPerikanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereSawah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereTanahKosong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereTanahNegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereTegalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahDesa withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class TanahDesa extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tanah_desa';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_penduduk');
    }

    public function scopeVisible($query, $value = 1)
    {
        return $query->where('visible', $value);
    }
}
