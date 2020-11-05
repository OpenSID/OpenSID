<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php 
	$pages = array();
	for($i=$paging->start_link; $i<=$paging->end_link; $i++) {
		array_push($pages, $i);
	}
?>
<?php if((int) $paging->end_link > 1) : ?>
	<ul class="pagination">
		<?php if($paging->start_link) : ?>
			<li class="pagination__item">
				<a href="<?= site_url('first/'.$paging_page.'/'.$paging->start_link)?>" class="pagination__link"><i class="fa fa-angle-double-left"></i></a>
			</li>
		<?php endif ?>
		<?php if($paging->prev) : ?>
			<li class="pagination__item">
				<a href="<?= site_url('first/'.$paging_page.'/'.$paging->prev.$paging->suffix)?>" class="pagination__link"><i class="fa fa-chevron-left"></i></a>
			</li>
		<?php endif ?>
		<?php foreach($pages as $page) : ?>
			<li class="pagination__item <?php ($p == $page) and print('pagination__item--active') ?>">
				<a href="<?= site_url('first/'.$paging_page.'/'.$page.$paging->suffix)?>" class="pagination__link"><?= $page ?></a>
			</li>
		<?php endforeach ?>
		<?php if($paging->next) : ?>
			<li class="pagination__item">
				<a href="<?= site_url('first/'.$paging_page.'/'.$paging->next.$paging->suffix)?>" class="pagination__link"><i class="fa fa-chevron-right"></i></a>
			</li>
		<?php endif ?>
		<?php if($paging->end_link) : ?>
			<li class="pagination__item">
				<a href="<?= site_url('first/'.$paging_page.'/'.$paging->end_link)?>" class="pagination__link"><i class="fa fa-angle-double-right"></i></a>
			</li>
		<?php endif ?>
	</ul>
<?php endif ?>