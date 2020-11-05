<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $abstract = potong_teks($headline['isi'], 180); ?>
<?php $url = site_url('artikel/'.buat_slug($headline)); ?>
<?php $image = ($headline['gambar'] && is_file(LOKASI_FOTO_ARTIKEL.'kecil_'.$headline['gambar'])) ? 
	AmbilFotoArtikel($headline['gambar'],'kecil') :
	base_url($this->theme_folder.'/'.$this->theme .'/assets/images/placeholder.png') ?>

<section class="headline">
	<div class="headline__caption">
		<h4 class="headline__title"><a href="<?= $url ?>"><?= $headline['judul'] ?></h4>
		<div class="headline__description">
			<p><?= $abstract ?>...</p>
		</div>
		<a href="<?= $url ?>" class="headline__button">Selengkapnya <i class="fa fa-chevron-right headline__button__icon"></i></a>
	</div>
	<div class="headline__thumbnail">
		<img src="<?= $image ?>" alt="<?= $headline['judul'] ?>" class="headline__image">
	</div>
</section>