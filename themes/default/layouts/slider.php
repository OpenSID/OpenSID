<link type='text/css' href="<?php echo base_url()?>assets/css/slider.css" rel='Stylesheet' />

<div class="box">
	<div class="cycle-slideshow">
    <span class="cycle-prev"><img src="<?php echo base_url()?>assets/images/back_button.png" alt="Back"></span> <!-- Untuk membuat tanda panah di kiri slider -->
    <span class="cycle-next"><img src="<?php echo base_url()?>assets/images/next_button.png" alt="Next"></span><!-- Untuk membuat tanda panah di kanan slider -->
    <span class="cycle-pager"></span>  <!-- Untuk membuat tanda bulat atau link pada slider -->
    <?php foreach($slider_photos as $foto): ?>
			<img src="<?php echo base_url()?>desa/upload/galeri/<?php echo 'sedang_'.$foto['gambar']?>" alt="Logo dan kata kata">
    <?php endforeach; ?>
	</div>
</div>