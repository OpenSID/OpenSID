<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_rentan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Cetak Laporan Kelompok Rentan</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?=base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">

	   <table  width="100%"><?foreach($config as $data){?>	
				<tbody><tr>			
				<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA <?=$data['nama_kabupaten']?></h4></td>
																	
				<td align= "right" width="17%"><h4>LAMPIRAN A - 9</h4></td>		</tr>	
				<tr>				
					<td></td>
					<td width="100%"><h3>LAPORAN BULANAN DESA/KELURAHAN</h3></td>
					
									
				</tr>
				</tbody></table>
				<br>
				<table>
				<tbody><tr>						
					<td>Desa/Kelurahan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?=$data['nama_desa']?></h4></td>
					<td></td>	

				</tr>
				<tr>					
					<td>Kecamatan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?=$data['nama_kecamatan']?></td>
					<td></td>	
			<?}?>	
				</tr>
				<tr>						
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
			<?$bln = date("m");?>
					<td><?=$bln?> </td>
					<td width="40%"></td>	
				</tr>
				<?if($dusun){?>
				<tr>						
					<td>Dusun</td>
					<td width="3%">:</td>
					<td>
					<?=$dusun?>
					</td>
					<td width="40%"></td>	
				</tr>
				<?}?>
		</tbody></table>
		<br>
	<table class="border thick">
	
<thead>
<tr class="border thick">
	<th rowspan="2"><div align="center">RW</div></th>
	<th rowspan="2"><div align="center">RT</div></th>
	<th colspan="2"><div align="center">KK</div></th>
	<th colspan="6"><div align="center">Kondisi dan Kelompok Umur</div></th>
	<th rowspan="2"><div align="center">Cacat</div></th>
	<th colspan="2"><div align="center">Sakit Menahun</div></th>
	<th rowspan="2"><div align="center">Hamil</div></th>
</tr>
<tr>
	<th><div align="center">L</div></th>
	<th><div align="center">P</div></th>
	<th><div align="center">Bayi(<1thn)</div></th>
	<th><div align="center">Balita(1-5thn)</div></th>
	<th><div align="center">SD(6-12thn)</div></th>
	<th><div align="center">SMP(13-15thn)</div></th>
	<th><div align="center">SMA(16-18thn)</div></th>
	<th><div align="center">Lansia(>60thn)</div></th>
	<th><div align="center">L</div></th>
	<th><div align="center">P</div></th>
</tr>
</thead>
<tbody>
<?
	$bayi=0;
	$balita=0;
	$sd=0;
	$smp=0;
	$sma=0;
	$lansia=0;
	$cacat=0;
	$sakit_L=0;
	$sakit_P=0;
	$hamil=0;
?>
<?foreach($main as $data){?>
<td align="right"><?=$data['rw']?></td>
<td align="right"><?=$data['rt']?></td>
<td align="right"><?=$data['L']?></td>
<td align="right"><?=$data['P']?></td>
<td width="13%" align="right"><?=$data['bayi']?></td>
<td width="14%" align="right"><?=$data['balita']?></td>
<td width="13%" align="right"><?=$data['sd']?></td>
<td width="15%" align="right"><?=$data['smp']?></td>
<td width="15%" align="right"><?=$data['sma']?></td>
<td width="13%" align="right"><?=$data['lansia']?></td>
<td align="right"><?=$data['cacat']?></td>
<td align="right"><?=$data['sakit_L']?></td>
<td align="right"><?=$data['sakit_P']?></td>
<td align="right"><?=$data['hamil']?></td>
<?
	$bayi=$bayi+$data['bayi'];
	$balita=$balita+$data['balita'];
	$sd=$sd+$data['sd'];
	$smp=$smp+$data['smp'];
	$sma=$sma+$data['sma'];
	$lansia=$lansia+$data['lansia'];
	$cacat=$cacat+$data['cacat'];
	$sakit_L=$sakit_L+$data['sakit_L'];
	$sakit_P=$sakit_P+$data['sakit_P'];
	$hamil=$hamil+$data['hamil'];
?>
</tr>
  <?}?>
  </tbody>
  
<thead>
	<tr>
		<th colspan="4" align="center"><div align="center">Total</div></th>
		<th><div align="right"><? echo $bayi;?></div></th>
		<th><div align="right"><? echo $balita;?></div></th>
		<th><div align="right"><? echo $sd;?></div></th>
		<th><div align="right"><? echo $smp;?></div></th>
		<th><div align="right"><? echo $sma;?></div></th>
		<th><div align="right"><? echo $lansia;?></div></th>
		<th><div align="right"><? echo $cacat;?></div></th>
		<th><div align="right"><? echo $sakit_L;?></div></th>
		<th><div align="right"><? echo $sakit_P;?></div></th>
		<th><div align="right"><? echo $hamil;?></div></th>
	</tr>
</thead>
</table>   


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div>
	</div>
   <label>Tanggal cetak : &nbsp; </label><?=tgl_indo(date("Y m d"))?>
</div>

</body></html>
