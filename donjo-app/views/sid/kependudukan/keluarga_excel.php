<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=keluarga_".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Keluarga</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
<style>
	.textx{
	  mso-number-format:"\@";
	}
</style>
</head>
<body>
<div id="container">

<!-- Print Body --><div id="body"><div class="header" align="center"><label align="left"><?php echo get_identitas()?></label>
<h3> DATA KELUARGA </h3>
</div>
    <table border=1 class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th width="150" >Nomor KK</th>
			<th width="200">Kepala Keluarga</th>
			<th width="200">NIK</th>
			<th width="100"  >Jumlah Anggota</th>
			<th   width="100">Jenis Kelamin</th>
			<th align="center" width="180">Alamat</th>
			<th   width="100"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
			<th   width="30">RW</th>
			<th   width="30">RT</th>
			<th   width="100">Tanggal Terdaftar</th>
			<th   width="100">Tanggal Cetak KK</th>			
		</tr>
		</thead>
		
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td><?php echo $data['no']?></td>
		  <td class="textx"><?php echo $data['no_kk']?></td>
          <td><?php echo strtoupper($data['kepala_kk'])?></td>
		  <td class="textx"><?php echo $data['nik']?></td>		  
          <td align="center"><?php echo $data['jumlah_anggota']?></td>
          <td><?php echo $data['sex']?></td>
		  <td><?php echo strtoupper($data['alamat'])?></td>
          <td><?php echo strtoupper(ununderscore($data['dusun']))?></td>
		  <td class="textx"><?php echo $data['rw']?></td>
		  <td class="textx"><?php echo $data['rt']?></td>				  
          <td><?php echo tgl_indo($data['tgl_daftar'])?></td>
		  <td><?php echo tgl_indo($data['tgl_cetak_kk'])?></td>
		</tr>
		<?php  endforeach; ?>
		</tbody>

</table>
</div>

   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
