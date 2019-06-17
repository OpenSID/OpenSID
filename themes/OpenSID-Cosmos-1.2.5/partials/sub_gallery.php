<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($gallery) : ?>
	<div class="galeri-wrapper">
		<?php foreach($gallery as $data) : ?>
			<?php if(is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])) : ?>
				<a href="<?= AmbilGaleri($data['gambar'],'sedang') ?>" class="item-foto" data-fancybox="images" data-caption="<?= $data['nama'] ?>">
					<img src="<?= AmbilGaleri($data['gambar'],'kecil') ?>" alt="<?= $data['nama'] ?>" class="img-fluid" title="<?= $data['nama'] ?>">
				</a>
			<?php endif ?>
		<?php endforeach ?>
	</div>
<?php endif ?>