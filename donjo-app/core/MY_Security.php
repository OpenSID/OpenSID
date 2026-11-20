<?php

use App\Bootstrap\RequestAwareTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Security extends CI_Security
{
    use RequestAwareTrait;

    /**
     * CSRF Verify
     *
     * @return	CI_Security
     */
    public function csrf_verify()
    {
        // If it's not a POST request we will set the CSRF cookie
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            return $this->csrf_set_cookie();
        }

        // Check if URI has been whitelisted from CSRF checks
        if ($exclude_uris = config_item('csrf_exclude_uris')) {
            $uri = load_class('URI', 'core');
            foreach ($exclude_uris as $excluded) {
                if (preg_match('#^' . $excluded . '$#i' . (UTF8_ENABLED ? 'u' : ''), $uri->uri_string())) {
                    return $this;
                }
            }
        }

        // Check CSRF token validity, but don't error on mismatch just yet - we'll want to regenerate
        $valid = isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])
            && is_string($_POST[$this->_csrf_token_name]) && is_string($_COOKIE[$this->_csrf_cookie_name])
            && hash_equals($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name]);

        // We kill this since we're done and we don't want to pollute the _POST array
        unset($_POST[$this->_csrf_token_name]);

        $request = $this->hasRequest()
            ? $this->getRequest()
            : request();

        // Remove csrf token from request data
        $request->request->remove($this->_csrf_token_name);

        if (config_item('csrf_regenerate')) {
            unset($_COOKIE[$this->_csrf_cookie_name]);

            $this->_csrf_hash = null;

            $request->cookies->remove($this->_csrf_cookie_name);
        }

        $this->_csrf_set_hash();
        $this->csrf_set_cookie();

        if ($valid !== true) {
            $this->csrf_show_error();
        }

        log_message('info', 'CSRF token verified');

        return $this;
    }
}
