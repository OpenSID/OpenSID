<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>DATA ARSIP LAYANAN SURAT</title>
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
					<h3> DATA ARSIP LAYANAN SURAT <?= strtoupper($this->setting->sebutan_desa) ?> </h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>No Kode Surat</th>
							<th>No Urut Surat</th>
              <th>Jenis Surat</th>
							<th>Nama Penduduk</th>
							<th>Keterangan</th>
							<th>Ditandatangani Oleh</th>
							<th>Tanggal</th>
							<th>User</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td><?= $data['no']?></td>
								<td class="textx"><?= $data['kode_surat']?></td>
								<td class="textx"><?= $data['no_surat']?></td>
								<td class="textx"><?= $data['format']?></td>
								<td>
                  <?php if ($data['nama']): ?>
                      <?= $data['nama']; ?>
                  <?php elseif ($data['nama_non_warga']): ?>
                      <strong>Non-warga: </strong><?= $data['nama_non_warga']; ?><br>
                      <strong>NIK: </strong><?= $data['nik_non_warga']; ?>
                  <?php endif; ?>
								</td>
								<td><?= $data['keterangan']?></td>
                <td><?= $data['pamong']?></td>
                <td nowrap><?= tgl_indo($data['tanggal'])?></td>
								<td><?= $data['nama_user']?></td>
 							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<table>
				<col span="5" style="width: 18%">
					<col style="width: 28%">
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="1">&nbsp;</td>
						<td colspan="2">Mengetahui</td>
						<td colspan="2">&nbsp;</td>
						<td><?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, <?= tgl_indo(date("Y m d"))?></td>
					</tr>
					<tr>
						<td colspan="1">&nbsp;</td>
						<td colspan="2"><?= $pamong_ketahui['jabatan']?> <?= $desa['nama_desa']?></td>
						<td colspan="2">&nbsp;</td>
						<td><?= $pamong_ttd['jabatan']?> <?= $desa['nama_desa']?></td>
					</tr>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr>
						<td colspan="1">&nbsp;</td>
						<td colspan="2">( <?= $pamong_ketahui['pamong_nama']?> )</td>
						<td colspan="2">&nbsp;</td>
						<td>( <?= $pamong_ttd['pamong_nama']?> )</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
