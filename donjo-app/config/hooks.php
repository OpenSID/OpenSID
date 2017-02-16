<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
$hook['pre_system'] = array(
 'class' => 'Router',
 'function' => 'route',
 'filename' => 'router.php',
 'filepath' => 'hooks'
);