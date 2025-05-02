<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="aparatur" class="rightpanel">
	<div class="panel-cover bg-gradient-hor"></div>
	<div class="rightpanel-container">
		<div class="head-aparatur-top bg-grey-dark2 flexcenter">
		<?= ucwords(setting('sebutan_pemerintah_desa')) ?>
		</div>
		<?php $this->load->view($folder_themes . "/partials/home/aparatur"); ?>
	</div>
	<a href="javascript:void(0)" onclick="closeaparatur()">
		<div class="close-area flexcenter">
			<div class="close-button bg-white"></div>
		</div>
	</a>
</div>
<script src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/js/wow.min.js") ?>"></script>
<script src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/js/slick.min.js") ?>"></script>
<script src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/js/custom.js") ?>"></script>
<?php if (! setting('inspect_element')): ?>
<script src="<?= asset('js/disabled.min.js') ?>"></script>
<?php endif ?>
