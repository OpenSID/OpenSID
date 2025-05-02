<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="module-kategori">
	<div class="head-widget flexleft bg-grey-dark2"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/><?= $judul_widget ?></div>
	<div class="widget-height bg-white border-grey-soft">
		<div class="widgetscroll">
			<div class="p-10">
				<ul id="ul-menu" style="text-align:left;">
					<?php foreach($menu_kiri as $data):?>
						<li>
							<div class="single-link">
								<a href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?><?php (count($data['submenu'] ?? []) > 0) and print('<span class="caret"></span>'); ?></a>
							</div>
							<?php if(count($data['submenu'] ?? []) > 0): ?>
								<ul>
									<?php foreach($data['submenu'] as $submenu):?>
										<div class="subsingle-link"><a href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><?= $submenu['kategori']?></a></div>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
	</div>
</div>