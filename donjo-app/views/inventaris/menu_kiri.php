<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Inventaris</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php ($tip==1) and print('active')?>"><a href="<?=site_url($this->tipe);?>"><i class="fa fa-list"></i> Daftar Inventaris</a></li>
			<?php if($this->tab_ini != 6):?>
	  			<li class="<?php ($tip==2) and print('active')?>"><a href="<?=site_url($this->tipe.'/mutasi')?>"><i class="fa fa-share"></i> Mutasi Inventaris</a></li>
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
			<li class="<?php ($this->tab_ini == 1) and print('active')?>"><a href="<?=site_url('inventaris_tanah')?>"><i class="fa fa-tags"></i> Tanah</a></li>
			<li class="<?php ($this->tab_ini == 2) and print('active')?>"><a href="<?=site_url('inventaris_peralatan')?>"><i class="fa fa-tags"></i> Peralatan Dan Mesin</a></li>
			<li class="<?php ($this->tab_ini == 3) and print('active')?>"><a href="<?=site_url('inventaris_gedung')?>"><i class="fa fa-tags"></i> Gedung dan Bangunan</a></li>
			<li class="<?php ($this->tab_ini == 4) and print('active')?>"><a href="<?=site_url('inventaris_jalan')?>"><i class="fa fa-tags"></i> Jalan, Irigasi, dan Jaringan</a></li>
			<li class="<?php ($this->tab_ini == 5) and print('active')?>"><a href="<?=site_url('inventaris_asset')?>"><i class="fa fa-tags"></i> Asset Tetap Lainnya</a></li>
			<li class="<?php ($this->tab_ini == 6) and print('active')?>"><a href="<?=site_url('inventaris_kontruksi')?>"><i class="fa fa-tags"></i> Kontruksi dalam pengerjaan</a></li>
			<li class="<?php ($this->tab_ini == 7) and print('active')?>"><a href="<?=site_url('laporan_inventaris')?>"><i class="fa fa-tags"></i> Laporan Semua Asset</a></li>

		</ul>
	</div>
</div>

