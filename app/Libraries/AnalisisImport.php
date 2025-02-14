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

use Exception;
use Google\Client;
use Google\Service\Script; // Perbarui namespace
use Google\Service\Script\ExecutionRequest;

 // Perbarui namespace

class AnalisisImport
{
    protected function getOAuthCredentialsFile()
    {
        // Hanya ambil dari config jika tidak ada setting aplikasi utk redirect_uri
        if (setting('api_gform_credential')) {
            $api_gform_credential = setting('api_gform_credential');
        } elseif (empty(setting('api_gform_redirect_uri'))) {
            $api_gform_credential = config_item('api_gform_credential');
        }

        return json_decode(str_replace('\"', '"', $api_gform_credential), true);
    }

    public function importGform($redirectLink = '')
    {
        // Check Credential File
        if (! $oauthCredentials = $this->getOAuthCredentialsFile()) {
            echo 'ERROR - File Credential Not Found';

            return;
        }

        $redirectUri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

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
        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['upload_token']);
        }

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token);
            $_SESSION['upload_token'] = $token;
        }

        if (! empty($_SESSION['upload_token'])) {
            $client->setAccessToken($_SESSION['upload_token']);
            if ($client->isAccessTokenExpired()) {
                unset($_SESSION['upload_token']);
            }
        } else {
            $authUrl = $client->createAuthUrl();
        }

        // Create an execution request object.
        $request = new ExecutionRequest(); // Perbarui untuk menggunakan ExecutionRequest
        $request->setFunction('getFormItems');
        $formId = $_SESSION['google_form_id'];
        if ($formId == '') {
            $formId = $_SESSION['gform_id'];
        }
        $request->setParameters([$formId]); // Parameter harus dalam array

        try {
            if (isset($authUrl) && $_SESSION['inside_retry'] != true) {
                // If no authentication before
                $_SESSION['gform_id']             = $formId;
                $_SESSION['inside_retry']         = true;
                $_SESSION['inside_redirect_link'] = $redirectLink;
                header('Location: ' . $authUrl);
            } else {
                // If it has authenticated
                // Make the API request.
                $response = $service->scripts->run($scriptId, $request);

                if ($response->getError()) {
                    echo 'Error';
                    // The API executed, but the script returned an error.

                    // Extract the first (and only) set of error details. The values of this
                    // object are the script's 'errorMessage' and 'errorType', and an array of
                    // stack trace elements.
                    $error = $response->getError()['details'][0];
                    printf("Script error message: %s\n", $error['errorMessage']);

                    if (array_key_exists('scriptStackTraceElements', $error)) {
                        // There may not be a stacktrace if the script didn't start executing.
                        echo "Script error stacktrace:\n";

                        foreach ($error['scriptStackTraceElements'] as $trace) {
                            printf("\t%s: %d\n", $trace['function'], $trace['lineNumber']);
                        }
                    }
                } else {
                    // Get Response
                    $resp = $response->getResponse();

                    return $resp['result'];
                }
            }
        } catch (Exception $e) {
            // The API encountered a problem before the script started executing.
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return '0';
    }
}
