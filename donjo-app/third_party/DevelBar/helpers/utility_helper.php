<?php defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

if (!function_exists('image_base64_encode')) {
    function image_base64_encode($image)
    {
        $type = pathinfo($image, PATHINFO_EXTENSION);
        $data = file_get_contents($image);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}

// ------------------------------------------------------------------------

if (!function_exists('check_ci_version')) {
    function check_ci_version($url)
    {
        if(!$ci_version = @file_get_contents($url)) {
            return false;
        }

        $ci_version = htmlentities($ci_version);

        preg_match("/CI_VERSION',\s'(.*)'\)/", $ci_version, $matches);

        if(count($matches) && version_compare($matches[1], CI_VERSION, '>')) {
            return $matches[1];
        }

        return FALSE;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('check_develbar_version')) {
    function check_develbar_version($url)
    {
        if(!$develbar = @file_get_contents($url))
            return FALSE;

        $develbar = json_decode($develbar, TRUE);

        if(version_compare($develbar['version'], DevelBar::VERSION, '>')) {
            return $develbar['version'];
        }

        return FALSE;
    }
}
