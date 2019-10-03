<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Session Helper
 *
 * A simple session class helper for Codeigniter
 *
 * @package     Codeigniter Session Helper
 * @author      Dwayne Charrington
 * @copyright   Copyright (c) 2014, Dwayne Charrington
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 * @link        http://ilikekillnerds.com
 * @since       Version 1.0
 * @filesource
 */

if (!function_exists('get_flashdata'))
{
    function get_flashdata($key)
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->flashdata($key);
    }
}
if (!function_exists('set_flashdata'))
{
    function set_flashdata($key, $value)
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->set_flashdata($key, $value);
    }
}
if (!function_exists('keep_flashdata'))
{
    function keep_flashdata($key)
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->keep_flashdata($key);
    }
}
if (!function_exists('get_userdata'))
{
    function get_userdata($key)
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->userdata($key);
    }
}
if (!function_exists('all_userdata'))
{
    function all_userdata()
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->all_userdata();
    }
}
if (!function_exists('set_userdata'))
{
    function set_userdata($key, $value)
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->set_userdata($key, $value);
    }
}
if (!function_exists('unset_userdata'))
{
    function unset_userdata($data)
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->unset_userdata($data);
    }
}
if (!function_exists('session_destroy'))
{
    function session_destroy()
    {
        $CI = get_instance();
        $CI->load->library('session');
        return $CI->session->sess_destroy();
    }
}