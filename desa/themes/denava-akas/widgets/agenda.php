<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="module-agenda">
	<div class="head-widget flexleft bg-grey-dark2"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/agenda.svg") ?>" alt=""/><?= $judul_widget ?></div>
	<div class="widget-height bg-white border-grey-soft">
		<div class="widgetscroll">
			<div class="p-10">
				<div class="agenda" style="text-align:left;">
					<?php if (count(array_merge($hari_ini, $yad, $lama) ?? []) > 0): ?>
					<?php foreach ($hari_ini as $agenda): ?>
						<div class="head-small border-grey-soft"><h1>Hari Ini</h1></div>
						<div class="agenda-row">
							<a href="<?= site_url('artikel/'.buat_slug($agenda))?>"><h2><?= $agenda['judul']?></h2></a>
							<table style="width:100%;">
								<tr>
									<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/calendar.svg") ?>" alt=""/>Tgl</td>
									<td style="padding:1px 0;" width="5px">:</td>
									<td style="padding:1px 0;"><?= tgl_indo2($agenda['tgl_agenda']);?></td>
								</tr>
								<tr>
									<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location-solid.svg") ?>" alt=""/>Tempat</td>
									<td style="padding:1px 0;" width="5px">:</td>
									<td style="padding:1px 0;"><?= $agenda['lokasi_kegiatan']?></td>
								</tr>
								<tr>
									<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/user-solid.svg") ?>" alt=""/>Koordinator</td>
									<td style="padding:1px 0;" width="5px">:</td>
									<td style="padding:1px 0;"><?= $agenda['koordinator_kegiatan']?></td>
								</tr>
							</table>
						</div>
						<?php endforeach; ?>
						<?php foreach ($yad as $agenda): ?>
							<div class="head-small border-grey-soft"><h1>Mendatang</h1></div>
							<div class="agenda-row">
								<a href="<?= site_url('artikel/'.buat_slug($agenda))?>"><h2><?= $agenda['judul']?></h2></a>
								<table style="width:100%;">
									<tr>
										<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/calendar.svg") ?>" alt=""/>Tgl</td>
										<td style="padding:1px 0;" width="5px">:</td>
										<td style="padding:1px 0;"><?= tgl_indo2($agenda['tgl_agenda'])?></td>
									</tr>
									<tr>
										<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location-solid.svg") ?>" alt=""/>Tempat</td>
										<td style="padding:1px 0;" width="5px">:</td>
										<td style="padding:1px 0;"><?= $agenda['lokasi_kegiatan']?></td>
									</tr>
									<tr>
										<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/user-solid.svg") ?>" alt=""/>Koordinator</td>
										<td style="padding:1px 0;" width="5px">:</td>
										<td style="padding:1px 0;"><?= $agenda['koordinator_kegiatan']?></td>
									</tr>
								</table>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="nodata-small flexcenter">
							<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofdata1.png") ?>" alt=""/>
							<p>Untuk sementara, belum ada agenda yang akan dilaksanakan.</p>
						</div>
					<?php endif; ?>
				</div>	
				<div class="agenda" style="text-align:left;">
					<?php if (count($lama ?? []) > 0): ?>
						<?php foreach ($lama as $agenda): ?>
							<h1><div class="head-small border-grey-soft"><h1>Terdahulu</h1></div></h1>	
							<div class="agenda-row">
								<a href="<?= site_url('artikel/'.buat_slug($agenda))?>"><h2><?= $agenda['judul']?></h2></a>
								<table style="width:100%;">
									<tr>
										<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/calendar.svg") ?>" alt=""/>Tgl</td>
										<td style="padding:1px 0;" width="5px">:</td>
										<td style="padding:1px 0;"><?= tgl_indo2($agenda['tgl_agenda'])?></td>
									</tr>
									<tr>
										<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location-solid.svg") ?>" alt=""/>Tempat</td>
										<td style="padding:1px 0;" width="5px">:</td>
										<td style="padding:1px 0;"><?= $agenda['lokasi_kegiatan']?></td>
									</tr>
									<tr>
										<td class="flexleft" style="padding:1px 0;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/user-solid.svg") ?>" alt=""/>Koordinator</td>
										<td style="padding:1px 0;" width="5px">:</td>
										<td style="padding:1px 0;"><?= $agenda['koordinator_kegiatan']?></td>
									</tr>
								</table>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>	
				</div>	
			</div>
			<div class="gradient-white-bottom"></div>
		</div>	
	</div>	
</div>
