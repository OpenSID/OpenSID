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
		<style>
			td
			{
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
					<h3> Tabel Data Kependudukan berdasarkan Populasi Per Wilayah </h3>
					<h4> <?= ucwords($this->setting->sebutan_kabupaten)?> <?= $desa['desa']['nama_kabupaten']?>, <?= ucwords($this->setting->sebutan_kecamatan)?> <?= $desa['desa']['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['desa']['nama_desa']?></h4>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>Nama <?= ucwords($this->setting->sebutan_dusun)?></th>
							<th>Nama RW</th>
							<th>Nama RT</th>
							<th>NIK Kepala/Ketua</th>
							<th>Nama Kepala/Ketua</th>
							<th>RW</th>
							<th>RT</th>
							<th>KK</th>
							<th>L+P</th>
							<th>L</th>
							<th>P</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $indeks => $data): ?>
							<tr>
								<td align="center"><?= $indeks + 1?></td>
								<td><?= ($main[$indeks - 1]['dusun'] == $data['dusun']) ? '' : strtoupper($data['dusun'])?></td>
								<td><?= ($main[$indeks - 1]['rw'] == $data['rw']) ? '' : $data['rw']?></td>
								<td><?= $data['rt']?></td>
								<td><?= $data['nik_kepala']?></td>
								<td><?= $data['nama_kepala']?></td>
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
							<td colspan="6" align="left"><label>TOTAL</label></td>
							<td align="right"><?= $total['total_rw']?></td>
							<td align="right"><?= $total['total_rt']?></td>
							<td align="right"><?= $total['total_kk']?></td>
							<td align="right"><?= $total['total_warga']?></td>
							<td align="right"><?= $total['total_warga_l']?></td>
							<td align="right"><?= $total['total_warga_p']?></td>
						</tr>
					</tbody>
				</table>
				<?php include("donjo-app/views/global/blok_ttd_pamong.php"); ?>
			</div>
			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>
