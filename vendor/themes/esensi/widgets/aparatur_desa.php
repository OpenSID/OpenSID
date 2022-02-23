<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
  <div class="box-header">
    <h3 class="box-title">
      <i class="fas fa-user"></i>&ensp;<?= $judul_widget ?>
    </h3>
  </div>
  <div class="box-body">
    <div class="owl-carousel">
      <?php foreach($aparatur_desa['daftar_perangkat'] as $data) : ?>
        <div class="relative space-y-2">
          <img src="<?= $data['foto'] ?>" alt="<?= $data['nama'] ?>" class="lg:w-3/4 mx-auto object-cover object-center bg-gray-300">
          <?php if($this->web_widget_model->get_setting('aparatur_desa', 'overlay') == true) : ?>
            <div class="space-y-1 text-sm text-center z-10">
              <span class="text-h6"><?= $data['nama'] ?></span>
              <span class="block"><?= $data['jabatan'] ?></span>
              <span class="block"><?= $this->setting->sebutan_nip_desa ?> : <?= $data['pamong_niap'] ?></span>
            </div>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</div>