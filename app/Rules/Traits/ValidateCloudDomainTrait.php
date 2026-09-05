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

namespace App\Rules\Traits;

use App\Rules\SecureCloudUrl;
use Illuminate\Support\Facades\Validator;

trait ValidateCloudDomainTrait
{
    /**
     * Validates the cloud domain and redirects if necessary.
     *
     * @return mixed
     */
    protected function validateDomain(array $data, bool $redirect = false, string $redirectUrl = '')
    {
        // Jika tipe adalah cloud (2), lakukan validasi URL
        if ($data['tipe'] == 2) {
            $secureCloudUrl = new SecureCloudUrl();

            $validator = Validator::make($data, [
                'url' => ['required', 'url', $secureCloudUrl],
            ]);

            if ($validator->fails()) {
                $allowed = implode(', ', $secureCloudUrl->getTrustedDomains());
                $message = "{$validator->errors()->first()} <br>Domain yang diperbolehkan: {$allowed}";

                redirect_with('error', $message, $redirectUrl, true);
            }

            if ($redirect) {
                // Jika valid, redirect ke URL cloud storage
                return redirect($data['url']);
            }
        }

        return null;
    }
}
