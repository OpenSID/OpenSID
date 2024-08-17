<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php $this->load->view("$folder_themes/commons/meta"); ?>
</head>
<body onLoad="renderDate()">
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container" style="background-color: #f6f6f6;">
	<header id="header">
		<?php $this->load->view("$folder_themes/partials/header"); ?>
	</header>
	<div id="navarea">
			<?php $this->load->view("$folder_themes/partials/menu_head"); ?>
		</div>
		<div class="row">
			<section>
				<div class="content_middle"></div>
				<div class="content">
					<?php if ($single_artikel['tampilan'] == 1) : ?>
						<div class="col-lg-9 col-md-9">
							<div class="content_left">
								<?php $this->load->view("$folder_themes/partials/artikel"); ?>
							</div>
						</div>
						<div class="col-lg-3 col-md-3">
							<div class="content_right">
								<?php $this->load->view("$folder_themes/partials/bottom_content_right"); ?>
							</div>
						</div>
					<?php elseif ($single_artikel['tampilan'] == 2) : ?>
						<div class="col-lg-3 col-md-3">
							<div class="content_right">
								<?php $this->load->view("$folder_themes/partials/bottom_content_right"); ?>
							</div>
						</div>
						<div class="col-lg-9 col-md-9">
							<div class="content_left">
								<?php $this->load->view("$folder_themes/partials/artikel"); ?>
							</div>
						</div>
					<?php else : ?>
						<div class="col-lg-12 col-md-12">
							<div class="content_left">
								<?php $this->load->view("$folder_themes/partials/artikel"); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>
<footer id="footer">
	<?php $this->load->view("$folder_themes/partials/footer_top"); ?>
	<?php $this->load->view("$folder_themes/partials/footer_bottom"); ?>
</footer>
<?php $this->load->view("$folder_themes/commons/meta_footer"); ?>
</body>
</html>
