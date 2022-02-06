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
    <main class="lg:w-3/4 w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
          <li><a href="<?= site_url() ?>">Beranda</a></li>
          <li>Data Analisis</li>
        </ol>
      </nav>
      <?php if($list_jawab): ?>
        <?php $this->load->view("$folder_themes/partials/statistics/analisis.php"); ?>
        <?php else : ?>
          <h2 class="text-h2">Daftar Agregasi Data Analisis Desa</h2>
          <?php foreach ($list_indikator AS $data): ?>
            <a href="<?= site_url("data_analisis/$data[id]/$data[subjek_tipe]/$data[id_periode]"); ?>"><h5>&nbsp;<b><?= $data['indikator']?></b></h5></a>
            <div class="table-responsive content py-3">
              <table>
                <tr>
                  <td width="20%">Pendataan </td>
                  <td width="1%"> :</td>
                  <td><?= $data['master']; ?></td>
                </tr>
                <tr>
                  <td>Subjek </td>
                  <td> : </td>
                  <td><?= $data['subjek']; ?></td>
                </tr>
                <tr>
                  <td>Tahun </td>
                  <td> :</td>
                  <td><?= $data['tahun']; ?></td>
                </tr>
              </table>
            </div>
            <hr>
          <?php endforeach; ?>
      <?php endif; ?>
    </main>
    <div class="lg:w-1/4 w-full">
      <?php $this->load->view($folder_themes .'/partials/sidebar') ?>
    </div>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>