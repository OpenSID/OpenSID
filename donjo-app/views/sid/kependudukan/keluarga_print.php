<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Keluarga</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?=base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body --><div id="body"><div class="header" align="center"><label align="left"><?=get_identitas()?></label>
<h3> DATA KELUARGA </h3>
<strong><? echo $_SESSION['judul_statistik']; ?></strong>
</div>
<br>
    <table class="border thick">
	<thead>
		<tr class="border thick">
			<th>No</th>
			<th width="150" >Nomor KK</th>
			<th width="200">Kepala Keluarga</th>
			<th width="100"  >Jumlah Anggota</th>
			<th   width="100">Dusun</th>
			<th   width="30">RW</th>
			<th   width="30">RT</th>
			<th   width="100">Tanggal Terdaftar</th>
		</tr>
		</thead>
		
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td  width="2"><?=$data['no']?></td>
          <td><?=$data['no_kk']?></td>
		  <td><?=strtoupper($data['kepala_kk'])?></td>
          <td><?=$data['jumlah_anggota']?></td>
          <td><?=strtoupper(ununderscore($data['dusun']))?></td>
		  <td><?=strtoupper($data['rw'])?></td>
          <td><?=strtoupper($data['rt'])?></td>
          <td><?=tgl_indo($data['tgl_daftar'])?></td>
		</tr>
		<? endforeach; ?>
	</tbody>
	
</table>
</div>
   
   <label>Tanggal cetak : &nbsp; </label><?=tgl_indo(date("Y m d"))?>
</div>

</body></html>
