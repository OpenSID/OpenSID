<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view($folder_themes . '/commons/meta') ?>
  <?php $this->load->view($folder_themes . '/commons/source_css') ?>
</head>
<body class="font-primary bg-gray-100">

  <?php $this->load->view($folder_themes . '/commons/loading_screen') ?>
  <?php $this->load->view($folder_themes . '/commons/header') ?>

  <div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <div class="lg:w-1/3 w-full">
      <?php $this->load->view($folder_themes .'/partials/statistics/sidenav') ?>
    </div>
    <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      <?php
        switch ($tipe) {
          case null:
          case '0':
            $page = '/partials/statistics/default';
            break;
          case '3':
            $page = '/partials/statistics/regions';
            break;
          case '4':
            $page = '/partials/statistics/voters';
            break;
          default:
            $page = '/commons/404';
            break;
        }
        ?>
      <?php $this->load->view($folder_themes . $page) ?>
      <script>
        const enable3d = <?=$this->setting->statistik_chart_3d ?> ? true : false;
      </script>
    </main>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>
</body>
</html>