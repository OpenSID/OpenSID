<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Data Penduduk</title>
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
	</style>
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">
	<div class="header" align="center">
		<label align="left"><?php echo get_identitas()?></label>
		<h3> BUKU INVENTARIS DAN KEKAYAAN DESA - Tahun <?php echo $input['tahun']?></h3>
		<br>
	</div>
  <table id="inventaris" class="list border thick">
		<thead>
      <tr>
        <th rowspan="3" class="border">No</th>
				<th rowspan="3" class="border">Jenis Barang 	</th>
				<th colspan="5" class="border">Asal Barang</th>
				<th colspan="2" class="border">Keadaan Barang Awal Tahun</th>
				<th colspan="4" class="border">Penghapusan</th>
				<th colspan="2" class="border">Keadaan Barang Akhir Tahun</th>
				<th rowspan="3" class="border" >Keterangan</th>
			</tr>
			<tr>
				<th rowspan="2" class="border">Dibeli Sendiri</th>
				<th colspan="3" class="border">Bantuan</th>
				<th rowspan="2" class="border">Sumbangan</th>
				<th rowspan="2" class="border">Baik</th>
				<th rowspan="2" class="border">Rusak</th>
				<th rowspan="2" class="border">Rusak</th>
				<th rowspan="2" class="border">Dijual</th>
				<th rowspan="2" class="border">Disumbang</th>
				<th rowspan="2" class="border">Tgl Penghapusan</th>
				<th rowspan="2" class="border">Baik</th>
				<th rowspan="2" class="border">Rusak</th>
			</tr>
			<tr>
				<th class="border">Pemerintah</th>
				<th class="border">Provinsi</th>
				<th class="border">Kabupaten</th>
			</tr>
		</thead>
		<tbody>
	    <?php $i = 0;
	    	foreach($main as $data){
	    		$i++; ?>
				<tr>
					<td class="border" align="center" width="2"><?php echo $i+$paging->offset?></td>
				  <td class="border"><?php echo $data['nama']?></td>
				  <td class="border"><?php echo $data['asal_sendiri']?></td>
				  <td class="border"><?php echo $data['asal_pemerintah']?></td>
				  <td class="border"><?php echo $data['asal_provinsi']?></td>
				  <td class="border"><?php echo $data['asal_kab']?></td>
				  <td class="border"><?php echo $data['asal_sumbangan']?></td>
				  <td class="border"><?php echo $data['status_baik_awal']?></td>
				  <td class="border"><?php echo $data['status_rusak_awal']?></td>
				  <td class="border"><?php echo $data['hapus_rusak']?></td>
				  <td class="border"><?php echo $data['hapus_dijual']?></td>
				  <td class="border"><?php echo $data['hapus_sumbangkan']?></td>
				  <td class="border"><?php echo tgl_indo_out($data['tgl_penghapusan'])?></td>
				  <td class="border"><?php echo $data['status_baik_akhir']?></td>
				  <td class="border"><?php echo $data['status_rusak_akhir']?></td>
				  <td class="border"><?php echo $data['keterangan']?></td>
				</tr>
	    <?php }?>
	  </tbody>
	</table>
	<table id="ttd">
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr>
			<!-- Persen untuk tampilan cetak.
					 Colspan untuk tampilan unduh.
			 -->
			<td colspan="6" width="35%">&nbsp;</td>
			<td colspan="2" width="15%"	>MENGETAHUI</td>
			<td colspan="5" width="25%"><span class="underline"><?php echo strtoupper($this->setting->sebutan_desa.' '.$desa['nama_desa'].','.$desa['nama_kecamatan'].','.tgl_indo(date("Y m d")))?></span></td>
			<td colspan="3" width="25%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
			<td colspan="2"><?php echo strtoupper($input['jabatan_ketahui'])?></td>
			<td colspan="5"><?php echo strtoupper($input['jabatan_ttd'])?></td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr><td colspan="16">&nbsp;</td></tr>
		<tr>
			<td colspan="6">&nbsp;</td>
			<td colspan="5"><span class="underline"><?php echo strtoupper($input['pamong_ketahui'])?></span></td>
			<td colspan="2"><span class="underline"><?php echo strtoupper($input['pamong_ttd'])?></span></td>
			<td colspan="3">&nbsp;</td>
		</tr>
	</table>
</div>
</div> <!-- Container -->

</body></html>
