<?php
$tgl =  date('d_m_Y');

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=penduduk_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Data Penduduk</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
<style>
.textx{
 mso-number-format:"\@";
}
td,th{
	font-size:8pt;
	mso-number-format:"\@";
}
</style>
</head>
<body>
<div id="container">
<!-- Print Body --><div id="body">
 <table border=1 class="border thick">
	<thead>
		<tr class="border thick">
			<th>Alamat</th>
			<th>Dusun</th>
			<th>RW</th>
			<th>RT</th>
			<th>Nama</th>
			<th>Nomor KK</th>
			<th>Nomor NIK</th>
			<th>Jenis Kelamin</th>
			<th>Tempat Lahir</th>
			<th>Tanggal Lahir</th>
			<th>Agama</th>
			<th>Pendidikan (dLm KK)</th>
			<th>Pendidikan (sdg ditemph)</th>
			<th>Pekerjaan</th>
			<th>Kawin</th>
			<th>Hub. Keluarga</th>
			<th>Kewarganegaraan</th>
			<th>Nama Ayah</th>
			<th>Nama Ibu</th>
			<th>Gol. Darah</th>
			<th>Akta Lahir</th>
			<th>Nomor Dokumen Pasport</th>
			<th>Tanggal Akhir Pasport</th>
			<th>Nomor Dokumen KITAS</th>
			<th>NIK Ayah</th>
			<th>NIK Ibu</th>
			<th>Nomor Akta Perkawinan</th>
			<th>Tanggal Perkawinan</th>
			<th>Nomor Akta Perceraian</th>
			<th>Tanggal Perceraian</th>
			<th>Cacat</th>
			<th>Cara KB</th>
			<th>Hamil</th>			
		</tr>
	</thead>
	<tbody>
		 <?php foreach($main as $data): ?>
		 <tr>
			<td><?php echo strtoupper($data['alamat'])?></td>
			<td><?php echo strtoupper(ununderscore($data['dusun']))?></td>
			<td><?php echo $data['rw']?></td>
			<td><?php echo $data['rt']?></td>
			<td><?php echo strtoupper($data['nama'])?></td>
			<td><?php echo $data['no_kk']?> </td>
			<td><?php echo $data['nik']?></td>
			<td><?php echo $data['sex']?></td>
			<td><?php echo $data['tempatlahir']?></td>
			<td><?php echo $data['tanggallahir']?></td>
			<td><?php echo $data['agama_id']?></td>
			<td><?php echo $data['pendidikan_kk_id']?></td>
			<td><?php echo $data['pendidikan_sedang_id']?></td>
			<td><?php echo $data['pekerjaan_id']?></td>
			<td><?php echo $data['status_kawin']?></td>
			<td><?php echo $data['kk_level']?></td>
			<td><?php echo $data['warganegara_id']?></td>
			<td><?php echo $data['nama_ayah']?></td>
			<td><?php echo $data['nama_ibu']?></td>
			<td><?php echo $data['golongan_darah_id']?></td>
			<td><?php echo $data['akta_lahir']?></td>
			<td><?php echo $data['dokumen_pasport']?></td>
			<td><?php echo $data['tanggal_akhir_paspor']?></td>
			<td><?php echo $data['dokumen_kitas']?></td>
			<td><?php echo $data['ayah_nik']?></td>
			<td><?php echo $data['ibu_nik']?></td>
			<td><?php echo $data['akta_perkawinan']?></td>
			<td><?php echo $data['tanggalperkawinan']?></td>
			<td><?php echo $data['akta_perceraian']?></td>
			<td><?php echo $data['tanggalperceraian']?></td>
			<td><?php echo $data['cacat_id']?></td>
			<td><?php echo $data['cara_kb_id']?></td>
			<td><?php echo $data['hamil']?></td>			
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>
</div>
</body>
</html>