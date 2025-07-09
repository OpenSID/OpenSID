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

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NoCaptcha
{
    public const CLIENT_API = 'https://www.google.com/recaptcha/api.js';
    public const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    protected Client $http;

    /**
     * The cached verified responses.
     *
     * @var array
     */
    protected $verifiedResponses = [];

    /**
     * NoCaptcha.
     *
     * @param string $secret
     * @param string $sitekey
     * @param array  $options
     */
    public function __construct(/**
     * The recaptcha secret key.
     */
    protected $secret, /**
     * The recaptcha sitekey key.
     */
    protected $sitekey,
        $options = []
    ) {
        $this->http = new Client($options);
    }

    /**
     * Render HTML captcha.
     *
     * @param array $attributes
     */
    public function display($attributes = []): string
    {
        $attributes = $this->prepareAttributes($attributes);

        return '<div' . $this->buildAttributes($attributes) . '></div>';
    }

    /**
     * @see display()
     */
    public function displayWidget(mixed $attributes = [])
    {
        return $this->display($attributes);
    }

    /**
     * Display a Invisible reCAPTCHA by embedding a callback into a form submit button.
     *
     * @param string $formIdentifier the html ID of the form that should be submitted.
     * @param string $text           the text inside the form button
     * @param array  $attributes     array of additional html elements
     */
    public function displaySubmit($formIdentifier, $text = 'submit', $attributes = []): string
    {
        $javascript = '';
        if (! isset($attributes['data-callback'])) {
            $functionName                = 'onSubmit' . str_replace(['-', '=', '\'', '"', '<', '>', '`'], '', $formIdentifier);
            $attributes['data-callback'] = $functionName;
            $javascript                  = sprintf(
                '<script>function %s(){document.getElementById("%s").submit();}</script>',
                $functionName,
                $formIdentifier
            );
        }

        $attributes = $this->prepareAttributes($attributes);

        $button = sprintf('<button%s><span>%s</span></button>', $this->buildAttributes($attributes), $text);

        return $button . $javascript;
    }

    /**
     * Render js source
     *
     * @param null   $lang
     * @param bool   $callback
     * @param string $onLoadClass
     */
    public function renderJs($lang = null, $callback = false, $onLoadClass = 'onloadCallBack'): string
    {
        return '<script src="' . $this->getJsLink($lang, $callback, $onLoadClass) . '" async defer></script>' . "\n";
    }

    /**
     * Verify no-captcha response.
     *
     * @param string $response
     * @param string $clientIp
     */
    public function verifyResponse($response, $clientIp = null): bool
    {
        if (empty($response)) {
            return false;
        }

        // Return true if response already verfied before.
        if (in_array($response, $this->verifiedResponses)) {
            return true;
        }

        $verifyResponse = $this->sendRequestVerify([
            'secret'   => $this->secret,
            'response' => $response,
            'remoteip' => $clientIp,
        ]);

        if (isset($verifyResponse['success']) && $verifyResponse['success'] === true) {
            // A response can only be verified once from google, so we need to
            // cache it to make it work in case we want to verify it multiple times.
            $this->verifiedResponses[] = $response;

            return true;
        }

            return false;

    }

    /**
     * Verify no-captcha response by Symfony Request.
     *
     * @return bool
     */
    public function verifyRequest(Request $request)
    {
        return $this->verifyResponse(
            $request->get('g-recaptcha-response'),
            $request->getClientIp()
        );
    }

    /**
     * Get recaptcha js link.
     *
     * @param string $lang
     * @param bool   $callback
     * @param string $onLoadClass
     */
    public function getJsLink($lang = null, $callback = false, $onLoadClass = 'onloadCallBack'): string
    {
        $client_api = static::CLIENT_API;
        $params     = [];

        if ($callback) {
            $this->setCallBackParams($params, $onLoadClass);
        }
        if ($lang) {
            $params['hl'] = $lang;
        }

        return $client_api . '?' . http_build_query($params);
    }

    protected function setCallBackParams(array &$params, $onLoadClass)
    {
        $params['render'] = 'explicit';
        $params['onload'] = $onLoadClass;
    }

    /**
     * Send verify request.
     *
     * @return array
     */
    protected function sendRequestVerify(array $query = [])
    {
        $response = $this->http->request('POST', static::VERIFY_URL, [
            'form_params' => $query,
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Prepare HTML attributes and assure that the correct classes and attributes for captcha are inserted.
     */
    protected function prepareAttributes(array $attributes): array
    {
        $attributes['data-sitekey'] = $this->sitekey;
        if (! isset($attributes['class'])) {
            $attributes['class'] = '';
        }
        $attributes['class'] = trim('g-recaptcha ' . $attributes['class']);

        return $attributes;
    }

    /**
     * Build HTML attributes.
     */
    protected function buildAttributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $html[] = $key . '="' . $value . '"';
        }

        return count($html) ? ' ' . implode(' ', $html) : '';
    }
}
