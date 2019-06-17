<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $paging_page ? $paging_page : $paging_page = 'arsip' ?>
<?php 
	if ($paging_page == 'arsip') {
		$pages = array();
		for($i=$paging->start_link; $i<=$paging->end_link; $i++) {
			array_push($pages, $i);
		}
	}
?>
<?php if((int) $paging->end_link > 1) : ?>
	<div class="paging mt-4 mb-5 mx-auto">
		<div class="col-12 col-sm-12">
			<div class="halaman-ke">
				<span>Halaman <?= $p ?> dari <?= $paging->end_link ?></span>
			</div>
			<ul class="pagination pagination-md justify-content-center">
				<?php if($paging->start_link) : ?>
					<li class="page-item">
						<a href="<?= site_url('first/'.$paging_page.'/'.$paging->start_link)?>" class="page-link" title="Halaman Awal">
							<i class="fa fa-fast-backward"></i>
						</a>
					</li>
				<?php endif ?>
				<?php foreach ($pages as $i): ?>
					<li class="page-item <?php ($p == $i) and print('active') ?>">
						<a class="page-link" href="<?= site_url('first/'.$paging_page.'/'.$i. $paging->suffix) ?>" title="Halaman <?= $i ?>"><?= $i ?></a>
					</li>
				<?php endforeach; ?>
				<?php if($paging->next) : ?>
					<li class="page-item">
						<a href="<?= site_url('first/'.$paging_page.'/'.$paging->next . $paging->suffix)?>" class="page-link" title="Halaman Selanjutnya">
							<i class="fa fa-forward"></i>
						</a>
					</li>
				<?php endif ?>
				<?php if($paging->end_link) : ?>
					<li class="page-item">
						<a href="<?= site_url('first/'.$paging_page.'/'.$paging->end_link . $paging->suffix)?>" class="page-link" title="Halaman Akhir">
							<i class="fa fa-fast-forward"></i>
						</a>
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
<?php endif ?>