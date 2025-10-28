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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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

        // Get the API client and construct the service object.
        $client = new Client();
        $client->setAuthConfig($oauthCredentials);
        $client->setRedirectUri($redirectUri);
        $client->addScope('https://www.googleapis.com/auth/forms');
        $client->addScope('https://www.googleapis.com/auth/spreadsheets');

        // Perbarui untuk menggunakan Google\Service\Script
        $service = new Script($client);

        // API script id
        if (empty(setting('api_gform_id_script')) && empty(setting('api_gform_redirect_uri'))) {
            $scriptId = config_item('api_gform_script_id');
        } else {
            $scriptId = setting('api_gform_id_script');
        }

        // add "?logout" to the URL to remove a token from the session
        if ($this->input->get('logout')) {
            $this->session->unset_userdata('upload_token');
        }

        if ($this->input->get('code')) {
            try {
                $token = $client->fetchAccessTokenWithAuthCode($this->input->get('code'));

                // Check if token contains error
                if (isset($token['error'])) {
                    $errorMsg = $token['error_description'] ?? $token['error'];

                    return redirect_with('error', "OAuth Error: {$errorMsg}", 'analisis_master', true);
                }

                $client->setAccessToken($token);
                $this->session->set_userdata('upload_token', $token);
            } catch (Exception $e) {
                // Handle invalid authorization code
                logger()->error($e);

                // Clean up session to prevent stuck states
                $this->session->unset_userdata('upload_token');
                $this->session->unset_userdata('inside_retry');
                $this->session->unset_userdata('google_form_id');
                $this->session->unset_userdata('gform_id');

                return redirect_with('error', 'Kode otorisasi tidak valid atau sudah kedaluwarsa. Silakan coba lagi.', 'analisis_master', true);
            }
        }

        if ($this->session->userdata('upload_token')) {
            $client->setAccessToken($this->session->userdata('upload_token'));
            if ($client->isAccessTokenExpired()) {
                $this->session->unset_userdata('upload_token');
            }
        } else {
            $authUrl = $client->createAuthUrl();
        }

        // Get and validate form ID
        $formId = $this->session->userdata('google_form_id') ?? '';
        if (empty($formId)) {
            $formId = $this->session->userdata('gform_id') ?? '';
        }

        // Create an execution request object.
        $request = new ExecutionRequest(); // Perbarui untuk menggunakan ExecutionRequest
        $request->setFunction('getFormItems');
        $request->setParameters([$formId]); // Parameter harus dalam array

        try {
            if (isset($authUrl) && $this->session->userdata('inside_retry') != true) {
                // If no authentication before
                $this->session->set_userdata('gform_id', $formId);
                $this->session->set_userdata('inside_retry', true);
                $this->session->set_userdata('inside_redirect_link', $redirectLink);

                header("Location: {$authUrl}");
            } else {
                // If it has authenticated
                // Make the API request.
                $response = $service->scripts->run($scriptId, $request);

                // Get Response
                $resp = $response->getResponse();

                return $resp['result'];
            }
        } catch (Exception $e) {
            // Handle different types of exceptions
            logger()->error($e);
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'Invalid code') !== false) {
                return redirect_with('error', 'Kode verifikasi tidak valid atau telah kedaluwarsa. Silakan bersihkan data browser dan ulangi proses.', 'analisis_master', true);
            }
            if (strpos($errorMessage, 'invalid_grant') !== false) {
                return redirect_with('error', 'Sesi verifikasi telah berakhir. Silakan lakukan verifikasi ulang untuk melanjutkan.', 'analisis_master', true);
            }
            if (strpos($errorMessage, '"code": 404') !== false || strpos($errorMessage, 'Requested entity was not found') !== false) {
                // Handle 404 errors - script or form not found
                $currentScriptId = $scriptId ?? 'Tidak diatur';
                $currentFormId   = $formId ?? 'Tidak diatur';

                return redirect_with('error', "Sumber daya tidak ditemukan. Silakan periksa:<br>1. ID Google Apps Script sudah benar dan dapat diakses<br>2. ID Google Form sudah benar dan dapat diakses<br>3. Anda memiliki hak akses ke script dan form tersebut<br><br>Script ID Saat Ini: {$currentScriptId}<br>Form ID Saat Ini: {$currentFormId}", 'analisis_master', true);
            }
            if (strpos($errorMessage, '"code": 403') !== false) {
                // Handle permission errors
                return redirect_with('error', 'Akses tidak diizinkan. Pastikan Anda memiliki hak akses yang sesuai untuk menggunakan Google Apps Script dan Form yang dimaksud.', 'analisis_master', true);
            }

                // Generic error for other API issues
                return redirect_with('error', "Kesalahan Google API:<br> {$errorMessage}", 'analisis_master', true);

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
