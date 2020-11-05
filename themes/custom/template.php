<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view($folder_themes .'/commons/meta') ?>
	<?php $this->load->view($folder_themes .'/commons/source_css') ?>
</head>
<body>
	<?php if($this->uri->segment(2) == 'kategori' && empty($judul_kategori)) : ?>
		<?php $this->load->view($folder_themes .'/commons/404') ?>
		<?php else : ?>
			<?php $this->load->view($folder_themes .'/commons/header') ?>
			<?php $this->load->view($folder_themes .'/partials/newsticker') ?>
			<section class="main-wrapper">
				<main class="content">
					<?php $this->load->view($folder_themes .'/layouts/beranda.tpl.php') ?>
				</main>
				<?php $this->load->view($folder_themes .'/partials/sidebar.php') ?>
			</section>
			<?php $this->load->view($folder_themes .'/commons/footer') ?>
	<?php endif ?>
	<?php $this->load->view($folder_themes .'/commons/source_js') ?>
</body>
</html>