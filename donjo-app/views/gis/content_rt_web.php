<div id="isi_popup_rt">
	<?php foreach ($rt_gis as $key_rt => $rt): ?>
		<div id="isi_popup_rt_<?= $key_rt ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading">
					Wilayah RT <?= $rt['rt'] ?> RW <?= $rt['rw'] ?> <p> <?= ucwords($this->setting->sebutan_dusun) . " " . $rt['dusun']?></p></h5></center>
				<div id="bodyContent">
					<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-pie-chart"></i>&nbsp;&nbsp;Statistik Pie&nbsp;&nbsp;</a></center></p>
					<div class="collapse box-body no-padding" id="collapseStat">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik_web/chart_gis_rt/pie/2/$key/".trim($rt[dusun])."/".trim($rt[rw])."/".trim($rt[rt]))?>' data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk RT <?= $rt['rt'] ?> RW <?= $rt['rw'] ?> <?= $wilayah . $rt['dusun']?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
					<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa  fa-bar-chart"></i>Statistik Graph</a></center></p>
					<div class="collapse box-body no-padding" id="collapseStatGraph">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik_web/chart_gis_rt/bar/1/$key/".trim($rt[dusun])."/".trim($rt[rw])."/".trim($rt[rt]))?>' data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk RT <?= $rt['rt'] ?> RW <?= $rt['rw'] ?> <?= $wilayah . $rt['dusun']?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
					<p><center><a href="<?=site_url("first/load_ketua_rt/$rt[id_kepala]")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Ketua RT" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;Ketua RT&nbsp;&nbsp;&nbsp;&nbsp;</a></center></p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
