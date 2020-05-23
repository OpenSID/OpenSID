<link type='text/css' href="<?= base_url()?>assets/front/css/slider.css" rel='Stylesheet' />
<script>
	$('.cycle-slideshow').cycle();
</script>
<style type="text/css">
	#aparatur_wilayah .cycle-pager span
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
<!-- widget Aparatur Dusun/rw/rt -->
<div class="modal-body" id="maincontent">
	<div class="box box-primary box-solid">
		<div class="box-body">
			<div id="aparatur_wilayah" class="cycle-slideshow">
				<div class="cycle-overlay"></div>
				<?php if ($penduduk['foto']): ?>
					<img src="<?= AmbilFoto($penduduk['foto'])?>"
					data-cycle-title="<span class='cycle-overlay-title'><?= $penduduk['nama'] ?></span>"
					data-cycle-desc="<?= $jabatan ?>">
				<?php else: ?>
					<img src="<?= base_url("assets/files/user_pict/kuser.png") ?>"
					data-cycle-title="<span class='cycle-overlay-title'><?= $penduduk['nama'] ?></span>"
					data-cycle-desc="<?= $jabatan ?>">
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
