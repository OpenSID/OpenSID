<style>
	.nav-sidebar > .active > a, .nav-sidebar > .active > a:hover, .nav-sidebar > .active > a:focus {
		color: #fff;
		background-color: #428bca;
	}

</style>

<div class="sidebar">
	<ul class="nav nav-sidebar" style="margin: 0px;">
  	<li <?= ($data == 1) ? "class='active'" : "" ?>><a href="<?= site_url('laporan_inventaris')?>"><i class="fa fa-list-alt"></i>&nbsp;<span>Laporan Keseluruhan Asset </span></a></li>
    <li <?= ($data == 2) ? "class='active'" : "" ?>><a href="<?= site_url('laporan_inventaris/mutasi')?>"><i class="fa fa-list-alt"></i>&nbsp;<span>Laporan Asset Yang Dihapus </span></a></li>
  </ul>
</div>