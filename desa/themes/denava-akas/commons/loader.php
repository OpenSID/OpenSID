<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (theme_config('loader', true)) : ?>
<div id="loading">
	<div class="load-container">
		<div class="loader bg-gradient-hor">
			<div class="loader-inner">
				<img src="<?= gambar_desa($desa['logo']);?>" alt=""/>
				<h1><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
				<h2><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?><br/><?= ucwords("Prov. ".$desa['nama_propinsi'])?></h2>
			</div>
		</div>
		<div class="loading">
			<div class="loading-inner">
				<h2>Loading</h2>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
