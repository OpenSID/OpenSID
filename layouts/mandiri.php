<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view("$folder_themes/commons/meta.php"); ?>
</head>
<body>
	<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
	<div class="container"style="background-color: #f6f6f6;">
	<header id="header">
		<?php $this->load->view("$folder_themes/partials/header.php"); ?>
	</header>
	<div id="navarea">
		<?php $this->load->view("$folder_themes/partials/menu_head.php"); ?>
	</div>
	<section id="mainContent">
		<div class="content_middle"></div>
		<div class="content_bottom">
			<div class="col-lg-9 col-md-9">
				<div class="content_bottom_left"><?php
							$views_partial_layout = '';
							switch($m){
								case 1 :
									$views_partial_layout = $folder_themes.'/partials/mandiri.php';
									break;
								case 2 :
									$views_partial_layout = $folder_themes.'/partials/layanan.php';
									break;
								case 4 :
									$views_partial_layout = $folder_themes.'/partials/bantuan.php';
									break;
								default:
									$views_partial_layout = $folder_themes.'/partials/lapor.php';
							}
							$this->load->view($views_partial_layout); ?>
				</div>
			</div>
			<div class="col-lg-3 col-md-3">
				<?php $this->load->view("$folder_themes/partials/bottom_content_right.php"); ?>
			</div>
		</div>
	</section>
	</div>
	<footer id="footer">
		<?php $this->load->view("$folder_themes/partials/footer_top.php"); ?>
		<?php $this->load->view("$folder_themes/partials/footer_bottom.php"); ?>
	</footer>
	<?php $this->load->view("$folder_themes/commons/meta_footer.php"); ?>
</body>
</html>
