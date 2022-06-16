<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Rumah Tangga</title>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<!-- TODO: Pindahkan ke external css -->
		<style>
			td, th {
				mso-number-format: "\@";
			}
		</style>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> Data Rumah Tangga </h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th width="150">Nomor Rumah Tangga</th>
							<th width="200">Kepala Rumah Tangga</th>
							<th width="100">NIK</th>
							<th width="100">Jumlah Anggota</th>
							<th width="100">Alamat</th>
							<th width="100"><?= ucwords($this->setting->sebutan_dusun)?></th>
							<th width="30">RW</th>
							<th width="30">RT</th>
							<th width="100">Tanggal Terdaftar</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $key => $data): ?>
							<tr>
								<td class="text-center" width="2"><?= ($key + 1); ?></td>
								<td><?= $data['no_kk']?></td>
								<td><?= strtoupper($data['kepala_kk'])?></td>
								<td><?= $privasi_nik ? sensor_nik_kk($data['nik']) : $data['nik']?></td>
								<td><?= $data['jumlah_anggota']?></td>
								<td><?= strtoupper($data['alamat'])?></td>
								<td><?= strtoupper($data['dusun'])?></td>
								<td><?= strtoupper($data['rw'])?></td>
								<td><?= strtoupper($data['rt'])?></td>
								<td><?= tgl_indo($data['tgl_daftar'])?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<br>
			<label>Tanggal cetak : &nbsp; </label>
			<?= tgl_indo(date('Y m d'))?>
		</div>
	</body>
</html>
