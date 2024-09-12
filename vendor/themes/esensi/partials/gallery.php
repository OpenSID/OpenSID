<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Galeri</li>
  </ol>
</nav>
<h1 class="text-h2">Album Galeri</h1>

<?php if(count($gallery ?? [])) : ?>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-5 main-content py-4">
    <?php foreach($gallery as $album) : ?>
      <?php if(is_file(LOKASI_GALERI . "kecil_" . $album['gambar'])) : ?>
        <?php $link = IS_PREMIUM ? site_url("galeri/{$album['id']}") : site_url('first/sub_gallery/'.$album['id']) ?>
        <a href="<?= $link ?>" class="w-full bg-gray-100 block relative">
          <img src="<?= AmbilGaleri($album['gambar'],'kecil') ?>" alt="<?= $album['nama'] ?>" class="h-44 w-full object-cover object-center" title="<?= $album['nama'] ?>">
          <p class="py-2 text-center block"><?= $album['nama'] ?></p>
        </a>
      <?php endif ?>
    <?php endforeach ?>
  </div>
  <?php else : ?>
    <div class="alert text-primary-100">Maaf album galeri belum tersedia!</div>
<?php endif ?>