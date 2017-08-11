<link type='text/css' href="<?php echo base_url()?>assets/css/slider.css" rel='Stylesheet' />
<script type="text/javascript">
	$(document).ready(function() {
    $('#aparatur_desa').cycle({
			pauseOnHover: true
		});
	});
</script>
<style type="text/css">
	#aparatur_desa img {width: 100%;}
	#aparatur_desa .cycle-pager span {
		height: 7px;
		width: 7px;
		margin: 0 3px;
	}
</style>

<!-- widget Aparatur Desa -->
<div class="box box-warning box-solid">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-user"></i> Aparatur <?php echo ucwords($this->setting->sebutan_desa)?></h3>
	</div>
	<div class="box-body">

		<div id="aparatur_desa">
		  <span class="cycle-pager"></span>  <!-- Untuk membuat tanda bulat atau link pada slider -->
		  <?php foreach($aparatur_desa as $data) : ?>
		  	<?php if(AmbilFoto($data['foto'],"besar") AND is_file(LOKASI_USER_PICT.$data['foto'])) : ?>
					<img src="<?php echo AmbilFoto($data['foto'],"besar") ?>" alt="<?php echo $data['jabatan'] ?>">
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>