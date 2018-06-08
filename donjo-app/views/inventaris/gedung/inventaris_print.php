<html >
<head>
	<title>KIB C</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
	<?php endif; ?>
	<style>
		.textx{
		  mso-number-format:"\@";
		}
		td,th{
			font-size:9pt;
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
		table#inventaris { border: solid 2px black; }
		td.border { border: dotted 0.5px gray; }
		th.border { border: solid 0.5pt gray; }

		.pull-left{
			position: relative;
			width: 50%;
			float: left;
		}

		.pull-right{
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
			C. GEDUNG DAN BANGUNAN
		</h3>
		<br>
	</div>
	<div style="padding-bottom: 35px;">
		<div class="pull-left">
			<?php foreach($header as $desa){ echo strtoupper($this->setting->sebutan_desa.' = '.$desa['nama_desa']);} ?><br>
			<?php foreach($header as $desa){ echo strtoupper($this->setting->sebutan_kecamatan.' = '.$desa['nama_kecamatan']);} ?><br>
			<?php foreach($header as $desa){ echo strtoupper($this->setting->sebutan_kabupaten.' = '.$desa['nama_kabupaten']);} ?>
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
				<th class="text-center" rowspan="2">Kondisi Bangunan (B, KB, RB)</th>
				<th class="text-center" colspan="2">Kontruksi Bangunan</th>
				<th class="text-center" rowspan="2">Luas Lantai (M<sup>2</sup>)</th>
				<th class="text-center" rowspan="2">Letak/Lokasi</th>
				<th class="text-center" colspan="2">Dokumen Gedung</th>
				<th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
				<th class="text-center" rowspan="2">Status Tanah</th>
				<th class="text-center" rowspan="2">Nomor Tanah</th>
				<th class="text-center" rowspan="2">Asal Usul</th>
				<th class="text-center" rowspan="2">Harga (Rp)</th>
				<th class="text-center" rowspan="2">Keterangan</th>
		</tr>
		<tr>
				<th class="text-center" style="text-align:center;" rowspan="1">Kode Barang</th>
				<th class="text-center" style="text-align:center;" rowspan="1">Register</th>
				<th class="text-center" style="text-align:center;" rowspan="1">Bertingkat / Tidak</th>
				<th class="text-center" style="text-align:center;" rowspan="1">Beton / Tidak</th>
				<th class="text-center" style="text-align:center;" rowspan="1">Tanggal</th>
				<th class="text-center" style="text-align:center;" rowspan="1">Nomor</th>
		</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				foreach($print as $data){
					
			?>
	    	<tr>
				<td><?php echo $i ?></td>
				<td><?php echo $data->nama_barang; ?></td>
				<td><?php echo $data->kode_barang; ?></td>
				<td><?php echo $data->register; ?></td>
				<td><?php echo $data->kondisi_bangunan; ?></td>
				<td><?php echo $data->kontruksi_bertingkat; ?></td>
				<td><?php echo ($data->kontruksi_beton == '1' ? 'Ya': 'Tidak'); ?></td>
				<td><?php echo $data->luas_bangunan; ?></td>
				<td><?php echo $data->letak; ?></td>
				<td><?php echo date('d M Y',strtotime($data->tanggal_dokument)); ?></td>
				<td><?php echo (!empty($data->no_dokument) ? $data->no_dokument : '-'); ?></td>
				<td><?php echo $data->luas; ?></td>
				<td><?php echo $data->status_tanah; ?></td>
				<td><?php echo (!empty($main->no_tanah) ? $main->no_tanah : '-'); ?></td>
				<td><?php echo $data->asal; ?></td>
				<td><?php echo number_format($data->harga,0,".","."); ?></td>
				<td><?php echo $data->keterangan; ?></td>
			</tr>
			<?php
				$i = $i+1;
				} 
			?>
	  </tbody>
	  <tfooot>
			<tr>
				<th colspan="15" style="text-align:right">Total:</th>
				<th colspan="2"><?php echo number_format($total,0,".","."); ?></th>
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
			<td colspan="3" width="30%"	></td>
			<td colspan="5" width="55%"><span class="underline"><?php echo strtoupper($this->setting->sebutan_desa.' '.$desa['nama_desa'].','.$desa['nama_kecamatan'].','.tgl_indo(date("Y m d")))?></span></td>
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
			<td colspan="5" width="55%"><?php echo strtoupper($pamong->jabatan)?></td>
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
			<td colspan="5" width="55%">( <?php echo strtoupper($pamong->pamong_nama)?>) </td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" width="10%">&nbsp;</td>
			<td colspan="3" width="30%"	>NIP ...................................</td>
			<td colspan="5" width="55%"> <?php echo strtoupper($pamong->pamong_nip)?> </td>
			<td colspan="5" width="5%">&nbsp;</td>
		</tr>
	</table>
</div>
</div> <!-- Container -->

</body></html>
