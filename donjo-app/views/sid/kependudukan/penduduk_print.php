<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Penduduk</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body --><div id="body"><div class="header" align="center"><label align="left"><?php echo get_identitas()?></label>
<h3> DATA PENDUDUK </h3>
<h3> <?php  echo $_SESSION['judul_statistik']; ?></h3>
</div>
<br>
    <table class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th width='100'>NIK</th>
			<th>Nama</th>
			<th width="100">No. KK</th>
			<th >Dusun</th>
			<th >RW</th>
			<th >RT</th>
			<th >Pendidikan</th>
			<th width="50">Umur</th>
			<th >Pekerjaan</th>
			<th >Kawin</th>
			<th >Status</th>
							
		</tr>
	</thead>
	<tbody>
		 <?php  foreach($main as $data): ?>
		<tr>
			<td  width="2"><?php echo $data['no']?></td>
			<td><?php echo $data['nik']?></td>
			<td><?php echo strtoupper(unpenetration($data['nama']))?></td>
			<td><?php echo $data['no_kk']?> </td>
			<td><?php echo strtoupper(ununderscore(unpenetration($data['dusun'])))?></td>
			<td><?php echo $data['rw']?></td>
			<td><?php echo $data['rt']?></td>
			<td><?php echo $data['pendidikan']?></td>
			<td align="right"><?php echo $data['umur']?></td>
			<td><?php echo $data['pekerjaan']?></td>
			<td><?php echo $data['kawin']?></td>
			<td><?php if($data['status']==1){echo "Tetap";}else{echo "Pendatang";}?></td>
		</tr>
		<?php  endforeach; ?>
	</tbody>
</table>
</div>
   
   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
