<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="bloading">
	<div>
	<div class="outer">
		<div class="logo-loading">
		<?php $weblogo = 'desa/logo.gif'; ?>
		<?php if(is_file($weblogo)) : ?>
			<img class="logoloader" src="<?= base_url('desa/logo.gif')?>">
		<?php else : ?>
			<img src="<?= gambar_desa($desa['logo']);?>"/>
		<?php endif; ?>
		</div>
		<div class="infinityChrome">
		  <div></div>
		  <div></div>
		  <div></div>
		</div>
		<div class="infinity">
		  <div> <span></span></div>
		  <div> <span></span></div>
		  <div> <span></span></div>
		</div>	
		<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="goo-outer">
		  <defs>
			<filter id="goo">
			  <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur"></feGaussianBlur>
			  <feColorMatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
			  <feBlend in="SourceGraphic" in2="goo"></feBlend>
			</filter>
		  </defs>
		</svg>
	</div>
	<h1 class="color-2"><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
	<p class="color-2"><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?><br/><?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?> - <?= ucwords(($desa['nama_propinsi']) ? ' ' . $desa['nama_propinsi'] : ''); ?></p>
	</div>
</div>	