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

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id_sasaran_paud
 * @property int $config_id
 * @property int|null $posyandu_id
 * @property int|null $kia_id
 * @property int $kategori_usia
 * @property int $januari
 * @property int $februari
 * @property int $maret
 * @property int $april
 * @property int $mei
 * @property int $juni
 * @property int $juli
 * @property int $agustus
 * @property int $september
 * @property int $oktober
 * @property int $november
 * @property int $desember
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\KIA|null $kia
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereAgustus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereApril($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereDesember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereFebruari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereIdSasaranPaud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereJanuari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereJuli($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereJuni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereKategoriUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereKiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereMaret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereMei($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereNovember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereOktober($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud wherePosyanduId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereSeptember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paud withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Paud extends BaseModel
{
    use ConfigId;

    /**
     * The table update parameter.
     *
     * @var string
     */
    public $primaryKey = 'id_sasaran_paud';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sasaran_paud';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public function kia()
    {
        return $this->belongsTo(KIA::class, 'kia_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['bulan'])) {
            $query->whereMonth('sasaran_paud.created_at', $filters['bulan']);
        }

        if (! empty($filters['tahun'])) {
            $query->whereYear('sasaran_paud.created_at', $filters['tahun']);
        }

        if (! empty($filters['posyandu'])) {
            $query->where('posyandu_id', $filters['posyandu']);
        }

        return $query;
    }
}
