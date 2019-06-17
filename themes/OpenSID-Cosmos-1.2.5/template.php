<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view($folder_themes .'/commons/meta') ?>
	<?php $this->load->view($folder_themes .'/commons/source_css') ?>
</head>
<body>
	<div id="loader-wrapper">
		<div id="loader"></div>
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>
	</div>
	<?php if($this->uri->segment(2) == 'kategori' && empty($judul_kategori)) : ?>
		<?php $this->load->view($folder_themes .'/commons/404') ?>
		<?php else : ?>
			<?php $this->load->view($folder_themes .'/commons/header') ?>
			<?php $this->load->view($folder_themes .'/commons/nav') ?>
			<?php $this->load->view($folder_themes .'/partials/content') ?>
			<?php $this->load->view($folder_themes .'/commons/footer') ?>
	<?php endif ?>
	<?php $this->load->view($folder_themes .'/commons/source_js') ?>
</body>
</html>