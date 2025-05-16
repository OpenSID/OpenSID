<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="hidelogo">
	<div class="hidelogo-backg hover-height">
		<img src="<?= $latar_website ?>"/>
	</div>	
	<div class="hidelogo-color bgcolor-1"></div>
	<div class="hidelogo-inner flexcenter">
		<div>
		<?php $weblogo = 'desa/logo.gif'; ?>
		<?php if(is_file($weblogo)) : ?>
			<img src="<?= base_url('desa/logo.gif')?>">
		<?php else : ?>
			<img src="<?= gambar_desa($desa['logo']);?>"/>
		<?php endif; ?>
		<h2 class="hover-height"><?= ucwords($this->setting->website_title); ?></h2>
		<h1 class="hover-height"><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>	
		<h3 class="hover-height"><?=$desa['nama_kecamatan']?>, <?=$desa['nama_kabupaten']?></h3>
		</div>
	</div>
</div>
<div class="hideicon">
	<div class="hideicon-inner">
	<?php $this->load->view("$folder_themes/plus/icon"); ?>
	</div>
</div>