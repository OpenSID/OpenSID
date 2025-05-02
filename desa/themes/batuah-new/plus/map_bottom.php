<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
.maphome .leaflet-left {left:auto !important;right:10px !important;margin-top:35px;}
</style>

<div class="col-default-nopad margin-top-10">
<div class="margin-side-10">
	<div class="rowsame margin-minlr-5">
		<div class="mapleft bgwhite bordergrey">
			<?php $this->load->view("$folder_themes/widgets/peta_lokasi_kantor"); ?>
		</div>
		<div class="mapright bgwhite bordergrey">
			<?php $this->load->view("$folder_themes/widgets/peta_wilayah_desa"); ?>
		</div>
	</div>
</div>
</div>
