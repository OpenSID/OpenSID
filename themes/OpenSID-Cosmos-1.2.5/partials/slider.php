<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($slider_gambar['gambar']) : ?>
	<section class="slider">
		<div class="owl-carousel owl-theme" id="slides">
			<?php foreach($slider_gambar['gambar'] as $data) : ?>
				<?php $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar']; ?>
				<?php if(is_file($img)) : ?>
					<div class="item">
						<img data-src="<?= base_url($img) ?>" alt="<?= $data['judul'] ?>" class="img-fluid owl-lazy">
					</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</section>
<?php endif ?>