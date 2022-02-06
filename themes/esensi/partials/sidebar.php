<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<aside class="space-y-5 sidebar">
  <form action="<?= site_url() ?>" role="form" class="relative">
    <i class="fas fa-search absolute top-1/2 left-0 transform -translate-y-1/2 z-10 px-3 text-gray-500"></i>
    <input type="text" name="cari" class="form-input px-10 w-full h-12 bg-white relative inline-block" placeholder="Cari...">
  </form>
  <?php foreach($w_cos as $widget) : ?>
    <?php if ($widget["jenis_widget"] == 1): ?>
      <div class="shadow rounded-lg bg-white overflow-hidden">
        <?php $this->load->view($folder_themes .'/widgets/'.$widget['isi']) ?>
      </div>
      <?php elseif($widget['jenis_widget'] == 2) : ?>
        <div class="shadow rounded-lg bg-white overflow-hidden">
          <div class="box-header">
            <h3 class="box-title"><?= strip_tags($widget['judul']) ?></h3>
          </div>
          <div class="box-body">
            <?php include($widget['isi']) ?>
          </div>
        </div>
      <?php else : ?>
        <div class="shadow rounded-lg bg-white overflow-hidden">
          <div class="box-header">
            <h3 class="box-title"><?= strip_tags($widget['judul']) ?></h3>
          </div>
          <div class="box-body">
              <?= html_entity_decode($widget['isi']) ?>
          </div>
        </div>
    <?php endif ?>
  <?php endforeach ?>
</aside>