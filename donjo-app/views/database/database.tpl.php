<?php	$this->load->view('header', $this->header); ?>
<?php	$this->load->view('nav'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Database</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Database</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
					<div class="box box-info">
						<div class="box-body">
								<div class="row">
									<div class="col-xs-12">
											<div class="nav-tabs-custom">
												<ul class="nav nav-tabs">
													<li <?= jecho($act_tab, 1, 'class="active"'); ?>><a href="<?= site_url('database'); ?>">Ekspor Database</a></li>
													<?php if ($this->CI->cek_hak_akses('u') && ! config_item('demo_mode')): ?>
														<li <?= jecho($act_tab, 2, 'class="active"'); ?>><a href="<?= site_url('database/import'); ?>">Impor Database</a></li>
														<li <?= jecho($act_tab, 3, 'class="active"'); ?>><a href="<?= site_url('database/import_bip'); ?>">Impor BIP</a></li>
													<?php endif; ?>
													<li <?= jecho($act_tab, 4, 'class="active"'); ?>><a href="<?= site_url('database/backup'); ?>">Backup <?= jecho(config_item('demo_mode'), false,' /Restore'); ?></a></li>
													<?php if ($this->CI->cek_hak_akses('u')): ?>
														<?php if (! config_item('demo_mode')): ?>
															<li <?= jecho($act_tab, 6, 'class="active"'); ?>><a href="<?= site_url('database/kosongkan'); ?>">Kosongkan DB</a></li>
														<?php endif; ?>
														<li <?= jecho($act_tab, 5, 'class="active"'); ?>><a href="<?= site_url('database/migrasi_cri'); ?>">Migrasi DB</a></li>
													<?php endif; ?>
												</ul>
												<div class="tab-content">

<?php	$this->load->view($content); ?>
<?php	$this->load->view('footer'); ?>
