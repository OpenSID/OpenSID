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
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> DATA APARATUR PEMERINTAHAN DESSA </h3>
					<strong><?= $_SESSION['judul_statistik']; ?></strong>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th width="150">NIK</th>
                            <th width="150">NIP</th>
							<th width="200">Nama</th>
							<th width="200">Jabatan</th>
							<th width="100">Status</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
                                <td width="2"><?= $data['no']?></td>
								<td width="2"><?= $data['pamong_nik']?></td>
								<td class="textx"><?= $data['pamong_nip']?></td>
								<td><?= unpenetration($data['pamong_nama'])?></td>
								<td class="textx"><?= unpenetration($data['jabatan'])?></td>
								<td><?php if ($data['pamong_status'] == '1'): echo "Aktif" ?>
                                    <?php else: echo "Tidak Aktif" ?>
                                    <?php endif; ?></td>
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
