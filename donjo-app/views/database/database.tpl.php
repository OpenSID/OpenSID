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
													<li <?php if ($act_tab==1): ?>class="active"<?php endif ?>><a href="<?= site_url('database')?>">Ekspor Database</a></li>
													<li <?php if ($act_tab==2): ?>class="active"<?php endif ?>><a href="<?= site_url('database/import')?>">Impor Database</a></li>
													<li <?php if ($act_tab==3): ?>class="active"<?php endif ?>><a href="<?= site_url('database/import_bip')?>">Impor BIP</a></li>
													<li <?php if ($act_tab==4): ?>class="active"<?php endif ?>><a href="<?= site_url('database/backup')?>">Backup/Restore</a></li>
													<li <?php if ($act_tab==6): ?>class="active"<?php endif ?>><a href="<?= site_url('database/kosongkan')?>">Kosongkan DB</a></li>
													<li <?php if ($act_tab==5): ?>class="active"<?php endif ?>><a href="<?= site_url('database/migrasi_cri')?>">Migrasi DB</a></li>
												</ul>
												<div class="tab-content">

<?php	$this->load->view($content); ?>
<?php	$this->load->view('footer'); ?>
