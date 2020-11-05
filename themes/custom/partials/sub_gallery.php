<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2 class="content__heading">Galeri Album <a href="<?= site_url('first/gallery') ?>" class="gallery__link"><?= $parrent['nama'] ?></a></h2>
<hr class="--mb-2 --mt-2">
<section class="gallery">
	<?php if($gallery) : ?>
		<ul class="gallery__list">
			<?php foreach($gallery as $album) : ?>
				<?php if(is_file(LOKASI_GALERI . "kecil_" . $album['gambar'])) : ?>
					<?php $link = AmbilGaleri($album['gambar'],'sedang') ?>
					<li class="gallery__item">
						<a href="<?= $link ?>" data-fancybox="images" data-caption="<?= $data['nama'] ?>">
							<img src="<?= AmbilGaleri($album['gambar'],'kecil') ?>" alt="<?= $album['nama'] ?>" class="gallery__image" title="<?= $album['nama'] ?>">
						</a>
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
</section>