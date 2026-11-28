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
 * @property int|null $log_surat_id
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereLogSuratId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogPerubahanSurat withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LogPerubahanSurat extends BaseModel
{
    use ConfigId;
    use Author;

    // protected $casts = [
    //     'tanggal' => 'datetime:Y-m-d H:i:s',
    // ];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_perubahan_surat';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    // public function surat()
    // {
    //     return $this->hasOne(LogSurat::class, 'id', 'id_surat');
    // }
}
