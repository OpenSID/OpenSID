<div id="isi_popup_dusun">
	<?php foreach ($dusun_gis as $key => $dusun): ?>
		<div id="isi_popup_dusun_<?= $key ?>" style="visibility: hidden;">
			<div id="content">
				<center><h4 id="firstHeading" class="firstHeading">Wilayah <?= $wilayah . $dusun['dusun']?></h4></center>
				<div id="bodyContent">
					<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-bar-chart"></i>Statistik Penduduk</a></center></p>
					<div class="collapse box-body no-padding" id="collapseStat">
						<div class="card card-body">
							<?php foreach ($list_lap as $key => $value): ?>
								<li class="<?php ($lap==$key) and print('active') ?>"><a href='<?=site_url("statistik/pie_gis_dusun/2/$key/"."'".$dusun['dusun']."'")?>' data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?= $wilayah . $dusun['dusun']?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
