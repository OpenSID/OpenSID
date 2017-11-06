<style>
	#sidecontent3 li{
		padding:5px 3px;
	}
</style>
<table class="inner">
<tr style="vertical-align:top">
<td class="side-menu" style="background:#fff;padding:0px;">
<h3>MENU ANALISIS</h3>
<hr>
<div id="sidecontent7" class="lmenu" >
<h4>PENGATURAN ANALISIS</h4>
<?php //echo $_SESSION['submenu']?>
<ul>
	<li <?php if($_SESSION['submenu'] == "Data Kategori")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_kategori/clear")."/".$analisis_master['id']; ?>">KATEGORI/VARIABEL</a>
	</li>
	<li <?php if($_SESSION['submenu'] == "Data Indikator")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_indikator/clear")."/".$analisis_master['id']; ?>">INDIKATOR & PERTANYAAN</a>
	</li>
	<li <?php if($_SESSION['submenu'] == "Data Klasifikasi")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_klasifikasi/clear")."/".$analisis_master['id']; ?>">KLASIFIKASI ANALISIS</a>
	</li>
	<li <?php if($_SESSION['submenu'] == "Data Periode")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_periode/clear")."/".$analisis_master['id']; ?>">PERIODE SENSUS/SURVEI</a>
	</li>
	</ul>
	<hr>
<h4>INPUT DATA ANALISIS</h4>
	<ul>
	<li <?php if($_SESSION['submenu'] == "Input Data")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_respon/clear")."/".$analisis_master['id']; ?>">INPUT DATA SENSUS/SURVEI</a>
	</li>
	</ul>
	<hr>
<h4>LAPORAN ANALISIS</h4>
	<ul>
	<li <?php if($_SESSION['submenu'] == "Laporan Analisis")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_laporan/clear")."/".$analisis_master['id']; ?>">LAPORAN HASIL KLASIFIKASI</a>
	</li>
	<li <?php if($_SESSION['submenu'] == "Statistik Jawaban")echo "class='selected'";?>>
		<a href="<?php echo site_url("analisis_statistik_jawaban/clear")."/".$analisis_master['id']; ?>">LAPORAN PER INDIKATOR</a>
	</li>
</ul>
</td>
<td style="background:#fff;padding:0px;">