<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik Realisasi</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == "Data Kategori"): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan/grafik/grafik-RP-APBD')?>">Grafik Realisasi Pelaksanaan</a></li>
			<li <?php if ($_SESSION['submenu'] == "Data Kategori"): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan/grafik/grafik-R-BD')?>">Grafik Realisasi Belanja Bidang</a></li>
			<li <?php if ($_SESSION['submenu'] == "Data Kategori"): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan/grafik/grafik-R-PD')?>">Grafik Realisasi Pendapatan</a></li>
			<li <?php if ($_SESSION['submenu'] == "Data Kategori"): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan/grafik/grafik-R-PEMDES')?>">Grafik Realisasi Pembiayaan Desa</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Rincian Keuangan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == "Laporan Analisis"): ?>class="active"<?php endif; ?>><a href="<?=site_url("keuangan/grafik/rincian_realisasi")?>">Realisasi</a></li>
		</ul>
	</div>
	<div class="box-header">
		<a href="<?= site_url("keuangan/cetak/cetak_rincian_realisasi")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Rincian Realisasi" target="_blank"><i class="fa fa-print"></i>Cetak Rincian Realisasi</a>
	</div>


</div>
