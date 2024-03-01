<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Arsip Artikel</li>
  </ol>
</nav>
<h1 class="text-h2">Arsip Situs Web</h1>
<?php if(count($farsip) > 0) : ?>
  <ol class="divide-y mb-5">
    <?php foreach($farsip AS $data): ?>
      <li class="py-5">
        <span class="fas fa-external-link-alt mr-2"></span>
        <a class="text-h6 transition-all duration-200 hover:text-link" href="<?= site_url('artikel/'.buat_slug($data))?>"><?= $data["judul"]?></a>
        <p class="text-xs lg:text-sm">Diterbitkan pada <?= tgl_indo($data["tgl_upload"])?></p>
        <p class="text-xs lg:text-sm">Oleh: <?= $data["owner"]?></p>
      </li>
    <?php endforeach; ?>
  </ol>
    <?php else: ?>
      <p class="py-5">Belum ada arsip konten web.</p>
<?php endif ?>