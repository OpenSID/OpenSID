
<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<fieldset><legend>Laporan : </legend>
			<div class="lmenu">
				<ul>
				<li ><a href="<?php echo site_url()?>laporan">Laporan Bulanan</a></li>
				<li><a href="<?php echo site_url()?>laporan_rentan">Data Kelompok Rentan</a></li>
				
				</ul>
			</div>
		</fieldset>
		
		</td>
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">

    <div class="ui-layout-north panel top">
        <div class="left">
            <div class="uibutton-group">

			<a href="<?php echo site_url("laporan_perubahan/cetak")?>" class="uibutton special tipsy south" title="Cetak" target="_blank"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</a>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<style type="text/css">
table.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #729ea5;border-collapse: collapse;}
table.tftable th {font-size:12px;background-color:#8DABD4;border-width: 1px;padding: 3px;border-style: solid;border-color: #7195BA;text-align:left;}
table.tftable tr {background-color:#ffffff;}
table.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;}
</style>

	   <table  width="100%">
				<tbody><tr>	<?php foreach($config as $data){?>				
					<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA  <?php echo strtoupper($data['nama_kabupaten'])?></h4></td>
				</tr>	
				<tr>				
					<td></td>
					<td width="100%"><h3>LAPORAN PERUBAHAN PENDUDUK</h3></td>								
				</tr>
				</tbody></table>
				<table>
				<tbody><tr>						
					<td>Desa/Kelurahan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_desa']?></h4></td>
					<td></td>	

				</tr>
				<tr>					
					<td>Kecamatan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_kecamatan']?></td>
					<td></td>	
					<?php   ?>
				</tr>
				<tr>					
					<td>Kabupaten</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_kabupaten']?></td>
					<td></td>	
					<?php  } ?>
				</tr>
				<tr>						
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
					<td><?php echo $bln?> </td>
					<td width="40%"></td>	

				</tr>
				 
		</tbody></table>
	
	<table width="100%" id="tfhover" class="tftable" border="1">
	<thead>
		<tr>
			<th rowspan="3" scope="col" ><div align="center">NO</div></th>
			<th rowspan="3" scope="col"  ><div align="center">DUSUN</div></th>			
			<th colspan="3" rowspan="2" scope="col"  width="15%"><div align="center">PENDUDUK AKHIR BULAN LALU</div></th>			
			<th colspan="12" scope="col"  width="60%"><div align="center">PERUBAHAN PENDUDUK</div></th>				
			<th colspan="3" rowspan="2" scope="col"  width="15%"><div align="center">PENDUDUK AKHIR BULAN INI</div></th>			
		</tr>
		<tr>
			<th colspan="3" width="15%"><div align="center">KELAHIRAN</div></th>
			<th colspan="3" width="15%"><div align="center">DATANG</div></th>
			<th colspan="3" width="15%"><div align="center">PERGI</div></th>
			<th colspan="3" width="15%"><div align="center">KEMATIAN</div></th>
		</tr>
		<tr>
			<th><div align="center">L</div></th>
			<th><div align="center">P</div></th>
			<th><div align="center">JML</div></th>
			<th><div align="center">L</div></th>
			<th><div align="center">P</div></th>
			<th><div align="center">JML</div></th>			
			<th><div align="center">L</div></th>
			<th><div align="center">P</div></th>
			<th><div align="center">JML</div></th>
			<th><div align="center">L</div></th>
			<th><div align="center">P</div></th>
			<th><div align="center">JML</div></th>
			<th><div align="center">L</div></th>
			<th><div align="center">P</div></th>
			<th><div align="center">JML</div></th>
			<th><div align="center">L</div></th>
			<th><div align="center">P</div></th>
			<th><div align="center">JML</div></th>
		</tr>
		<tr>
			<th><div align="center">1</div></th>
			<th><div align="center">2</div></th>
			<th><div align="center">3</div></th>
			<th><div align="center">4</div></th>
			<th><div align="center">5</div></th>
			<th><div align="center">6</div></th>
			<th><div align="center">7</div></th>
			<th><div align="center">8</div></th>
			<th><div align="center">9</div></th>
			<th><div align="center">10</div></th>
			<th><div align="center">11</div></th>
			<th><div align="center">12</div></th>
			<th><div align="center">13</div></th>
			<th><div align="center">14</div></th>
			<th><div align="center">15</div></th>
			<th><div align="center">16</div></th>
			<th><div align="center">17</div></th>
			<th><div align="center">18</div></th>
			<th><div align="center">19</div></th>
			<th><div align="center">20</div></th>
		</tr>
	</thead>
	<tbody>
	
	<?php  $no=1; 
	foreach($main as $data){?>
		<tr>		
		<td><div align="center"><?php  echo $no ?></td>
		<td><?php echo $data['dusun']?></td>
		<td><div align="center"><?php echo $data['lalu_L']?></div></td>
		<td><div align="center"><?php echo $data['lalu_P']?></div></td>
		<td><div align="center"><?php echo $data['lalu_L'] + $data['lalu_P']?></div></td>
		<td><div align="center"><?php echo $data['pecah_L']?></div></td>
		<td><div align="center"><?php echo $data['pecah_P']?></div></td>
		<td><div align="center"><?php echo $data['pecah_L'] + $data['pecah_P']?></div></td>
		<td><div align="center"><?php echo $data['datang_L']?></div></td>
		<td><div align="center"><?php echo $data['datang_P']?></div></td>
		<td><div align="center"><?php echo $data['datang_L'] + $data['datang_P']?></div></td>
		<td><div align="center"><?php echo $data['pergi_L']?></div></td>
		<td><div align="center"><?php echo $data['pergi_P']?></div></td>
		<td><div align="center"><?php echo $data['pergi_L'] + $data['pergi_P']?></div></td>
		<td><div align="center"><?php echo $data['mati_L']?></div></td>
		<td><div align="center"><?php echo $data['mati_P']?></div></td>
		<td><div align="center"><?php echo $data['mati_L'] + $data['mati_P']?></div></td>
		<td><div align="center"><?php echo $data['lalu_L']+$data['pecah_L']+$data['datang_L']-$data['pergi_L']-$data['mati_L']?></div></td>
		<td><div align="center"><?php echo $data['lalu_P']+$data['pecah_P']+$data['datang_P']-$data['pergi_P']-$data['mati_P']?></div></td>
		<td><div align="center"><?php echo $data['lalu_L']+$data['pecah_L']+$data['datang_L']-$data['pergi_L']-$data['mati_L']+$data['lalu_P']+$data['pecah_P']+$data['datang_P']-$data['pergi_P']-$data['mati_P']?></div></td>
		</tr>
	<?php  $no++; 
	} ?>
	</tbody>
	<thead>
		<tr><?php  foreach($total as $data){?>
			<th colspan="2"><div align="center">Total</div></th>
			<th><div align="center"><?php echo $data['tlaluL']?></div></th>
			<th><div align="center"><?php echo $data['tlaluP']?></div></th>
			<th><div align="center"><?php echo $data['tlaluL']+$data['tlaluP']?></div></th>
			<th><div align="center"><?php echo $data['tpecahL']?></div></th>
			<th><div align="center"><?php echo $data['tpecahP']?></div></th>
			<th><div align="center"><?php echo $data['tpecahL']+$data['tpecahP']?></div></th>
			<th><div align="center"><?php echo $data['tdatangL']?></div></th>
			<th><div align="center"><?php echo $data['tdatangP']?></div></th>
			<th><div align="center"><?php echo $data['tdatangL']+$data['tdatangP']?></div></th>
			<th><div align="center"><?php echo $data['tpergiL']?></div></th>
			<th><div align="center"><?php echo $data['tpergiP']?></div></th>
			<th><div align="center"><?php echo $data['tpergiL']+$data['tpergiP']?></div></th>
			<th><div align="center"><?php echo $data['tmatiL']?></div></th>
			<th><div align="center"><?php echo $data['tmatiP']?></div></th>
			<th><div align="center"><?php echo $data['tmatiL']+$data['tmatiP']?></div></th>
			<th><div align="center"><?php echo $data['tlaluL']+$data['tpecahL']+$data['tdatangL']-$data['tpergiL']-$data['tmatiL']?></div></th>
			<th><div align="center"><?php echo $data['tlaluP']+$data['tpecahP']+$data['tdatangP']-$data['tpergiP']-$data['tmatiP']?></div></th>
			<th><div align="center"><?php echo $data['tlaluL']+$data['tpecahL']+$data['tdatangL']-$data['tpergiL']-$data['tmatiL']+$data['tlaluP']+$data['tpecahP']+$data['tdatangP']-$data['tpergiP']-$data['tmatiP']?></div></th>
			<?php  } ?>
		</tr>
	</thead>
	</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div></div>
    <div class="ui-layout-south panel bottom">
<div class="left">     
<a href="<?php echo site_url()?>sid_wilayah" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton confirm" type="submit" >Cetak</button>
</div>
</div>

	</form>
</div>
</td></tr></table>
</div>
