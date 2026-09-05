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

namespace App\Libraries;

use CI_Input;
use CI_Session;
use Exception; // Perbarui namespace
use Google\Client;
use Google\Service\Script;
use Google\Service\Script\ExecutionRequest;

class AnalisisImport
{
    protected $ci;
    protected CI_Input $input;
    protected CI_Session $session;

    public function __construct()
    {
        $this->ci = &get_instance();

        $this->input   = $this->ci->input;
        $this->session = $this->ci->session;
    }

    public function importGform($redirectLink = '')
    {
        // Check Credential File
        if (! $oauthCredentials = $this->getOAuthCredentialsFile()) {
            return redirect_with('error', 'File Credential Tidak Ditemukan', 'analisis_master', true);
        }

        $redirectUri = setting('api_gform_redirect_uri') ?: config_item('api_gform_redirect_uri');

        $client = new Client();
        $client->setAuthConfig($oauthCredentials);
        $client->setRedirectUri($redirectUri);
        $client->addScope('https://www.googleapis.com/auth/forms');
        $client->addScope('https://www.googleapis.com/auth/spreadsheets');
        $client->addScope('https://www.googleapis.com/auth/script.projects');

        $service = new Script($client);

        if (empty(setting('api_gform_id_script')) && empty(setting('api_gform_redirect_uri'))) {
            $scriptId = config_item('api_gform_script_id');
        } else {
            $scriptId = setting('api_gform_id_script');
        }

        if ($this->input->get('logout')) {
            $this->session->unset_userdata('upload_token');
        }

        // STEP 1: Proses authorization code dari Google
        if ($this->input->get('code')) {
            try {
                $token = $client->fetchAccessTokenWithAuthCode($this->input->get('code'));

                if (isset($token['error'])) {
                    $errorMsg = $token['error_description'] ?? $token['error'];
                    $this->session->unset_userdata('upload_token');
                    $this->session->unset_userdata('inside_retry');

                    return redirect_with('error', "OAuth Error: {$errorMsg}", 'analisis_master', true);
                }

                // Simpan token ke session
                $client->setAccessToken($token);
                $this->session->set_userdata('upload_token', $token);
                logger()->info('Token berhasil disimpan ke session');
            } catch (Exception $e) {
                logger()->error('OAuth Token Exchange Error: ' . $e->getMessage());
                $this->session->unset_userdata('upload_token');
                $this->session->unset_userdata('inside_retry');

                return redirect_with('error', 'Kode otorisasi tidak valid atau sudah kedaluwarsa. Silakan coba lagi.', 'analisis_master', true);
            }
        }

        // STEP 2: Cek token di session, refresh jika expired
        $token = $this->session->userdata('upload_token');
        if ($token) {
            $client->setAccessToken($token);

            if ($client->isAccessTokenExpired()) {
                if (isset($token['refresh_token'])) {
                    try {
                        $newToken = $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                        $client->setAccessToken($newToken);
                        $this->session->set_userdata('upload_token', $newToken);
                        log_message('info', 'Token berhasil di-refresh');
                    } catch (Exception $e) {
                        logger()->error('Token Refresh Error: ' . $e->getMessage());
                        $this->session->unset_userdata('upload_token');
                        unset($token);
                    }
                } else {
                    $this->session->unset_userdata('upload_token');
                    unset($token);
                }
            }
        }

        // STEP 3: Jika tidak ada token, buat authorization URL
        if (! $token) {
            // Simpan state untuk kembali lagi
            $this->session->set_userdata('gform_id', $this->session->userdata('google_form_id'));
            $this->session->set_userdata('inside_redirect_link', $redirectLink);

            $authUrl = $client->createAuthUrl();
            logger()->info("Redirect ke Google OAuth: {$authUrl}");
            header("Location: {$authUrl}");

            return;
        }

        // STEP 4: Jika sudah ada token valid, eksekusi API
        $formId = $this->session->userdata('google_form_id') ?? $this->session->userdata('gform_id') ?? '';

        if (empty($formId)) {
            return redirect_with('error', 'Form ID tidak ditemukan', 'analisis_master', true);
        }

        $request = new ExecutionRequest();
        $request->setFunction('getFormItems');
        $request->setParameters([$formId]);

        try {
            logger()->info("Eksekusi Google Apps Script - Script ID: {$scriptId}, Form ID: {$formId}");
            $response = $service->scripts->run($scriptId, $request);
            $resp     = $response->getResponse();

            logger()->info('Google Apps Script response berhasil');

            // Cek jika ini proses update (detection via session flag)
            if ($this->session->userdata('gform_is_update') && $this->session->userdata('analisis_update_id')) {
                // Simpan data ke session sebelum redirect
                $this->session->set_userdata('gform_update_data', $resp['result']);
                // Cleanup flag
                $this->session->unset_userdata('gform_is_update');
                // Redirect ke handler update
                redirect(ci_route('analisis_master.handle_update_gform'));
            }

            return $resp['result'];
        } catch (Exception $e) {
            logger()->error('Google API Error: ' . $e->getMessage());
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Invalid code') !== false) {
                return redirect_with('error', 'Kode verifikasi tidak valid.', 'analisis_master', true);
            }
            if (strpos($errorMessage, 'invalid_grant') !== false) {
                $this->session->unset_userdata('upload_token');

                return redirect_with('error', 'Sesi verifikasi telah berakhir. Silakan verifikasi ulang.', 'analisis_master', true);
            }
            if (strpos($errorMessage, '"code": 401') !== false) {
                $this->session->unset_userdata('upload_token');

                return redirect_with('error', 'Token tidak valid. Silakan autentikasi ulang.', 'analisis_master', true);
            }
            if (strpos($errorMessage, '"code": 404') !== false) {
                $currentScriptId = $scriptId ?? 'Tidak diatur';
                $currentFormId   = $formId ?? 'Tidak diatur';

                return redirect_with('error', "Sumber daya tidak ditemukan.<br>Script ID: {$currentScriptId}<br>Form ID: {$currentFormId}", 'analisis_master', true);
            }
            if (strpos($errorMessage, '"code": 403') !== false) {
                return redirect_with('error', 'Akses tidak diizinkan. Periksa permission Anda.', 'analisis_master', true);
            }

            return redirect_with('error', "Kesalahan: {$errorMessage}", 'analisis_master', true);
        }
    }

    protected function getOAuthCredentialsFile(): mixed
    {
        // Hanya ambil dari config jika tidak ada setting aplikasi utk redirect_uri
        if (setting('api_gform_credential')) {
            $api_gform_credential = setting('api_gform_credential');
        } elseif (empty(setting('api_gform_redirect_uri'))) {
            $api_gform_credential = config_item('api_gform_credential');
        }

        return json_decode(str_replace('\"', '"', $api_gform_credential), true);
    }
}
