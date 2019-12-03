<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div style="margin-left: .5em">
	<div class="box box-primary box-solid">
		<div class="box-header">
			<h3 class="box-title">Arsip Galeri <?= $desa['nama_desa'] ?></h3>
		</div>
		<div class="box-body">
			<ul class="thumbnail">
				<?php $i = 1 ?>
				<?php foreach($gallery as $data) : ?>
					<?php if(is_file(LOKASI_GALERI . 'sedang_' . $data['gambar'])) : ?>
						<li>
							<div class="entry">
								<a href="<?= AmbilGaleri($data['gambar'],'sedang') ?>" class="group2">
									<img src="<?= AmbilGaleri($data['gambar'], 'kecil') ?>" alt="">
								</a>
							</div>
							<div class="title">
								<a href="<?= site_url('first/sub_gallery/' . $data['id']) ?>" title="<?= $data['nama'] ?>">Album : <?= $data['nama'] ?></a>
							</div>
						</li>
					<?php endif ?>
					<?php if(fmod($i, 2) == 0) : ?>
						<div class="clearboth"></div>
					<?php endif ?>
					<?php $i++ ?>
				<?php endforeach ?>
			</ul>
			<div class="clearboth"></div>
		</div>
		<div class="box-footer">
			<p>Halaman <?= $p ?> dari <?= $paging->end_link ?></p>
			<ul class="pagination	pagination-sm no-margin">
				<?php if($paging->start_link) : ?>
					<li>
						<a href="<?= site_url('first/gallery/'.$paging->start_link)?>" title="Halaman Awal">
							<i class="fa fa-fast-backward"></i>&nbsp;
						</a>
					</li>
				<?php endif ?>
				<?php foreach ($pages as $i): ?>
					<li class="<?php ($p == $i) and print('active') ?>">
						<a class="page-link" href="<?= site_url('first/gallery/'.$i. $paging->suffix) ?>" title="Halaman <?= $i ?>"><?= $i ?></a>&nbsp;
					</li>
				<?php endforeach; ?>
				<?php if($paging->next) : ?>
					<li>
						<a href="<?= site_url('first/gallery/'.$paging->next . $paging->suffix)?>" title="Halaman Selanjutnya">
							<i class="fa fa-forward"></i>
						</a>
					</li>
				<?php endif ?>
				<?php if($paging->end_link) : ?>
					<li>
						<a href="<?= site_url('first/gallery/'.$paging->end_link . $paging->suffix)?>" title="Halaman Akhir">
							<i class="fa fa-fast-forward"></i>&nbsp;
						</a>
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
</div>
