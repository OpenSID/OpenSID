<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view($folder_themes .'/commons/meta') ?>
  <?php $this->load->view($folder_themes .'/commons/source_css') ?>
  <?php $this->load->view($folder_themes .'/commons/source_js') ?>
</head>
<body class="font-primary bg-gray-100">
  <?php if($this->uri->segment(2) == 'kategori' && empty($judul_kategori)) : ?>
    <?php $this->load->view($folder_themes .'/commons/404') ?>
    <?php else : ?>
      <?php $this->load->view($folder_themes . '/commons/loading_screen') ?>
      <?php $this->load->view($folder_themes .'/commons/header') ?>
      <?php $this->load->view($folder_themes .'/layouts/beranda.tpl.php') ?>
      <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php endif ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>
</body>
</html>