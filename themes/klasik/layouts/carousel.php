<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!--
	Untuk bisa menghentikan scroller, perlu menambah plugin jquery.pause
	dan mengubah jquery.cycle2.carousel.js, mengikuti contoh di
	https://github.com/malsup/cycle2/issues/178
 -->
<script src="<?= base_url()?>assets/front/js/jquery.pause.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
	    $('.carousel').cycle({
			fx: 'carousel',
			speed: 5000,
			timeout: '1',
			easing: 'linear',
			pauseOnHover: true
		});
	});
</script>
<div class="carousel">
  <?php foreach ($slide_artikel as $gambar) : ?>
		<?php $file_gambar = LOKASI_FOTO_ARTIKEL . 'kecil_' . $gambar['gambar']; ?>
  	<?php if (is_file($file_gambar)) : ?>
	    <img src="<?= base_url($file_gambar)?>" onclick="location.href='<?= site_url('artikel/' . buat_slug($gambar)); ?>'">
	   <?php endif; ?>
  <?php endforeach; ?>
</div>