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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereAgustus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereApril($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereDesember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereFebruari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereIdSasaranPaud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereJanuari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereJuli($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereJuni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereKategoriUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereKiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereMaret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereMei($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereNovember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereOktober($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud wherePosyanduId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereSeptember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SasaranPaud withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class SasaranPaud extends BaseModel
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
    protected $table = 'sasaran_paud';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The casts with the model.
     *
     * @var array
     */
    // protected $casts = [
    //     'status' => 'boolean',
    // ];

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function peserta()
    // {
    //     return $this->hasMany(BantuanPeserta::class, 'program_id');
    // }

    /**
     * Scope query untuk status bantuan
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    // public function scopeStatus($query, $value = 1)
    // {
    //     return $query->where('status', $value);
    // }
}
