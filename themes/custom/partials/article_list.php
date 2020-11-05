<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $url = site_url('artikel/'.buat_slug($article)) ?>
<?php $abstract = potong_teks(strip_tags($article['isi']), 200) ?>
<?php $image = ($article['gambar'] && is_file(LOKASI_FOTO_ARTIKEL.'kecil_'.$article['gambar'])) ? 
	AmbilFotoArtikel($article['gambar'],'kecil') :
	base_url($this->theme_folder.'/'.$this->theme .'/assets/images/placeholder.png');
?>
<li class="content__item">
	<a href="<?= $url ?>" class="content__thumbnail">
		<img src="<?= $image ?>" alt="<?= $article['judul'] ?>" class="content__image">
	</a>
	<div class="content__caption">
		<h4 class="content__title"><a href="<?= $url ?>"><?= $article['judul'] ?></a></h4>
		<div class="content__meta">
			<span class="content__meta__item"><i class="fa fa-calendar content__meta__icon"></i> <?= tgl_indo($article['tgl_upload']) ?></span>
			<span class="content__meta__item"><i class="fa fa-user content__meta__icon"></i> <?= $article['owner'] ?></span>
			<?php if($article['kategori']) : ?>
				<span class="content__meta__item"><i class="fa fa-tag content__meta__icon"></i> <a href="<?= site_url('first/kategori/'.$article['kat_slug']) ?>" class="content__link"><?= $article['kategori'] ?></a></span>
			<?php endif ?>
		</div>
		<div class="content__description">
			<p><?= $abstract ?>...</p>
		</div>
	</div>
</li>