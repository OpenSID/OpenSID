<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="runningstyle ptb-10 bg-grey-medium">
	<div class="container-page flexleft">
		<div class="runningpage bgwhite border-grey-soft flexleft">
			<div class="runningpage-info flexleft">
				<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/white-toa.svg") ?>" alt=""/> Info
			</div>
			<?php if (!empty($teks_berjalan)): ?>
				<marquee onmouseover="this.stop()" onmouseout="this.start()" class="flexleft">
					<?php foreach ($teks_berjalan AS $teks): ?>
						<?= $teks['teks']?>
						<?php if ($teks['tautan']): ?>
							<a href="<?= $teks['tautan'] ?>" rel="noopener noreferrer" title="Baca Selengkapnya"><?= $teks['judul_tautan']?></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</marquee>
			<?php else: ?>
				<marquee onmouseover="this.stop()" onmouseout="this.start()" class="flexleft">
					Selamat datang di <?= ucwords($this->setting->website_title); ?> <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?> <?= ucwords($this->setting->sebutan_kecamatan); ?> <?= ucwords(($desa['nama_kecamatan']) ? ' ' . $desa['nama_kecamatan'] : ''); ?> <?= ucwords($this->setting->sebutan_kabupaten); ?> <?= ucwords(($desa['nama_kabupaten']) ? ' ' . $desa['nama_kabupaten'] : ''); ?> Provinsi <?= ucwords(($desa['nama_propinsi']) ? ' ' . $desa['nama_propinsi'] : ''); ?>
				</marquee>
			<?php endif; ?>
		</div>
		<div class="aparatur-top bg-color5 flexcenter tip-bottom" data-toggle="tooltip" data-original-title="" onclick="openaparatur()">
			<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/aparatur.svg") ?>" alt=""/>
		</div>
      	<?php if(setting('tampilkan_kehadiran')) : ?>
		<div class="big-screen">
			<div class="aparatur-top bg-color2 flexcenter tip-bottom" data-toggle="tooltip" data-original-title="">
				<a href="<?= site_url('kehadiran') ?>" target="_blank">
					<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/list.svg") ?>" alt=""/>
				</a>
			</div>
		</div>
      	<?php endif; ?>
		<div class="big-screen">
			<div class="aparatur-top bg-color4 flexcenter tip-bottom" data-toggle="tooltip" data-original-title="">
				<a href="<?= site_url('siteman') ?>" target="_blank">
					<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/admin.svg") ?>" alt=""/>
				</a>
			</div>
		</div>
	</div>
</div>
