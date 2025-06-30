<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik Laporan Keuangan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Grafik Keuangan'): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan/grafik/grafik-RP-APBD')?>">Grafik Pelaksanaan APBDes</a></li>
		</ul>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Grafik Keuangan DD'): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan/grafik/grafik-RP-APBD-DD')?>">Grafik Pelaksanaan Dana Desa</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel Laporan (Belanja Per Kelompok)</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Semester1'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_smt1')?>">Laporan Pelaksanaan APBDes Semester 1</a></li>
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Akhir'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi')?>">Laporan Pelaksanaan APBDes Semester 2</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel Laporan (Belanja Per Bidang)</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Semester1 Bidang'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_smt1_bidang')?>">Laporan Pelaksanaan APBDes Semester 1</a></li>
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Akhir Bidang'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_bidang')?>">Laporan Pelaksanaan APBDes Semester 2</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel Laporan Dana Desa(Kelompok)</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Semester1 DD'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_smt1_dd')?>">Laporan Pelaksanaan Dana Desa Semester 1</a></li>
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Akhir DD'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_dd')?>">Laporan Pelaksanaan Dana Desa Semester 2</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel Laporan Dana Desa (Bidang)</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Semester1 Bidang DD'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_smt1_bidang_dd')?>">Laporan Pelaksanaan Dana Desa Semester 1</a></li>
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Akhir Bidang DD'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan/grafik/rincian_realisasi_bidang_dd')?>">Laporan Pelaksanaan Dana Desa Semester 2</a></li>
		</ul>
	</div>
</div>

