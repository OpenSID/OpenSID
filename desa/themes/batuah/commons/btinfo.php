<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="btinfo-aktivasi">
	<div class="btinfo flexcenter bgcolor-1">
		<div class="batuah-row">
			<div class="batuah-left bgcolor-3 flexcenter">
				<div>	
				<div class="batuah-padding">
				<img src="<?= base_url("$this->theme_folder/$this->theme/images/pngfile/opendesa-white.png") ?>"/>
				<h3>Tema Batuah belum diaktifkan... !</h3>
				<h2>Untuk Aktivasi silahkan hubungi OPENDESA</h2>
				</div>	
				</div>	
			</div>	
			<div class="batuah-right flexcenter">
				<div>	
				<div class="batuah-padding">
				<div style="position:relative;overflow:hidden;">
				<?php if(IS_PREMIUM) : ?>
					<?php $file = __DIR__ . 'desa/pengaturan/images/latar_website.jpg'; ?>
					<?php if(is_file($file)) : ?>
						<img style="width:100%;height:auto;display:block;" src="<?= base_url('desa/pengaturan/images/latar_website.jpg') ?>"/>
					<?php else: ?>
						<img style="width:100%;height:auto;display:block;" src="<?= asset($latar_website ?: 'assets/front/css/images/latar_website.jpg?v', false); ?>"/>
					<?php endif; ?>
				<?php else: ?>
					<?php $file = __DIR__ . 'desa/pengaturan/images/latar_website.jpg'; ?>
					<?php if(is_file($file)) : ?>
					<img style="width:100%;height:auto;display:block;" src="<?= base_url('desa/pengaturan/images/latar_website.jpg') ?>"/>
					<?php else: ?>
					<img style="width:100%;height:auto;display:block;" src="<?= base_url($latar_website ? $latar_website : 'assets/front/css/images/latar_website.jpg'); ?>"/>
					<?php endif; ?>
				<?php endif; ?>
				<div class="batuah-absolute flexcenter">
					<div>
					<img src="<?= gambar_desa($desa['logo']);?>"/>
					<h3><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h3>
					<p><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?><br/><?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?> - <?= ucwords(($desa['nama_propinsi']) ? ' ' . $desa['nama_propinsi'] : ''); ?></p>
					</div>
				</div>	
				</div>	
				</div>
				</div>	
			</div>	
		</div>	
	</div>
</div>