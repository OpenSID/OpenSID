<?php
	$tgl =  date('d_m_Y');
	$subjek = $_SESSION['subjek_tipe'];
	switch ($subjek):
		case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
		case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
		case 3: $sql = $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
		case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
		default: return null;
	endswitch;
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_$tgl.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<style>
	td
	{
	mso-number-format:"\@";
	}
	td,th
	{
		font-size:8pt;
	}
</style>
<!-- Print Body -->
<div id="body">
	<div class="header">
		<label><?= get_identitas()?></label>
		<h3> DATA STATISTIK ANALISIS JAWABAN</h3>
	</div>
	<table>
		<tr>
			<th>No</th>
			<th><?= $nomor ?></th>
			<th><?= $nama ?></th>
			<th>L/P</th>
			<th>Dusun</th>
			<th>RW</th>
			<th>RT</th>
			<th>Nilai</th>
			<th>Klasifikasi</th>
		</tr>
		<?php foreach ($main as $data): ?>
		<tr>
			<td><?= $data['no']?></td>
			<td><?= $data['uid']?></td>
			<td><?= $data['nama']?></td>
			<td><?= $data['jk']?></td>
			<td><?= $data['dusun']?></td>
			<td><?= $data['rw']?></td>
			<td><?= $data['rt']?></td>
			<td><?= $data['nilai']?></td>
			<td><?= $data['klasifikasi']?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>