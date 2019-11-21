<div id="isi_popup" style="visibility: hidden;">
	<div id="content">
		<center><h4 id="firstHeading" class="firstHeading">Wilayah <?= $wilayah ?></h4></center>
		<div id="bodyContent">
			<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-pie-chart"></i>&nbsp;&nbsp;Statistik Pie&nbsp;&nbsp;</a></center></p>
			<div class="collapse box-body no-padding" id="collapseStat">
				<div class="card card-body">
					<?php foreach ($list_lap as $key => $value): ?>
						<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/pie_gis_desa/$key/"."'".$desa['nama_desa']."'")?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?= $wilayah ?>"><?= $value ?></a></li>
					<?php endforeach; ?>
				</div>
			</div>
			<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa  fa-bar-chart"></i>Statistik Graph</a></center></p>
			<div class="collapse box-body no-padding" id="collapseStatGraph">
				<div class="card card-body">
					<?php foreach ($list_lap as $key => $value): ?>
						<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/graph_gis_desa/$key/"."'".$desa['nama_desa']."'")?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?= $wilayah ?>"><?= $value ?></a></li>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
