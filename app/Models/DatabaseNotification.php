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
        return tap(json_decode($value, true), function (&$data) {
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
