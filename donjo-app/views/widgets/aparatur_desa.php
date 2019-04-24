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
<div class="box box-warning box-solid">

	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-user"></i> Aparatur <?= ucwords($this->setting->sebutan_desa)?></h3>
	</div>
	<div class="box-body">
		<div id="aparatur_desa" class="cycle-slideshow"
			data-cycle-pause-on-hover=true
			data-cycle-fx=scrollHorz
			data-cycle-timeout=3000
			data-cycle-caption-plugin=caption2
			data-cycle-overlay-fx-out="slideUp"
			data-cycle-overlay-fx-in="slideDown"
			>
			<div class="cycle-caption"></div>
			<div class="cycle-overlay"></div>

		  <?php foreach($aparatur_desa as $data) : ?>
		  	<?php if(AmbilFoto($data['foto'],"besar") AND is_file(LOKASI_USER_PICT.$data['foto'])) : ?>
					<img src="<?php echo AmbilFoto($data['foto'],"besar") ?>"
						data-cycle-title="<span class='cycle-overlay-title'><?= $data['pamong_nama'] ?></span>"
						data-cycle-desc="<?= $data['jabatan'] ?>"
					>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>