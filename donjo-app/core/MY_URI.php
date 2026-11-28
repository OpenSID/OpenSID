<?php

use Illuminate\Http\Request;
use App\Bootstrap\RequestAwareTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_URI extends CI_URI
{
    use RequestAwareTrait;

    /**
     * The configuration instance.
     *
     * @var \CI_Config
     */
    protected $config;

    /**
     * {@inheritDoc}
     */
    public function __construct(?Request $request = null)
    {
        if ($request !== null) {
            $this->setRequest($request);
        }

        $this->config = &load_class('Config', 'core');
        $this->parseAndSetUri();
        log_message('info', 'URI Class Initialized');
    }

    /**
     * Inject request object and reparse URI segments
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        $this->overrideGlobals($request);

        $this->uri_string = '';
        $this->segments = [];
        $this->rsegments = [];

        // Reparse URI with new request globals
        $this->parseAndSetUri();

        return $this;
    }

    /**
     * Parse and set URI based on current configuration and protocol
     */
    private function parseAndSetUri(): void
    {
        // If query strings are enabled, we don't need to parse any segments.
        // However, they don't make sense under CLI.
        if (is_cli() || $this->config->item('enable_query_strings') !== true) {
            $this->_permitted_uri_chars = $this->config->item('permitted_uri_chars');

            // If it's a CLI request, ignore the configuration
            if (is_cli() && ! defined('ARTISAN_BINARY')) {
                $uri = $this->_parse_argv();
            } else {
                $protocol = $this->config->item('uri_protocol');
                empty($protocol) && $protocol = 'REQUEST_URI';

                switch ($protocol) {
                    case 'AUTO': // For BC purposes only
                    case 'REQUEST_URI':
                        $uri = $this->_parse_request_uri();
                        break;
                    case 'QUERY_STRING':
                        $uri = $this->_parse_query_string();
                        break;
                    case 'PATH_INFO':
                    default:
                        $uri = isset($_SERVER[$protocol])
                            ? $_SERVER[$protocol]
                            : $this->_parse_request_uri();
                        break;
                }
            }

            $this->_set_uri_string($uri);
        }
    }
}
