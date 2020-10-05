<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Wilayah</title>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> Tabel Data Kependudukan berdasarkan Populasi Per Wilayah </h3>
					<h4>  <?= ucwords($this->setting->sebutan_kabupaten)?> <?= $header['nama_kabupaten']?>, <?= ucwords($this->setting->sebutan_kecamatan)?> <?= $header['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_desa)?> <?= $header['nama_desa']?></h4>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th width="30">No</th>
							<th width="100">Nama <?= ucwords($this->setting->sebutan_dusun)?></th>
							<th width="100">Nama Kepala <?= ucwords($this->setting->sebutan_dusun)?></th>
							<th width="50">RW</th>
							<th width="50">RT</th>
							<th width="50">KK</th>
							<th width="50">L+P</th>
							<th width="50">L</th>
							<th width="50">P</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td align="center" width="2"><?= $data['no']?></td>
								<td><?= strtoupper($data['dusun'])?></td>
								<td><?= $data['nama_kadus']?></td>
								<td align="right"><?= $data['jumlah_rw']?></td>
								<td align="right"><?= $data['jumlah_rt']?></td>
								<td align="right"><?= $data['jumlah_kk']?></td>
								<td align="right"><?= $data['jumlah_warga']?></td>
								<td align="right"><?= $data['jumlah_warga_l']?></td>
								<td align="right"><?= $data['jumlah_warga_p']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
						<tr style="background-color:#BDD498;font-weight:bold;">
							<td colspan="3" align="left"><label>TOTAL</label></td>
							<td align="right"><?= $total['total_rw']?></td>
							<td align="right"><?= $total['total_rt']?></td>
							<td align="right"><?= $total['total_kk']?></td>
							<td align="right"><?= $total['total_warga']?></td>
							<td align="right"><?= $total['total_warga_l']?></td>
							<td align="right"><?= $total['total_warga_p']?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>
