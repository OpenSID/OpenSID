<?php
	$tgl =  date('d_m_Y');

	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=penduduk_$tgl.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Penduduk</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<style>
		.textx
		{
			mso-number-format:"\@";
		}
		td,th
		{
			font-size:8pt;
			mso-number-format:"\@";
		}
		</style>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
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
						<?php foreach ($main as $data): ?>
						<tr>
							<td><?= strtoupper($data['alamat'])?></td>
							<td><?= strtoupper(ununderscore($data['dusun']))?></td>
							<td><?= $data['rw']?></td>
							<td><?= $data['rt']?></td>
							<td><?= strtoupper($data['nama'])?></td>
							<td><?= $data['no_kk']?> </td>
							<td><?= $data['nik']?></td>
							<td><?= $data['sex']?></td>
							<td><?= $data['tempatlahir']?></td>
							<td><?= $data['tanggallahir']?></td>
							<td><?= $data['agama_id']?></td>
							<td><?= $data['pendidikan_kk_id']?></td>
							<td><?= $data['pendidikan_sedang_id']?></td>
							<td><?= $data['pekerjaan_id']?></td>
							<td><?= $data['status_kawin']?></td>
							<td><?= $data['kk_level']?></td>
							<td><?= $data['warganegara_id']?></td>
							<td><?= $data['nama_ayah']?></td>
							<td><?= $data['nama_ibu']?></td>
							<td><?= $data['golongan_darah_id']?></td>
							<td><?= $data['akta_lahir']?></td>
							<td><?= $data['dokumen_pasport']?></td>
							<td><?= $data['tanggal_akhir_paspor']?></td>
							<td><?= $data['dokumen_kitas']?></td>
							<td><?= $data['ayah_nik']?></td>
							<td><?= $data['ibu_nik']?></td>
							<td><?= $data['akta_perkawinan']?></td>
							<td><?= $data['tanggalperkawinan']?></td>
							<td><?= $data['akta_perceraian']?></td>
							<td><?= $data['tanggalperceraian']?></td>
							<td><?= $data['cacat_id']?></td>
							<td><?= $data['cara_kb_id']?></td>
							<td><?= $data['hamil']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>