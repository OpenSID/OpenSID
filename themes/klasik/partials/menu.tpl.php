<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- https://stackoverflow.com/questions/25692514/bootstrap-how-do-i-make-dropdown-navigation-parent-links-an-active-link -->
<script>
	jQuery(function($) {
		$('.navbar .dropdown').hover(function() {
			$(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
		}, function() {
			$(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
		});
		$('.navbar .dropdown > a').click(function(){
			location.href = this.href;
		});
	});
</script>

<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.bar.css">
<link rel="stylesheet" href="<?= base_url()?>assets/front/css/default.css" />
<link rel="stylesheet" href="<?= base_url().$this->theme_folder.'/'.$this->theme.'/css/default.css'?>" />
<?php if (is_file("desa/css/".$this->theme."/desa-default.css")): ?>
	<link rel="stylesheet" href="<?= base_url().'desa/css/'.$this->theme.'/desa-default.css'?>" />
<?php endif; ?>

<nav class="navbar navbar-inverse">
	<div class="container-fluid-inverse">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php foreach ($menu_atas as $data): ?>
					<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?= $data['link']?>"><?= $data['nama'] ?><?php (count($data['submenu']) > 0) and print("<span class='caret'></span>") ?></a>
						<?php if (count($data['submenu']) > 0): ?>
							<ul class="dropdown-menu">
								<?php foreach ($data['submenu'] as $submenu): ?>
									<li>
										<a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>

