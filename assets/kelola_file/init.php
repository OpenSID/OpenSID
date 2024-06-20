<?php

define('ENVIRONMENT', 'production');

$ds = DIRECTORY_SEPARATOR;
define('FMPATH', dirname(__FILE__, 3) . $ds);
define('FCPATH', FMPATH . $ds);
define('APPPATH', FMPATH . "donjo-app{$ds}");
define('DESAPATH', FMPATH . "desa{$ds}");
define('LOKASI_CACHE', FMPATH . "desa{$ds}cache{$ds}");

define('RESOURCESPATH', FMPATH . "resources{$ds}");
define('STORAGEPATH', FMPATH . "storage{$ds}");
define('BASEPATH', FMPATH . "vendor{$ds}codeigniter{$ds}framework{$ds}system{$ds}");
define('APP_URL', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}" . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));

require_once BASEPATH . "libraries{$ds}Session{$ds}Session_driver.php";
require_once BASEPATH . "libraries{$ds}Session{$ds}CI_Session_driver_interface.php";
require_once BASEPATH . "libraries{$ds}Session{$ds}drivers{$ds}Session_files_driver.php";
require_once BASEPATH . "core{$ds}Common.php";

$config = get_config();

if (empty($config['sess_save_path'])) {
    $config['sess_save_path'] = rtrim(ini_get('session.save_path'), '/\\');
}

$config = ['cookie_lifetime'   => $config['sess_expiration'], 'cookie_name'       => $config['sess_cookie_name'], 'cookie_path'       => $config['cookie_path'], 'cookie_domain'     => $config['cookie_domain'], 'cookie_secure'     => $config['cookie_secure'], 'expiration'        => $config['sess_expiration'], 'match_ip'          => $config['sess_match_ip'], 'save_path'         => $config['sess_save_path'], '_sid_regexp'       => '[0-9a-v]{32}'];

$class = new CI_Session_files_driver($config);

session_set_save_handler(
    [$class, 'open'],
    [$class, 'close'],
    [$class, 'read'],
    [$class, 'write'],
    [$class, 'destroy'],
    [$class, 'gc']
);
register_shutdown_function('session_write_close');

session_name($config['cookie_name']);