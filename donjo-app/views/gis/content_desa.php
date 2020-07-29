<div id="isi_popup" style="visibility: hidden;">
	<div id="content">
		<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah <?= $wilayah; ?></b></h5></center>
		<div id="bodyContent">
			<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa fa-bar-chart"></i>Statistik Penduduk</a></center></p>
			<div class="collapse box-body no-padding" id="collapseStatGraph">
				<div class="card card-body">
					<?php foreach ($list_lap as $key => $value): ?>
						<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik/chart_gis_desa/$key/" . underscore($desa[nama_desa])); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?= $wilayah; ?>"><?= $value; ?></a></li>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
