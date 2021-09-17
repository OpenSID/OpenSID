<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div style="content_bottom_left">
	<div class="single_page_area"><h2>Arsip Galeri <?= $desa["nama_desa"] ?></h2></div>
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
	</div>

	<div class="pagination_area">
		<?php // TODO : butuh helper untuk menggenerate html tag untuk paging ?>
		<div>Halaman <?= $p ?> dari  <?= $paging->end_link ?></div>
		<ul class="pagination">

		<?php if ($paging->start_link): ?>
			<li><a href="<?= site_url("first/gallery/$paging->start_link") ?>"
					title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a>
			</li>
		<?php endif ?>

		<?php if ($paging->prev): ?>
			<li>
				<a href="<?= site_url("first/gallery/$paging->prev") ?>" 
					title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a>
			</li>
		<?php endif ?>

		<?php foreach($pages as $i): ?> 
		<?php $strC = ($p == $i) ? 'class="active"' : '' ?>
			<li <?= $strC ?> >
				<a href="<?= site_url("first/gallery/$i") ?>" title="Halaman <?= $i ?>"><?= $i ?></a>
			</li>
		<?php endforeach ?>

		<?php if($paging->end_link): ?>
			<li><a href="<?= site_url("first/gallery/$paging->end_link") ?>"
					title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a>
			</li>
		<?php endif ?>
		</ul>
	</div>
</div>
