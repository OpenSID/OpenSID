<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="module-komentar">
	<div class="head-widget flexleft bg-grey-dark2"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/komentar.svg") ?>" alt=""/><?= $judul_widget ?></div>
	<div class="widget-height border-grey-soft bg-white">
		<div class="widgetscroll">
			<div class="p-10">
				<?php foreach($komen As $data): ?>
					<div class="komentar" style="text-align:left;">
						<div class="komentar-person flexleft">
							<div class="komentar-icon bg-grey-dark2 flexcenter">
								<svg viewBox="0 0 24 24">
									<path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"></path>
								</svg>
							</div>
							<?= $data['owner']; ?>
						</div>
						<a href="<?= site_url('artikel/' . buat_slug($data)); ?>">
							<h3><?= tgl_indo2($data['tgl_upload'])?></h3>
							<p><?= potong_teks ($data['komentar'], 100); ?>...</p>
						</a>
					</div>
				<?php endforeach; ?>
				<div class="gradient-white-bottom"></div>
			</div>
		</div>
	</div>
</div>
