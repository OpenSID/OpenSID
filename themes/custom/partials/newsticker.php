<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($teks_berjalan) : ?>
	<div class="newsticker">
		<div class="newsticker__label">
			<i class="fa fa-bullhorn"></i>
			<span>Info</span>
		</div>
		<ul class="newsticker__list">
			<?php foreach($teks_berjalan as $newsticker) : ?>
				<li class="newsticker__item">
					<?= $newsticker['teks'] ?>
					<?php if($newsticker['tautan']) : ?>
					<a href="<?= $newsticker['tautan'] ?>" class="newsticker__link"><?= $newsticker['judul_tautan']?></a>
					<?php endif ?>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>