<html>

<head>
	<title>KIB F</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?= base_url() ?>assets/css/report.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
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
					F. KONSTRUKSI DALAM PENGERJAAN
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
						<th class="text-center" rowspan="2">No</th>
						<th class="text-center" rowspan="2">Nama Barang</th>
						<th class="text-center" rowspan="2">Bangunan (P, SP, D)</th>
						<th class="text-center" colspan="2">Konstruksi Bangunan</th>
						<th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
						<th class="text-center" rowspan="2">Letak/Lokasi</th>
						<th class="text-center" colspan="2">Dokumen</th>
						<th class="text-center" rowspan="2">Tgl, Bln, Thn mulai</th>
						<th class="text-center" rowspan="2">Status Tanah</th>
						<th class="text-center" rowspan="2">Nomor Kode Tanah</th>
						<th class="text-center" rowspan="2">Asal Usul Pembiayaan</th>
						<th class="text-center" rowspan="2">Nulai Kontrak (Rp)</th>
						<th class="text-center" rowspan="2">Keterangan</th>
					</tr>
					<tr>
						<th class="text-center" style="text-align:center;" rowspan="1">Bertingkat / Tidak</th>
						<th class="text-center" style="text-align:center;" rowspan="1">Beton / Tidak</th>
						<th class="text-center" style="text-align:center;" rowspan="1">Tanggal</th>
						<th class="text-center" style="text-align:center;" rowspan="1">Nomor</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1 ?>
					<?php foreach ($print as $data) : ?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $data->nama_barang; ?></td>
							<td><?= $data->kondisi_bangunan; ?></td>
							<td><?= (! empty($data->kontruksi_bertingkat) ? $data->kontruksi_bertingkat : '-'); ?></td>
							<td><?= ($data->kontruksi_beton == '1' ? 'Ya' : 'Tidak'); ?></td>
							<td><?= (! empty($data->luas_bangunan) ? $data->luas_bangunan : '-'); ?></td>
							<td><?= $data->letak; ?></td>
							<td><?= date('d M Y', strtotime($data->tanggal_dokument)); ?></td>
							<td><?= (! empty($data->no_dokument) ? $data->no_dokument : '-'); ?></td>
							<td><?= date('d M Y', strtotime($data->tanggal)); ?></td>
							<td><?= (! empty($data->status_tanah) ? $data->status_tanah : '-'); ?></td>
							<td><?= (! empty($main->no_tanah) ? $main->no_tanah : '-'); ?></td>
							<td><?= $data->asal; ?></td>
							<td><?= number_format($data->harga, 0, '.', '.'); ?></td>
							<td><?= $data->keterangan; ?></td>
						</tr>
						<?php $i = $i + 1 ?>
					<?php endforeach; ?>
				</tbody>
				<tfooot>
					<tr>
						<th colspan="13" style="text-align:right">Total:</th>
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