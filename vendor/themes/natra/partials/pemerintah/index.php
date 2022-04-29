<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style>
  .image {
    width: 80%;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
    transition: all 500ms ease;
    padding: 5px;
  }

  .pamong {
    padding: 20px;
  }

  .card {
    background-color: darkgrey;
    padding: 5px;
    border-radius: 10px;
  }

  .line {
    margin: 5px 0;
    height: 1px;
  }
</style>
<div class="single_category wow fadeInDown">
  <h2><span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Pemerintah <?= ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa']) ?></span></h2>
</div>

<div class="box box-primary">
  <div class="box-body">
    <br>
    <div class="row">
      <?php if ($pemerintah): ?>
        <?php foreach ($pemerintah as $data): ?>
          <div class="col-sm-3 pamong">
            <div class="card text-center">
              <img width="auto" class="rounded-circle image" src="<?= AmbilFoto($data['foto'], '', $data['id_sex']) ?>" alt="Foto <? $data['nama'] ?>"/>
              <hr class="line">
              <b>
                <?= $data['nama'] ?><br>
                <?= $data['jabatan'] ?><br>
                <?php if ($this->setting->tampilkan_kehadiran && $data['status_kehadiran'] == 'hadir') : ?>
                  <span class='label label-success'>Hadir</span>
                <?php else: ?>
                  <br>
                <?php endif ?>
            </div>
          </div>
        <?php endforeach ?>
      <?php else: ?>
        <h5>Pemerintah <?= ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa']) ?> tidak tersedia.</h5>
      <?php endif ?>
    </div>
  </div>
</div>