<style>
#userbox .avatar {
    width: 32px;
    height: 31px;
}
#userbox .info {
    font-size: 10px;
}

</style>
<!-- START -->
<!-- boostrap dan datatables -->
<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<!-- jQuery library -->
	<script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js'); ?>"></script>
<!-- Latest compiled JavaScript -->
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/js/DataTables/datatables.min.css')?>">
	<script type="text/javascript" charset="utf8" src="<?php echo base_url('assets/js/DataTables/datatables.min.js')?>"></script>
<!-- END -->
		

<div style="padding-left:12px; color:white;" class=" nav btn-group btn-group-sm">
    <a style="color:white;" class="btn btn-primary"  <?php if($this->tab_ini == 1){?>disabled<?php }?> href="<?php echo site_url('inventaris_tanah'); ?>">Tanah</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 2){?>disabled<?php }?> href="<?php echo site_url('inventaris_peralatan'); ?>">Peralatan dan Mesin</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 3){?>disabled<?php }?> href="<?php echo site_url('inventaris_gedung'); ?>">Gedung dan Bangunan</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 4){?>disabled<?php }?> href="<?php echo site_url('inventaris_jalan'); ?>">Jalan, Irigasi, dan Jaringan</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 5){?>disabled<?php }?> href="<?php echo site_url('inventaris_asset'); ?>">Asset Tetap Lainnya</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 6){?>disabled<?php }?> href="<?php echo site_url('inventaris_kontruksi'); ?>">Kontruksi dalam pengerjaan</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 7){?>disabled<?php }?> href="<?php echo site_url('laporan_inventaris'); ?>">Laporan Semua Asset</a>
</div>
