<html >
	<head>
		<title>KIB F</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<style>
			.textx{
				mso-number-format:"\@";
			}
			td,th
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
			/* Style berikut untuk unduh excel.
				Cetak mengabaikan dan menggunakan style dari report.css
			*/
			table#inventaris { border: solid 2px black; }
			td.border { border: dotted 0.5px gray; }
			th.border { border: solid 0.5pt gray; }

			.pull-left
			{
				position: relative;
				width: 50%;
				float: left;
			}

			.pull-right
			{
				position: relative;
				width: 50%;
				float: right;
				text-align:right;
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
						F. ASET TETAP LAINNYA
					</h3>
					<br>
				</div>
				<div style="padding-bottom: 35px;">
					<div class="pull-left">
						<?= strtoupper($this->setting->sebutan_desa.' = '.$header['nama_desa']) ?><br>
						<?= strtoupper($this->setting->sebutan_kecamatan.' = '.$header['nama_kecamatan']) ?><br>
						<?= strtoupper($this->setting->sebutan_kabupaten.' = '.$header['nama_kabupaten']) ?><br>
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
							<th class="text-center" colspan="2">Nomor</th>
							<th class="text-center" colspan="2">Buku / Perpustakaan</th>
							<th class="text-center" colspan="3">Barang Bercorak Kesenian/Kebudayaan</th>
							<th class="text-center" colspan="2">Hewan / Ternak</th>
							<th class="text-center" colspan="2">Tumbuhan</th>
							<th class="text-center" rowspan="2">Jumlah</th>
							<th class="text-center" rowspan="2">Tahun Cetak / Pembelian</th>
							<th class="text-center" rowspan="2">Asal Usul</th>
							<th class="text-center" rowspan="2">Harga (Rp)</th>
							<th class="text-center" rowspan="2">Keterangan</th>
					</tr>
					<tr>
							<th class="text-center" style="text-align:center;" rowspan="1">Kode Barang</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Register</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Judui / Pencipta</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Spesifikasi</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Asal Daerah</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Pencipta</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Bahan</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Jenis</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Ukuran (M)</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Jenis</th>
							<th class="text-center" style="text-align:center;" rowspan="1">Ukuran (cm)</th>
					</tr>
					</thead>
					<tbody>
						<?php $i = 1 ?>
						<?php foreach ($print as $data): ?>
							<tr>
								<td><?= $i ?></td>
								<td><?= $data->nama_barang; ?></td>
								<td><?= $data->kode_barang; ?></td>
								<td><?= $data->register; ?></td>
								<td><?= (empty($data->judul_buku) ? '-': $data->judul_buku); ?></td>
								<td><?= (empty($data->spesifikasi_buku) ? '-': $data->spesifikasi_buku); ?></td>
								<td><?= (empty($data->asal_daerah) ? '-': $data->asal_daerah); ?></td>
								<td><?= (empty($data->pencipta) ? '-': $data->pencipta); ?></td>
								<td><?= (empty($data->bahan) ? '-': $data->bahan); ?></td>
								<td><?= (empty($data->jenis_hewan) ? '-': $data->jenis_hewan); ?></td>
								<td><?= (empty($data->ukuran_hewan) ? '-': $data->ukuran_hewan); ?></td>
								<td><?= (empty($data->jenis_tumbuhan) ? '-': $data->jenis_tumbuhan); ?></td>
								<td><?= (empty($data->ukuran_tumbuhan) ? '-': $data->ukuran_tumbuhan); ?></td>
								<td><?= $data->jumlah; ?></td>
								<td><?= $data->tahun_pengadaan; ?></td>
								<td><?= $data->asal; ?></td>
								<td><?= number_format($data->harga,0,".","."); ?></td>
								<td><?= $data->keterangan; ?></td>
							</tr>
							<?php $i = $i+1 ?>
						<?php endforeach; ?>
					</tbody>
					<tfooot>
						<tr>
							<th colspan="16" style="text-align:right">Total:</th>
							<th colspan="2"><?= number_format($total,0,".","."); ?></th>
						</tr>
					</tfooot>
				</table>


				<table id="ttd">
					<tr><td colspan="14">&nbsp;</td></tr>
					<tr><td colspan="14">&nbsp;</td></tr>
					<tr>
						<!-- Persen untuk tampilan cetak.
								Colspan untuk tampilan unduh.
						-->
						<td colspan="2" width="10%">&nbsp;</td>
						<td colspan="3" width="30%"></td>
						<td colspan="5" width="55%"><span class="underline"><?= strtoupper($this->setting->sebutan_desa.' '.$header['nama_desa'].','.$header['nama_kecamatan'].','.tgl_indo(date("Y m d")))?></span></td>
						<td colspan="5" width="5%">&nbsp;</td>
					</tr>

					<tr><td colspan="14">&nbsp;</td></tr>
					<tr><td colspan="14">&nbsp;</td></tr>
					<tr>
						<td colspan="2" width="10%">&nbsp;</td>
						<td colspan="3" width="30%">MENGETAHUI</td>
						<td colspan="5" width="55%"></td>
						<td colspan="5" width="5%">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="2" width="10%">&nbsp;</td>
						<td colspan="3" width="30%">KEPALA SKPD</td>
						<td colspan="5" width="55%"><?= strtoupper($pamong->jabatan)?></td>
						<td colspan="5" width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" width="10%">&nbsp;</td>
						<td colspan="3" width="30%"></td>
						<td colspan="5" width="55%"></td>
						<td colspan="5" width="5%">&nbsp;</td>
					</tr>
					<tr><td colspan="14">&nbsp;</td></tr>
					<tr><td colspan="14">&nbsp;</td></tr>
					<tr><td colspan="14">&nbsp;</td></tr>
					<tr>
						<td colspan="2" width="10%">&nbsp;</td>
						<td colspan="3" width="30%">(...................................)</td>
						<td colspan="5" width="55%">( <?= strtoupper($pamong->pamong_nama)?>) </td>
						<td colspan="5" width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" width="10%">&nbsp;</td>
						<td colspan="3" width="30%">NIP ...................................</td>
						<td colspan="5" width="55%"> <?= strtoupper($pamong->pamong_nip)?> </td>
						<td colspan="5" width="5%">&nbsp;</td>
					</tr>
				</table>
			</div>
		</div> <!-- Container -->
	</body>
</html>
