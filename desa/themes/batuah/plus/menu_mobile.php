<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="relative-hidden">
	<div class="rowsame mlr-min-5">
		<div class="flexitem-menu bgbiru">
		<a href="<?= site_url('siteman') ?>">
		<div class="flexitem-menu-inner flexcenter">
		Login<br/>Admin
		</div>
		</a>
		</div>
		<div class="flexitem-menu bgorange">
		<a href="<?= site_url('layanan-mandiri/masuk') ?>">
		<div class="flexitem-menu-inner flexcenter">
		Layanan<br/>Mandiri
		</div>
		</a>
		</div>
	</div>
</div>

<div class="relative-hidden">
	<div class="icon-room-inner mb-15">
	<div class="flex-container">
		<?php $this->load->view("$folder_themes/plus/icon"); ?>
	</div>
	</div>
</div>	

<div class="relative-hidden">
		
	<div class="mobile-menu">
		
		<nav class="navbar">
			<ul>
				<?php foreach($menu_atas as $data) { ?>
				<?php if(count($data['submenu'])>0): ?>
				<li class="dropdown bgwhite-transparent">
					<a href="<?= $data['link']?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
					<ul class="dropdown-menu" style="background:rgba(0,0,0,0.2);margin-top:10px;padding:15px 20px 8px;">
					<?php foreach($data['submenu'] as $submenu): ?>
						<p><a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a></p>
					<?php endforeach; ?>
					</ul>
				</li>
				<?php else: ?>
				<li class="bgwhite-transparent"><a href="<?= $data['link']?>"><?= $data['nama']?></a></li>
				<?php endif; ?>
				<?php } ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Kategori<span class='caret'></span></a>
					<ul class="dropdown-menu" style="background:rgba(0,0,0,0.2);margin-top:10px;padding:15px 20px 8px;">
					<?php foreach ($menu_kiri as $data): ?>
						<p><a href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?></a></p>
					<?php endforeach; ?>
					</ul>
				</li>
				<li><a href="<?= site_url('arsip') ?>">Arsip</a></li>
			</ul>
		</nav>
	</div>

</div>