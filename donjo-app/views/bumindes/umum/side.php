<style type="text/css">
  .mini-submenu{
    display:none;
    background-color: rgba(0, 0, 0, 0);
    border: 1px solid rgba(0, 0, 0, 0.9);
    border-radius: 4px;
    padding: 9px;
    /*position: relative;*/
    width: 42px;
    cursor: pointer;
  }
</style>

<button class="mini-submenu">
  <i class="fas fa-bars"></i>
</button>

<div class="list-group panel-group card card-outline card-info">
	<div class="card-header with-border">
		<h1 class="card-title">Buku Administrasi Umum</h1>
		<div class="card-tools">
			<button id="slide-submenu" type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar"><i class="fas fa-bars"></i></button>
		</div>
	</div>
	<div class="card-body collapse show navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
      <li class="<?php compared_return($selected_nav, "peraturan", "active"); ?>"><a class="nav-link" href="<?= site_url('dokumen_sekretariat/clear/3') ?>"><i class="fas fa-book"></i> Buku Peraturan Desa </a></li>
      <li class="<?php compared_return($selected_nav, "keputusan", "active"); ?>"><a class="nav-link" href="<?= site_url('dokumen_sekretariat/clear/2') ?>"><i class="fas fa-book"></i> Buku Keputusan Kepala Desa </a></li>
      <li class="<?php compared_return($selected_nav, "aparat", "active"); ?>"><a class="nav-link" href="<?= site_url('pengurus') ?>"><i class="fas fa-sitemap"></i> Buku Aparat Pemerintah Desa </a></li>
      <li class="<?php compared_return($selected_nav, "agenda_keluar", "active"); ?>"><a class="nav-link" href="<?= site_url('surat_keluar') ?>"><i class="fas fa-envelope-open"></i> Buku Agenda - Surat Keluar </a></li>
      <li class="<?php compared_return($selected_nav, "agenda_masuk", "active"); ?>"><a class="nav-link" href="<?= site_url('surat_masuk') ?>"><i class="far fa-envelope-open"></i> Buku Agenda - Surat Masuk </a></li>
      <li class="<?php compared_return($selected_nav, "ekspedisi", "active"); ?>"><a class="nav-link" href="<?= site_url('ekspedisi/clear') ?>"><i class="fas fa-car"></i> Buku Ekspedisi </a></li>
      <li class="<?php compared_return($selected_nav, "lembaran", "active"); ?>"><a class="nav-link" href="<?= site_url('buku_umum/lembaran_desa/clear') ?>"><i class="fas fa-book"></i> Buku Lembaran dan Berita Desa </a></li>
		</ul>
	</div>
</div>

<script type="text/javascript">
  $(function(){

    $('#slide-submenu').on('click',function() {
      $(this).closest('.list-group').fadeOut('slow',function(){
        $('.mini-submenu').fadeIn();
      });
      $('#umum-sidebar').removeClass("col-sm-3");
      $('#umum-sidebar').addClass("col-sm-1");
      $('#umum-content').removeClass("col-sm-9");
      $('#umum-content').addClass("col-sm-11");
    });

    $('.mini-submenu').on('click',function(){
      $(this).next('.list-group').fadeIn('slow');
      $('.mini-submenu').hide();
      $('#collapsibleNavbar').addClass("show");
      $('#umum-sidebar').removeClass("col-sm-1");
      $('#umum-sidebar').addClass("col-sm-3");
      $('#umum-content').removeClass("col-sm-11");
      $('#umum-content').addClass("col-sm-9");
    })
  })
</script>
