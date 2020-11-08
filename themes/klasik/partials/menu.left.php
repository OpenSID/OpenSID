<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<nav id="menu-left" class="navbar navbar-inverse">
	<div class="container-fluid-inverse">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
			<ul class="nav navbar-nav">
				<li><a href="<?= site_url(); ?>">Beranda</a></li>
				<?php foreach($menu_kiri as $data): ?>
					<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?> <?php if(count($data['submenu']) > 0) { echo "<span class='caret'></span>"; } ?></a>
						<?php if(count($data['submenu']) > 0): ?>
							<ul class="dropdown-menu">
								<?php foreach($data['submenu'] as $submenu): ?>
									<li><a href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><?= $submenu['kategori']; ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>
