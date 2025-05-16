<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="statstyle">
  <?php
  $viewMapping = [
    2 => 'statistik_sos',
    3 => 'wilayah',
    4 => 'dpt',
  ];
  
  $defaultView = 'statistik';
  
  $viewName = isset($viewMapping[$tipe]) ? $viewMapping[$tipe] : $defaultView;
  $viewPath = "{$folder_themes}/partials/kependudukan/{$viewName}";
  
  $this->load->view($viewPath);
  ?>
</div>
