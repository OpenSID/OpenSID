<link type='text/css' href="<?= base_url()?>assets/front/css/slider.css" rel='Stylesheet' />
<script type="text/javascript" src= "<?= base_url()?>assets/front/js/jquery.cycle2.min.js" type= "text/javascript"></script>
<script type="text/javascript" src= "<?= base_url()?>assets/front/js/jquery.cycle2.caption2.min.js" type= "text/javascript"></script>
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
<div class="modal-body" id="maincontent">
	<div class="box box-warning box-solid">

		<div class="box-body">
			<div id="aparatur_desa" class="cycle-slideshow"
				data-cycle-pause-on-hover=true
				data-cycle-fx=scrollHorz
				data-cycle-timeout=1000
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
						<img src="<?php echo AmbilFoto($data['foto'],"besar") ?>"
							data-cycle-title="<span class='cycle-overlay-title'><?= $data['nama'] ?></span>"
							data-cycle-desc="<?= $data['jabatan'] ?>"
						>
					<?php else: ?>
					<img src="<?= base_url("assets/files/user_pict/kuser.png") ?>"
						data-cycle-title="<span class='cycle-overlay-title'><?= $data['nama'] ?></span>"
						data-cycle-desc="<?= $data['jabatan'] ?>"
					>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
