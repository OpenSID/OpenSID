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
					<h3>DATA WILAYAH ADMINISTRASI </h3>
					<h4><?= strtoupper($this->setting->sebutan_dusun); ?> <?= $desa['desa']['nama_desa']?></h4>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th class="no_urut">No</th>
							<th width="10%">Nama <?= ucwords($this->setting->sebutan_dusun)?></th>
							<th width="60%" nowrap>Kepala <?= ucwords($this->setting->sebutan_dusun)?></th>
							<th class="bilangan">RW</th>
							<th class="bilangan">RT</th>
							<th class="bilangan">KK</th>
							<th class="bilangan">L+P</th>
							<th class="bilangan">L</th>
							<th class="bilangan">P</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td class="no_urut"><?= $data['no']?></td>
								<td nowrap><?= $data['dusun']?></td>
								<td nowrap><?= $data['nama_kadus']?> - <?= $data['nik_kadus']?></td>
								<td class="bilangan"><?= $data['jumlah_rw']?></td>
								<td class="bilangan"><?= $data['jumlah_rt']?></td>
								<td class="bilangan"><?= $data['jumlah_kk']?></td>
								<td class="bilangan"><?= $data['jumlah_warga']?></td>
								<td class="bilangan"><?= $data['jumlah_warga_l']?></td>
								<td class="bilangan"><?= $data['jumlah_warga_p']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr style="background-color:#BDD498;font-weight:bold;">
							<td colspan="3" align="left"><label>TOTAL</label></td>
							<td class="bilangan"><?= $total['total_rw']?></td>
							<td class="bilangan"><?= $total['total_rt']?></td>
							<td class="bilangan"><?= $total['total_kk']?></td>
							<td class="bilangan"><?= $total['total_warga']?></td>
							<td class="bilangan"><?= $total['total_warga_l']?></td>
							<td class="bilangan"><?= $total['total_warga_p']?></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<br>
			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>
