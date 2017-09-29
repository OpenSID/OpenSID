<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.bar.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/bootstrap/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.js"></script>
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
<link type='text/css' href="<?php echo base_url()?>assets/front/css/default.css" rel='Stylesheet' />
<link type='text/css' href="<?php echo base_url().$this->theme_folder.'/'.$this->theme.'/css/default.css'?>" rel='Stylesheet' />
<?php if(is_file("desa/css/".$this->theme."/desa-default.css")):?>
  <link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme ?>/desa-default.css" rel='Stylesheet' />
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
			<a class="navbar-brand" href="<?php echo site_url(); ?>first/"><span id="header_sebutan_desa"><?php echo ucwords($this->setting->sebutan_desa)." "?></span><?php echo ucwords($desa['nama_desa'])?></a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo site_url()."first"?>"><i class="fa fa-home fa-lg"></i> Beranda</a></li>
				<?php foreach($menu_atas as $data){?>
					<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo site_url()."first/".$data['link']?>"><i class="fa fa-th-large"></i> <?php echo $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?></a>
						<?php if(count($data['submenu'])>0): ?>
							<ul class="dropdown-menu">
								<?php foreach($data['submenu'] as $submenu): ?>
									<li><a href="<?php echo site_url()."first/".$submenu['link']?>"><?php echo $submenu['nama']?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php }?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<a href="<?php echo site_url('siteman') ?>"><button class="btn btn-primary navbar-btn"><i class="fa fa-lock fa-lg"></i> Login Admin</button></a>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>