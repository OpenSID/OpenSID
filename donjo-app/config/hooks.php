<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	https://codeigniter.com/user_guide/general/hooks.html
  |
 */
$hook['pre_system'] = array(
    'class' => 'Router',
    'function' => 'route',
    'filename' => 'router.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = function() {
    $ci = get_instance();
    $ci->load->library('rbac', array(
        'role' => $ci->session->role,
        'role_table' => 'user_grup',
        'action_table' => 'user_action',
    ));
    User_access_control::run();
};
