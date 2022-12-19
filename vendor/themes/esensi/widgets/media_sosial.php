<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title"><i class="fas fa-globe mr-1"></i><?= $judul_widget ?></h3>
  </div>
  <div class="box-body flex gap-2">
    <?php foreach ($sosmed As $data): ?>
    <?php if (!empty($data["link"])): ?>
    <a href="<?= $data['link']?>" target="_blank">
      <img src="<?= base_url().'assets/front/'.$data['gambar'] ?>" alt="<?= $data['nama'] ?>"
        style="width:50px;height:50px;" />
    </a>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>