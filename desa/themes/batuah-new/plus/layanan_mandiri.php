<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php if ($this->setting->layanan_mandiri): ?>
	<div class="col-default margin-top-10">
	<div class="mandiri bordergrey">
		<div class="mandiri-backg-image">
			<img src="<?= $latar_website ?>"/>
		</div>
		<div class="mandiri-backg-color bggrad-grey2"></div>
		<div class="rowsame">
			<div class="mandiri-col flexcenter">
				<div class="mandiri-head">
					<div class="hello" style="color:transparent;">Layanan Mandiri</div>
					<div class="hello" style="color:transparent;">Layanan Mandiri</div>
					<div class="hello color-grey3">Layanan Mandiri</div>
					<div class="hello color-1">Layanan Mandiri</div>
				</div>
				<div class="mandiri-vector">
				<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/layanan-intro.png") ?>" />
				</div>
				<style>
				* *, *::before, *::after {animation-play-state: running !important;}
				</style>
			</div>
			<div class="mandiri-col flexcenter">
				<div class="mandiri-title">
				<img src="<?= gambar_desa($desa['logo']);?>"/>
				<div>
				<h2><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h2>
				<p><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$desa['nama_kecamatan']?>, <?=ucwords($this->setting->sebutan_kabupaten)?> <?=$desa['nama_kabupaten']?>, <?=$desa['nama_propinsi']?></p>
				</div>
				</div>
			</div>
			<div class="mandiri-col flexcenter">
			<div class="mandiri-login">
				<div class="dapat-pin flexleft"><svg viewBox="0 0 24 24"><path d="M5,3H19A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3M13,13V7H11V13H13M13,17V15H11V17H13Z"/></svg><p>Hubungi Perangkat <?=ucwords($this->setting->sebutan_desa)?> untuk mendapatkan PIN</p></div>
				<?php if(IS_PREMIUM) : ?>	
					<?php if (theme_config('pintasan_masuk', true)): ?>
					<a href="<?= site_url('layanan-mandiri') ?>" rel="noopener noreferrer" target="_blank">
					<div class="btn mandiri-masuk flexcenter bgcolor-1" style="border:none;box-shadow:none;color:#fff;">
						<svg viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg><p>Masuk</p>
					</div>
					</a>
					<?php endif ?>
				<?php else: ?>
					<a href="<?= site_url('layanan-mandiri') ?>" rel="noopener noreferrer" target="_blank">
					<div class="btn mandiri-masuk flexcenter bgcolor-1" style="border:none;box-shadow:none;color:#fff;">
						<svg viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg><p>Masuk</p>
					</div>
					</a>
				<?php endif ?>
			</div>
			</div>
		</div>
	</div>
	</div>
<?php endif; ?>