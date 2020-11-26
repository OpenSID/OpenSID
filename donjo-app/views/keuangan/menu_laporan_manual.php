<div class="panel-group card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title">Grafik Laporan Keuangan</h3>
		<div class="card-tools">
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar"><i class="plus-minus fa fa-minus"></i></button>
		</div>
	</div>
	<div class="card-body collapse show navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
			<li <?php if ($_SESSION['submenu'] == "Grafik Keuangan"): ?>class="nav-item active"<?php endif; ?>><a class="nav-link" href="<?= site_url('keuangan_manual/grafik_manual/grafik-RP-APBD-manual')?>">Grafik Pelaksanaan APBDes</a></li>
		</ul>
	</div>
</div>
<div class="panel-group card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title">Tabel Laporan (Belanja Per Bidang)</h3>
		<div class="card-tools">
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar1"><i class="plus-minus fa fa-minus"></i></button>
		</div>
	</div>
	<div class="card-body collapse show navbar-collapse" id="collapsibleNavbar1">
		<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
			<li <?php if ($_SESSION['submenu'] == "Laporan Keuangan Akhir Bidang Manual"): ?>class="nav-item active"<?php endif; ?>><a class="nav-link" href="<?=site_url("keuangan_manual/grafik_manual/rincian_realisasi_bidang_manual")?>">Laporan Pelaksanaan APBDes Manual</a></li>
		</ul>
	</div>
</div>
<script>
	$(function() {
	  function toggleIcon(e) {
	      $(e.target)
	          .prev('.card-header')
	          .find(".plus-minus")
	          .toggleClass('fa-plus fa-minus');
	  }
	  $('.panel-group').on('hidden.bs.collapse', toggleIcon);
	  $('.panel-group').on('shown.bs.collapse', toggleIcon);
	});
</script>
