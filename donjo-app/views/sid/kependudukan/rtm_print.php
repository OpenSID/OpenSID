<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Rumah Tangga</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body --><div id="body"><div class="header" align="center"><label align="left"><?php echo get_identitas()?></label>
<h3> Data Rumah Tangga </h3>
</div>
<br>
    <table class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th width="150" >Nomor Rumah Tangga</th>
			<th width="200">Kepala Rumah Tangga</th>
			<th width="100"  >Jumlah Anggota</th>
			<th   width="100"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
			<th   width="30">RW</th>
			<th   width="30">RT</th>
			<th   width="100">Tanggal Terdaftar</th>
		</tr>
		</thead>

		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td  width="2"><?php echo $data['no']?></td>
          <td><?php echo $data['no_kk']?></td>
		  <td><?php echo strtoupper($data['kepala_kk'])?></td>
          <td><?php echo $data['jumlah_anggota']?></td>
          <td><?php echo strtoupper(ununderscore($data['dusun']))?></td>
		  <td><?php echo strtoupper($data['rw'])?></td>
          <td><?php echo strtoupper($data['rt'])?></td>
          <td><?php echo tgl_indo($data['tgl_daftar'])?></td>
		</tr>
		<?php  endforeach; ?>
	</tbody>

</table>
</div>

   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
