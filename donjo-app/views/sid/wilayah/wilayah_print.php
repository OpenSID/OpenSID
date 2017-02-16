<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Wilayah</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container" style="min-width:800px;max-width:1024px;">
<table width="100%">
<tr> <img src="<?php echo base_url()?>assets/files/logo/<?php echo $desa['desa']['logo']?>" alt="" class="logo"></tr>
<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?php echo strtoupper(unpenetration($desa['desa']['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['desa']['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?php echo strtoupper(unpenetration($desa['desa']['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['desa']['alamat_kantor']))?> </h5>
<hr />
</div>
<div align="center">
<br>
<h2>Data Kependudukan berdasarkan Wilayah</h2>
<br>
</div>
</table>
<div class="clear"></div>
<div id="body">
 <table class="border thick">
	<thead>
		<tr class="border thick">
 <th>No</th>
				<th width="100">Nama Dusun</th>
				<th width="100">Nama Kadus</th>
				<th width="50">RW</th>
				<th width="50">RT</th>
				<th width="50">KK</th>
				<th width="50">Jiwa</th>
				<th width="50">LK</th>
				<th width="50">PR</th>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
			
			<td><?php echo strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
			<td><?php echo $data['nama_kadus']?></td> 
			<td align="right"><?php echo $data['jumlah_rw']?></td>
			<td align="right"><?php echo $data['jumlah_rt']?></td>
			<td align="right"><?php echo $data['jumlah_kk']?></td>
			<td align="right"><?php echo $data['jumlah_warga']?></td>
			<td align="right"><?php echo $data['jumlah_warga_l']?></td>
			<td align="right"><?php echo $data['jumlah_warga_p']?></td>
		</tr>
 <?php endforeach; ?>
		</tbody>
		
 <tr style="background-color:#BDD498;font-weight:bold;">
 <td colspan="3" align="left"><label>TOTAL</label></td>
				<td align="right"><?php echo $total['total_rw']?></td>
				<td align="right"><?php echo $total['total_rt']?></td>
				<td align="right"><?php echo $total['total_kk']?></td>
				<td align="right"><?php echo $total['total_warga']?></td>
				<td align="right"><?php echo $total['total_warga_l']?></td>
				<td align="right"><?php echo $total['total_warga_p']?></td>
			</tr>
	</tbody>
</table>
</div>
<label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>
</body>
</html>