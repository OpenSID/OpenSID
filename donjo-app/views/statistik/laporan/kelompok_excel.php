<?php php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_rentan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Cetak Laporan Kelompok Rentan</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">

	   <table  width="100%"><?php foreach($config as $data){?>
				<tbody><tr>
				<td width="37%"><h4>PEMERINTAH KABUPATEN/KOTA <?php echo $data['nama_kabupaten']?></h4></td>

				<td align= "right" width="17%"><h4>LAMPIRAN A - 9</h4></td>		</tr>
				<tr>
					<td></td>
					<td width="100%"><h3>LAPORAN BULANAN KELURAHAN</h3></td>


				</tr>
				</tbody></table>
				<br>
				<table>
				<tbody><tr>
					<td>Kelurahan</td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_desa']?></h4></td>
					<td></td>

				</tr>
				<tr>
					<td><?php echo ucwords($this->setting->sebutan_kecamatan)?></td>
					<td width="3%">:</td>
					<td width="38.5%"><?php echo $data['nama_kecamatan']?></td>
					<td></td>
			<?php }?>
				</tr>
				<tr>
					<td>Laporan Bulan</td>
					<td width="3%">:</td>
			<?php $bln = date("m");?>
					<td><?php echo $bln?> </td>
					<td width="40%"></td>
				</tr>
				<?php if($dusun){?>
				<tr>
					<td>Dusun</td>
					<td width="3%">:</td>
					<td>
					<?php echo $dusun?>
					</td>
					<td width="40%"></td>
				</tr>
				<?php }?>
		</tbody></table>
		<br>
	<table class="border thick">

<thead>
<tr class="border thick">
<th scope="col" width="4%"><div align="center">RW</div></th>
<th scope="col" width="4%"><div align="center">RT</div></th>
<th colspan="2" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="2" scope="col">KK</th>
 </tr>
 <tr>
<th width="100%" ><div align="center">L</div></th>
<th width="100%" ><div align="center">P</div></th>
 </tr>
  </table></div>
<th colspan="6" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="6" scope="col"><div align="center">Kondisi dan kelompok umur </div> </th>
 </tr>
 <tr>
<th><div align="center">Bayi(<1thn)</div></th>
<th><div align="center">Balita(1-5thn)</div></th>
<th><div align="center">SD(6-12thn)</div></th>
<th><div align="center">SMP(13-15thn)</div></th>
<th><div align="center">SMA(16-18thn)</div></th>
<th><div align="center">Lansia(>60)</div></th>
 </tr>
  </table></div>
  </th>
<th colspan="2" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="2" scope="col"><div align="center">Difabel</div> </th>
 </tr>
 <tr>
<th width="100%" ><div align="center">Fisik</div></th>
<th width="100%" ><div align="center">Mental</div></th>

 </tr>
  </table></div>
  </th>
<th colspan="2" scope="col"><div align="center">
<table width="100%">
 <tr>
<th colspan="2" scope="col"><div align="center">Sakit Menahun</div> </th>
 </tr>
 <tr>
<th width="100%" ><div align="center">L</div></th>
<th width="100%" ><div align="center">P</div></th>
 </tr>
  </table></div>
  </th>
<th width="100%" ><div align="center">Hamil</div></th>
 </tr>
</thead>
<tbody>
<?php foreach($main as $data){?>
<td><?php echo $data['rw']?></td>
<td><?php echo $data['rt']?></td>
<td><?php echo $data['L']?></td>
<td><?php echo $data['P']?></td>
<td width="13%"><?php echo $data['bayi']?></td>
<td width="14%"><?php echo $data['balita']?></td>
<td width="13%"><?php echo $data['sd']?></td>
<td width="15%"><?php echo $data['smp']?></td>
<td width="15%"><?php echo $data['sma']?></td>
<td width="13%"><?php echo $data['lansia']?></td>
<td><?php echo $data['fisik']?></td>
<td><?php echo $data['mental']?></td>
<td><?php echo $data['sakit_L']?></td>
<td><?php echo $data['sakit_P']?></td>
<td><?php echo $data['hamil']?></td>
</tr>
  <?php }?>
  </tbody>
</table>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    </div>
	</div>
   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
