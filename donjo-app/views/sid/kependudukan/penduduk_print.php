<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Penduduk</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
<h3> DATA PENDUDUK </h3>
<h3> <?php  echo $_SESSION['judul_statistik']; ?></h3>
</div>
<br>
    <table class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th>NIK</th>
			<th>Nama</th>
			<th>No. KK</th>
			<th ><?php echo ucwords(config_item('sebutan_dusun'))?></th>
			<th >RW</th>
			<th >RT</th>
			<th >Pendidikan (dLm KK)</th>
			<th >Pendidikan (sdg ditemph)</th>
			<th >Pekerjaan</th>
			<th >Tanggal Lahir</th>
			<th >Tempat Lahir</th>
			<th >Umur</th>
			<th >Kawin</th>
			<th >Hub. Keluarga</th>
			<th >Gol. Darah</th>
			<th >Nama Ayah</th>
			<th >Nama Ibu</th>
			<th >Status</th>

		</tr>
	</thead>
	<tbody>
		 <?php  foreach($main as $data): ?>
		 <tr>
			<td  width="2"><?php echo $data['no']?></td>
			<td class="textx"><?php echo $data['nik']?></td>
			<td><?php echo strtoupper($data['nama'])?></td>
			<td  class="textx"><?php echo $data['no_kk']?> </td>
			<td><?php echo strtoupper(ununderscore($data['dusun']))?></td>
			<td><?php echo $data['rw']?></td>
			<td><?php echo $data['rt']?></td>
			<td><?php echo $data['pendidikan']?></td>
			<td><?php echo $data['pendidikan_sedang']?></td>
			<td><?php echo $data['pekerjaan']?></td>
			<td><?php echo $data['tanggallahir']?></td>
			<td><?php echo $data['tempatlahir']?></td>
			<td align="right"><?php echo $data['umur']?></td>
			<td><?php echo $data['kawin']?></td>
			<td><?php echo $data['hubungan']?></td>
			<td><?php echo $data['gol_darah']?></td>
			<td><?php echo $data['nama_ayah']?></td>
			<td><?php echo $data['nama_ibu']?></td>
			<td><?php if($data['status']==1){echo "Tetap";}else{echo "Pendatang";}?></td>
		</tr>
		<?php  endforeach; ?>
	</tbody>
</table>
</div>

   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
