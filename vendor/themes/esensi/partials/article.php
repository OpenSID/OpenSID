<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $post = $single_artikel ?>
<?php $alt_slug = IS_PREMIUM ? 'artikel' : 'first'; ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li><?= $post['kategori'] ? '<a href="'.site_url("{$alt_slug}/kategori/{$post['kat_slug']}").'">'.$post['kategori'].'</a>' : 'Artikel' ?></li>
  </ol>
</nav>

<article>
  <h1 class="text-h2">
    <?= $post['judul'] ?>
  </h1>
  
  <span class="inline-flex flex-wrap gap-x-3 gap-y-2 text-xs lg:text-sm py-2 text-accent-200">
    <span><?= $post['owner'] ?> <i class="fas fa-check text-xs bg-green-500 h-4 w-4 inline-flex items-center justify-center rounded-full text-white"></i></span>
    <span class="before:content-['-'] before:pr-3 before:inline-block"><?= tgl_indo($post['tgl_upload']) ?></span>
    <span class="before:content-['-'] before:pr-3 before:inline-block">Dibaca <?= hit($post['hit']) ?></span>
  </span>
</article>

<div class="content space-y-2 py-4">
  <?php if($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$post['gambar'])) : ?>
    <a href="<?= AmbilFotoArtikel($post['gambar'],'sedang') ?>" class="h-auto block pb-3" data-fancybox="images">
      <figure>
        <img src="<?= AmbilFotoArtikel($post['gambar'],'sedang') ?>" alt="<?= $post['judul'] ?>" class="w-full h-auto">
      </figure>
    </a>
  <?php endif ?>
  <?= $post['isi'] ?>
</div>

<?php for($i = 1; $i <= 3; $i++) : ?>
  <?php if($post['gambar'.$i] && is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$post['gambar'.$i])) : ?>
    <a href="<?= AmbilFotoArtikel($post['gambar'.$i],'sedang') ?>" class="block" data-fancybox="images">
      <figure>
        <img src="<?= AmbilFotoArtikel($post['gambar'.$i],'sedang') ?>" alt="<?= $post['nama'] ?>" class="w-full">
      </figure>
    </a>
  <?php endif ?>
<?php endfor ?>
<?php if($post['dokumen']) : ?>
  <div class="alert alert-info">
    <h4 class="text-h6">Dokumen Lampiran</h4>
    <a href="<?= site_url('first/unduh_dokumen_artikel/'.$post['id']) ?>" class="text-primary-200 text-sm flex space-x-3 pt-2">
      <span class="fas fa-download text-secondary inline-block"></span>
      <span class="hover:text-link"><?= $post['dokumen'] ?></span>
    </a>
  </div>
<?php endif ?>