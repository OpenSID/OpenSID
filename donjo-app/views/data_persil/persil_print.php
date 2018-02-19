<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Data Penduduk</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
	<?php endif; ?>
	<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	<style>
		.textx{
		  mso-number-format:"\@";
		}
		td,th{
			font-size:6.5pt;
		}
	</style>
</head>
<body>
<div id="container">

<!-- Print Body --><div id="body"><div class="header" align="center"><label align="left"><?php echo get_identitas()?></label>
<h3> DATA PERSIL </h3>
<h3> <?php  echo $_SESSION['judul_statistik']; ?></h3>
</div>
<br>
    <table class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th>No Persil</th>
			<th>NIK</th>
			<th>Nama Pemilik</th>
			<th>Luas (m2)</th>
			<th>Nomor SPPT PBB</th>
			<th>Tanggal Terdaftar</th>
		</tr>
	</thead>
	<tbody>
		 <?php  foreach($data_persil as $persil): ?>
		 <tr>
			<td><?php echo $persil['no']?></td>
			<td class="textx"><?php echo $persil['nopersil']?></td>
			<td class="textx"><?php echo $persil['nik']?></td>
			<td><?php echo $persil['namapemilik']?></td>
			<td><?php echo $persil['luas']?></td>
			<td class="textx"><?php echo $persil['no_sppt_pbb']?></td>
			<td><?php echo tgl_indo($persil['tanggal_daftar'])?></td>
		</tr>
		<?php  endforeach; ?>
	</tbody>
</table>
</div>

   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
