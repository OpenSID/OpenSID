<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>DATA APARATUR PEMERINTAHAN DESSA</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<style>
			.textx
			{
				mso-number-format:"\@";
			}
		</style>
	</head>
	<body>
		<div id="container">

		<!-- Print Body -->
			<div id="body">
				<div align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> DATA APARATUR PEMERINTAHAN <?= strtoupper($this->setting->sebutan_desa) ?> </h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>NIK</th>
              <th>NIP</th>
							<th>Nama</th>
							<th>Tempat, Tanggal Lahir</th>
							<th>Jenis Kelamin</th>
							<th>Pendidikan</th>
							<th>Agama</th>
							<th>Jabatan</th>
							<th>Nomor SK</th>
							<th>Tanggal SK</th>
							<th>Masa/Periode Jabatan</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
                <td><?= $data['no']?></td>
								<td class="textx"><?= $data['nik']?></td>
								<td class="textx"><?= $data['pamong_nip']?></td>
								<td><?= $data['nama']?></td>
								<td><?= $data['tempatlahir'].', '.tgl_indo_out($data['tanggallahir'])?></td>
								<td><?= $data['sex']?></td>
								<td><?= $data['pendidikan_kk']?></td>
								<td><?= $data['agama']?></td>
								<td><?= $data['jabatan']?></td>
								<td><?= $data['pamong_nosk']?></td>
								<td><?= tgl_indo_out($data['pamong_tglsk'])?></td>
								<td><?= $data['pamong_masajab']?></td>
								<td><?= ($data['pamong_status'] == '1') ? "Aktif" : "Tidak Aktif" ?>
 							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<label>Tanggal cetak : &nbsp; </label>
			<?= tgl_indo(date("Y m d"))?>
		</div>
	</body>
</html>
