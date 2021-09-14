<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php $this->load->view("$folder_themes/commons/meta.php"); ?>
</head>
<body>
<!--
<div id="preloader">
	<div id="status">&nbsp;</div>
</div>
-->
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container"style="background-color: #f6f6f6;">
	<header id="header">
		<?php $this->load->view("$folder_themes/partials/header.php"); ?>
	</header>
	<div id="navarea">
		<?php $this->load->view("$folder_themes/partials/menu_head.php"); ?>
	</div>
	<div class="row">
		<section>
			<div class="content_middle"></div>
			<div class="content_bottom">
				<div class="col-lg-9 col-md-9">
					<div class="content_bottom_left">
						<?php $this->load->view($folder_themes.'/partials/sub_gallery.php');?>
					</div>
				</div>
				<div class="col-lg-3 col-md-3">
					<?php $this->load->view("$folder_themes/partials/bottom_content_right.php"); ?>
				</div>
			</div>
		</section>
	</div>
</div>
<footer id="footer">
	<?php $this->load->view("$folder_themes/partials/footer_top.php"); ?>
	<?php $this->load->view("$folder_themes/partials/footer_bottom.php"); ?>
</footer>
<?php $this->load->view("$folder_themes/commons/meta_footer.php"); ?>
</body>
</html>
