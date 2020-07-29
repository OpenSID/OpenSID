<div id="isi_popup_rw">
	<?php foreach ($rw_gis as $key_rw => $rw): ?>
		<div id="isi_popup_rw_<?= $key_rw ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah RW <?= $rw['rw'] . " " . ucwords($this->setting->sebutan_dusun) . " " . $rw['dusun']; ?></b></h5></center>
				<div id="bodyContent">
					<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa  fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></center></p>
					<div class="collapse box-body no-padding" id="collapseStatGraph">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_rw/$key/" . underscore($rw[dusun]) . "/" . underscore($rw[rw])); ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk RW <?= $rw['rw']?> <?= $wilayah . $rw['dusun']?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
					<p><center><a href="<?=site_url("first/load_aparatur_wilayah/$rw[id_kepala]/2")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Ketua RW" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;Ketua RW&nbsp;&nbsp;&nbsp;&nbsp;</a></center></p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
