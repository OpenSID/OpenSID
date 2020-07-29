<div id="isi_popup_rt">
	<?php foreach ($rt_gis as $key_rt => $rt): ?>
		<div id="isi_popup_rt_<?= $key_rt ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah RT <?= set_ucwords($rt['rt']) . " RW " . set_ucwords($rt['rw']) . " " . ucwords($this->setting->sebutan_dusun) . " " . set_ucwords($rt['dusun']); ?></b></h5></center>
				<div id="bodyContent">
					<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa  fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></center></p>
					<div class="collapse box-body no-padding" id="collapseStatGraph">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_rt/$key/" . underscore($rt[dusun]) . "/" . underscore($rt[rw]) . "/" . underscore($rt[rt])) ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk RT <?= $rt['rt'] ?> RW <?= $rt['rw'] ?> <?= set_ucwords($wilayah) . " " . set_ucwords($rt['dusun']); ?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
					<p><center><a href="<?= site_url("first/load_aparatur_wilayah/$rt[id_kepala]/3"); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Ketua RT" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;Ketua RT&nbsp;&nbsp;&nbsp;&nbsp;</a></center></p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
