<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<aside class="sidebar">
	<form action="<?= site_url('first') ?>" class="form form--sidebar" method="get">
		<input type="search" name="cari" class="form__input" placeholder="Cari...">
		<i class="fa fa-search form__icon"></i>
	</form>
	<?php foreach($w_cos as $widget) : ?>
		<?php if ($widget["jenis_widget"] == 1): ?>
			<?php if($widget['isi'] == 'keuangan.php') : ?>
				<?php continue; ?>
			<?php endif ?>
			<div class="sidebar__item">
				<?php include('donjo-app/views/widgets/'.$widget['isi']) ?>
			</div>
			<?php elseif($widget['jenis_widget'] == 2) : ?>
				<div class="sidebar__item">
					<div class="panel panel--sidebar">
						<div class="panel__header">
							<h3 class="panel__title"><?= strip_tags($widget['judul']) ?></h3>
						</div>
						<div class="panel__body">
							<?php include($widget['isi']) ?>
						</div>
					</div>
				</div>
			<?php else : ?>
				<div class="sidebar__item">
					<div class="panel panel--sidebar">
						<div class="panel__header">
							<h3 class="panel__title"><?= strip_tags($widget['judul']) ?></h3>
						</div>
						<div class="panel__body">
							<?= html_entity_decode($widget['isi']) ?>
						</div>
					</div>
				</div>
		<?php endif ?>
	<?php endforeach ?>
</aside>