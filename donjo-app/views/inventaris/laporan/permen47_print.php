<html>

<head>
	<title>Buku Inventaris Dan Kekayaan Desa</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
	<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
	<!-- TODO: Pindahkan ke external css -->
	<style>
	.textx {
		mso-number-format: "\@";
	}

	td,
	th {
		font-size: 9pt;
	}

	table#ttd td {
		text-align: center;
		white-space: nowrap;
	}

	.underline {
		text-decoration: underline;
	}

	/* Style berikut untuk unduh excel.
				Cetak mengabaikan dan menggunakan style dari report.css
			*/
	table#inventaris {
		border: solid 2px black;
	}

	td.border {
		border: dotted 0.5px gray;
	}

	th.border {
		border: solid 0.5pt gray;
	}

	.pull-left {
		position: relative;
		width: 50%;
		float: left;
	}

	.pull-right {
		position: relative;
		width: 50%;
		float: right;
		text-align: right;
		/* padding-right:20px; */
	}
	</style>
</head>

<body>
	<div id="container">
		<!-- Print Body -->
		<div id="body">
			<div class="" align="center">
				<h3> Buku Inventaris Dan Kekayaan <?= ucfirst($this->setting->sebutan_desa) . ' ' . ucfirst($header['nama_desa']) ?>
					<br><?= 'Tahun ' . $tahun; ?>
				</h3>
				<br>
			</div>
			<div style="padding-bottom: 35px;">
				<div class="pull-left" style="width: auto">
					<table>
						<tr>
							<td><?= strtoupper($this->setting->sebutan_desa) ?></td>
							<td style="padding-left: 10px"><?= strtoupper(' : ' . $header['nama_desa']) ?></td>
						</tr>
						<tr>
							<td><?= strtoupper($this->setting->sebutan_kecamatan) ?></td>
							<td style="padding-left: 10px"><?= strtoupper(' : ' . $header['nama_kecamatan']) ?></td>
						</tr>
						<tr>
							<td><?= strtoupper($this->setting->sebutan_kabupaten) ?></td>
							<td style="padding-left: 10px"><?= strtoupper(' : ' . $header['nama_kabupaten']) ?></td>
						</tr>
					</table>
					<br>

				</div>
			</div>
			<br>
			<table id="example" class="list border thick">
				<thead style="background-color:#f9f9f9;">
					<tr>
						<th class="text-center" rowspan="3">No</th>
						<th class="text-center" rowspan="3">Jenis Barang/Bangunan</th>
						<th class="text-center" rowspan="1" colspan="5">Asal Barang/Bangungan</th>
						<th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan AWal Tahun</th>
						<th class="text-center" rowspan="1" colspan="4">Penghapusan Barang Dan Bangunan</th>
						<th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan Akhir Tahun</th>
						<th class="text-center" rowspan="3">Ket</th>
					</tr>
					<tr>
						<th class="text-center" rowspan="2">Dibeli Sendiri</th>
						<th class="text-center" rowspan="1" colspan="3">Bantuan</th>
						<th class="text-center" rowspan="2">Sumbangan</th>
						<th class="text-center" rowspan="2" width="50px">Baik</th>
						<th class="text-center" rowspan="2" width="50px">Rusak</th>
						<th class="text-center" rowspan="2">Rusak</th>
						<th class="text-center" rowspan="2">Dijual</th>
						<th class="text-center" rowspan="2">Disumbangkan</th>
						<th class="text-center" rowspan="2">Tgl Penghapusan</th>
						<th class="text-center" rowspan="2" width="50px">Baik</th>
						<th class="text-center" rowspan="2" width="50px">Rusak</th>
					</tr>
					<tr>
						<th>Pemerintah</th>
						<th>Provinsi</th>
						<th>Kab/Kota</th>
					</tr>
					<tr>
						<th class="text-center">1</th>
						<th class="text-center">2</th>
						<th class="text-center">3</th>
						<th class="text-center">4</th>
						<th class="text-center">5</th>
						<th class="text-center">6</th>
						<th class="text-center">7</th>
						<th class="text-center">8</th>
						<th class="text-center">9</th>
						<th class="text-center">10</th>
						<th class="text-center">11</th>
						<th class="text-center">12</th>
						<th class="text-center">13</th>
						<th class="text-center">14</th>
						<th class="text-center">15</th>
						<th class="text-center">16</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1 ?>
					<?php foreach ($data as $uraian => $asset): ?>
					<tr>
						<td><?= $i ?></td>
						<td><?= $uraian ?></td>
						<td class="text-center"><?= count($asset['Pembelian Sendiri']) ?></td>
						<td class="text-center"><?= count($asset['Bantuan Pemerintah']) ?></td>
						<td class="text-center"><?= count($asset['Bantuan Provinsi']) ?></td>
						<td class="text-center"><?= count($asset['Bantuan Kabupaten']) ?></td>
						<td class="text-center"><?= count($asset['Sumbangan']) ?></td>
						<td class="text-center"><?= count($asset['awal_baik']) ?></td>
						<td class="text-center"><?= count($asset['awal_rusak']) ?></td>
						<td class="text-center"><?= count($asset['hapus_rusak']) ?></td>
						<td class="text-center"><?= count($asset['hapus_jual']) ?></td>
						<td class="text-center"><?= count($asset['hapus_sumbang']) ?></td>
						<td class="text-center"><?= tgl_indo($asset['tgl_hapus']) ?></td>
						<td class="text-center"><?= count($asset['akhir_baik']) ?></td>
						<td class="text-center"><?= count($asset['akhir_rusak']) ?></td>
						<td>
							<?php foreach ($asset['keterangan'] as $ket): ?>
							<li><?= $ket ?></li>
							<?php endforeach ?>
						</td>
					</tr>
					<?php $i++ ?>
					<?php endforeach ?>
				</tbody>
			</table>
			<table id="ttd">
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<!-- Persen untuk tampilan cetak.
								Colspan untuk tampilan unduh.
						-->
					<td colspan="2" width="10%">&nbsp;</td>
					<td colspan="3" width="30%"></td>
					<td colspan="5" width="55%"> </td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" width="10%">&nbsp;</td>
					<td colspan="3" width="30%">MENGETAHUI</td>
					<td colspan="5" width="55%"></td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" width="10%">&nbsp;</td>
					<td colspan="3" width="30%"><?= ucfirst($this->setting->sebutan_kepala_desa) . ' ' . ucfirst($header['nama_desa']) ?></td>
					<td colspan="5" width="55%">Sekretaris <?= ucfirst($this->setting->sebutan_desa) . ' ' . ucfirst($header['nama_desa']) ?></td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" width="10%">&nbsp;</td>
					<td colspan="3" width="30%"></td>
					<td colspan="5" width="55%"></td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" width="10%">&nbsp;</td>
					<td colspan="3" width="30%">(<?= strtoupper($kades['nama'])?>)</td>
					<td colspan="5" width="55%">(<?= strtoupper($sekdes['nama'])?>) </td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
			</table>
		</div>
	</div> <!-- Container -->
</body>

</html>