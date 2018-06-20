<html>
<head>
	<title>KIB C</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
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
		<h3> BUKU INVENTARIS DAN KEKAYAAN DESA
			<br><?= ($tahun == 1 ? "Semua Tahun" : "Tahun ". $tahun); ?>
		</h3>
		<br>
	</div>
	<div style="padding-bottom: 35px;">
		<div class="pull-left">
			<?php foreach ($header as $desa): ?>
				<?= strtoupper($this->setting->sebutan_desa.' = '.$desa['nama_desa']) ?>
			<?php endforeach; ?><br>
			<?php foreach ($header as $desa): ?>
				<?= strtoupper($this->setting->sebutan_kecamatan.' = '.$desa['nama_kecamatan']) ?>
			<?php endforeach; ?><br>
			<?php foreach ($header as $desa): ?>
				<?= strtoupper($this->setting->sebutan_kabupaten.' = '.$desa['nama_kabupaten']) ?>
			<?php endforeach; ?>
		</div>
		<div class="pull-right">
			KODE LOKASI : _ _ . _ _ . _ _ . _ _ . _ _ . _ _ . _ _ _
		</div>

	</div>
	<br>
  <table id="example" class="list border thick">
		<thead style="background-color:#f9f9f9;" >
			<tr>
					<th class="text-center" rowspan="3">No</th>
					<th class="text-center" rowspan="3">Jenis Barang</th>
					<th class="text-center" colspan="5">Asal barang</th>
					<th class="text-center" width="40%" rowspan="3">Keterangan</th>

			</tr>
			<tr>
					<th class="text-center" style="text-align:center;" rowspan="2">Dibeli Sendiri</th>
					<th class="text-center" style="text-align:center;" colspan="3">Bantuan</th>
					<th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
			</tr>
			<tr>
					<th class="text-center" style="text-align:center;">Pemerintah</th>
					<th class="text-center" style="text-align:center;">Provinsi</th>
					<th class="text-center" style="text-align:center;">Kabupaten</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>Tanah Kas Desa</td>
				<td>
					<?php
						$this->db->select('count(inventaris_tanah.asal) as total');
						$this->db->where('inventaris_tanah.visible',1);
						$this->db->where('inventaris_tanah.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_tanah.asal','Pembelian Sendiri');
						$result = $this->db->get('inventaris_tanah')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_tanah.asal) as total');
						$this->db->where('inventaris_tanah.visible',1);
						$this->db->where('inventaris_tanah.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_tanah.asal','Bantuan Pemerintah');
						$result = $this->db->get('inventaris_tanah')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_tanah.asal) as total');
						$this->db->where('inventaris_tanah.visible',1);
						$this->db->where('inventaris_tanah.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_tanah.asal','Bantuan Provinsi');
						$result = $this->db->get('inventaris_tanah')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_tanah.asal) as total');
						$this->db->where('inventaris_tanah.visible',1);
						$this->db->where('inventaris_tanah.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_tanah.asal','Bantuan Kabupaten');
						$result = $this->db->get('inventaris_tanah')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_tanah.asal) as total');
						$this->db->where('inventaris_tanah.visible',1);
						$this->db->where('inventaris_tanah.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_tanah.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_tanah.asal','Sumbangan');
						$result = $this->db->get('inventaris_tanah')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
				Informasi mengenai segala yang menyangkut dengan tanah
				(dalam hal ini tanah yang digunakan dalam instansi tersebut).
				</td>
			</tr>
			<tr>
				<td>2</td>
				<td>Peralatan dan Mesin</td>
				<td>
					<?php
						$this->db->select('count(inventaris_peralatan.asal) as total');
						$this->db->where('inventaris_peralatan.visible',1);
						$this->db->where('inventaris_peralatan.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_peralatan.asal','Pembelian Sendiri');
						$result = $this->db->get('inventaris_peralatan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_peralatan.asal) as total');
						$this->db->where('inventaris_peralatan.visible',1);
						$this->db->where('inventaris_peralatan.status',0);
						$this->db->where('inventaris_peralatan.asal','Bantuan Pemerintah');
						$result = $this->db->get('inventaris_peralatan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_peralatan.asal) as total');
						$this->db->where('inventaris_peralatan.visible',1);
						$this->db->where('inventaris_peralatan.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_peralatan.asal','Bantuan Provinsi');
						$result = $this->db->get('inventaris_peralatan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_peralatan.asal) as total');
						$this->db->where('inventaris_peralatan.visible',1);
						$this->db->where('inventaris_peralatan.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_peralatan.asal','Bantuan Kabupaten');
						$result = $this->db->get('inventaris_peralatan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_peralatan.asal) as total');
						$this->db->where('inventaris_peralatan.visible',1);
						$this->db->where('inventaris_peralatan.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_peralatan.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_peralatan.asal','Sumbangan');
						$result = $this->db->get('inventaris_peralatan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>Informasi mengenai peralatan dan mesin</td>
			</tr>
			<tr>
				<td>3</td>
				<td>Gedung dan Bangunan</td>
				<td>
					<?php
						$this->db->select('count(inventaris_gedung.asal) as total');
						$this->db->where('inventaris_gedung.visible',1);
						$this->db->where('inventaris_gedung.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_gedung.asal','Pembelian Sendiri');
						$result = $this->db->get('inventaris_gedung')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_gedung.asal) as total');
						$this->db->where('inventaris_gedung.visible',1);
						$this->db->where('inventaris_gedung.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_gedung.asal','Bantuan Pemerintah');
						$result = $this->db->get('inventaris_gedung')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_gedung.asal) as total');
						$this->db->where('inventaris_gedung.visible',1);
						$this->db->where('inventaris_gedung.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_gedung.asal','Bantuan Provinsi');
						$result = $this->db->get('inventaris_gedung')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_gedung.asal) as total');
						$this->db->where('inventaris_gedung.visible',1);
						$this->db->where('inventaris_gedung.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_gedung.asal','Bantuan Kabupaten');
						$result = $this->db->get('inventaris_gedung')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_gedung.asal) as total');
						$this->db->where('inventaris_gedung.visible',1);
						$this->db->where('inventaris_gedung.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_gedung.asal','Sumbangan');
						$result = $this->db->get('inventaris_gedung')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>Informasi mengenai gedung dan bangunan yang dimiliki.</td>
			</tr>
			<tr>
				<td>4</td>
				<td>Jalan Irigasi dan Jaringan</td>
				<td>
					<?php
						$this->db->select('count(inventaris_jalan.asal) as total');
						$this->db->where('inventaris_jalan.visible',1);
						$this->db->where('inventaris_jalan.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_jalan.asal','Pembelian Sendiri');
						$result = $this->db->get('inventaris_jalan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_jalan.asal) as total');
						$this->db->where('inventaris_jalan.visible',1);
						$this->db->where('inventaris_jalan.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_jalan.asal','Bantuan Pemerintah');
						$result = $this->db->get('inventaris_jalan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_jalan.asal) as total');
						$this->db->where('inventaris_jalan.visible',1);
						$this->db->where('inventaris_jalan.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_jalan.asal','Bantuan Provinsi');
						$result = $this->db->get('inventaris_jalan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_jalan.asal) as total');
						$this->db->where('inventaris_jalan.visible',1);
						$this->db->where('inventaris_jalan.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_jalan.asal','Bantuan Kabupaten');
						$result = $this->db->get('inventaris_jalan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_jalan.asal) as total');
						$this->db->where('inventaris_jalan.visible',1);
						$this->db->where('inventaris_jalan.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_jalan.asal','Sumbangan');
						$result = $this->db->get('inventaris_jalan')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>Informasi mengenai jaringan, seperti listrik atau Internet.</td>
			</tr>
			<tr>
				<td>5</td>
				<td>Asset Tetap Lainnya</td>
				<td>
					<?php
						$this->db->select('count(inventaris_asset.asal) as total');
						$this->db->where('inventaris_asset.visible',1);
						$this->db->where('inventaris_asset.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_asset.asal','Pembelian Sendiri');
						$result = $this->db->get('inventaris_asset')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_asset.asal) as total');
						$this->db->where('inventaris_asset.visible',1);
						$this->db->where('inventaris_asset.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_asset.asal','Bantuan Pemerintah');
						$result = $this->db->get('inventaris_asset')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_asset.asal) as total');
						$this->db->where('inventaris_asset.visible',1);
						$this->db->where('inventaris_asset.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_asset.asal','Bantuan Provinsi');
						$result = $this->db->get('inventaris_asset')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_asset.asal) as total');
						$this->db->where('inventaris_asset.visible',1);
						$this->db->where('inventaris_asset.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_asset.asal','Bantuan Kabupaten');
						$result = $this->db->get('inventaris_asset')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_asset.asal) as total');
						$this->db->where('inventaris_asset.visible',1);
						$this->db->where('inventaris_asset.status',0);
						if($tahun != 1){
							$this->db->where('inventaris_asset.tahun_pengadaan',$tahun);
						}
						$this->db->where('inventaris_asset.asal','Sumbangan');
						$result = $this->db->get('inventaris_asset')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.</td>
			</tr>
			<tr>
				<td>6</td>
				<td>Kontruksi Dalam Pengerjaan</td>
				<td>
					<?php
						$this->db->select('count(inventaris_kontruksi.asal) as total');
						$this->db->where('inventaris_kontruksi.visible',1);
						$this->db->where('inventaris_kontruksi.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_kontruksi.asal','Pembelian Sendiri');
						$result = $this->db->get('inventaris_kontruksi')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_kontruksi.asal) as total');
						$this->db->where('inventaris_kontruksi.visible',1);
						$this->db->where('inventaris_kontruksi.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_kontruksi.asal','Bantuan Pemerintah');
						$result = $this->db->get('inventaris_kontruksi')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_kontruksi.asal) as total');
						$this->db->where('inventaris_kontruksi.visible',1);
						$this->db->where('inventaris_kontruksi.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_kontruksi.asal','Bantuan Provinsi');
						$result = $this->db->get('inventaris_kontruksi')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_kontruksi.asal) as total');
						$this->db->where('inventaris_kontruksi.visible',1);
						$this->db->where('inventaris_kontruksi.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_kontruksi.asal','Bantuan Kabupaten');
						$result = $this->db->get('inventaris_kontruksi')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>
					<?php
						$this->db->select('count(inventaris_kontruksi.asal) as total');
						$this->db->where('inventaris_kontruksi.visible',1);
						$this->db->where('inventaris_kontruksi.status',0);
						if($tahun != 1){
							$this->db->where('year(tanggal_dokument)',$tahun);
						}
						$this->db->where('inventaris_kontruksi.asal','Sumbangan');
						$result = $this->db->get('inventaris_kontruksi')->row();
						echo (!empty($result->total) ? $result->total : '0');
					?>
				</td>
				<td>Informasi mengenai bangunan yang masih dalam pengerjaan.</td>
			</tr>

		</tbody>
	</table>


	<table id="ttd">
		<tr><td colspan="14">&nbsp;</td></tr>
		<tr><td colspan="14">&nbsp;</td></tr>
		<tr>
			<!-- Persen untuk tampilan cetak.
					 Colspan untuk tampilan unduh.
			 -->
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	></td>
			<td colspan="5" width="55%"><span class="underline"><?= strtoupper($this->setting->sebutan_desa.' '.$desa['nama_desa'].','.$desa['nama_kecamatan'].','.tgl_indo(date("Y m d")))?></span></td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>

		<tr><td colspan="14">&nbsp;</td></tr>
		<tr><td colspan="14">&nbsp;</td></tr>
		<tr>
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	>MENGETAHUI</td>
			<td colspan="5" width="55%"></td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	>KEPALA SKPD</td>
			<td colspan="5" width="55%"><?= strtoupper($pamong->jabatan)?></td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	></td>
			<td colspan="5" width="55%"></td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>
		<tr><td colspan="14">&nbsp;</td></tr>
		<tr><td colspan="14">&nbsp;</td></tr>
		<tr><td colspan="14">&nbsp;</td></tr>
		<tr>
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	>(...................................)</td>
			<td colspan="5" width="55%">( <?= strtoupper($pamong->pamong_nama)?>) </td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	>NIP ...................................</td>
			<td colspan="5" width="55%"> <?= strtoupper($pamong->pamong_nip)?> </td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>
	</table>
</div>
</div> <!-- Container -->

</body></html>