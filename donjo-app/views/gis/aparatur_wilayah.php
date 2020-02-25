<link type='text/css' href="<?= base_url()?>assets/front/css/slider.css" rel='Stylesheet' />
<script type="text/javascript" src= "<?= base_url()?>assets/front/js/jquery.cycle2.min.js" type= "text/javascript"></script>
<script type="text/javascript" src= "<?= base_url()?>assets/front/js/jquery.cycle2.caption2.min.js" type= "text/javascript"></script>
<style type="text/css">
	#aparatur_wilayah .cycle-pager span
	{
		height: 5px;
		width: 5px;
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
					<div id="aparatur_wilayah" class="cycle-slideshow"
						data-cycle-fx=scrollHorz
						data-cycle-timeout=3000
						data-cycle-caption-plugin=caption2
						data-cycle-overlay-fx-out="slideUp"
						data-cycle-overlay-fx-in="slideDown"
						>

							<div class="cycle-caption"></div>
							<div class="cycle-overlay"></div>

							<?php if ($penduduk['foto']): ?>
								<img src="<?php echo AmbilFoto($penduduk['foto'],"besar") ?>"
									data-cycle-title="<span class='cycle-overlay-title'><?= $penduduk['nama'] ?></span>"
									data-cycle-desc="<?= $jabatan ?>"
								>
							<?php else: ?>
							<img src="<?= base_url("assets/files/user_pict/kuser.png") ?>"
								data-cycle-title="<span class='cycle-overlay-title'><?= $penduduk['nama'] ?></span>"
								data-cycle-desc="<?= $jabatan ?>"
							>
							<?php endif; ?>
					</div>
				</div>

				
			</div>
</div>
