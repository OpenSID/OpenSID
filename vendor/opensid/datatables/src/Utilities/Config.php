<?php

namespace Fluent\DataTables\Utilities;

class Config
{
    /**
     * @var CI_Controller
     */
    private $ci;

    public function __construct()
    {
        $this->ci = get_instance();
        $this->ci->load->config('datatables');
    }

    /**
     * Check if config uses wild card search.
     *
     * @return bool
     */
    public function isWildcard()
    {
        return $this->ci->config->item('search')['use_wildcards'];
    }

    /**
     * Check if config uses smart search.
     *
     * @return bool
     */
    public function isSmartSearch()
    {
        return $this->ci->config->item('search')['smart'];
    }

    /**
     * Check if config uses case insensitive search.
     *
     * @return bool
     */
    public function isCaseInsensitive()
    {
        return $this->ci->config->item('search')['case_insensitive'];
    }

    /**
     * Check if app is in debug mode.
     *
     * @return bool
     */
    public function isDebugging()
    {
        return ENVIRONMENT === 'development'
            ? true
            : false;
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->ci->config->item($key) ?? $default;
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $this->ci->config->set_item($key, $value);
    }

    /**
     * Check if dataTable config uses multi-term searching.
     *
     * @return bool
     */
    public function isMultiTerm()
    {
        return $this->ci->config->item('search')['multi_term'];
    }

    /**
     * Check if dataTable config uses starts_with searching.
     *
     * @return bool
     */
    public function isStartsWithSearch()
    {
        return $this->ci->config->item('search')['starts_with'];
    }
}
