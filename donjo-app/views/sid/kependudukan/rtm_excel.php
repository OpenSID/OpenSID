<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Print.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<div id="body">
<div class="header" align="center">
<h3> Data Rumah Tangga </h3>
</div>
<br>
    <table border="1">
	<thead>
		<tr class="border thick">
			<th width="25">No</th>
			<th width="150" >Nomor Rumah Tangga</th>
			<th width="200">Kepala Rumah Tangga</th>
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
          <td><?=$data['no']?></td>
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