<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<header class="header">
	<div class="header__group">
		<a href="<?= site_url('first') ?>" class="header-brand">
			<img src="<?= gambar_desa($desa['logo']) ?>" alt="Logo <?= ucfirst($this->setting->sebutan_desa).' '.ucwords($desa['nama_desa']) ?>" class="header-brand__logo">
			<div class="header-brand__group">
				<h1 class="header-brand__title">Sistem Informasi <?= ucfirst($this->setting->sebutan_desa).' '.ucwords($desa['nama_desa']) ?></h1>
				<span class="header-brand__subtitle"><?= ucfirst($this->setting->sebutan_kecamatan_singkat) ?> <?= ucwords($desa['nama_kecamatan']) ?>, <?= ucfirst($this->setting->sebutan_kabupaten_singkat) ?> <?= ucwords($desa['nama_kabupaten']) ?>, Prov. <?= ucwords($desa['nama_propinsi']) ?></span>
			</div>
		</a>
		<button class="button button--menu"><i class="fa fa-list"></i> <span>MENU</span></button>
	</div>
	<?php $this->load->view($folder_themes .'/commons/nav') ?>
</header>