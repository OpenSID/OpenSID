<!-- Widget Agenda -->

<style type="text/css">
	#agenda .tab-content { margin-top: 10px; }
</style>

<div class="box box-primary box-solid">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-calendar"></i> Agenda</a></h3>
	</div>
	<div id="agenda" class="box-body">
		<ul class="nav nav-tabs">
			<?php if (count($agenda_hari_ini) > 0): ?>
				<li class="active"><a data-toggle="tab" href="#hari_ini">Hari ini</a></li>
			<?php endif; ?>
			<li <?php count($agenda_hari_ini) > 0 or print('class="active"')?>><a data-toggle="tab" href="#yad">Yang akan datang</a></li>
			<?php if (count($agenda_lama) > 0): ?>
				<li><a data-toggle="tab" href="#lama">Lama</a></li>
			<?php endif; ?>
		</ul>

		<div class="tab-content">
			<?php foreach (array('hari_ini' => 'agenda_hari_ini', 'yad' => 'agenda_yad', 'lama' => 'agenda_lama') as $jenis => $jenis_agenda) : ?>
				<div id="<?= $jenis ?>" class="tab-pane fade in <?php ($jenis == 'hari_ini') and print('active')?>">
					<?php ($jenis) == 'lama' and print('<marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior=behavior=”alternate”>')?>
					<ul class="sidebar-latest">
						<?php foreach ($$jenis_agenda as $agenda): ?>
							<li>
								<table id="table-agenda" width="100%">
									<tr>
										<td colspan="3"><a href="<?= site_url('first/artikel/'.buat_slug($agenda))?>"><?= $agenda['judul']?></a></td>
									</tr>
									<tr>
										<th id="label-meta-agenda" width="40%">Waktu</th>
										<td width="5%">:</td>
										<td id="isi-meta-agenda" width="55%"><?= tgl_indo2($agenda['tgl_agenda'])?></td>
									</tr>
									<tr>
										<th id="label-meta-agenda">Lokasi</th>
										<td>:</td>
										<td id="isi-meta-agenda"><?= $agenda['lokasi_kegiatan']?></td>
									</tr>
									<tr>
										<th id="label-meta-agenda">Koordinator</th>
										<td>:</td>
										<td id="isi-meta-agenda"><?= $agenda['koordinator_kegiatan']?></td>
									</tr>
								</table>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php ($jenis) == 'lama' and print('</marquee>')?>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>
