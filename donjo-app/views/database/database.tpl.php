<?php	$this->load->view('header', $this->header); ?>
<?php	$this->load->view('nav'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Database</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">Pengaturan Database</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
											<div class="nav-tabs-custom">
												<ul class="nav nav-tabs">
													<li <?= jecho($act_tab, 1, 'class="active"') ?>><a href="<?= site_url('database'); ?>">Backup <?= jecho(config_item('demo_mode'), false, ' /Restore'); ?></a></li>
													<?php if (can('u')): ?>
														<li <?= jecho($act_tab, 2, 'class="active"') ?>><a href="<?= site_url('database/migrasi_cri'); ?>">Migrasi DB</a></li>
													<?php endif; ?>
												</ul>
												<div class="tab-content">

<?php	$this->load->view($content); ?>
<?php	$this->load->view('footer'); ?>
