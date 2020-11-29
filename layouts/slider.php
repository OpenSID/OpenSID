<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="slick_slider" style="margin-bottom:5px;">
	<?php foreach ($slider_gambar['gambar'] as $gambar) : ?>
		<?php $file_gambar = $slider_gambar['lokasi'] . 'sedang_' . $gambar['gambar']; ?>
		<?php if(is_file($file_gambar)) : ?>
			<div class="single_iteam">
				<style type="text/css">
					.slick_slider img {
						width: 100%;
					}

					.slick_slider, .cycle-slideshow {
						max-height: 300px;
						border: 5px solid #e5e5e5;
						display: block;
						position: relative;
						/*margin: 0px auto;*/
						overflow: hidden;
					}
				</style>
				<img class="tlClogo" src="<?= base_url($file_gambar); ?>"
					<?php if ($slider_gambar['sumber'] != 3): ?>
					onclick="location.href='<?= site_url().'artikel/'.buat_slug($gambar); ?>'" <?php endif; ?>
				>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<script>
	$('.tlClogo').bind('contextmenu', function(e) {
		return false;
	});
</script>
