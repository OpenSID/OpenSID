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
    <main class="lg:w-2/3 w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
          <li><a href="<?= site_url() ?>">Beranda</a></li>
          <li>Data Analisis</li>
        </ol>
      </nav>
      <?php if($list_jawab): ?>
        <?php $this->load->view("$folder_themes/partials/statistics/analisis.php"); ?>
        <?php else : ?>
          <h1 class="text-h2">Daftar Agregasi Data Analisis Desa</h1>
          <?php if ($list_indikator): ?>
            <?php if(IS_PREMIUM) : ?>
              <?php if (count($master_indikator ?? []) > 1) : ?>
                <form action="<?=site_url('data_analisis'); ?>" method="get">
                  <div class="space-y-1 flex gap-3 items-center">
                    <label for="master" class="block text-sm">Analisis:</label>
                    <select class="form-input inline-block w-auto" name="master" onchange="this.form.submit()" id="master">
                      <?php foreach ($master_indikator as $master): ?>
                        <option value="<?= $master['id']?>" <?= selected($list_indikator['0']['id_master'], $master['id'])?>><?= "{$master['master']} ({$master['tahun']})"?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </form>
              <?php endif; ?>
              <div class="table-responsive content py-2">
                <table>
                  <tr>
                    <td width="200">Pendataan </td>
                    <td width="20"> :</td>
                    <td><?= $list_indikator['0']['master']; ?></td>
                  </tr>
                  <tr>
                    <td>Subjek </td>
                    <td> : </td>
                    <td><?= $list_indikator['0']['subjek']; ?></td>
                  </tr>
                  <tr>
                    <td>Tahun </td>
                    <td> :</td>
                    <td><?= $list_indikator['0']['tahun']; ?></td>
                  </tr>
                </table>
              </div>
              <h4 class="text-h4 py-2">Indikator</h4>
              <div class="table-responsive content">
                <table>
                  <?php foreach ($list_indikator as $data): ?>
                    <tr>
                      <td><?= $data['nomor'].'.'; ?>
                      <td><a href="<?= site_url("jawaban_analisis/$data[id]/$data[subjek_tipe]/$data[id_periode]"); ?>" class="font-semibold"><?= $data['indikator']?></a></td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
              <?php else : ?>
                <?php foreach ($list_indikator AS $data): ?>
                  <a href="<?= site_url("data_analisis/$data[id]/$data[subjek_tipe]/$data[id_periode]"); ?>"><?= $data['indikator']?></a>
                  <div class="table-responsive">
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
                <?php endforeach; ?>
            <?php endif ?>
          <?php else: ?>
          <p class="py-3">Data tidak tersedia</p>
        <?php endif; ?>
      <?php endif; ?>
    </main>
    <div class="lg:w-1/3 w-full">
      <?php $this->load->view($folder_themes .'/partials/sidebar') ?>
    </div>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
  <script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>