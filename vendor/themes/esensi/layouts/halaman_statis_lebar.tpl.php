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

  <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <main class="w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      <?php if(IS_PREMIUM) : ?>
        <?php if(preg_match("/halaman_statis/i", $halaman_statis)) : ?>
          <?php $this->load->view($halaman_statis); ?>
          <?php else : ?>
            <?php $halaman_statis = str_replace('home/idm', 'idm/index', $halaman_statis); ?>
            <?php $this->load->view("{$folder_themes}/partials/{$halaman_statis}"); ?>
        <?php endif ?>
        <?php else : ?>
          <?php if (in_array($halaman_statis, ['web/halaman_statis/lapak', 'home/idm', 'idm/index'])): ?>
              <?php $halaman_statis = $halaman_statis === 'web/halaman_statis/lapak' ? 'lapak/index' : ($halaman_statis === 'home/idm' ? 'idm/index' : $halaman_statis) ?>
              <?php $this->load->view("{$folder_themes}/partials/{$halaman_statis}"); ?>
            <?php else: ?>
              <?php $this->load->view($halaman_statis); ?>
          <?php endif; ?>
      <?php endif ?>
    </main>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>