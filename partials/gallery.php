<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Galeri <?= $desa["nama_desa"] ?></span></h2>
</div>

<div style="content_left">
	<ul>
	<?php $i = 1; ?>
	<?php foreach($gallery AS $data): ?>
		<?php if(is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])): ?>
			<li>
				<div class="single_page_content">
				<a class="group2" href="<?= site_url() . "first/sub_gallery/" . $data['id'] ?>">
					<img class='img-fluid img-thumbnail' src="<?= AmbilGaleri($data['gambar'],'kecil') ?>" />
				</a>
				</div>
				<div class="title">
					<a href="<?= site_url() . "first/sub_gallery/" . $data['id'] ?>"
					title="<?= $data["nama"] ?>" > Album : <?= $data["nama"] ?></a>
				</div>
			</li>
			<?php if (fmod($i,2) == 0): ?>
				<br class="clearboth">
			<?php endif ?>
		<?php endif ?>
	<?php endforeach ?>
	</ul>
	<br class="clearboth">

	<?php

		$data['paging_page'] = 'first/gallery';

		$this->load->view("$folder_themes/commons/page", $data);

	?>
</div>
