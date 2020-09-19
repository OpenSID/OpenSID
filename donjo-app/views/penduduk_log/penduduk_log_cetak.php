<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Log Penduduk</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<style>
		.textx
		{
			mso-number-format:"\@";
		}
		td,th
		{
			font-size:6.5pt;
			mso-number-format:"\@";
		}
		</style>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> DAFTAR PENDUDUK YANG STATUS DASARNYA MATI, HILANG ATAU PINDAH</h3>
					<br>
				</div>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>NIK</th>
							<th>Nama</th>
							<th>No. KK / Nama KK</th>
							<th><?= ucwords($this->setting->sebutan_dusun)?></th>
							<th>RW</th>
							<th>RT</th>
							<th>Umur</th>
							<th>Status Menjadi</th>
							<th>Tanggal Peristiwa</th>
							<th>Tanggal Rekam</th>
							<th>Catatan Peristiwa</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
						<tr>
							<td><?= $data['no']?></td>
							<td><?= $privasi_nik ? sensor_nik_kk($data['nik']) : $data['nik']?></td>
							<td><?= strtoupper($data['nama'])?></td>
							<td>
								<?= $privasi_nik ? sensor_nik_kk($data['no_kk']) : $data['no_kk']?>
								<?= " / ".$data['nama_kk']?>
							</td>
							<td><?= strtoupper($data['dusun'])?></td>
							<td><?= $data['rw']?></td>
							<td><?= $data['rt']?></td>
							<td align="right"><?= $data['umur_pada_peristiwa']?></td>
							<td><?= $data['status_dasar']?></td>
							<td><?= tgl_indo($data['tgl_peristiwa'])?></td>
							<td><?= tgl_indo($data['tanggal'])?></td>
							<td><?= $data['catatan']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<br>
			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>

	</body>
</html>
