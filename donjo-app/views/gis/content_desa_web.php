<div id="isi_popup" style="visibility: hidden;">
	<div id="content">
		<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah <?= $wilayah; ?></b></h5></center>
		<div id="bodyContent">
			<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></center></p>
			<div class="collapse box-body no-padding" id="collapseStatGraph">
				<div class="card card-body">
					<?php foreach ($list_lap as $key => $value): ?>
						<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_desa/$key/" . underscore($desa[nama_desa])); ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk <?= $wilayah ?>"><?= $value ?></a></li>
					<?php endforeach; ?>
				</div>
			</div>
			<p><center><a href="<?= site_url("first/load_aparatur_desa"); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Aparatur <?= ucwords($this->setting->sebutan_desa)?>" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;Aparatur <?= ucwords($this->setting->sebutan_desa)?>&nbsp;&nbsp;</a></center></p>
			<p><center><a href="<?= site_url("first/load_apbdes"); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Laporan APBDES 2019 - Grafik" data-remote="false" data-toggle="modal" data-target="#modalSedang"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Grafik Keuangan&nbsp;&nbsp;</a></center></p>
		</div>
	</div>
</div>
