<link type='text/css' href="<?= base_url()?>assets/css/slider.css" rel='Stylesheet' />
<script type="text/javascript" src= "<?= base_url()?>assets/js/jquery.cycle2.caption2.min.js" type= "text/javascript"></script>
<style type="text/css">
	#aparatur_desa .cycle-pager span
	{
		height: 10px;
		width: 10px;
	}
	.cycle-slideshow
	{
		max-height: none;
		margin-bottom: 0px;
		border: 0px;
	}
</style>

<!-- widget Aparatur Desa -->
<div class="single_bottom_rightbar">
    <h2 class="box-title"><i class="fa fa-user"></i> Aparatur <?= ucwords($this->setting->sebutan_desa)?></h2>
	<div class="box-body"><div class="content_middle_middle">
		<div id="aparatur_desa" class="cycle-slideshow"
			data-cycle-pause-on-hover=true
			data-cycle-fx=scrollHorz
			data-cycle-timeout=3000
			data-cycle-caption-plugin=caption2
			data-cycle-overlay-fx-out="slideUp"
			data-cycle-overlay-fx-in="slideDown"
			>

			<?php if ($this->web_widget_model->get_setting('aparatur_desa', 'overlay') == true): ?>
				<div class="cycle-caption"></div>
				<div class="cycle-overlay"></div>
			<?php else: ?>
			  <span class="cycle-pager"></span>  <!-- Untuk membuat tanda bulat atau link pada slider -->
			<?php endif; ?>

		  <?php foreach($aparatur_desa as $data) : ?>
		  	<?php if(AmbilFoto($data['foto'],"besar") AND is_file(LOKASI_USER_PICT.$data['foto'])) : ?>
					<img src="<?= AmbilFoto($data['foto'],"besar") ?>"
						data-cycle-title="<span class='cycle-overlay-title'><?= $data['nama'] ?></span>"
						data-cycle-desc="<?= $data['jabatan'] ?>"
					>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div></div>
</div>
