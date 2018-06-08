<style>
	.nav-sidebar > .active > a, .nav-sidebar > .active > a:hover, .nav-sidebar > .active > a:focus {
		color: #fff;
		background-color: #428bca;
	}

</style>

<div class="sidebar">
	<ul class="nav nav-sidebar" style="margin: 0px;">
    	<li <?php if($data == 1){ echo "class='active'";} ?>><a href="<?php echo site_url('inventaris_tanah')?>"><i class="fa fa-list-alt"></i>&nbsp;<span>Daftar Inventaris </span></a></li>
        <li <?php if($data == 2){ echo "class='active'";} ?>><a href="<?php echo site_url('inventaris_tanah/mutasi')?>"><i class="fa fa-share-alt"></i>&nbsp;<span>Mutasi Inventaris </span></a></li>
    </ul>
</div>