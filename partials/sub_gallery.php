<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text"><a href="<?= site_url('galeri'); ?>">Album Galeri : </a> <?= $parent['nama']; ?></span></h2>
</div>

<div style="content_left">
	<?php if ($gallery): ?>
		<div class="row">
			<?php foreach ($gallery as $data): ?>
				<?php if (is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])): ?>
					<div class="col-sm-6">
						<div class="card">
							<img width="auto" class="img-fluid img-thumbnail" src="<?= AmbilGaleri($data['gambar'], 'kecil') ?>" alt="<?= $data['nama']; ?>"/>
							<p align="center"><b><?= $data['nama']; ?></b></p>
							<hr/>
						</div>
					</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>

	<?php $this->load->view("$folder_themes/commons/page"); ?>

	<?php else: ?>
		<p>Data tidak tersedia</p>
	<?php endif; ?>
</div>