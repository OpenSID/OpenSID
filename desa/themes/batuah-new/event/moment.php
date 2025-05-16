<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $banner_aktif = theme_config('banner_aktif', ''); ?>
<?php $judul = theme_config('judul', ''); ?>
<?php $sub_judul = theme_config('sub_judul', ''); ?>
<?php $formal = theme_config('formal', ''); ?>
<?php $ucapan = theme_config('ucapan', ''); ?>
<?php $deskripsi = theme_config('deskripsi', ''); ?>

<?php if (theme_config('banner_aktif') == 'true'): ?>
	
	<div class="col-default margin-top-10">		
		<div class="event-inner bggrad-color1">
			<div class="merdeka">
			<div class="merahputih" style="background-image:url(<?= base_url("$this->theme_folder/$this->theme/assets/images/animation-color.svg") ?>);"></div>
			</div>
			<div class="event-row">
			<?php if (theme_config('formal') == 'true'): ?>
				<div class="logo-formal">
					<img src="<?= gambar_desa($desa['logo']);?>"/>
				</div>
			<?php endif; ?>
			<div class="event-title">
				<div>
				<?php if (theme_config('formal') == 'true'): ?>
					<h2>Pemerintah <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?><br/><font style="font-weight:500;font-size:90%;padding-top:5px;">mengucapkan,</font></h2>
				<?php else : ?>	
					<?php if (theme_config('ucapan')) : ?>
					<h2><?=$ucapan?></h2>
					<?php endif; ?>
				<?php endif; ?>
				<?php if (theme_config('judul')) : ?>
					<h1><?=$judul?></h1>
				<?php endif; ?>
				<?php if (theme_config('sub_judul')) : ?>
					<h2><?=$sub_judul?></h2>
				<?php endif; ?>
				<?php if (theme_config('deskripsi')) : ?>
					<p><?=$deskripsi?></p>
				<?php endif; ?>
				</div>
			</div>
			</div>
		</div>
	</div>

<?php endif ?>