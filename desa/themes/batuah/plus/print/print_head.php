<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="print-header flexleft">
	<img src="<?= gambar_desa($desa['logo']);?>"/>
	<div>
	<h2><?= ucwords($this->setting->website_title); ?></h2>
	<h1><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
	<h2><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$desa['nama_kecamatan']?>, <?=ucwords($this->setting->sebutan_kabupaten)?> <?=$desa['nama_kabupaten']?> - <?=$desa['nama_propinsi']?></h2>
	</div>
</div>