<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Wilayah</title>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<!-- TODO: Pindahkan ke external css -->
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
				<?php $this->load->view('global/blok_ttd_pamong.php', ['total_col' => 12, 'spasi_kiri' => 2, 'spasi_tengah' => 6]); ?>
			</div>
		</div>
	</body>
</html>
