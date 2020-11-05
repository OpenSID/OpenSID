<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if($slider_gambar['gambar']) : ?>
	<section class="slideshow --relative">
		<ul class="slider__list slick-featured">
			<?php foreach($slider_gambar['gambar'] as $data) : ?>
				<?php $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar']; ?>
				<?php if(is_file($img)) : ?>
					<li class="slider__item slider__item--original">
						<img src="<?= base_url($img) ?>" alt="<?= $data['judul'] ?>" class="slider__image">
						<?php if($data['judul']) : ?>
							<div class="slider__caption">
								<figcaption><?= $data['judul'] ?></figcaption>
							</div>
						<?php endif ?>
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
		<ul class="slider__list slick-thumbnail">
			<?php foreach($slider_gambar['gambar'] as $data) : ?>
				<?php $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar']; ?>
				<?php if(is_file($img)) : ?>
					<li class="slider__item slider__item--thumbnail">
						<img src="<?= base_url($img) ?>" alt="<?= $data['judul'] ?>" class="slider__image">
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
		<button class="slider__arrow slider__arrow--left"><i class="fa fa-chevron-left"></i></button>
		<button class="slider__arrow slider__arrow--right"><i class="fa fa-chevron-right"></i></button>
	</section>
<?php endif ?>