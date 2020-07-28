<div id="isi_popup_dusun">
	<?php foreach ($dusun_gis as $key_dusun => $dusun): ?>
		<div id="isi_popup_dusun_<?= $key_dusun ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Statistik Penduduk <?= $wilayah . $dusun['dusun']?></b></h5></center>
				<div id="bodyContent">
					<div class="card card-body">
						<?php foreach ($list_lap as $key => $value): ?>
							<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/chart_gis_dusun/$key/".underscore($dusun[dusun]))?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?= $wilayah . $dusun['dusun']?>"><?= $value ?></a></li>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
