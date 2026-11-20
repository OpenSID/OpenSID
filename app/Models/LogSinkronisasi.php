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
 * @property string $modul
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereModul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSinkronisasi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LogSinkronisasi extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_sinkronisasi';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'modul',
        'created_by',
        'updated_by',
    ];
}
