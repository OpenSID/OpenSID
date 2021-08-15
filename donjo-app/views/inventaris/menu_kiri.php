<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Inventaris</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php if ($this->tab_ini == 0): ?>
				<li <?= jecho($tip, 1, 'class="active"'); ?>><a href="<?= site_url('laporan_inventaris'); ?>"><i class="fa fa-list"></i> Laporan Semua Asset</a></li>
				<li <?= jecho($tip, 2, 'class="active"'); ?>><a href="<?= site_url('laporan_inventaris/mutasi'); ?>"><i class="fa fa-list"></i> Laporan Asset Yang Dihapus</a></li>
			<?php else: ?>
				<li <?= jecho($tip, 1, 'class="active"'); ?>"><a href="<?=site_url($this->tipe);?>"><i class="fa fa-list"></i> Daftar Inventaris</a></li>
				<?php if($this->tab_ini != 7):?>
					<li <?= jecho($tip, 2, 'class="active"'); ?>><a href="<?=site_url($this->tipe.'/mutasi'); ?>"><i class="fa fa-share"></i> Daftar Mutasi</a></li>
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
			<li <?= jecho($this->tab_ini, 0, 'class="active"'); ?>><a href="<?= site_url('laporan_inventaris'); ?>"><i class="fa fa-tags"></i> Laporan Semua Asset</a></li>
			<li <?= jecho($this->tab_ini, 2, 'class="active"'); ?>><a href="<?= site_url('inventaris_tanah'); ?>"><i class="fa fa-tags"></i> Tanah</a></li>
			<li <?= jecho($this->tab_ini, 3, 'class="active"'); ?>><a href="<?= site_url('inventaris_peralatan'); ?>"><i class="fa fa-tags"></i> Peralatan Dan Mesin</a></li>
			<li <?= jecho($this->tab_ini, 4, 'class="active"'); ?>><a href="<?= site_url('inventaris_gedung'); ?>"><i class="fa fa-tags"></i> Gedung dan Bangunan</a></li>
			<li <?= jecho($this->tab_ini, 5, 'class="active"'); ?>><a href="<?= site_url('inventaris_jalan'); ?>"><i class="fa fa-tags"></i> Jalan, Irigasi, dan Jaringan</a></li>
			<li <?= jecho($this->tab_ini, 6, 'class="active"'); ?>><a href="<?= site_url('inventaris_asset'); ?>"><i class="fa fa-tags"></i> Asset Tetap Lainnya</a></li>
			<li <?= jecho($this->tab_ini, 7, 'class="active"'); ?>><a href="<?= site_url('inventaris_kontruksi'); ?>"><i class="fa fa-tags"></i> Konstruksi dalam pengerjaan</a></li>
		</ul>
	</div>
</div>

