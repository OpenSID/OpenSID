<?php $pengaturan = json_decode(setting('tampilkan_tombol_peta'), true) ?>
<div id="isi_popup_dusun">
	<?php foreach ($dusun_gis as $key_dusun => $dusun): ?>
		<div id="isi_popup_dusun_<?= $key_dusun ?>" style="visibility: hidden;">
			<div id="content">
				<?php $link       = underscore($dusun['dusun']) ?>
				<?php $data_title = "{$wilayah} {$dusun['dusun']}" ?>

				<h5 id="firstHeading" class="firstHeading">Wilayah <?= $data_title; ?></h5>
				<div id="bodyContent">
					<!-- statistik penduduk -->
					<?php if (in_array('Statistik Penduduk', $pengaturan)): ?>
						<p><a href="#collapseStatPenduduk" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatPenduduk" aria-expanded="false" aria-controls="collapseStatPenduduk"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></p>
						<div class="collapse box-body no-padding" id="collapseStatPenduduk">
							<div class="card card-body">
								<?php foreach ($list_ref as $key => $value): ?>
									<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_dusun/{$key}/{$link}") ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk <?= $data_title ?>"><?= $value ?></a></li>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif ?>

					<!-- statistik bantuan -->
					<?php if (in_array('Statistik Bantuan', $pengaturan)): ?>
						<p><a href="#collapseStatBantuan" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Bantuan" data-toggle="collapse" data-target="#collapseStatBantuan" aria-expanded="false" aria-controls="collapseStatBantuan"><i class="fa fa-heart"></i>&nbsp;&nbsp;Statistik Bantuan&nbsp;&nbsp;</a></p>
						<div class="collapse box-body no-padding" id="collapseStatBantuan">
							<div class="card card-body">
								<?php foreach ($list_bantuan as $key => $value): ?>
									<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_dusun/{$key}/{$link}") ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Bantuan <?= $data_title ?>"><?= $value; ?></a></li>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif ?>

					<!-- statistik kepala wilayah -->
					<?php if (in_array('Kepala Wilayah', $pengaturan)): ?>
						<p><a href="<?=site_url("load_aparatur_wilayah/{$dusun['id_kepala']}/1")?>" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Kepala <?= set_ucwords($wilayah) . ' ' . $dusun['dusun']?>" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;Kepala <?= set_ucwords($wilayah)?>&nbsp;&nbsp;</a></p>
					<?php endif ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
