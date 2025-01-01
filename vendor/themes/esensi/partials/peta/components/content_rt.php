<?php $pengaturan = json_decode(setting('tampilkan_tombol_peta'), true) ?>
<div id="isi_popup_rt">
	<?php foreach ($rt_gis as $key_rt => $rt): ?>
		<div id="isi_popup_rt_<?= $key_rt ?>" style="visibility: hidden;">
		<?php $link       = underscore($rt['dusun']) . '/' . underscore($rt['rw']) . '/' . underscore($rt['rt']) ?>
		<?php $data_title = " RT {$rt['rt']} RW {$rt['rw']} {$wilayah} {$rt['dusun']}"; ?>
			<div id="content">
				<h5 id="firstHeading" class="firstHeading">Wilayah RT <?= set_ucwords($rt['rt']) . ' RW ' . set_ucwords($rt['rw']) . ' ' . ucwords($this->setting->sebutan_dusun) . ' ' . set_ucwords($rt['dusun']); ?></h5>
				<div id="bodyContent">

					<!-- statistik penduduk -->
					<?php if (in_array('Statistik Penduduk', $pengaturan)): ?>
						<p><a href="#collapseStatPenduduk" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatPenduduk" aria-expanded="false" aria-controls="collapseStatPenduduk"><i class="fa  fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></p>
						<div class="collapse box-body no-padding" id="collapseStatPenduduk">
							<div class="card card-body">
								<?php foreach ($list_ref as $key => $value): ?>
									<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_rt/{$key}/{$link}") ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk <?= $data_title ?>"><?= $value ?></a></li>
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
									<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_rt/{$key}/{$link}") ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Bantuan <?= $data_title ?>"><?= $value; ?></a></li>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif ?>

					<!-- statistik kepala wilayah -->
					<?php if (in_array('Kepala Wilayah', $pengaturan)): ?>
						<p><a href="<?= site_url("load_aparatur_wilayah/{$rt['id_kepala']}/3"); ?>" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Ketua RT <?= underscore($rt['rt']) ?>" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;Ketua RT&nbsp;&nbsp;&nbsp;&nbsp;</a></p>
					<?php endif ?>

				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
