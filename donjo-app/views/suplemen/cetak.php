<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Terdata Suplemen <?= $suplemen["nama"];?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<style type="text/css">
			.textx
			{
				mso-number-format:"\@";
			}
		</style>
	</head>
	<body>
		<div id="body">
			<table>
				<tbody>
					<tr>
						<td align="center">
							<?php if ($aksi != 'unduh'): ?>
								<img src="<?= gambar_desa($config['logo']);?>" alt="" style="width:100px; height:auto">
							<?php endif; ?>
							<h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($config['nama_kabupaten'])?> </h1>
							<h1 style="text-transform: uppercase;"></h1>
							<h1><?= strtoupper($this->setting->sebutan_kecamatan)?> <?= strtoupper($config['nama_kecamatan'])?> </h1>
							<h1><?= strtoupper($this->setting->sebutan_desa)." ".strtoupper($config['nama_desa'])?></h1>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<hr style="border-bottom: 2px solid #000000; height:0px;">
						</td>
					</tr>
					<tr>
						<td align="center" >
							<h4><u>Daftar Terdata Suplemen <?= $suplemen["nama"];?></u></h4>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<strong>Sasaran Suplemen: </strong><?= $sasaran[$suplemen["sasaran"]];?><br>
							<strong>Keterangan : </strong><?= $suplemen["keterangan"];?>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<table class="border thick">
								<thead>
									<tr class="border thick">
										<th>No</th>
										<th><?= $suplemen["judul_terdata_nama"]?></th>
										<th><?= $suplemen["judul_terdata_info"]?></th>
										<th>Tempat Lahir</th>
										<th>Tanggal Lahir</th>
										<th>Jenis Kelamin</th>
										<th>Alamat</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php	$i=1;	foreach ($terdata as $key=>$item): ?>
									<tr>
										<td><?= $i?></td>
										<td class='text'><?= $item["terdata_nama"]?></td>
										<td><?= $item["terdata_info"]?></td>
										<td><?= $item["tempat_lahir"] ?></td>
										<td><?= $item["tanggal_lahir"] ?></td>
										<td><?= $item["sex"] ?></td>
										<td><?= $item["info"]?></td>
										<td><?= $item["keterangan"]?></td>
									</tr>
									<?php $i++;	endforeach;	?>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
