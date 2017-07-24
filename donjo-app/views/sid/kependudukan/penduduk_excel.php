<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Penduduk.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

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
	font-size:8pt;
}
</style>
</head>
<body>
<div id="container">
<!-- Print Body --><div id="body">
<div class="header" align="center">
<label align="left"><?php echo get_identitas()?></label>
<h3> DATA PENDUDUK </h3>
</div>
    <table border=1 class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th>No. KK</th>
			<th>Nama</th>
			<th>NIK</th>
			<th>Alamat</th>
			<th ><?php echo ucwords($this->setting->sebutan_dusun)?></th>
			<th >RW</th>
			<th >RT</th>
			<th >Jenis Kelamin</th>
			<th >Tempat Lahir</th>
			<th >Tanggal Lahir</th>
			<th >Umur</th>
			<th >Agama</th>
			<th >Pendidikan (dlm KK)</th>
			<th >Pekerjaan</th>
			<th >Kawin</th>
			<th >Hub. Keluarga</th>
			<th >Nama Ayah</th>
			<th >Nama Ibu</th>
			<th >Status</th>

		</tr>
	</thead>
	<tbody>
		 <?php  foreach($main as $data): ?>
		 <tr>
			<td><?php echo $data['no']?></td>
			<td  class="textx"><?php echo $data['no_kk']?> </td>
			<td><?php echo strtoupper($data['nama'])?></td>
			<td class="textx"><?php echo $data['nik']?></td>
			<td><?php echo strtoupper($data['alamat'])?></td>
			<td><?php echo strtoupper(ununderscore($data['dusun']))?></td>
			<td class="textx"><?php echo $data['rw']?></td>
			<td class="textx"><?php echo $data['rt']?></td>
			<td><?php echo $data['sex']?></td>
			<td><?php echo $data['tempatlahir']?></td>
			<td><?php echo tgl_indo($data['tanggallahir'])?></td>
			<td align="center"><?php echo $data['umur']?></td>
			<td><?php echo $data['agama']?></td>
			<td><?php echo $data['pendidikan']?></td>
			<td><?php echo $data['pekerjaan']?></td>
			<td><?php echo $data['kawin']?></td>
			<td><?php echo $data['hubungan']?></td>
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
</body>
</html>