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
      <?php if(in_array($halaman_statis, ['pembangunan/index', 'pembangunan/detail', 'pengaduan/index'])) : ?>
        <?php $this->load->view($folder_themes .'/partials/'. $halaman_statis) ?>

        <?php elseif(in_array($halaman_statis, ['web/halaman_statis/lapak','lapak/index'])) : ?>
          <?php $data['full_layout'] = true; ?>
          <?php $this->load->view($folder_themes .'/partials/lapak/index', $data); ?>

          <?php $data['paging_page'] = ($paging_page ?? 'lapak') ?>
          <?php $this->load->view($folder_themes .'/commons/paging', $data) ?>

        <?php elseif($halaman_statis === 'home/idm') : ?>
          <?php $this->load->view($folder_themes .'/partials/idm'); ?>
          
        <?php else : ?>
          <div class="content">
            <?php $this->load->view($halaman_statis); ?>
          </div>
      <?php endif ?>
    </main>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>