
<script type="text/javascript">
	function tampil_artikel(id_artikel){
		href = window.location.href;
		first = '/first';
		url = href.substring(0,href.indexOf(first)+first.length)+'/artikel/'+id_artikel;
		window.location = url;
	}
</script>

<link type='text/css' href="<?php echo base_url()?>assets/css/slider.css" rel='Stylesheet' />

<div class="box">
	<div class="cycle-slideshow">
    <span class="cycle-prev"><img src="<?php echo base_url()?>assets/images/back_button.png" alt="Back"></span> <!-- Untuk membuat tanda panah di kiri slider -->
    <span class="cycle-next"><img src="<?php echo base_url()?>assets/images/next_button.png" alt="Next"></span><!-- Untuk membuat tanda panah di kanan slider -->
    <span class="cycle-pager"></span>  <!-- Untuk membuat tanda bulat atau link pada slider -->
    <?php if($slide_artikel_utama) : ?>
	  	<?php foreach ($slide_artikel_utama as $gambar) : ?>
	    	<?php if(is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$gambar['gambar'])) : ?>
			    <img src="<?php echo base_url().LOKASI_FOTO_ARTIKEL.'sedang_'.$gambar['gambar']?>" data-artikel="<?php echo $gambar['id']?>" onclick="tampil_artikel($(this).data('artikel'));">
			   <?php endif; ?>
		   <?php endforeach; ?>
		<?php else : ?>
	    <?php foreach($slide_galeri as $foto): ?>
				<img src="<?php echo base_url().LOKASI_GALERI.'sedang_'.$foto['gambar']?>">
	    <?php endforeach; ?>
	  <?php endif; ?>
	</div>
</div>