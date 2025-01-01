<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php theme_view("commons/meta"); ?>
</head>
<body onLoad="renderDate()">
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container" style="background-color: #f6f6f6;">
	<header id="header">
		<?php theme_view("partials/header"); ?>
	</header>
	<div id="navarea">
		<?php theme_view("partials/menu_head"); ?>
	</div>
	<div class="row">
		<section>
			<div class="content_bottom">
				<div class="col-lg-12 col-md-12">
					<?php theme_view($halaman_peta); ?>
				</div>
			</div>
		</section>
	</div>
</div>
<footer id="footer">
	<?php theme_view("partials/footer_top"); ?>
	<?php theme_view("partials/footer_bottom"); ?>
</footer>
<?php theme_view("commons/meta_footer"); ?>
</body>
</html>
