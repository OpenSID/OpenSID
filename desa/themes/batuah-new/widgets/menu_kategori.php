<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="widget-box bgwhite bordergrey">
	<div class="widget-head bggrad-color2 flexleft">
		<svg viewBox="0 0 24 24">
			<path d="M3,4H7V8H3V4M9,5V7H21V5H9M3,10H7V14H3V10M9,11V13H21V11H9M3,16H7V20H3V16M9,17V19H21V17H9" />
		</svg>
		<h1><?= $judul_widget ?></h1>
	</div>
	<div class="widget-inner category-menu">
		<ul id="ul-menu" style="text-align:left;list-style:none;">
			<?php foreach ($menu_kiri as $data) : ?>
				<li>
					<div class="cat-link"><a href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?><?php (count($data['submenu'] ?? []) > 0) and print('<span class="caret"></span>'); ?>
						</a></div>
					<?php if (count($data['submenu'] ?? []) > 0) : ?>
						<ul>
							<?php foreach ($data['submenu'] as $submenu) : ?>
								<div class="subcat-link trans-def"><a href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><?= $submenu['kategori'] ?></a></div>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>