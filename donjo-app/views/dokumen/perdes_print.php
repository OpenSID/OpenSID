<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Data Peraturan Desa</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
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
	</style>
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">
	<div class="header" align="center">
		<h3>BUKU PERATURAN DESA <?= strtoupper($desa['nama_desa'])?></h3>
		<h3><?= strtoupper($this->setting->sebutan_kecamatan.' '.$desa['nama_kecamatan'].' '.$this->setting->sebutan_kabupaten.' '.$desa['nama_kabupaten'])?></h3>
		<h3><?= !empty($tahun) ? 'TAHUN '. $tahun : ''?></h3>
		<br>
	</div>
	<table class="border thick">
		<thead>
			<tr class="border thick">
				<th>NOMOR URUT</th>
				<th>JENIS PERATURAN DI DESA</th>
		  	<th>NOMOR DAN TANGGAL DITETAPKAN</th>
				<th>TENTANG</th>
		  	<th>URAIAN SINGKAT</th>
		  	<th>TANGGAL KESEPAKATAN PERATURAN DESA</th>
		  	<th>NOMOR DAN TANGGAL DILAPORKAN</th>
		  	<th>NOMOR DAN TANGGAL DIUNDANGKAN DALAM LEMBARAN DESA</th>
		  	<th>NOMOR DAN TANGGAL DIUNDANGKAN DALAM BERITA DESA</th>
		  	<th>KET.</th>
			</tr>
		</thead>
		<tbody>
			 <?php  foreach($main as $data): ?>
			 <tr>
				<td><?= $data['no']?></td>
				<td><?= $data['attr']['jenis_peraturan']?></td>
		  	<td><?= 'Nomor '.$data['attr']['no_ditetapkan'].", Tanggal ".tgl_indo_dari_str($data['attr']['tgl_ditetapkan'])?></td>
			  <td><?= $data['nama']?></td>
		  	<td><?= $data['attr']['uraian']?></td>
		  	<td><?= tgl_indo_dari_str($data['attr']['tgl_kesepakatan'])?></td>
		  	<td><?= 'Nomor '.$data['attr']['no_lapor'].", Tanggal ".tgl_indo_dari_str($data['attr']['tgl_lapor'])?></td>
		  	<td><?= 'Nomor '.$data['attr']['no_lembaran_desa'].", Tanggal ".tgl_indo_dari_str($data['attr']['tgl_lembaran_desa'])?></td>
		  	<td><?= 'Nomor '.$data['attr']['no_berita_desa'].", Tanggal ".tgl_indo_dari_str($data['attr']['tgl_berita_desa'])?></td>
			  <td><?= $data['attr']['keterangan']?></td>
			</tr>
			<?php  endforeach; ?>
		</tbody>
	</table>
	<br><br>
	<table id="ttd">
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr>
			<!-- Persen untuk tampilan cetak.
					 Colspan untuk tampilan unduh.
			 -->
			<td colspan="2" width="35%">&nbsp;</td>
			<td colspan="3" width="15%"	align="center">MENGETAHUI</td>
			<td colspan="3" width="25%" align="center"><span class="underline"><?php echo strtoupper($this->setting->sebutan_desa.' '.$desa['nama_desa'].', '.$desa['nama_kecamatan'].', '.tgl_indo(date("Y m d")))?></span></td>
			<td colspan="2" width="25%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="3" align="center"><?php echo strtoupper($input['jabatan_ketahui'])?></td>
			<td colspan="3" align="center"><?php echo strtoupper($input['jabatan_ttd'])?></td>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr><td colspan="10">&nbsp;</td></tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="3" align="center"><span class="underline"><?php echo strtoupper($input['pamong_ketahui'])?></span></td>
			<td colspan="3" align="center"><span class="underline"><?php echo strtoupper($input['pamong_ttd'])?></span></td>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table>
</div>
</div>

</body></html>
