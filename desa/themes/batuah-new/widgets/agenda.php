<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="widget-box bgwhite bordergrey">
	<div class="widget-head bggrad-color2 flexleft">
		<svg viewBox="0 0 24 24">
			<path d="M21,17V8H7V17H21M21,3A2,2 0 0,1 23,5V17A2,2 0 0,1 21,19H7C5.89,19 5,18.1 5,17V5A2,2 0 0,1 7,3H8V1H10V3H18V1H20V3H21M3,21H17V23H3C1.89,23 1,22.1 1,21V9H3V21M19,15H15V11H19V15Z" />
		</svg>
		<h1><?= $judul_widget ?></h1>
	</div>
	<?php if (count(array_merge($hari_ini, $yad, $lama) ?? []) > 0) : ?>
		<div class="fortab2">
			<div class="fortab2-inner">
				<div class="main2">
					<input id="tab2_one" type="radio" name="tab2x" checked>
					<label class="flexcenter bordergrey" for="tab2_one">Terjadwal</label>
					<input id="tab2_two" type="radio" name="tab2x">
					<label class="flexcenter bordergrey" for="tab2_two" style="border-left:1px solid;">Sebelumnya</label>
					<div class="content3">
						<div id="content3_one">
							<div class="widget-inner" style="padding:0;">
								<div class="">
										<div class="widget-inner" style="padding-top:0;">
											<?php if (count(array_merge($hari_ini, $yad) ?? []) > 0) : ?>
												<marquee class="agendaheight" onmouseover="this.stop()" onmouseout="this.start()" scrollamount="3" direction="up" width="100%" behavior=”alternate”>
												<?php if (count($hari_ini ?? []) > 0) : ?>
													<?php foreach ($hari_ini as $agenda) : ?>
														<a href="<?= site_url('artikel/' . buat_slug($agenda)) ?>">
															<div class="agenda-row">
																<h3><?= $agenda['judul'] ?></h3>
																<table style="width:100%;" class="tableagenda">
																	<tr>
																		<td>Tanggal</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><?= tgl_indo2($agenda['tgl_agenda']) ?></td>
																	</tr>
																	<tr>
																		<td>Tempat</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><?= $agenda['lokasi_kegiatan'] ?></td>
																	</tr>
																</table>
															</div>
														</a>
													<?php endforeach; ?>
												<?php endif; ?>
												<?php if (count($yad ?? []) > 0) : ?>
													<?php foreach ($yad as $agenda) : ?>
														<a href="<?= site_url('artikel/' . buat_slug($agenda)) ?>">
															<div class="agenda-row">
																<h3><?= $agenda['judul'] ?></h3>
																<table style="width:100%;" class="tableagenda">
																	<tr>
																		<td>Tanggal</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><?= tgl_indo2($agenda['tgl_agenda']) ?></td>
																	</tr>
																	<tr>
																		<td>Tempat</td>
																		<td style="width:15px;text-align:center;">:</td>
																		<td><?= $agenda['lokasi_kegiatan'] ?></td>
																	</tr>
																</table>
															</div>
														</a>
													<?php endforeach; ?>
												<?php endif; ?>
												</marquee>
											<?php else : ?>
												<div class="widget-inner">
													<div class="nodata-small flexcenter">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pngfile/nodata-small.png") ?>" />
														<p>Belum ada agenda terdata</p>
													</div>
												</div>
											<?php endif; ?>
											
										</div>
									
								</div>
							</div>
						</div>
					</div>
					<div class="content3">
						<div id="content3_two">
							<div class="widget-inner" style="padding:0;">
								<div class="">
										<div class="widget-inner" style="padding-top:0;">
											<?php if (count($lama ?? []) > 0) : ?>
												<marquee class="agendaheight" onmouseover="this.stop()" onmouseout="this.start()" scrollamount="3" direction="up" width="100%" behavior=”alternate”>
												<?php foreach ($lama as $agenda) : ?>
													<a href="<?= site_url('artikel/' . buat_slug($agenda)) ?>">
														<div class="agenda-row">
															<h3><?= $agenda['judul'] ?></h3>
															<table style="width:100%;" class="tableagenda">
																<tr>
																	<td>Tanggal</td>
																	<td style="width:15px;text-align:center;">:</td>
																	<td><?= tgl_indo2($agenda['tgl_agenda']) ?></td>
																</tr>
																<tr>
																	<td>Tempat</td>
																	<td style="width:15px;text-align:center;">:</td>
																	<td><?= $agenda['lokasi_kegiatan'] ?></td>
																</tr>
															</table>
														</div>
													</a>
												<?php endforeach; ?>
												</marquee>
											<?php else : ?>
												<div class="widget-inner">
													<div class="nodata-small flexcenter">
														<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pngfile/nodata-small.png") ?>" />
														<p>Belum ada agenda terdata</p>
													</div>
												</div>
											<?php endif; ?>
										</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="widget-inner">
			<div class="nodata-small flexcenter">
				<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pngfile/nodata-small.png") ?>" />
				<p>Belum ada agenda terdata</p>
			</div>
		</div>
	<?php endif; ?>
</div>