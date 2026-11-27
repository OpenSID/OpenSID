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

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * Log audit percobaan SSO OpenKab -> OpenSID (POST /admin/sso-login).
 *
 * @property int $id
 * @property string $status
 * @property string|null $reason
 * @property string|null $jti
 * @property string|null $openkab_username
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpenSidSsoLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpenSidSsoLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OpenSidSsoLog query()
 * @mixin \Eloquent
 */
class OpenSidSsoLog extends Model
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED  = 'failed';

    public const UPDATED_AT = null;

    protected $table = 'opensid_sso_logs';

    protected $fillable = [
        'status',
        'reason',
        'jti',
        'openkab_username',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * Log audit bersifat append-only dan tidak boleh diubah.
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Log audit SSO tidak dapat diubah.');
    }

    /**
     * Log audit bersifat append-only dan tidak boleh dihapus.
     */
    public function delete(): bool|null
    {
        throw new LogicException('Log audit SSO tidak dapat dihapus.');
    }
}
