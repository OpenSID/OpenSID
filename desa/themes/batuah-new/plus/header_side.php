<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="header-side">
	<div class="logoweb smooth-trans" style="background-image: url('<?= $latar_website ?>')">
	<a href="<?= site_url(); ?>">
		<div class="logocenter smooth-trans flexcenter">
			<div>
			<img class="smooth-trans" src="<?= gambar_desa($desa['logo']);?>"/>
			<h1 class="smooth-trans"><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
			<p class="smooth-trans"><?= ucwords(($desa['nama_kecamatan']) ? ' ' . $desa['nama_kecamatan'] : ''); ?>, <?= ucwords(($desa['nama_kabupaten']) ? ' ' . $desa['nama_kabupaten'] : ''); ?></p>
			</div>
		</div>
	</a>	
	</div>
	<div class="menu-section smooth-trans">
		<div class="scroll-area" id="scrollstyle">
			<?php $this->load->view("$folder_themes/plus/icon"); ?>
			<div class="desktop-only"><?php $this->load->view("$folder_themes/partials/menu_head"); ?></div>
		</div>
	</div>
</div>
