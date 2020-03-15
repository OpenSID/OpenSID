<?php
	$subjek = $_SESSION['subjek_tipe'];
	switch ($subjek){
		case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
		case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
		case 3: $sql = $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
		case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
		default: return null;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Analisis</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
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
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3>Laporan Hasil Analisis <?= $asubjek ?></h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th width="10">No</th>
							<th align="left"><?= $nomor ?></th>
							<th align="left"><?= $nama ?></th>
							<th align="left">L/P</th>
							<th align="left">Dusun</th>
							<th align="left">RW</th>
							<th align="left">RT</th>
							<th align="left">Nilai</th>
							<th align="left">Klasifikasi</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($main as $data): ?>
							<tr>
								<td align="center" width="2"><?= $data['no'] ?></td>
								<td><?= $data['uid'] ?></td>
								<td><?= $data['nama'] ?></td>
								<td align="center"><?= $data['jk'] ?></td>
								<td><?= $data['dusun'] ?></td>
								<td><?= $data['rw'] ?></td>
								<td><?= $data['rt'] ?></td>
								<td align="right"><?= $data['nilai'] ?></td>
								<td align="right"><?= $data['klasifikasi'] ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>