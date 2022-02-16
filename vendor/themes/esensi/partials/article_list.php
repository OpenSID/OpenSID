<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $url = site_url('artikel/'.buat_slug($post)) ?>
<?php $abstract = potong_teks(strip_tags($post['isi']), 250) ?>
<?php $image = ($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$post['gambar'])) ?
  AmbilFotoArtikel($post['gambar'],'sedang') :
  gambar_desa($desa['logo']);
?>

<div class="max-w-full w-full bg-white shadow rounded-lg p-5 border flex gap-3 lg:gap-5">
  <figure class="h-10 w-12 lg:h-24 lg:w-36 overflow-hidden flex-shrink-0 <?php $image === gambar_desa($desa['logo']) and print('flex items-center') ?>">
    <img src="<?= $image ?>" alt="<?= $post['judul'] ?>" class="<?php $image !== gambar_desa($desa['logo']) and print('h-10 w-12 lg:h-24 lg:w-36 object-cover object-center') ?> max-w-full mx-auto">
  </figure>
  <div class="flex flex-col justify-between gap-3">
    <a href="<?= $url ?>" class="text-h5 hover:text-primary-100"><?= $post['judul'] ?></a>
    <p class="line-clamp-3"><?= $abstract ?></p>
    <ul class="inline-flex gap-x-5 gap-y-3 text-xs lg:text-sm text-gray-500 flex-wrap">
      <li><i class="fas fa-calendar-alt mr-1 text-primary-100"></i> <?= tgl_indo($post['tgl_upload']) ?></li>
      <li><i class="fas fa-user mr-1 text-primary-100"></i> <?= $post['owner'] ?></li>
      <?php if($post['kategori']) : ?>
        <li><i class="fas fa-bookmark mr-1 text-primary-100"></i> <?= $post['kategori'] ?></li>
      <?php endif ?>
    </ul>
  </div>
</div>