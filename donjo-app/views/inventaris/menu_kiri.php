<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Inventaris</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php if ($this->controller == 'laporan_inventaris'):?>
				<li <?= jecho($tip, 1, 'class="active"'); ?>><a href="<?= site_url('laporan_inventaris'); ?>"><i class="fa fa-list"></i> Laporan Semua Asset</a></li>
				<li <?= jecho($tip, 2, 'class="active"'); ?>><a href="<?= site_url('laporan_inventaris/mutasi'); ?>"><i class="fa fa-list"></i> Laporan Asset Yang Dihapus</a></li>
			<?php else: ?>
				<li <?= jecho($tip, 1, 'class="active"'); ?>"><a href="<?=site_url($this->controller); ?>"><i class="fa fa-list"></i> Daftar Inventaris</a></li>
				<?php if ($this->controller != 'inventaris_kontruksi'): ?>
					<li <?= jecho($tip, 2, 'class="active"'); ?>><a href="<?=site_url("{$this->controller}/mutasi"); ?>"><i class="fa fa-share"></i> Daftar Mutasi</a></li>
				<?php endif ?>
			<?php endif ?>
		</ul>
	</div>
</div>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Kategori Inventaris</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?= jecho($this->controller, 'laporan_inventaris', 'class="active"'); ?>><a href="<?= site_url('laporan_inventaris'); ?>"><i class="fa fa-tags"></i> Laporan Semua Asset</a></li>
			<li <?= jecho($this->controller, 'inventaris_tanah', 'class="active"'); ?>><a href="<?= site_url('inventaris_tanah'); ?>"><i class="fa fa-tags"></i> Tanah</a></li>
			<li <?= jecho($this->controller, 'inventaris_peralatan', 'class="active"'); ?>><a href="<?= site_url('inventaris_peralatan'); ?>"><i class="fa fa-tags"></i> Peralatan Dan Mesin</a></li>
			<li <?= jecho($this->controller, 'inventaris_gedung', 'class="active"'); ?>><a href="<?= site_url('inventaris_gedung'); ?>"><i class="fa fa-tags"></i> Gedung dan Bangunan</a></li>
			<li <?= jecho($this->controller, 'inventaris_jalan', 'class="active"'); ?>><a href="<?= site_url('inventaris_jalan'); ?>"><i class="fa fa-tags"></i> Jalan, Irigasi, dan Jaringan</a></li>
			<li <?= jecho($this->controller, 'inventaris_asset', 'class="active"'); ?>><a href="<?= site_url('inventaris_asset'); ?>"><i class="fa fa-tags"></i> Asset Tetap Lainnya</a></li>
			<li <?= jecho($this->controller, 'inventaris_kontruksi', 'class="active"'); ?>><a href="<?= site_url('inventaris_kontruksi'); ?>"><i class="fa fa-tags"></i> Konstruksi dalam pengerjaan</a></li>
		</ul>
	</div>
</div>

