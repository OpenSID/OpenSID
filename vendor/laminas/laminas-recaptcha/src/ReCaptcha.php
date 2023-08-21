<?php

declare(strict_types=1);

namespace Laminas\ReCaptcha;

use Exception as PhpException;
use Laminas\Http\Client as HttpClient;
use Laminas\Http\Request as HttpRequest;
use Laminas\ReCaptcha\Response;
use Laminas\Stdlib\ArrayUtils;
use Traversable;

use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function sprintf;
use function trigger_error;

use const E_USER_WARNING;

/**
 * Render and verify ReCaptchas
 */
class ReCaptcha
{
    /**
     * URI to the API
     *
     * @var string
     */
    public const API_SERVER = 'https://www.google.com/recaptcha/api';

    /**
     * URI to the verify server
     *
     * @var string
     */
    public const VERIFY_SERVER = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Site key used when displaying the captcha
     *
     * @var string
     */
    protected $siteKey;

    /**
     * Secret key used when verifying user input
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Ip address used when verifying user input
     *
     * @var string
     */
    protected $ip;

    /**
     * Parameters for the object
     *
     * @var array
     */
    protected $params = [
        'noscript' => false, /* Includes the <noscript> tag */
    ];

    /**
     * Options for tailoring reCaptcha
     *
     * See the different options on https://developers.google.com/recaptcha/docs/display#config
     *
     * @var array
     */
    protected $options = [
        'theme'            => 'light',
        'type'             => 'image',
        'size'             => 'normal',
        'tabindex'         => 0,
        'callback'         => null,
        'expired-callback' => null,
        'hl'               => null, // Auto-detect language
    ];

    /** @var HttpClient */
    protected $httpClient;

    /**
     * @param string $siteKey
     * @param string $secretKey
     * @param array|Traversable $params
     * @param array|Traversable $options
     * @param string $ip
     */
    public function __construct(
        $siteKey = null,
        $secretKey = null,
        $params = null,
        $options = null,
        $ip = null,
        ?HttpClient $httpClient = null
    ) {
        if ($siteKey !== null) {
            $this->setSiteKey($siteKey);
        }

        if ($secretKey !== null) {
            $this->setSecretKey($secretKey);
        }

        if ($ip !== null) {
            $this->setIp($ip);
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $this->setIp($_SERVER['REMOTE_ADDR']);
        }

        if ($params !== null) {
            $this->setParams($params);
        }

        if ($options !== null) {
            $this->setOptions($options);
        }

        $this->setHttpClient($httpClient ?: new HttpClient());
    }

