<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Wilayah</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<!-- TODO: Pindahkan ke external css -->
		<style>
			.textx
			{
				mso-number-format:"\@";
			}
			td,th
			{
				font-size:6.5pt;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> DATA WILAYAH ADMINISTRASI </h3>
					<h4>RW <?= strtoupper($this->setting->sebutan_dusun)?> <?= strtoupper($dusun)?></h4>
				</div>
				<br>
					<table class="border thick">
						<thead>
							<tr class="border thick">
								<th width="30">No</th>
								<th width="50">RW</th>
								<th width="100">NIK Ketua RW</th>
								<th width="100">Nama Ketua RW</th>
								<th width="50">Jumlah RT</th>
								<th width="50">Jumlah KK</th>
								<th width="50">L+P</th>
								<th width="50">L</th>
								<th width="50">P</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($main as $data): ?>
								<tr>
									<td align="center"><?= $data['no']?></td>
									<td align="center" class="textx"><?= $data['rw']?></td>
									<td class="textx"><?= $data['nik_ketua']?></td>
									<td><?= $data['nama_ketua']?></td>
									<td align="right"><?= $data['jumlah_rt']?></td>
									<td align="right"><?= $data['jumlah_kk']?></td>
									<td align="right"><?= $data['jumlah_warga']?></td>
									<td align="right"><?= $data['jumlah_warga_l']?></td>
									<td align="right"><?= $data['jumlah_warga_p']?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
						<tr style="background-color:#BDD498;font-weight:bold;">
							<td colspan="4" width="50"><label>TOTAL</label></th>
							<td  align="right"><?= $total['jmlrt']?></th>
							<td  align="right"><?= $total['jmlkk']?></th>
							<td  align="right"><?= $total['jmlwarga']?></th>
							<td  align="right"><?= $total['jmlwargal']?></th>
							<td  align="right"><?= $total['jmlwargap']?></th>
						</tr>
					</tbody>
				</table>
			</div>
			<label>Tanggal cetak : &nbsp; </label>
			<?= tgl_indo(date('Y m d'))?>
		</div>
	</body>
</html>
