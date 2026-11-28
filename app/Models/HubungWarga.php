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
 * @property int $id_grup
 * @property string $subjek
 * @property string $isi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\GrupKontak|null $kontak
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereIdGrup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereSubjek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HubungWarga withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class HubungWarga extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hubung_warga';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'kontak',
        'createdBy',
        'updatedBy',
    ];

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'id_grup',
        'subjek',
        'isi',
        'pengirim',
        'created_by',
        'updated_by',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kontak()
    {
        return $this->hasOne(GrupKontak::class, 'id_grup', 'id_grup');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
