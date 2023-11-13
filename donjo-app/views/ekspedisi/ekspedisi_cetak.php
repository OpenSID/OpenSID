<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Buku Ekspedisi</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= asset('css/report.css') ?>" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3>
						<span>BUKU EKSPEDISI</span>
						<?php if (! empty($_SESSION['filter'])): ?>
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
							<td><?= tgl_indo($data['tanggal_surat']) . ' / ' . $data['nomor_surat']?></td>
							<td><?= $data['isi_singkat']?></td>
							<td><?= $data['tujuan']?></td>
							<td><?= $data['keterangan']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php $this->load->view('global/blok_ttd_pamong.php', ['total_col' => 6, 'spasi_kiri' => 1, 'spasi_tengah' => 2]); ?>
			</div>
		</div>
	</body>
</html>
