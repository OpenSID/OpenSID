<?php $pengaturan = json_decode(setting('tampilkan_tombol_peta'), true) ?>
<div id="isi_popup" style="visibility: hidden;">
	<div id="content">
		<h5 id="firstHeading" class="firstHeading">Wilayah <?= set_ucwords($wilayah); ?></h5>
		<div id="bodyContent">
			<?php $link       = underscore($desa['nama_desa']) ?>
			<?php $data_title = "{$wilayah}" ?>

			<!-- statistik penduduk -->
			<?php if (in_array('Statistik Penduduk', $pengaturan)): ?>
				<p><a href="#collapseStatPenduduk" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatPenduduk" aria-expanded="false" aria-controls="collapseStatPenduduk"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></p>
				<div class="collapse box-body no-padding" id="collapseStatPenduduk">
					<div class="card card-body">
						<?php foreach ($list_ref as $key => $value): ?>
							<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_desa/{$key}/{$link}") ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk <?= set_ucwords($wilayah) ?>"><?= $value ?></a></li>
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
							<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_desa/{$key}/{$link}") ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Bantuan <?= $data_title; ?>"><?= $value; ?></a></li>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif ?>

			<!-- statistik aparatur -->
			<?php if (in_array('Aparatur Desa', $pengaturan)): ?>
				<p><a href="<?= site_url('load_aparatur_desa'); ?>" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="<?= ucwords(setting('sebutan_pemerintah_desa')) ?>" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;<?= ucwords(setting('sebutan_pemerintah_desa')) ?>&nbsp;&nbsp;</a></p>
			<?php endif ?>
		</div>
	</div>
</div>
