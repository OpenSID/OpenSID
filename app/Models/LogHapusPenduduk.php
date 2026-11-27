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
 * @property int|null $id_pend
 * @property numeric $nik
 * @property string|null $foto
 * @property string|null $deleted_by
 * @property \Illuminate\Support\Carbon $deleted_at
 * @property-read \App\Models\Penduduk|null $penduduk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk data()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereIdPend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogHapusPenduduk withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LogHapusPenduduk extends BaseModel
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
    protected $table = 'log_hapus_penduduk';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    // protected $with = ['penduduk'];

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_pend');
    }

    public function scopeData($query)
    {
        return $query->where('id_pend', '!=', null);
    }
}
