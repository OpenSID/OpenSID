<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="head-line">
	<div class="p-15">
		<div class="head-line-flex">
			<div class="image-top">
				<a href="<?= site_url('artikel/'.buat_slug($headline))?>">
					<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar'])): ?>
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($headline['gambar'],'sedang') ?>" alt="">
					<?php else: ?>
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= gambar_desa($desa['kantor_desa'], TRUE)?>" alt=""/>
						<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt=""/></div>
					<?php endif; ?>
				</a>	
			</div>
			<div class="head-line-title">
				<h2><?= $headline['judul'] ?></h2>
				<p><?= potong_teks ($headline['isi'], 140); ?>...</p>
			</div>
		</div>
	</div>
</div>
<a href="<?= site_url('artikel/'.buat_slug($headline))?>">
	<div class="selengkapnya flexright">
		<h3>Selengkapnya...</h3>
	</div>
</a>
