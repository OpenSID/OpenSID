<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_bottom_rightbar">
	<h2><i class="fa fa-book"></i> <a href="<?= site_url('galeri'); ?>">&ensp;<?= $judul_widget ?></a></h2>
	<div class="latest_slider">
		<div class="slick_slider">
			<?php foreach ($w_gal As $data): ?>
				<?php if (is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])): ?>
					<div class="single_iteam"><img src="<?= AmbilGaleri($data['gambar'],'kecil')?>" alt="Album : <?= "$data[nama]" ?>">
						<h2><a class="slider_tittle" href="<?= site_url("galeri/$data[id]"); ?>"><?= "$data[nama]" ?></a></h2>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
