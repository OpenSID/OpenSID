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
    <?php 
        $jumlah = 0;
        foreach ($gallery as $album): ?>
        <?php if (file_exists(LOKASI_GALERI . "sedang_" . $album['gambar']) || $album['jenis'] == 2): 
          $gambar = $album['jenis'] == 2 ? $album['gambar'] : AmbilGaleri($album['gambar'], 'kecil'); 
          $jumlah++;
        ?>
        <a href="<?= site_url("galeri/{$album['id']}") ?>" class="w-full bg-gray-100 block relative">
          <img src="<?= $gambar ?>" alt="<?= $album['nama'] ?>" class="h-44 w-full object-cover object-center" title="<?= $album['nama'] ?>">
          <p class="py-2 text-center block"><?= $album['nama'] ?></p>
        </a>
      <?php endif ?>
    <?php endforeach ?>

    <?php if ($jumlah == 0): ?>
      <div class="alert text-primary-100">Maaf album galeri belum tersedia!</div>
    <?php endif ?>
  </div>
  <?php else : ?>
    <div class="alert text-primary-100">Maaf album galeri belum tersedia!</div>
<?php endif ?>