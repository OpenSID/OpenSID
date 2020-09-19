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
	<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
	<script src="<?= base_url('assets/bootstrap/js/bootstrap.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/js/DataTables/datatables.min.css')?>">
	<script type="text/javascript" charset="utf8" src="<?= base_url('assets/js/DataTables/datatables.min.js')?>"></script>
<!-- END -->
    <script src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-validation-1.17.0/dist/jquery.validate.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-validation-1.17.0/dist/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
    <style>
        #footer
        {
            color: #f83535;
            text-shadow: 1px 1px 0.5px #444;
            padding: 8px;
            text-align: center;
            bottom: 0px;
            width: 100%;
            background: #eaa852;
            height: 34px;
            position: fixed;
            z-index: 999;
        }

        input[type=search] {
            width: 200px;
            height: 30px;
        }
    </style>


<div style="padding-left:12px; color:white;" class=" nav btn-group btn-group-sm">
    <a style="color:white;" class="btn btn-primary"  <?php if($this->tab_ini == 1){?>disabled<?php }?> href="<?php echo site_url('inventaris_tanah'); ?>">Tanah</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 2){?>disabled<?php }?> href="<?php echo site_url('inventaris_peralatan'); ?>">Peralatan dan Mesin</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 3){?>disabled<?php }?> href="<?php echo site_url('inventaris_gedung'); ?>">Gedung dan Bangunan</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 4){?>disabled<?php }?> href="<?php echo site_url('inventaris_jalan'); ?>">Jalan, Irigasi, dan Jaringan</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 5){?>disabled<?php }?> href="<?php echo site_url('inventaris_asset'); ?>">Asset Tetap Lainnya</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 6){?>disabled<?php }?> href="<?php echo site_url('inventaris_kontruksi'); ?>">Kontruksi dalam pengerjaan</a>
    <a style="color:white;" class="btn btn-primary" <?php if($this->tab_ini == 7){?>disabled<?php }?> href="<?php echo site_url('laporan_inventaris'); ?>">Laporan Semua Asset</a>
</div>