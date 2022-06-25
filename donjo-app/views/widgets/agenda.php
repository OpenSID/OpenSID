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
			<?php if (count($hari_ini) > 0): ?>
				<li class="active"><a data-toggle="tab" href="#hari-ini">Hari ini</a></li>
			<?php endif; ?>
			<?php if (count($yad) > 0): ?>
				<li class="<?php count($hari_ini) == 0 && print 'active'?>"><a data-toggle="tab" href="#yad">Yang akan datang</a></li>
			<?php endif; ?>
			<?php if (count($lama) > 0): ?>
				<li class="<?php count(array_merge($hari_ini, $yad)) == 0 && print 'active'?>"><a data-toggle="tab" href="#lama">Lama</a></li>
			<?php endif; ?>
		</ul>

		<div class="tab-content">
			<?php if (count(array_merge($hari_ini, $yad, $lama)) > 0): ?>
				<div id="hari-ini" class="tab-pane fade in active">
					<ul class="sidebar-latest">
						<?php foreach ($hari_ini as $agenda): ?>
							<li>
								<table id="table-agenda" width="100%">
									<tr>
										<td colspan="3"><a href="<?= site_url('artikel/' . buat_slug($agenda))?>"><?= $agenda['judul']?></a></td>
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
				</div>

				<div id="yad" class="tab-pane fade <?php count($hari_ini) == 0 && print 'in active'?>">
					<ul class="sidebar-latest">
						<?php if (count($yad) > 0): ?>
							<?php foreach ($yad as $agenda): ?>
								<li>
									<table id="table-agenda" width="100%">
										<tr>
											<td colspan="3"><a href="<?= site_url('artikel/' . buat_slug($agenda))?>"><?= $agenda['judul']?></a></td>
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
						<?php endif; ?>
					</ul>
				</div>

				<div id="lama" class="tab-pane fade <?php count(array_merge($hari_ini, $yad)) == 0 && print 'in active'?>">
					<marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior=”alternate”>
						<ul class="sidebar-latest">
							<?php foreach ($lama as $agenda): ?>
								<li>
									<table id="table-agenda" width="100%">
										<tr>
											<td colspan="3"><a href="<?= site_url('artikel/' . buat_slug($agenda))?>"><?= $agenda['judul']?></a></td>
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
					</marquee>
				</div>
			<?php else: ?>
				<p>Belum ada agenda</p>
			<?php endif; ?>
		</div>
	</div>
</div>
