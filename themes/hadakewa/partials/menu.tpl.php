<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.bar.css">
<!--
https://stackoverflow.com/questions/24685000/bootstrap-3-dropdowns-on-hover-and-on-click
$('.navbar-toggle').is(':visible') checks if we are currently in mobile view, $(this).toggleClass('open', true) adds or removes open css class used by bootstrap, and window.location = $(this).attr('href') sends user to location set in the link href.

Di layar PC, hover untuk melihat submenu.
Di mobile, click untuk menampilkan submenu. Pada waktu submenu tampil, klik menu utama untuk
navigasi ke tautannya.
-->
<script>
	jQuery(function($) {
		$('ul.nav li.dropdown').hover(function() {
				if (!$('.navbar-toggle').is(':visible')) {
						$(this).toggleClass('open', true);
				}
		}, function() {
				if (!$('.navbar-toggle').is(':visible')) {
						$(this).toggleClass('open', false);
				}
		});
		$('ul.nav li.dropdown a').click(function(){
				if (!$('.navbar-toggle').is(':visible') && $(this).attr('href') != '#') {
						$(this).toggleClass('open', false);
						window.location = $(this).attr('href')
				} else if ($(this).parent().hasClass('open') && $(this).attr('href') != '#') {
						window.location = $(this).attr('href')
				}
		});
	});
</script>
<link rel="Stylesheet" href="<?= base_url()?>assets/front/css/default.css" />
<link rel="Stylesheet" href="<?= base_url().$this->theme_folder.'/'.$this->theme.'/css/default.css'?>" />
<?php if (is_file("desa/css/".$this->theme."/desa-default.css")):?>
	<link rel="Stylesheet" href="<?= base_url().'desa/css/'.$this->theme.'/desa-default.css'?>" />
<?php endif; ?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= site_url(); ?>">
				<img src="<?= gambar_desa($desa['logo']);?>" alt="<?= $desa['nama_desa']?>" width="30px" style="margin:-7px"/>
			</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?= site_url()?>"><i class="fa fa-home fa-lg"></i> Beranda</a></li>
				<?php foreach ($menu_atas as $data): ?>
					<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?= $data['link']?>"><i class="fa fa-th-large"></i> <?= $data['nama'] ?><?php (count($data['submenu']) > 0) and print("<span class='caret'></span>") ?></a>
						<?php if (count($data['submenu']) > 0): ?>
							<ul class="dropdown-menu">
								<?php foreach ($data['submenu'] as $submenu): ?>
									<li><a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<a href="<?= site_url('siteman') ?>"><button class="btn btn-primary navbar-btn"><i class="fa fa-lock fa-lg"></i> Login Admin</button></a>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>
