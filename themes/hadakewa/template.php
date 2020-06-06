<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view($folder_themes.'/layouts/header.php');?>
			<div id="contentwrapper">
				<!-- Ubah setting di desa/config/config.php untuk menampilkan/menyembunyikan data COVID-19 -->
				<?php if ($this->setting->covid_data) $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/covid.php'));?>
				<?php if ($this->setting->covid_desa) $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/covid_local.php')); ?>
			</div>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php	$this->load->view(Web_Controller::fallback_default($this->theme, '/partials/content.php'));?>
					</div>
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/side.right.php'));?>
				</div>
			</div>

			<div id="footer">
				<?php $this->load->view($folder_themes. '/partials/copywright.tpl.php');?>
			</div>
		</div>
	</body>
</html>
