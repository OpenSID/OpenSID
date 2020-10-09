<?php
$subjek_tipe = $_SESSION['subjek_tipe'];
switch ($subjek_tipe){
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
	<title>Laporan Hasil Analisis <?= $asubjek ?></title>
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
		.nama
		{
			width: 50%;
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
			<table class="nama" >
				<tr>
					<td>Nomor Identitas</td>
					<td>:</td>
					<td><?= $subjek['nid']?></td>
				</tr>
				<tr>
					<td>Nama Subjek</td>
					<td>:</td>
					<td><?= $subjek['nama']?></td>
				</tr>
			</table>
			<br>
			<p>DAFTAR ANGGOTA</p>
			<table class="border thick">
				<thead>
					<tr class="border thick">
						<th width="10">No</th>
						<th align="left">NIK</th>
						<th align="left">NAMA</th>
						<th align="left">TANGGAL LAHIR</th>
						<th align="left">JENIS KELAMIN</th>
					</tr>
				</thead>
				<tbody>

					<?php $i=1; foreach ($list_anggota AS $ang): ?>
					<tr>
						<td  align="center" width="2"><?= $i?></td>
						<td><?= $ang['nik']?></td>
						<td width="45%"><?= $ang['nama']?></td>
						<td><?= tgl_indo($ang['tanggallahir']) ?></td>
						<td><?php if ($ang['sex'] == 1): ?>LAKI-LAKI<?php endif; ?><?php if ($ang['sex'] == 2): ?>PEREMPUAN<?php endif; ?></td>
					</tr>
					<?php $i++; endforeach; ?>
				</tbody>
			</table>
			<br />

			<table class="border thick">
				<thead>
					<tr class="border thick">
						<th width="10">No</th>
						<th align="left">Pertanyaan / Indikator</th>
						<th align="left">Bobot</th>
						<th align="left">Jawaban</th>
						<th align="left">Nilai</th>
						<th align="left">Poin</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($list_jawab AS $data): ?>
						<?php if ($data['cek'] >= 1):$bg = "class='bg'";else:$bg ="";endif; ?>
						<tr>
							<td><?= $data['no']?></td>
							<td><?= $data['pertanyaan']?></td>
							<td><?= $data['bobot']?></td>
							<td><?= $data['jawaban']?></td>
							<td><?= $data['nilai']?></td>
							<td><?= $data['poin']?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot >
					<tr>
						<th colspan='5'><strong>TOTAL</strong></th>
						<th><?= $total?></th>
					</tr>
				</tfoot>
			</table>

		</div>
		<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
	</div>
</body>
</html>