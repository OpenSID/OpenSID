<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Kelompok - <?= $kelompok['nama']?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<style>
			.textx
			{
			mso-number-format:"\@";
			}
			td,th{
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
					<h3> DATA KELOMPOK  - <?= $kelompok['nama']?></h3>
				</div>
				<table border=1 class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>NIK</th>
							<th>Nama</th>
							<th>Alamat</th>
							<th>Umur (Tahun)</th>
							<th>Jenis Kelamin</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td><?= $data['no']?></td>
								<td><?= $data['nik']?></td>
								<td><?= $data['nama']?></td>
								<td><?= $data['alamat']?></td>
								<td><?= $data['umur']?></td>
								<td><?php if ($data['sex']==1): ?>Laki-laki <?php else: ?>Perempuan <?php endif; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
 			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>