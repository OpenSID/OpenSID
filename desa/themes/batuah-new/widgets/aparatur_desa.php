<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="col-default">
	<div class="aparatur bgwhite bordergrey">
	<div class="aparatur-head flexleft">
		<div class="head-icon bgcolor-1 flexcenter">
		<svg viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
		</div>
		<h1>Aparatur <?= ucwords($this->setting->sebutan_desa); ?></h1>
	</div>
		<div class="margin-minlr-5">
			<div class="bcarousel js-flickity" data-flickity='{ "autoPlay": true, "cellAlign": "left", "wrapAround": true }'>
				<?php foreach ($aparatur_desa['daftar_perangkat'] as $data) : ?>
					<div class="bcarousel-part">
						<div class="margin-lr-5">
						<div class="framepimpinan bgcolor-3">
						<div class="image-aparatur">
							<img src="<?= $data['foto'] ?>">
							<div class="staf-nama">
								<h3><?= $data['jabatan'] ?></h3>
								<p><?= $data['nama'] ?></p>
							</div>
						</div>
						</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

