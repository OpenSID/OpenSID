<html>

<head>
	<title>KIB B</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?= base_url() ?>assets/css/report.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
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
				<h3> KARTU INVENTARIS BARANG (KIB) <br>
					B. PERALATAN DAN MESIN
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
				</div>
				<div class="pull-right">
					KODE LOKASI : _ _ . _ _ . _ _ . _ _ . _ _ . _ _ . _ _ _
				</div>

			</div>
			<br>
			<table id="inventaris" class="list border thick">
				<thead>
					<tr>
						<th rowspan="2">No</th>
						<th rowspan="2">Jenis barang / Nama Barang</th>
						<th colspan="2">Nomor</th>
						<th rowspan="2">Merk/Type</th>
						<th rowspan="2">Ukuran/CC</th>
						<th rowspan="2">Bahan</th>
						<th rowspan="2">Tahun Pembelian</th>
						<th colspan="5">Nomor</th>
						<th rowspan="2">Asal Usul</th>
						<th rowspan="2">Harga (Rp)</th>
						<th rowspan="2">Keterangan</th>
					</tr>
					<tr>
						<th>Kode Barang</th>
						<th>Registrasi</th>
						<th>Pabrik</th>
						<th>Rangka</th>
						<th>Mesin</th>
						<th>Polisi</th>
						<th>BPKB</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1 ?>
					<?php foreach ($print as $data) : ?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $data->nama_barang; ?></td>
							<td><?= $data->kode_barang; ?></td>
							<td><?= $data->register; ?></td>
							<td><?= $data->merk; ?></td>
							<td><?= $data->ukuran; ?></td>
							<td><?= $data->bahan; ?></td>
							<td><?= $data->tahun_pengadaan; ?></td>
							<td><?= $data->no_pabrik; ?></td>
							<td><?= $data->no_rangka; ?></td>
							<td><?= $data->no_mesin; ?></td>
							<td><?= $data->no_polisi; ?></td>
							<td><?= $data->no_bpkb; ?></td>
							<td><?= $data->asal; ?></td>
							<td><?= number_format($data->harga, 0, '.', '.'); ?></td>
							<td><?= $data->keterangan; ?></td>
						</tr>
						<?php $i = $i + 1 ?>
					<?php endforeach; ?>
				</tbody>
				<tfooot>
					<tr>
						<th colspan="14" style="text-align:right">Total:</th>
						<th colspan="2"><?= number_format($total, 0, '.', '.'); ?></th>
					</tr>
				</tfooot>
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
					<td colspan="5" width="55%"><span class="underline"><?= strtoupper($this->setting->sebutan_desa . ' ' . $header['nama_desa'] . ', ' . tgl_indo(date('Y m d'))) ?></span></td>
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
					<td colspan="3" width="30%">KEPALA SKPD</td>
					<td colspan="5" width="55%"><?= strtoupper($pamong['jabatan']) ?></td>
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
					<td colspan="3" width="30%">(......................................................................)</td>
					<td colspan="5" width="55%">( <?= strtoupper($pamong['nama']) ?>) </td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" width="10%">&nbsp;</td>
					<td colspan="3" width="30%">NIP ............................................................</td>
					<td colspan="5" width="55%"> <?= strtoupper($pamong['pamong_nip']) ?> </td>
					<td colspan="5" width="5%">&nbsp;</td>
				</tr>
			</table>
		</div>
	</div> <!-- Container -->
</body>

</html>