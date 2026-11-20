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
 * @property int|null $id_kk
 * @property int $id_peristiwa
 * @property string $tgl_peristiwa
 * @property int|null $id_pend
 * @property int|null $updated_by
 * @property int|null $id_log_penduduk
 * @property-read \App\Models\Keluarga|null $Keluarga
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereIdKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereIdLogPenduduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereIdPend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereIdPeristiwa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereTglPeristiwa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogKeluarga withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LogKeluarga extends BaseModel
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
    protected $table = 'log_keluarga';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public function Keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_kk', 'id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }
}
