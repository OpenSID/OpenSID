<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Kelompok</title>
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
					<h3> DATA KELOMPOK </h3>
				</div>
				<table border=1 class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>Nama</th>
							<th>Nama Ketua</th>
							<th>Kategori Kelompok</th>
							<th>Jumlah Anggota</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($main as $data): ?>
							<tr>
								<td><?= $data['no']?></td>
								<td><?= $data['nama']?></td>
								<td><?= $data['ketua']?></td>
								<td><?= $data['master']?></td>
								<td><?= $data['jml_anggota']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
 			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>