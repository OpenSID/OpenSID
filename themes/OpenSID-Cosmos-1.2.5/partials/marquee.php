<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="teks-berjalan marquee-container">
	<div class="container">
		<?php if($teks_berjalan) : ?>
			<div class="teks-wrapper">
				<span>INFO</span>
			</div>
			<ul class="marquee" id="marquee">
			<?php foreach($teks_berjalan as $data) : ?>
				<li>
					<?= strip_tags($data['isi']) ?>
				</li>
			<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div>
</div>