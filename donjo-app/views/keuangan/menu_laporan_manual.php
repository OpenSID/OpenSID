<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik Laporan Keuangan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($_SESSION['submenu'] == 'Grafik Keuangan'): ?>class="active"<?php endif; ?>><a href="<?= site_url('keuangan_manual/grafik_manual/grafik-RP-APBD-manual')?>">Grafik Pelaksanaan APBDes</a></li>
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
			<li <?php if ($_SESSION['submenu'] == 'Laporan Keuangan Akhir Bidang Manual'): ?>class="active"<?php endif; ?>><a href="<?=site_url('keuangan_manual/grafik_manual/rincian_realisasi_bidang_manual')?>">Laporan Pelaksanaan APBDes Manual</a></li>
		</ul>
	</div>
</div>
