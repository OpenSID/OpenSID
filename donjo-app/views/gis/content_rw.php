<div id="isi_popup_rw">
	<?php foreach ($rw_gis as $key_rw => $rw): ?>
		<div id="isi_popup_rw_<?= $key_rw ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Statistik Penduduk RW <?= $rw['rw'] . " " . ucwords($this->setting->sebutan_dusun) . " " . $rw['dusun']?></b></h5></center>
				<div id="bodyContent">
					<div class="card card-body">
						<?php foreach ($list_lap as $key => $value): ?>
							<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/chart_gis_rw/$key/".underscore($rw[dusun])."/".underscore($rw[rw]))?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW <?= $rw['rw']?> <?= $wilayah . $rw['dusun']?>"><?= $value ?></a></li>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
