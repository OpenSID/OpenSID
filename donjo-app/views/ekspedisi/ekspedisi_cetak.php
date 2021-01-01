<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Buku Ekspedisi</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3>
						<span>BUKU EKSPEDISI</span>
						<?php if (!empty($_SESSION['filter'])): ?>
							TAHUN <?= $_SESSION['filter']; ?>
						<?php endif; ?>
					</h3>
					<br>
				</div>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>NOMOR URUT</th>
							<th>TANGGAL PENGIRIMAN</th>
							<th>TANGGAL DAN NOMOR SURAT</th>
							<th>ISI SINGKAT SURAT YANG DIKIRIM</th>
							<th>DITUJUKAN KEPADA</th>
							<th>KETERANGAN</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $indeks => $data): ?>
						<tr>
							<td><?= $indeks + 1?></td>
							<td><?= tgl_indo($data['tanggal_pengiriman'])?></td>
							<td><?= tgl_indo($data['tanggal_surat']).' / '.$data['nomor_surat']?></td>
							<td><?= $data['isi_singkat']?></td>
							<td><?= $data['tujuan']?></td>
							<td><?= $data['keterangan']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<table>
					<col span="5" style="width: 8%">
					<col style="width: 28%">
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="1">&nbsp;</td>
						<td colspan="2">Mengetahui</td>
						<td colspan="2">&nbsp;</td>
						<td><?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, <?= tgl_indo(date("Y m d"))?></td>
					</tr>
					<tr>
						<td colspan="1">&nbsp;</td>
						<td colspan="2"><?= $pamong_ketahui['jabatan']?> <?= $desa['nama_desa']?></td>
						<td colspan="2">&nbsp;</td>
						<td><?= $pamong_ttd['jabatan']?> <?= $desa['nama_desa']?></td>
					</tr>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr>
						<td colspan="1">&nbsp;</td>
						<td colspan="2">( <?= $pamong_ketahui['pamong_nama']?> )</td>
						<td colspan="2">&nbsp;</td>
						<td>( <?= $pamong_ttd['pamong_nama']?> )</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
