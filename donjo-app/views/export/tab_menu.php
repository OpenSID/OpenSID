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
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li <?= jecho($act_tab, 'export/exp', 'class="active"'); ?>><a href="<?= site_url('database')?>">Ekspor Database</a></li>
						<li <?= jecho($act_tab, 'import/imp', 'class="active"'); ?>><a href="<?= site_url('database/import')?>">Impor Database</a></li>
						<li <?= jecho($act_tab, 'import/bip', 'class="active"'); ?>><a href="<?= site_url('database/import_bip')?>">Impor BIP</a></li>
						<li <?= jecho($act_tab, 'database/backup', 'class="active"'); ?>><a href="<?= site_url('database/backup')?>">Backup/Restore</a></li>
						<li <?= jecho($act_tab, 'database/kosongkan', 'class="active"'); ?>><a href="<?= site_url('database/kosongkan')?>">Kosongkan DB</a></li>
						<li <?= jecho($act_tab, 'database/migrasi_cri', 'class="active"'); ?>><a href="<?= site_url('database/migrasi_cri')?>">Migrasi DB</a></li>
					</ul>
					<?php $this->load->view($act_tab, $data); ?>
				</div>
			</div>
		</div>
	</section>
</div>
