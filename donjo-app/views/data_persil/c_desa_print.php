<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data C-DESA</title>
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
					<h3> DATA C-DESA </h3>
					<h3> <?= $_SESSION['judul_statistik']; ?></h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th colspan="2" >NOMOR</th>
							<th colspan="3" >PEMILIK</th>
							<th colspan="4" >LUAS TANAH</th>
							<th rowspan="3" >TANGGAL TERDAFTAR</th>
						</tr>
						<tr>
							<th rowspan="2">URUT</th>
							<th rowspan="2">C-DESA</th>
							<th rowspan="2">NAMA</th>
							<th rowspan="2">NIK</th>
							<th rowspan="2">ALAMAT</th>
							<th colspan="2" > TANAH BASAH</th>
							<th colspan="2"> TANAH KERING</th>
						</tr>
						<tr>
							<th width="100">Ha</th>
							<th width="100">m<sup>2</sup></th>
							<th width="100">Ha</th>
							<th width="100">m<sup>2</sup></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data_persil as $persil): ?>
						<tr>
							<td><?= $persil['no']?></td>
							<td class="textx"><?= sprintf("%04s", $persil['c_desa'])?></td>
							<?php if ($persil['jenis_pemilik'] != '2'): ?>
								<td><?= $persil['namapemilik']?></td>
								<td class="textx"><?= $persil['nik']?></td>
								<td>RT: <?= $persil["rt"]?> RW: <?= $persil["rw"]?> Dusun <?= strtoupper($persil["dusun"])?></td>
							<?php else : ?>
								<td><?= strtoupper($persil["namapemilik"])?></td>
								<td>-</td>
								<td><?= $persil["alamat_luar"]?></td>
							<?php endif; ?>
							<td> <?= luas($persil['basah'], "ha") ?></td>
							<td> <?= luas($persil['basah'], "meter") ?></td>
							<td> <?= luas($persil['kering'], "ha") ?></td>
							<td> <?= luas($persil['kering'], "meter") ?></td>
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
