<div id="isi_popup_rt">
	<?php foreach ($rt_gis as $key_rt => $rt): ?>
		<div id="isi_popup_rt_<?= $key_rt ?>" style="visibility: hidden;">
			<div id="content_<?= $key_rt?>">
				<center><h4 id="firstHeading" class="firstHeading">Wilayah RT <?= $rt['rt'] ?> RW <?= $rt['rw'] . " " . ucwords($this->setting->sebutan_dusun) . " " . $rt['dusun']?></h4></center>
				<div id="bodyContent_<?= $key_rt ?>">
					<p><center><a href="#collapseStat_<?= $key_rt ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat_<?= $key_rt ?>" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-bar-chart"></i>Statistik Penduduk RW <?= $rt['rw'] ?></a></center></p>
					<div class="collapse box-body no-padding" id="collapseStat_<?= $key_rt ?>">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/pie_gis_rt/2/$key/".trim($rt[dusun])."/".trim($rt[rw])."/".trim($rt[rt]))?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT <?= $rt['rt'] ?> RW <?= $rt['rw'] ?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
