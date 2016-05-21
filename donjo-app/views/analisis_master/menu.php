<div id="pageC"> 
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px 5px;"> 
<div id="contentpane">
<div class="ui-layout-north panel"><h3><a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<?=$analisis_master['deskripsi']?><br /><br /><br />

	<a class="csurat2" href="<?=site_url()?>analisis_kategori/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/document-open-8.png" alt="sss"/>
		<span>Kategori Pertanyaan</span>
	</a>

	<a class="csurat2" href="<?=site_url()?>analisis_indikator/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/format-list-ordered.png" alt="sss"/>
		<span>Tipe Pertanyaan</span>
	</a>

	<a class="csurat2" href="<?=site_url()?>analisis_klasifikasi/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/view-sort-ascending.png" alt="sss"/>
		<span>Klasifikasi Hasil Analisis</span>
	</a>

	<a class="csurat2" href="<?=site_url()?>analisis_periode/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/view-pim-calendar.png" alt="sss"/>
		<span>Periode Sensus</span>
	</a>

	<a class="csurat2" href="<?=site_url()?><?=$menu_respon?>/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/view-process-users.png" alt="sss"/>
		<span>Input Data Sensus</span>
	</a>

	<a class="csurat2" href="<?=site_url()?><?=$menu_laporan?>/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/documentation.png" alt="sss"/>
		<span>Laporan Hasil Analisis</span>
	</a>
<?/*
	<a class="csurat2" href="<?=site_url()?><?=$menu_laporan?>/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/documentation.png" alt="sss"/>
		<span>Laporan Rentang/ Klasifikasi</span>
	</a>

	<a class="csurat2" href="<?=site_url()?><?=$menu_laporan?>/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/documentation.png" alt="sss"/>
		<span>Statistik Indikator/ Parameter</span>
	</a>
*/?>
	<a class="csurat2" href="<?=site_url()?>analisis_grafik/clear/<?=$analisis_master['id']?>">
		<img src="<?=base_url()?>assets/images/cpanel/office-chart-bar-stacked.png" alt="sss"/>
		<span>Grafik / Diagram Data Analisis</span>
	</a>
	
	<a class="csurat2" href="<?=site_url()?>analisis_grafik/time">
		<img src="<?=base_url()?>assets/images/cpanel/office-chart-line.png" alt="sss"/>
		<span>Grafik Periode Analisis</span>
	</a>


</div>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>analisis_master" class="uibutton icon prev">Kembali</a>
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