<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $abstrak_headline = potong_teks($headline['isi'], 250); ?>
<?php $url = site_url('artikel/'.buat_slug($headline)); ?>
<?php $image = ($headline['gambar'] && is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$headline['gambar'])) ?
  AmbilFotoArtikel($headline['gambar'],'sedang') :
  gambar_desa($desa['logo']);
?>

<?php if($headline) : ?>
  <div class="lg:h-[350px] h-44 overflow-hidden rounded-lg relative">
    <figure class="lg:h-[350px] h-44">
      <img src="<?= $image ?>" alt="<?= $headline['judul'] ?>" class=" lg:h-[350px] h-44 w-full object-cover object-center">
    </figure>
    <div class="absolute bg-black bg-opacity-60 bottom-0 left-0 right-0 text-white group">
      <a href="<?= $url ?>" class="font-bold text-h5 block py-4 px-5 group-hover:py-5 transition-all duration-300"><?= $headline['judul'] ?></a>
    </div>
  </div>
<?php endif ?>