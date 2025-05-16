<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="col-default-nopad">
<?php if (!is_null($transparansi)): ?>
	<?php if (!is_null($transparansi)) $this->load->view($folder_themes. '/plus/anggaran', $transparansi);?>	
<?php endif; ?>

<?php $this->load->view("$folder_themes/plus/map_bottom"); ?>
</div>