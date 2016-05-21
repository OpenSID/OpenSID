<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Cetak Laporan Perubahan Penduduk</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?=base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">

	   <table  width="100%">
				<tbody><tr>	<?foreach($config as $data){?>				
					<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA  <?=strtoupper($data['nama_kabupaten'])?></h4></td>
				</tr>	
				<tr>				
					<td></td>
					<td width="100%"><h3>LAPORAN PERUBAHAN PENDUDUK</h3></td>								
				</tr>
				</tbody></table>
	<br>
				<table>
				<tbody><tr>						
					<td>Kelurahan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?=$data['nama_desa']?></h4></td>
					<td></td>	

				</tr>
				<tr>					
					<td>Kecamatan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?=$data['nama_kecamatan']?></td>
					<td></td>	
					<?  ?>
				</tr>
				<tr>					
					<td>Kabupaten</td>
					<td width="3%">:</td>
					<td width="38.5%"><?=$data['nama_kabupaten']?></td>
					<td></td>	
					<? } ?>
				</tr>
				<tr>						
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
					<td><?=$bln?> </td>
					<td width="40%"></td>	

				</tr>
				 
		</tbody></table>
	<br>
	<table class="border thick">
	<thead>
<tr class="border thick">
			<th rowspan="3" scope="col" ><div align="center">NO</div></th>
			<th rowspan="3" scope="col"  ><div align="center">DUSUN</div></th>			
			<th colspan="3" rowspan="2" scope="col"  width="15%"><div align="center">PENDUDUK AKHIR BULAN LALU</div></th>			
			<th colspan="12" scope="col"  width="60%"><div align="center">PERUBAHAN PENDUDUK</div></th>				
			<th colspan="3" rowspan="2" scope="col"  width="15%"><div align="center">PENDUDUK AKHIR BULAN INI</div></th>			
		</tr>
<tr class="border thick">
			<th colspan="3" width="15%"><div align="center">KELAHIRAN</div></th>
			<th colspan="3" width="15%"><div align="center">DATANG</div></th>
			<th colspan="3" width="15%"><div align="center">PERGI</div></th>
			<th colspan="3" width="15%"><div align="center">KEMATIAN</div></th>
		</tr>
<tr class="border thick">
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
<tr class="border thick">
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
	
	<? $no=1; 
	foreach($main as $data){?>
		<tr>		
		<td><div align="center"><? echo $no ?></td>
		<td><?=$data['dusun']?></td>
		<td><div align="center"><?=$data['lalu_L']?></div></td>
		<td><div align="center"><?=$data['lalu_P']?></div></td>
		<td><div align="center"><?=$data['lalu_L'] + $data['lalu_P']?></div></td>
		<td><div align="center"><?=$data['pecah_L']?></div></td>
		<td><div align="center"><?=$data['pecah_P']?></div></td>
		<td><div align="center"><?=$data['pecah_L'] + $data['pecah_P']?></div></td>
		<td><div align="center"><?=$data['datang_L']?></div></td>
		<td><div align="center"><?=$data['datang_P']?></div></td>
		<td><div align="center"><?=$data['datang_L'] + $data['datang_P']?></div></td>
		<td><div align="center"><?=$data['pergi_L']?></div></td>
		<td><div align="center"><?=$data['pergi_P']?></div></td>
		<td><div align="center"><?=$data['pergi_L'] + $data['pergi_P']?></div></td>
		<td><div align="center"><?=$data['mati_L']?></div></td>
		<td><div align="center"><?=$data['mati_P']?></div></td>
		<td><div align="center"><?=$data['mati_L'] + $data['mati_P']?></div></td>
		<td><div align="center"><?=$data['lalu_L']+$data['pecah_L']+$data['datang_L']-$data['pergi_L']-$data['mati_L']?></div></td>
		<td><div align="center"><?=$data['lalu_P']+$data['pecah_P']+$data['datang_P']-$data['pergi_P']-$data['mati_P']?></div></td>
		<td><div align="center"><?=$data['lalu_L']+$data['pecah_L']+$data['datang_L']-$data['pergi_L']-$data['mati_L']+$data['lalu_P']+$data['pecah_P']+$data['datang_P']-$data['pergi_P']-$data['mati_P']?></div></td>
		</tr>
	<? $no++; 
	} ?>
	</tbody>
	<thead>
		<tr style="border-top:1px solid #000;"><? foreach($total as $data){?>
			<th colspan="2"><div align="center">Total</div></th>
			<th><div align="center"><?=$data['tlaluL']?></div></th>
			<th><div align="center"><?=$data['tlaluP']?></div></th>
			<th><div align="center"><?=$data['tlaluL']+$data['tlaluP']?></div></th>
			<th><div align="center"><?=$data['tpecahL']?></div></th>
			<th><div align="center"><?=$data['tpecahP']?></div></th>
			<th><div align="center"><?=$data['tpecahL']+$data['tpecahP']?></div></th>
			<th><div align="center"><?=$data['tdatangL']?></div></th>
			<th><div align="center"><?=$data['tdatangP']?></div></th>
			<th><div align="center"><?=$data['tdatangL']+$data['tdatangP']?></div></th>
			<th><div align="center"><?=$data['tpergiL']?></div></th>
			<th><div align="center"><?=$data['tpergiP']?></div></th>
			<th><div align="center"><?=$data['tpergiL']+$data['tpergiP']?></div></th>
			<th><div align="center"><?=$data['tmatiL']?></div></th>
			<th><div align="center"><?=$data['tmatiP']?></div></th>
			<th><div align="center"><?=$data['tmatiL']+$data['tmatiP']?></div></th>
			<th><div align="center"><?=$data['tlaluL']+$data['tpecahL']+$data['tdatangL']-$data['tpergiL']-$data['tmatiL']?></div></th>
			<th><div align="center"><?=$data['tlaluP']+$data['tpecahP']+$data['tdatangP']-$data['tpergiP']-$data['tmatiP']?></div></th>
			<th><div align="center"><?=$data['tlaluL']+$data['tpecahL']+$data['tdatangL']-$data['tpergiL']-$data['tmatiL']+$data['tlaluP']+$data['tpecahP']+$data['tdatangP']-$data['tpergiP']-$data['tmatiP']?></div></th>
			<? } ?>
		</tr>
	</thead>
	</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div></div>
   <label>Tanggal cetak : &nbsp; </label><?=tgl_indo(date("Y m d"))?>
</div>

</body></html>