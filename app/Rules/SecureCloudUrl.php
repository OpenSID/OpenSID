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

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SecureCloudUrl implements ValidationRule
{
    private string $errorMessage = 'Gunakan layanan cloud storage yang didukung atau URL internal aplikasi.';

    /**
     * {@inheritDoc}
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('Format URL tidak valid.');

            return;
        }

        $parsedUrl = parse_url((string) $value);
        $scheme    = strtolower($parsedUrl['scheme'] ?? '');
        $host      = strtolower($parsedUrl['host'] ?? '');

        if (! $this->isValidScheme($scheme)) {
            $fail($this->errorMessage);

            return;
        }

        if ($this->isCurrentAppDomain($host)) {
            return;
        }

        if ($this->isLocalDomain($host)) {
            return;
        }

        if ($this->isTrustedCloudDomain($host)) {
            return;
        }

        $fail("Domain '{$host}' tidak diizinkan. Gunakan layanan cloud storage yang didukung.");
    }

    /**
     * Get the list of trusted cloud domains.
     */
    public function getTrustedDomains(): array
    {
        return [
            'drive.google.com',
            'onedrive.live.com',
            '1drv.ms',
            'dropbox.com',
            'www.dropbox.com',
            'dl.dropboxusercontent.com',
            'box.com',
            'app.box.com',
            'mega.nz',
            'mega.co.nz',
            'amazonaws.com',
            's3.amazonaws.com',
            'mediafire.com',
            'wetransfer.com',
            'we.tl',
        ];
    }

    private function isValidScheme(string $scheme): bool
    {
        if (! in_array($scheme, ['http', 'https'])) {
            $this->errorMessage = 'Hanya URL HTTP/HTTPS yang diizinkan.';

            return false;
        }

        return true;
    }

    private function isCurrentAppDomain(string $host): bool
    {
        $appUrl    = APP_URL;
        $appDomain = strtolower(parse_url($appUrl)['host'] ?? '');

        return $host === $appDomain || str_ends_with($host, ".{$appDomain}");
    }

    private function isLocalDomain(string $host): bool
    {
        $localHosts = [
            'localhost', '127.0.0.1', '::1', '0.0.0.0',
        ];

        if (in_array($host, $localHosts)) {
            return true;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return filter_var(
                $host,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) === false;
        }

        $localPatterns = ['.local', '.localhost', '.test'];

        foreach ($localPatterns as $pattern) {
            if (str_ends_with($host, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function isTrustedCloudDomain(string $host): bool
    {
        foreach ($this->getTrustedDomains() as $domain) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return true;
            }
        }

        return false;
    }
}
