<div id="pageC"> 
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px 5px;"> 
<div id="contentpane">
<div class="ui-layout-north panel">
<h3><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
	<div style="max-width:640px;text-align:justify;border:1px solid #cecece;padding:10px 10px 10px 4px;background:#efffef;">
	<?php echo $analisis_master['deskripsi']?><br /><br /><br />
	</div>
	<br>
	<h3>MENU ANALISIS</h3>
	<a class="csurat2" href="<?php echo site_url()?>analisis_kategori/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/document-open-8.png" alt="sss"/>
		<span>Kategori Pertanyaan</span>
	</a>
	<a class="csurat2" href="<?php echo site_url()?>analisis_indikator/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/format-list-ordered.png" alt="sss"/>
		<span>Tipe Pertanyaan</span>
	</a>
	<a class="csurat2" href="<?php echo site_url()?>analisis_klasifikasi/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/view-sort-ascending.png" alt="sss"/>
		<span>Klasifikasi Hasil Analisis</span>
	</a>
	<a class="csurat2" href="<?php echo site_url()?>analisis_periode/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/view-pim-calendar.png" alt="sss"/>
		<span>Periode Sensus</span>
	</a>
	<a class="csurat2" href="<?php echo site_url()?><?php echo $menu_respon?>/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/view-process-users.png" alt="sss"/>
		<span>Input Data Sensus</span>
	</a>
	<a class="csurat2" href="<?php echo site_url()?><?php echo $menu_laporan?>/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/documentation.png" alt="sss"/>
		<span>Laporan Hasil</span>
	</a>
	<?php /*
	<a class="csurat2" href="<?php echo site_url()?>analisis_grafik/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/office-chart-bar-stacked.png" alt="sss"/>
		<span>Grafik / Diagram Data Analisis</span>
	</a>
	<a class="csurat2" href="<?php echo site_url()?>analisis_grafik/time">
		<img src="<?php echo base_url()?>assets/images/cpanel/office-chart-line.png" alt="sss"/>
		<span>Grafik Periode Analisis</span>
	</a>
	*/ ?>
	<a class="csurat2" href="<?php echo site_url()?>analisis_statistik_jawaban/clear/<?php echo $analisis_master['id']?>">
		<img src="<?php echo base_url()?>assets/images/cpanel/format-list-ordered.png" alt="sss"/>
		<span>Statistik Jawaban</span>
	</a>
</div>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_master" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
</div>
</div>
</div> 
</td>
</tr>
</table>
</div>