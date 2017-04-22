<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=rtm_".date("Y-m-d").".xls");
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
        <?php  foreach($main as $data): ?>
		<tr>
          <td><?php echo $data['no']?></td>
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