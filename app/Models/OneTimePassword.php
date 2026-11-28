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

/**
 * @property int $id
 * @property int|null $config_id
 * @property string $password
 * @property array<array-key, mixed>|null $origin_properties
 * @property \Illuminate\Support\Carbon $expires_at
 * @property string $authenticatable_type
 * @property int $authenticatable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $authenticatable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereAuthenticatableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereAuthenticatableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereOriginProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneTimePassword whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OneTimePassword extends \Spatie\OneTimePasswords\Models\OneTimePassword
{
    use ConfigId;
}
