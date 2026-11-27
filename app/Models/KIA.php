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
 * @property int $id
 * @property int $config_id
 * @property string $no_kia
 * @property int|null $ibu_id
 * @property int|null $anak_id
 * @property string|null $hari_perkiraan_lahir
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\PendudukSaja|null $anak
 * @property-read \App\Models\Anak|null $bulananAnak
 * @property-read \App\Models\PendudukSaja|null $ibu
 * @property-read \App\Models\IbuHamil|null $ibuHamil
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereAnakId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereHariPerkiraanLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereIbuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereNoKia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KIA withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class KIA extends BaseModel
{
    use ConfigId;

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
    protected $table = 'kia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_kia',
        'ibu_id',
        'anak_id',
        'hari_perkiraan_lahir',
    ];

    public function ibu()
    {
        return $this->belongsTo(PendudukSaja::class, 'ibu_id');
    }

    public function anak()
    {
        return $this->belongsTo(PendudukSaja::class, 'anak_id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ibuHamil()
    {
        return $this->hasOne(IbuHamil::class, 'kia_id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bulananAnak()
    {
        return $this->hasOne(Anak::class, 'kia_id');
    }
}
