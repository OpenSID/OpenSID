<?php

use Illuminate\Session\Store;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Session
{
    /**
     * Userdata array
     *
     * Just a reference to session data, for BC purposes.
     *
     * @var array
     */
    public $userdata;

    /**
     * Laravel Session Store instance
     *
     * @var \Illuminate\Session\Store
     */
    protected $store;

    /**
     * Session configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Session ID regexp pattern
     *
     * @var string
     */
    protected $_sid_regexp;

    /**
     * Driver name (for compatibility)
     *
     * @var string
     */
    protected $_driver = 'laravel';

    // ------------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @param  array  $params  Configuration parameters
     * @return void
     */
    public function __construct(array $params = [])
    {
        $this->store = app('session');

        // Configuration
        $this->_configure($params);

        // Start session if not already started
        if (! $this->store->isStarted()) {
            $this->store->start();
        }

        // Handle session regeneration
        $this->_handleSessionRegeneration();

        // Initialize internal variables
        $this->_ci_init_vars();

        log_message('info', 'Session: MY_Session initialized using Laravel Session Store.');
    }

    // ------------------------------------------------------------------------

    /**
     * Configuration
     *
     * Handle input parameters and configuration defaults
     *
     * @param  array  &$params  Input parameters
     * @return void
     */
    protected function _configure(&$params)
    {
        $expiration = config_item('sess_expiration');

        // Cookie lifetime
        if (isset($params['cookie_lifetime'])) {
            $params['cookie_lifetime'] = (int) $params['cookie_lifetime'];
        } else {
            $params['cookie_lifetime'] = (! isset($expiration) && config_item('sess_expire_on_close'))
                ? 0 : (int) $expiration;
        }

        // Cookie name
        isset($params['cookie_name']) || $params['cookie_name'] = config_item('sess_cookie_name');
        if (empty($params['cookie_name'])) {
            $params['cookie_name'] = 'ci_session';
        }

        // Other cookie parameters
        isset($params['cookie_path']) || $params['cookie_path'] = config_item('cookie_path');
        isset($params['cookie_domain']) || $params['cookie_domain'] = config_item('cookie_domain');
        isset($params['cookie_secure']) || $params['cookie_secure'] = (bool) config_item('cookie_secure');

        // SameSite attribute
        isset($params['cookie_samesite']) || $params['cookie_samesite'] = config_item('sess_samesite');
        if (isset($params['cookie_samesite'])) {
            $params['cookie_samesite'] = ucfirst(strtolower($params['cookie_samesite']));
            in_array($params['cookie_samesite'], ['Lax', 'Strict', 'None'], true) || $params['cookie_samesite'] = 'Lax';
        } else {
            $params['cookie_samesite'] = 'Lax';
        }

        // Expiration
        if (empty($expiration)) {
            $params['expiration'] = 7200; // 2 hours default
        } else {
            $params['expiration'] = (int) $expiration;
        }

        // IP matching
        $params['match_ip'] = (bool) (isset($params['match_ip']) ? $params['match_ip'] : config_item('sess_match_ip'));

        // Save path
        isset($params['save_path']) || $params['save_path'] = config_item('sess_save_path');

        $this->_config = $params;

        // Configure session ID pattern
        $this->_configure_sid_length();
    }

    // ------------------------------------------------------------------------

    /**
     * Configure session ID length and pattern
     *
     * @return void
     */
    protected function _configure_sid_length()
    {
        // Default pattern for Laravel session IDs (40 characters, alphanumeric)
        $this->_sid_regexp = '[0-9a-zA-Z]{40}';
    }

    // ------------------------------------------------------------------------

    /**
     * Handle session regeneration
     *
     * @return void
     */
    protected function _handleSessionRegeneration()
    {
        // Skip AJAX requests
        if ((empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
            && ($regenerate_time = config_item('sess_time_to_update')) > 0
        ) {
            $last_regenerate = $this->store->get('__ci_last_regenerate');

            if ($last_regenerate === null) {
                $this->store->put('__ci_last_regenerate', time());
            } elseif ($last_regenerate < (time() - $regenerate_time)) {
                $this->sess_regenerate((bool) config_item('sess_regenerate_destroy'));
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Handle temporary variables
     *
     * Clears old "flash" data, marks the new one for deletion and handles
     * "temp" data deletion.
     *
     * @return void
     */
    protected function _ci_init_vars()
    {
        $ci_vars = $this->store->get('__ci_vars', []);

        if (! empty($ci_vars)) {
            $current_time = time();

            foreach ($ci_vars as $key => $value) {
                if ($value === 'new') {
                    $ci_vars[$key] = 'old';
                } elseif ($value === 'old' || (is_int($value) && $value < $current_time)) {
                    $this->store->forget($key);
                    unset($ci_vars[$key]);
                }
            }

            if (empty($ci_vars)) {
                $this->store->forget('__ci_vars');
            } else {
                $this->store->put('__ci_vars', $ci_vars);
            }
        }

        // Create userdata reference
        $this->userdata = $this->store->all();
    }

    // ------------------------------------------------------------------------

    /**
     * Mark as flash
     *
     * @param  mixed  $key  Session data key(s)
     * @return bool
     */
    public function mark_as_flash($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if (! $this->store->has($k)) {
                    return false;
                }
            }

            $new = array_fill_keys($key, 'new');
            $ci_vars = $this->store->get('__ci_vars', []);
            $ci_vars = array_merge($ci_vars, $new);
            $this->store->put('__ci_vars', $ci_vars);

            return true;
        }

        if (! $this->store->has($key)) {
            return false;
        }

        $ci_vars = $this->store->get('__ci_vars', []);
        $ci_vars[$key] = 'new';
        $this->store->put('__ci_vars', $ci_vars);

        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Get flash keys
     *
     * @return array
     */
    public function get_flash_keys()
    {
        $ci_vars = $this->store->get('__ci_vars', []);

        if (empty($ci_vars)) {
            return [];
        }

        $keys = [];
        foreach (array_keys($ci_vars) as $key) {
            if (! is_int($ci_vars[$key])) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    // ------------------------------------------------------------------------

    /**
     * Unmark flash
     *
     * @param  mixed  $key  Session data key(s)
     * @return void
     */
    public function unmark_flash($key)
    {
        $ci_vars = $this->store->get('__ci_vars', []);

        if (empty($ci_vars)) {
            return;
        }

        is_array($key) || $key = [$key];

        foreach ($key as $k) {
            if (isset($ci_vars[$k]) && ! is_int($ci_vars[$k])) {
                unset($ci_vars[$k]);
            }
        }

        if (empty($ci_vars)) {
            $this->store->forget('__ci_vars');
        } else {
            $this->store->put('__ci_vars', $ci_vars);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Mark as temp
     *
     * @param  mixed  $key  Session data key(s)
     * @param  int  $ttl  Time-to-live in seconds
     * @return bool
     */
    public function mark_as_temp($key, $ttl = 300)
    {
        $ttl += time();

        if (is_array($key)) {
            $temp = [];

            foreach ($key as $k => $v) {
                // Do we have a key => ttl pair, or just a key?
                if (is_int($k)) {
                    $k = $v;
                    $v = $ttl;
                } else {
                    $v += time();
                }

                if (! $this->store->has($k)) {
                    return false;
                }

                $temp[$k] = $v;
            }

            $ci_vars = $this->store->get('__ci_vars', []);
            $ci_vars = array_merge($ci_vars, $temp);
            $this->store->put('__ci_vars', $ci_vars);

            return true;
        }

        if (! $this->store->has($key)) {
            return false;
        }

        $ci_vars = $this->store->get('__ci_vars', []);
        $ci_vars[$key] = $ttl;
        $this->store->put('__ci_vars', $ci_vars);

        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Get temp keys
     *
     * @return array
     */
    public function get_temp_keys()
    {
        $ci_vars = $this->store->get('__ci_vars', []);

        if (empty($ci_vars)) {
            return [];
        }

        $keys = [];
        foreach (array_keys($ci_vars) as $key) {
            if (is_int($ci_vars[$key])) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    // ------------------------------------------------------------------------

    /**
     * Unmark temp
     *
     * @param  mixed  $key  Session data key(s)
     * @return void
     */
    public function unmark_temp($key)
    {
        $ci_vars = $this->store->get('__ci_vars', []);

        if (empty($ci_vars)) {
            return;
        }

        is_array($key) || $key = [$key];

        foreach ($key as $k) {
            if (isset($ci_vars[$k]) && is_int($ci_vars[$k])) {
                unset($ci_vars[$k]);
            }
        }

        if (empty($ci_vars)) {
            $this->store->forget('__ci_vars');
        } else {
            $this->store->put('__ci_vars', $ci_vars);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * __get()
     *
     * @param  string  $key  'session_id' or a session data key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === 'session_id') {
            return $this->store->getId();
        }

        return $this->store->get($key);
    }

    // ------------------------------------------------------------------------

    /**
     * __isset()
     *
     * @param  string  $key  'session_id' or a session data key
     * @return bool
     */
    public function __isset($key)
    {
        if ($key === 'session_id') {
            return $this->store->isStarted();
        }

        return $this->store->has($key);
    }

    // ------------------------------------------------------------------------

    /**
     * __set()
     *
     * @param  string  $key  Session data key
     * @param  mixed  $value  Session data value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->store->put($key, $value);
        $this->userdata[$key] = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Session destroy
     *
     * Legacy CI_Session compatibility method
     *
     * @return void
     */
    public function sess_destroy()
    {
        $this->store->invalidate();
        $this->userdata = [];
    }

    // ------------------------------------------------------------------------

    /**
     * Session regenerate
     *
     * Legacy CI_Session compatibility method
     *
     * @param  bool  $destroy  Destroy old session data flag
     * @return void
     */
    public function sess_regenerate($destroy = false)
    {
        $this->store->put('__ci_last_regenerate', time());
        $this->store->regenerate($destroy);
    }

    // ------------------------------------------------------------------------

    /**
     * Get userdata reference
     *
     * Legacy CI_Session compatibility method
     *
     * @return array
     */
    public function &get_userdata()
    {
        $this->userdata = $this->store->all();

        return $this->userdata;
    }

    // ------------------------------------------------------------------------

    /**
     * Userdata (fetch)
     *
     * Legacy CI_Session compatibility method
     *
     * @param  string  $key  Session data key
     * @return mixed Session data value or NULL if not found
     */
    public function userdata($key = null)
    {
        if (isset($key)) {
            return $this->store->get($key);
        }

        $userdata = [];
        $all_data = $this->store->all();
        $_exclude = array_merge(
            ['__ci_vars', '__ci_last_regenerate'],
            $this->get_flash_keys(),
            $this->get_temp_keys()
        );

        foreach (array_keys($all_data) as $session_key) {
            if (! in_array($session_key, $_exclude, true)) {
                $userdata[$session_key] = $all_data[$session_key];
            }
        }

        return $userdata;
    }

    // ------------------------------------------------------------------------

    /**
     * Set userdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  mixed  $data  Session data key or an associative array
     * @param  mixed  $value  Value to store
     * @return void
     */
    public function set_userdata($data, $value = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->store->put($key, $val);
                $this->userdata[$key] = $val;
            }

            return;
        }

        $this->store->put($data, $value);
        $this->userdata[$data] = $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Unset userdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  mixed  $key  Session data key(s)
     * @return void
     */
    public function unset_userdata($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                $this->store->forget($k);
                unset($this->userdata[$k]);
            }

            return;
        }

        $this->store->forget($key);
        unset($this->userdata[$key]);
    }

    // ------------------------------------------------------------------------

    /**
     * All userdata (fetch)
     *
     * Legacy CI_Session compatibility method
     *
     * @return array All session data, excluding flash data items
     */
    public function all_userdata()
    {
        return $this->userdata();
    }

    // ------------------------------------------------------------------------

    /**
     * Has userdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  string  $key  Session data key
     * @return bool
     */
    public function has_userdata($key)
    {
        return $this->store->has($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Flashdata (fetch)
     *
     * Legacy CI_Session compatibility method
     *
     * @param  string  $key  Session data key
     * @return mixed Session data value or NULL if not found
     */
    public function flashdata($key = null)
    {
        if (isset($key)) {
            $ci_vars = $this->store->get('__ci_vars', []);

            return (isset($ci_vars[$key]) && ! is_int($ci_vars[$key]) && $this->store->has($key))
                ? $this->store->get($key)
                : null;
        }

        $flashdata = [];
        $ci_vars = $this->store->get('__ci_vars', []);

        if (! empty($ci_vars)) {
            foreach ($ci_vars as $key => $value) {
                if (! is_int($value) && $this->store->has($key)) {
                    $flashdata[$key] = $this->store->get($key);
                }
            }
        }

        return $flashdata;
    }

    // ------------------------------------------------------------------------

    /**
     * Set flashdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  mixed  $data  Session data key or an associative array
     * @param  mixed  $value  Value to store
     * @return void
     */
    public function set_flashdata($data, $value = null)
    {
        $this->set_userdata($data, $value);
        $this->mark_as_flash(is_array($data) ? array_keys($data) : $data);
    }

    // ------------------------------------------------------------------------

    /**
     * Keep flashdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  mixed  $key  Session data key(s)
     * @return void
     */
    public function keep_flashdata($key)
    {
        $this->mark_as_flash($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Temp data (fetch)
     *
     * Legacy CI_Session compatibility method
     *
     * @param  string  $key  Session data key
     * @return mixed Session data value or NULL if not found
     */
    public function tempdata($key = null)
    {
        if (isset($key)) {
            $ci_vars = $this->store->get('__ci_vars', []);

            return (isset($ci_vars[$key]) && is_int($ci_vars[$key]) && $this->store->has($key))
                ? $this->store->get($key)
                : null;
        }

        $tempdata = [];
        $ci_vars = $this->store->get('__ci_vars', []);

        if (! empty($ci_vars)) {
            foreach ($ci_vars as $key => $value) {
                if (is_int($value) && $this->store->has($key)) {
                    $tempdata[$key] = $this->store->get($key);
                }
            }
        }

        return $tempdata;
    }

    // ------------------------------------------------------------------------

    /**
     * Set tempdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  mixed  $data  Session data key or an associative array of items
     * @param  mixed  $value  Value to store
     * @param  int  $ttl  Time-to-live in seconds
     * @return void
     */
    public function set_tempdata($data, $value = null, $ttl = 300)
    {
        $this->set_userdata($data, $value);
        $this->mark_as_temp(is_array($data) ? array_keys($data) : $data, $ttl);
    }

    // ------------------------------------------------------------------------

    /**
     * Unset tempdata
     *
     * Legacy CI_Session compatibility method
     *
     * @param  mixed  $data  Session data key(s)
     * @return void
     */
    public function unset_tempdata($key)
    {
        $this->unmark_temp($key);
    }

    /**
     * Get Laravel Session Store instance
     *
     * @return \Illuminate\Session\Store
     */
    public function getStore()
    {
        return $this->store;
    }

    // ------------------------------------------------------------------------

    /**
     * Set Laravel Session Store instance
     *
     * @return void
     */
    public function setStore(Store $store)
    {
        $this->store = $store;
        $this->_ci_init_vars();
    }
}
