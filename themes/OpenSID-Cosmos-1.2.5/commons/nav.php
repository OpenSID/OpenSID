<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<section id="navigation">
	<nav class="nav">
		<div class="container">
			<div class="menu-toggle">
				<div class="d-flex justify-content-between align-items-center">
					<div class="d-flex align-items-center justify-content-start">
						<button type="button" id="menu-btn">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="search-button">
						<button type="button" class="bg-transparent btn">
							<span><i class="fa fa-search"></i></span>
						</button>
					</div>
				</div>
			</div>
			<ul id="navMenu" class="ace-responsive-menu" data-menu-style="horizontal">
				<?php if($menu_atas) : ?>
					<li class="d-none d-lg-inline-block">
						<a href="<?= site_url('first') ?>" style="padding-right:10px" title="Ke halaman utama"><i class="fa fa-home" style="line-height: 1.5rem"></i></a>
					</li>
					<?php foreach($menu_atas as $data) : ?>
						<?php if(count($data['submenu']) > 0) : ?>
							<li class="<?php nested_array_search(current_url(), $data['submenu']) and print('active') ?>"><a href="#!"><?= strtoupper($data['nama']) ?></a>
								<ul>
									<?php foreach($data['submenu'] as $submenu) : ?>
										<li class="<?= current_url() == $submenu['link'] ?>">
											<a href="<?= $submenu['link'] ?>"><?= strtoupper($submenu['nama']) ?></a>
										</li>
									<?php endforeach ?>
								</ul>
							</li>
							<?php else : ?>
							<li class="<?php current_url() == $data['link'] and print('active')  ?>">
								<a href="<?= $data['link'] ?>"><?= strtoupper($data['nama']) ?></a>
							</li>
						<?php endif ?>
					<?php endforeach ?>
				<?php endif ?>
			</ul>
			<div class="search-button d-none d-lg-inline-block">
				<button type="button" class="bg-transparent btn pr-0">
					<span><i class="fa fa-search"></i></span>
				</button>
			</div>
		</div>
	</nav>
</section>
<section class="modal-search-form d-none" id="modal-search-form" role="dialog">
	<form action="<?= site_url('first');?>" method="get">
		<div class="input-group">
			<input type="search" class="form-control bg-transparent" placeholder="Cari sesuatu..." name="cari" autocomplete="off" autofocus>
			<span class="input-group-append">
				<button class="btn" type="submit">
					<i class="fa fa-search"></i>
				</button>
			</span>
		</div>
	</form>
	<button class="close-search-form btn" type="button">
		<i class="fa fa-times fa-lg mb-1"></i>
		<span class="small">TUTUP</span>
	</button>
</section>