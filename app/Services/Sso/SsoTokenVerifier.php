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

namespace App\Services\Sso;

use App\Exceptions\Sso\SsoVerificationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Memverifikasi token SSO sekali-pakai ke endpoint OpenKab
 * POST /api/v1/sso/verify-token. OpenKab adalah pemegang private key RS256
 * dan pencatat pemakaian token, sehingga verifikasi otoritatif dilakukan
 * server-to-server di sana; permintaan diautentikasi dengan HMAC-SHA256
 * memakai SSO_CALLBACK_SECRET (lihat contracts/opensid-sso-contract.md).
 */
class SsoTokenVerifier
{
    /**
     * @return array{username: string, desa_kode: string, jti: string|null}
     */
    public function verify(string $token, string $clientIp): array
    {
        $baseUrl = rtrim((string) (setting('sso_openkab_base_url') ?: config('sso.openkab_base_url')), '/');
        $secret  = (string) (setting('sso_callback_secret') ?: config('sso.callback_secret'));
        $timeout = (int) (setting('sso_http_timeout') ?: config('sso.http_timeout', 5));

        if ($baseUrl === '' || mb_strlen($secret) < 32) {
            throw new SsoVerificationException('Konfigurasi SSO belum lengkap.', 'configuration_error');
        }

        if (parse_url($baseUrl, PHP_URL_SCHEME) !== 'https' && ! app()->environment(['local', 'testing'])) {
            throw new SsoVerificationException('Konfigurasi SSO belum lengkap.', 'configuration_error');
        }

        $body      = json_encode(['token' => $token, 'client_ip' => $clientIp], JSON_THROW_ON_ERROR);
        $timestamp = (string) now()->timestamp;
        $signature = hash_hmac('sha256', $timestamp . '.' . $body, $secret);

        try {
            $response = Http::timeout($timeout)
                ->withBody($body, 'application/json')
                ->withHeaders([
                    'X-SSO-Callback-Timestamp' => $timestamp,
                    'X-SSO-Callback-Signature' => $signature,
                ])
                ->post("{$baseUrl}/api/v1/sso/verify-token");
        } catch (Throwable $e) {
            Log::warning('SSO verify-token: gagal menghubungi OpenKab.', ['error' => $e->getMessage()]);

            throw new SsoVerificationException('Tidak dapat menghubungi server SSO.', 'upstream_unreachable');
        }

        $data = $response->json() ?? [];

        if (! $response->successful() || ! ($data['valid'] ?? false)) {
            throw new SsoVerificationException('Token SSO tidak valid.', 'token_invalid');
        }

        // Claim desa_kode wajib ada di response agar ikatan token-ke-desa bisa
        // diverifikasi. Tanpa pengecekan key ini, string kosong bawaan (??) tidak
        // bisa dibedakan dari "OpenKab memang tidak mengirim field-nya" (fail-open).
        if (! array_key_exists('desa_kode', $data['data'] ?? [])) {
            throw new SsoVerificationException('Token SSO tidak memuat identitas desa.', 'claim_incomplete');
        }

        return [
            'username'  => (string) ($data['data']['username'] ?? ''),
            'desa_kode' => (string) $data['data']['desa_kode'],
            'jti'       => $data['data']['jti'] ?? null,
        ];
    }
}
