<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view($folder_themes . '/commons/meta') ?>
	<?php $this->load->view($folder_themes . '/commons/source_css') ?>
</head>
<body data-theme="light">

	<?php $this->load->view($folder_themes . '/commons/header') ?>
	<?php $this->load->view($folder_themes .'/partials/newsticker') ?>

	<script>
		const enable3d = <?=$this->setting->statistik_chart_3d ?> ? true : false;
	</script>

	<section class="main-wrapper">
		<main class="content">
			<?php 
				switch ($tipe) {
					case '0':
						$page = '/partials/statistics/default';
						break;
					case '3':
						$page = '/partials/statistics/regions';
						break;
					case '4':
						$page = '/partials/statistics/voters';
					default:
						$page = '/commons/404';
						break;
				}
			?>
			<?php $this->load->view($folder_themes . $page) ?>
		</main>
		<?php $this->load->view($folder_themes .'/partials/sidebar.php') ?>
	</section>
	<?php $this->load->view($folder_themes .'/commons/footer') ?>
	<?php $this->load->view($folder_themes . '/commons/source_js') ?>
</body>
</html>