    /** @return $this */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /** @return HttpClient */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Serialize as string
     *
     * When the instance is used as a string it will display the recaptcha.
     * Since we can't throw exceptions within this method we will trigger
     * a user warning instead.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $return = $this->getHtml();
        } catch (PhpException $e) {
            $return = '';
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return $return;
    }

    /**
     * Set the ip property
     *
     * @param string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get the ip property
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set a single parameter
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Set parameters
     *
     * @param array|Traversable $params
     * @return self
     * @throws Exception
     */
    public function setParams($params)
    {
        if ($params instanceof Traversable) {
            $params = ArrayUtils::iteratorToArray($params);
        }

        if (! is_array($params)) {
            throw new Exception(sprintf(
                '%s expects an array or Traversable set of params; received "%s"',
                __METHOD__,
                is_object($params) ? get_class($params) : gettype($params)
            ));
        }

        foreach ($params as $k => $v) {
            $this->setParam($k, $v);
        }

        return $this;
    }

    /**
     * Get the parameter array
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get a single parameter
     *
     * @param string $key
     * @return mixed
     */
    public function getParam($key)
    {
        if (! isset($this->params[$key])) {
            return null;
        }

        return $this->params[$key];
    }

    /**
     * Set a single option
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Set options
     *
     * @param array|Traversable $options
     * @return self
     * @throws Exception
     */
    public function setOptions($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->setOption($k, $v);
            }
        } else {
            throw new Exception('Expected array or Traversable object');
        }

        return $this;
    }

    /**
     * Get the options array
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get a single option
     *
     * @param string $key
     * @return mixed
     */
    public function getOption($key)
    {
        if (! isset($this->options[$key])) {
            return null;
        }

        return $this->options[$key];
    }

    /**
     * Get the site key
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * Set the site key
     *
     * @param string $siteKey
     * @return self
     */
    public function setSiteKey($siteKey)
    {
        $this->siteKey = $siteKey;

        return $this;
    }

    /**
     * Get the secret key
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set the secret key
     *
     * @param string $secretKey
     * @return self
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Get the HTML code for the captcha
     *
     * This method uses the public key to fetch a recaptcha form.
     *
     * @return string
     * @throws Exception
     */
    public function getHtml()
    {
        if ($this->siteKey === null) {
            throw new Exception('Missing site key');
        }

        $host = self::API_SERVER;

        // Should we use an onload callback?
        if (! empty($this->options['onload'])) {
            return sprintf(
                '<script type="text/javascript" src="%s.js?onload=%s&render=explicit" async defer></script>',
                $host,
                $this->options['onload']
            );
        }

        $langOption = '';

        if (! empty($this->options['hl'])) {
            $langOption = "?hl={$this->options['hl']}";
        }

        $data = "data-sitekey=\"{$this->siteKey}\"";

        foreach (
            [
                'theme',
                'type',
                'size',
                'tabindex',
                'callback',
                'expired-callback',
            ] as $option
        ) {
            if (! empty($this->options[$option])) {
                $data .= " data-$option=\"{$this->options[$option]}\"";
            }
        }

        $return = <<<HTML
<script type="text/javascript" src="{$host}.js{$langOption}" async defer></script>
<div class="g-recaptcha" $data></div>
HTML;

        if ($this->params['noscript']) {
            $return .= <<<HTML
<noscript>
  <div style="width: 302px; height: 422px;">
    <div style="width: 302px; height: 422px; position: relative;">
      <div style="width: 302px; height: 422px; position: absolute;">
        <iframe src="{$host}/fallback?k={$this->siteKey}"
                frameborder="0" scrolling="no"
                style="width: 302px; height:422px; border-style: none;">
        </iframe>
      </div>
      <div style="width: 300px; height: 60px; border-style: none;
                  bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;
                  background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
        <textarea id="g-recaptcha-response" name="g-recaptcha-response"
                  class="g-recaptcha-response"
                  style="width: 250px; height: 40px; border: 1px solid #c1c1c1;
                         margin: 10px 25px; padding: 0px; resize: none;" >
        </textarea>
      </div>
    </div>
  </div>
</noscript>
HTML;
        }
        return $return;
    }

    /**
     * Gets a solution to the verify server
     *
     * @param string $responseField
     * @return \Laminas\Http\Response
     * @throws Exception
     */
    protected function post($responseField)
    {
        if ($this->secretKey === null) {
            throw new Exception('Missing secret key');
        }

        if ($this->ip === null) {
            throw new Exception('Missing ip address');
        }

        /* Fetch an instance of the http client */
        $httpClient = $this->getHttpClient();

        $params = [
            'secret'   => $this->secretKey,
            'remoteip' => $this->ip,
            'response' => $responseField,
        ];

        $request = new HttpRequest();
        $request->setUri(self::VERIFY_SERVER);
        $request->getPost()->fromArray($params);
        $request->setMethod(HttpRequest::METHOD_POST);
        $httpClient->setEncType($httpClient::ENC_URLENCODED);

        return $httpClient->send($request);
    }

    /**
     * Verify the user input
     *
     * This method calls up the post method and returns a
     * \Laminas\ReCaptcha\Response object.
     *
     * @param string $responseField
     * @return Response
     */
    public function verify($responseField)
    {
        $response = $this->post($responseField);
        return new Response(null, null, $response);
    }
}
