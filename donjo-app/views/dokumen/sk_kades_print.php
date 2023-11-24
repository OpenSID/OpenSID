<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data SK Kepala Desa</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= asset('css/report.css') ?>" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
		<!-- TODO: Pindahkan ke external css -->
		<style>
			.textx
			{
				mso-number-format:"\@";
			}
			td, th
			{
				font-size:9pt;
			}
			table#ttd td
			{
				text-align: center;
				white-space: nowrap;
			}
			.underline
			{
				text-decoration: underline;
			}
			table#header td
			{
				text-align: center;
				font-size: 13.5pt;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="body">
				<!--
					Inline style diperlukan untuk unduh ke format 'excel'
					TODO: cari cara untuk mengatur style unduh 'excel' yang lebih baik
				-->
				<table id="header" style="border-top: 0px;">
					<tr>
						<td colspan="6" align="center" style="font-size: 13.5pt;">
							<strong>BUKU KEPUTUSAN KEPALA <?= strtoupper($this->setting->sebutan_desa) . ' ' . strtoupper($desa['nama_desa'])?></strong>
						</td>
					</tr>
					<tr>
						<td colspan="6" align="center" style="font-size: 13.5pt;">
							<strong><?= strtoupper($this->setting->sebutan_kecamatan . ' ' . $desa['nama_kecamatan'] . ' ' . $this->setting->sebutan_kabupaten . ' ' . $desa['nama_kabupaten'])?></strong>
						</td>
					</tr>
					<tr>
						<td colspan="6" align="center" style="font-size: 13.5pt;">
							<strong><?= empty($tahun) ? '' : 'TAHUN ' . $tahun?></strong>
						</td>
					</tr>
					<tr><td colspan="6">&nbsp;</td></tr>
				</table>
				<table class="border thick" width="100%">
					<thead>
						<tr class="border thick">
							<th>NOMOR URUT</th>
							<th>NOMOR DAN TANGGAL KEPUTUSAN KEPALA DESA</th>
							<th>TENTANG</th>
							<th>URAIAN SINGKAT</th>
							<th>NOMOR DAN TANGGAL DILAPORKAN</th>
							<th>KET.</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
						<tr>
							<td><?= $data['no']?></td>
							<td><?= 'Nomor ' . strip_kosong($data['attr']['no_kep_kades']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_kep_kades'])?></td>
							<td><?= $data['nama']?></td>
							<td><?= $data['attr']['uraian']?></td>
							<td><?= 'Nomor ' . strip_kosong($data['attr']['no_lapor']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_lapor'])?></td>
							<td><?= $data['attr']['keterangan']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<br><br>
				<?php $this->load->view('global/blok_ttd_pamong.php', ['total_col' => 6, 'spasi_kiri' => 1, 'spasi_tengah' => 2]); ?>
			</div>
		</div>
	</body>
</html>
