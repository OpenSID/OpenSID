<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view($folder_themes . '/commons/meta') ?>
  <?php $this->load->view($folder_themes . '/commons/source_css') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
</head>
<body class="font-primary bg-gray-100">
  
  <?php $this->load->view($folder_themes . '/commons/loading_screen') ?>
  <?php $this->load->view($folder_themes . '/commons/header') ?>
  <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <main class="lg:w-2/3 w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
        <?php if(preg_match("/halaman_statis/i", $halaman_statis)) : ?>
          <nav role="navigation" aria-label="navigation" class="breadcrumb">
            <ol>
              <li><a href="<?= site_url() ?>">Beranda</a></li>
              <li aria-current="page">Laman statis</li>
            </ol>
          </nav>
          <div class="content py-1">
            <?php $this->load->view($halaman_statis); ?>
          </div>
          <?php else : ?>
            <?php $this->load->view("{$folder_themes}/partials/{$halaman_statis}"); ?>
        <?php endif ?>
    </main>
    <div class="lg:w-1/3 w-full">
      <?php $this->load->view($folder_themes .'/partials/sidebar') ?>
    </div>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>