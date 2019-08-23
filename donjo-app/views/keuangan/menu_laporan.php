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
			<li <?php if ($_SESSION['submenu'] == "Laporan Analisis"): ?>class="active"<?php endif; ?>><a href="<?=site_url("analisis_laporan/clear")."/".$analisis_master['id']?>">Laporan Hasil Klasifikasi</a></li>
			<li <?php if ($_SESSION['submenu'] == "Statistik Jawaban"): ?>class="active"<?php endif; ?>><a href="<?=site_url("analisis_statistik_jawaban/clear")."/".$analisis_master['id']?>">Laporan Per Indikator</a></li>
		</ul>
	</div>
</div>
