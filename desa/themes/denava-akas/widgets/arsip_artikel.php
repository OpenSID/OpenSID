<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="head-widget flexleft bg-grey-dark2"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/><?= $judul_widget ?></div>
<div class="widget-height bg-white border-grey-soft">
	<div class="widgetscroll">
		<div class="relative-row p-10">
			<div class="head-small border-grey-soft"><h1>Populer</h1></div>
			<div class="width-default">
				<?php foreach (array('populer' => 'arsip_populer') as $jenis => $jenis_arsip) : ?>
					<?php foreach ($$jenis_arsip as $arsip): ?>
						<div class="arsip-right">
							<a href="<?= site_url('artikel/'.buat_slug($arsip))?>">
								<div class="arsip-right-image">
									<?php if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])): ?>
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])?>" alt=""/>
									<?php else: ?>
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
										<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt=""/></div>
									<?php endif;?>
								</div>
								<div class="arsip-right-title" style="text-align:left;"><?= hit($arsip['hit']); ?><br/><b><?= potong_teks($arsip['judul'], 50); ?>...</b></div>
							</a>
						</div>
					<?php endforeach; ?>
				<?php endforeach ?>
			</div>
			<div class="head-small border-grey-soft" style="margin-top:20px;"><h1>Terbaru</h1></div>
			<div class="width-default">
				<?php foreach (array('terkini' => 'arsip_terkini') as $jenis => $jenis_arsip) : ?>
					<?php foreach ($$jenis_arsip as $arsip): ?>
						<div class="arsip-right">
							<a href="<?= site_url('artikel/'.buat_slug($arsip))?>">
								<div class="arsip-right-image">
									<?php if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])): ?>
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])?>" alt=""/>
									<?php else: ?>
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
										<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt=""/></div>
									<?php endif;?>
								</div>
								<div class="arsip-right-title" style="text-align:left;"><?= hit($arsip['hit']); ?><br/><b><?= potong_teks($arsip['judul'], 50); ?>...</b></div>
							</a>
						</div>
					<?php endforeach; ?>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
