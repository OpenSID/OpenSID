<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Persil</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<style>
			.textx
			{
				mso-number-format:"\@";
			}
			td,th
			{
				font-size:6.5pt;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> DATA PERSIL </h3>
					<h3> <?= $_SESSION['judul_statistik']; ?></h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>No Persil</th>
							<th>NIK</th>
							<th>Nama Pemilik</th>
							<th>Luas (m2)</th>
							<th>Nomor Objek Pajak</th>
							<th>Tanggal Terdaftar</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data_persil as $persil): ?>
						<tr>
							<td><?= $persil['no']?></td>
							<td class="textx"><?= $persil['nopersil']?></td>
							<td class="textx"><?= $persil['nik']?></td>
							<td><?= $persil['namapemilik']?></td>
							<td><?= $persil['luas']?></td>
							<td class="textx"><?= $persil['no_objek_pajak']?></td>
							<td><?= tgl_indo($persil['tanggal_daftar'])?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>
