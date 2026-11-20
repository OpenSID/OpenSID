<?php

use App\Bootstrap\RequestAwareTrait;
use Illuminate\Http\Request;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Input extends CI_Input
{
    use RequestAwareTrait;

    public function __construct()
    {
        $this->_allow_get_array      = (config_item('allow_get_array') !== false);
        $this->_enable_xss           = (config_item('global_xss_filtering') === true);
        $this->_enable_csrf          = (config_item('csrf_protection') === true);
        $this->_standardize_newlines = (bool) config_item('standardize_newlines');

        $this->security =& load_class('Security', 'core');

        if (UTF8_ENABLED === true) {
            $this->uni =& load_class('Utf8', 'core');
        }

        $this->_sanitize_globals();

        log_message('info', 'Input Class Initialized');
    }

    /**
     * {inheritdoc}
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        if (method_exists($request, 'overrideGlobals')) {
            $request->overrideGlobals();
        }

        if ($this->_enable_csrf === true && ! is_cli()) {
            $this->security->csrf_verify();
        }

        return $this;
    }
}
