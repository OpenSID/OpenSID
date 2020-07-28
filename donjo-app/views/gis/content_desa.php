<div id="isi_popup" style="visibility: hidden;">
	<div id="content">
		<center><h5 id="firstHeading" class="firstHeading"><b>Statistik Penduduk <?= $wilayah ?></b></h5></center>
		<div id="bodyContent">
			<div class="card card-body">
				<?php foreach ($list_lap as $key => $value): ?>
					<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/chart_gis_desa/$key/$desa[nama_desa]")?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?= $wilayah ?>"><?= $value ?></a></li>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
