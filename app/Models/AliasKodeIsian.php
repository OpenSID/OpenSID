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

/**
 * @property int $id
 * @property int $config_id
 * @property string $judul
 * @property string $alias
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AliasKodeIsian withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class AliasKodeIsian extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * {@inheritDoc}
     */
    protected $table = 'alias_kodeisian';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'judul',
        'alias',
        'content',
        'created_by',
        'updated_by',
    ];
}
