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
use App\Models\Sso\OpenSidSsoLog;
use App\Models\User;
use App\Models\UserGrup;
use CI_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Orkestrasi POST /admin/sso-login: validasi origin, verifikasi token ke
 * OpenKab, lalu buat sesi admin. Setiap percobaan (berhasil/gagal) dicatat
 * ke opensid_sso_logs (lihat contracts/opensid-sso-contract.md).
 */
class SsoLoginService
{
    public function __construct(private readonly SsoTokenVerifier $verifier)
    {
    }

    public function login(Request $request): User
    {
        $this->assertOriginAllowed($request);

        $token = trim((string) $request->input('token', ''));

        if ($token === '') {
            $this->log(OpenSidSsoLog::STATUS_FAILED, 'token_missing', $request, null, null);

            throw new SsoVerificationException('Token SSO tidak ditemukan.', 'token_missing');
        }

        try {
            $claims = $this->verifier->verify($token, (string) $request->ip());
        } catch (SsoVerificationException $e) {
            $this->log(OpenSidSsoLog::STATUS_FAILED, $e->reason(), $request, null, null);

            throw $e;
        }

        if ($claims['desa_kode'] === '') {
            $this->log(OpenSidSsoLog::STATUS_FAILED, 'desa_kode_missing', $request, $claims['username'], $claims['jti']);

            throw new SsoVerificationException('Token SSO tidak memuat identitas desa.', 'desa_kode_missing');
        }

        if ((string) identitas('kode_desa') !== $claims['desa_kode']) {
            $this->log(OpenSidSsoLog::STATUS_FAILED, 'desa_mismatch', $request, $claims['username'], $claims['jti']);

            throw new SsoVerificationException('Token SSO tidak berlaku untuk desa ini.', 'desa_mismatch');
        }

        $user = User::where('username', $claims['username'])
            ->where('id_grup', UserGrup::getGrupId(UserGrup::ADMINISTRATOR))
            ->first();

        if (! $user || ! $user->active) {
            $this->log(OpenSidSsoLog::STATUS_FAILED, 'user_not_found', $request, $claims['username'], $claims['jti']);

            throw new SsoVerificationException('Akun administrator OpenSID tidak ditemukan.', 'user_not_found');
        }

        // Sinkronkan bridge sesi CodeIgniter (menu admin, flag "siteman", dll)
        // agar tetap terisi seperti login manual, konsisten dengan pola yang
        // dipakai bootstrap/routeCodeIgniter.php untuk route berbasis closure.
        if (! app()->bound('ci')) {
            new CI_Controller();
        }

        Auth::guard('admin')->login($user);

        $this->log(OpenSidSsoLog::STATUS_SUCCESS, null, $request, $claims['username'], $claims['jti'], $user->id);

        return $user;
    }

    private function assertOriginAllowed(Request $request): void
    {
        if (app()->environment(['local', 'testing'])) {
            return;
        }

        $rawOrigins     = setting('sso_allowed_origins');
        $allowedOrigins = ! empty($rawOrigins)
            ? array_values(array_filter(array_map('trim', explode(',', (string) $rawOrigins))))
            : config('sso.allowed_origins', []);
        $origin     = $request->headers->get('Origin') ?: $request->headers->get('Referer');
        $originHost = $this->normalizeOrigin($origin);

        if ($allowedOrigins === [] || $originHost === null || ! in_array($originHost, $allowedOrigins, true)) {
            $this->log(OpenSidSsoLog::STATUS_FAILED, 'origin_invalid', $request, null, null);

            throw new SsoVerificationException('Origin permintaan tidak diizinkan.', 'origin_invalid');
        }
    }

    private function normalizeOrigin(?string $origin): ?string
    {
        if (! $origin) {
            return null;
        }

        $scheme = parse_url($origin, PHP_URL_SCHEME);
        $host   = parse_url($origin, PHP_URL_HOST);

        if (! $scheme || ! $host) {
            return null;
        }

        $port = parse_url($origin, PHP_URL_PORT);

        return $scheme . '://' . $host . ($port ? ':' . $port : '');
    }

    private function log(string $status, ?string $reason, Request $request, ?string $openkabUsername, ?string $jti, ?int $userId = null): void
    {
        OpenSidSsoLog::create([
            'status'           => $status,
            'reason'           => $reason,
            'jti'              => $jti,
            'openkab_username' => $openkabUsername,
            'user_id'          => $userId,
            'ip_address'       => $request->ip(),
            'user_agent'       => (string) $request->userAgent(),
        ]);
    }
}
