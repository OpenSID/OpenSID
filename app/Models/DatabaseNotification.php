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
 * @property string $id
 * @property int|null $config_id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification query()
 * @method static Builder<static>|DatabaseNotification read()
 * @method static Builder<static>|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DatabaseNotification extends \Illuminate\Notifications\DatabaseNotification
{
    use ConfigId;

    /**
     * Mutator untuk menyesuaikan URL dengan domain aplikasi saat ini.
     *
     * Override dari parent Illuminate\Notifications\DatabaseNotification untuk:
     * - Menangani domain lama (berputar.opendesa.id) → domain aktual saat ini
     * - Menjaga query string & fragment saat transformasi
     * - Membersihkan leading index.php dari path (termasuk multi-slash)
     * - Menangani null/JSON malformed secara eksplisit
     *
     * @param mixed $value JSON value dari kolom data
     *
     * @return array|null
     */
    public function getDataAttribute($value)
    {
        return tap(json_decode($value, true), static function (&$data) {
            if (! is_array($data) || empty($data['url'])) {
                return;
            }
            $parsed = parse_url($data['url']);
            $path   = ltrim(preg_replace('|^/+index\.php|', '', $parsed['path'] ?? ''), '/');
            $suffix = ! empty($parsed['query']) ? '?' . $parsed['query'] : '';
            $suffix .= ! empty($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
            $data['url'] = url($path) . $suffix;
        });
    }
}
