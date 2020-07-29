<div id="isi_popup_rw">
	<?php foreach ($rw_gis as $key_rw => $rw): ?>
		<div id="isi_popup_rw_<?= $key_rw ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah RW <?= $rw['rw'] . " " . ucwords($this->setting->sebutan_dusun) . " " . $rw['dusun']; ?></b></h5></center>
				<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa fa-bar-chart"></i>Statistik Penduduk</a></center></p>
				<div class="collapse box-body no-padding" id="collapseStatGraph">
					<div id="bodyContent">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik/chart_gis_rw/$key/".underscore($rw[dusun]) . "/" . underscore($rw[rw])); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW <?= $rw['rw'] . " " . $wilayah . " " . $rw['dusun']; ?>"><?= $value; ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
