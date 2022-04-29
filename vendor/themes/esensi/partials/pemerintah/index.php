<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Pemerintah <?= NAMA_DESA ?></li>
  </ol>
</nav>
<h1 class="text-h2">Pemerintah <?= NAMA_DESA ?></h1>
<?php if($pemerintah) : ?>
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-5">
    <?php foreach($pemerintah as $data) : ?>
      <div class="space-y-3">
        <img class="h-44 w-full object-cover object-center bg-gray-300 dark:bg-gray-600" src="<?= AmbilFoto($data['foto'], '', $data['id_sex']) ?>" alt="Foto <? $data['nama'] ?>" />

        <div class="space-y-1 text-sm text-center z-10">
          <span class="text-h6"><?= $data['nama'] ?></span>
          <span class="block"><?= $data['jabatan'] ?></span>
          <?php if ($data['pamong_niap']) : ?>
            <span class="block"><?= $this->setting->sebutan_nip_desa ?> : <?= $data['pamong_niap'] ?></span>
          <?php endif ?>
          <?php if ($data['status_kehadiran'] == 'hadir'): ?>
            <span class="btn btn-primary w-auto mx-auto inline-block">Hadir</span>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  </div>
  <?php else: ?>
    <p class="py-3">Pemerintah <?= NAMA_DESA ?> tidak tersedia.</p>
<?php endif ?>