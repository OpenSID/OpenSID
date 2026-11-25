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
    protected function validateDomain(
        array $data,
        bool $redirect = false,
        string $redirectUrl = '',
        bool $requireCloudWhitelist = true,
        string $attribute = 'url'
    ) {
        // Jika tipe adalah cloud (2) atau tipe tidak didefinisikan secara spesifik (agar bisa dinamis), lakukan validasi URL
        // Jika parameter tipe = 2 ada dan kita sedang validasi whitelist, baru lanjutkan.
        // Tapi kita juga bisa menggunakan method ini untuk SSRF umum (bukan tipe 2) dengan menset requireCloudWhitelist = false.
        $isCloudTipe = (isset($data['tipe']) && $data['tipe'] == 2);

        if ($isCloudTipe || ! $requireCloudWhitelist || ! isset($data['tipe'])) {
            $secureUrlRule = new SecureCloudUrl($requireCloudWhitelist);

            $validator = Validator::make($data, [
                $attribute => ['required', 'url', $secureUrlRule],
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->first($attribute);

                if ($requireCloudWhitelist) {
                    $allowed = implode(', ', $secureUrlRule->getTrustedDomains());
                    $message .= " <br>Domain yang diperbolehkan: {$allowed}";
                }

                return redirect_with('error', $message, $redirectUrl, true);
            }

            if ($redirect) {
                // Jika valid, redirect ke URL
                return redirect($data[$attribute]);
            }
        }

        return null;
    }

    /**
     * Mengkonversi URL gambar menjadi format yang bisa ditampilkan di browser.
     * Jika URL berasal dari Google Drive, akan dikonversi ke format thumbnail.
     * Jika bukan URL Google Drive, URL akan dikembalikan apa adanya.
     *
     * @return string|null
     */
    protected function googleDriveDirectUrl(mixed $url)
    {
        if (empty($url)) {
            return null;
        }

        if (! str_contains((string) $url, 'drive.google.com')) {
            return $url;
        }

        preg_match('/\/d\/([a-zA-Z0-9_-]+)/', (string) $url, $matches);
        $fileId = $matches[1] ?? null;

        if (! $fileId) {
            return null;
        }

        return "https://drive.google.com/thumbnail?id={$fileId}";
    }
}
