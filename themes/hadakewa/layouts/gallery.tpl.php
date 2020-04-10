<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php $this->load->view($folder_themes.'/partials/gallery.php');?>
					</div>
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/side.right.php'));?>
				</div>
			</div>

			<div id="footer">
				<?php $this->load->view($folder_themes.'/partials/copywright.tpl.php');?>
			</div>
		</div>
		<?php if ($gallery): ?>
			<script src="<?= base_url()?>assets/front/js/jquery.colorbox.js"></script>
			<script>
				$(".group2").colorbox({rel:'group2', transition:"fade"});
			</script>
		<?php endif ?>
	</body>
</html>
