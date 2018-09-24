<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Inventaris</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($tip==1): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_jalan')?>"><i class="fa fa-list"></i> Daftar Inventaris</a></li>
      <li <?php if ($tip==2): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_jalan/mutasi')?>"><i class="fa fa-share"></i> Mutasi Inventaris</a></li>
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
			<li <?php if ($this->tab_ini == 1): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_tanah')?>"><i class="fa fa-tags"></i> Tanah</a></li>
			<li <?php if ($this->tab_ini == 2): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_peralatan')?>"><i class="fa fa-tags"></i> Peralatan Dan Mesin</a></li>
			<li <?php if ($this->tab_ini == 3): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_gedung')?>"><i class="fa fa-tags"></i> Gedung dan Bangunan</a></li>
			<li <?php if ($this->tab_ini == 4): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_jalan')?>"><i class="fa fa-tags"></i> Jalan, Irigasi, dan Jaringan</a></li>
			<li <?php if ($this->tab_ini == 5): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_asset')?>"><i class="fa fa-tags"></i> Asset Tetap Lainnya</a></li>
			<li <?php if ($this->tab_ini == 6): ?>class="active"<?php endif; ?>><a href="<?=site_url('inventaris_kontruksi')?>"><i class="fa fa-tags"></i> Kontruksi dalam pengerjaan</a></li>
			<li <?php if ($this->tab_ini == 7): ?>class="active"<?php endif; ?>><a href="<?=site_url('laporan_inventaris')?>"><i class="fa fa-tags"></i> Laporan Semua Asset</a></li>

		</ul>
	</div>
</div>

