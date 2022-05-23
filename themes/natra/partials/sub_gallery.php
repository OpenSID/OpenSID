<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Album Galeri <?= $parrent['nama']; ?></span></h2>
</div>

<div class="content_left" style="margin-bottom:10px;">
	<ul>
		<?php foreach ($gallery as $key => $data): ?>
			<?php if (is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])): ?>
				<li>
					<div class="single_page_content">
					<a class="group2" href="<?= site_url() . "first/sub_gallery/" . $data['id'] ?>">
						<img class='img-fluid img-thumbnail' src="<?= AmbilGaleri($data['gambar'], 'kecil') ?>" />
					</a>
					</div>
					<div class="title">
						<a href="<?= site_url() . "first/sub_gallery/" . $data['id'] ?>"
						title="<?= $data["nama"] ?>" > Album : <?= $data["nama"] ?></a>
					</div>
				</li>
				<br/>
			<?php endif ?>
		<?php endforeach ?>
	</ul>

	<?php

		$data['paging_page'] = 'first/sub_gallery';

		$this->load->view("$folder_themes/commons/page", $data);

	?>
</div>