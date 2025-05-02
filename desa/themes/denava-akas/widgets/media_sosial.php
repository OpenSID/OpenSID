<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include(FCPATH . "$this->theme_folder/$this->theme/commons/social_icons.php"); ?>
<div class="footer-social flexcenter">
	<?php foreach($sosmed as $data) : ?>
		<?php if(!empty($data['link'])) : ?>
			<a href="<?= $data['link'] ?>" target="_blank" rel="noopener">
			<img src="<?= $data['icon'] ?>" class="img-responsive cover" style="width:30px;height:30px;" alt="">
			</a>
		<?php endif ?>
	<?php endforeach ?>
</div>
