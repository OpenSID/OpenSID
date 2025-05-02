<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
.maphome .leaflet-left {left:auto !important;right:10px !important;margin-top:35px;}
</style>

<div class="col-default">
	<div class="rowsame margin-minlr-5">
		<div class="mapleft bgwhite bordergrey1">
			<?php $this->load->view("$folder_themes/widgets/peta_lokasi_kantor"); ?>
		</div>
		<div class="mapright bgwhite bordergrey1">
			<?php $this->load->view("$folder_themes/widgets/peta_wilayah_desa"); ?>
		</div>
	</div>
</div>

