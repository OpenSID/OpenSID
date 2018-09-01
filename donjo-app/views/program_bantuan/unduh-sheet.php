<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename="."bantuan_".urlencode($peserta[0]["nama"]).".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Peserta Program <?= $peserta[0]["nama"];?></title>
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
		<!-- Print Body -->
		<div id="body">
			<div class="header">
				<label><?= get_identitas()?></label>
				<h3> Daftar Peserta Program <?= $peserta[0]["nama"];?></h3>
				<p>
					<strong>Sasaran Peserta: </strong><?= $sasaran[$peserta[0]["sasaran"]];?><br>
					<strong>Masa Berlaku: </strong><?= fTampilTgl($peserta[0]["sdate"],$peserta[0]["edate"]);?>
				</p>
				<div><?= $peserta[0]["ndesc"];?></div>
			</div>
			<div id="table">
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th rowspan="2">No</th>
							<th rowspan="2"><?= $peserta[0]["judul_peserta"]?></th>
							<th rowspan="2">No. Kartu Peserta</th>
							<th rowspan="2"><?= $peserta[0]["judul_peserta_info"]?></th>
							<th rowspan="2">Alamat</th>
							<th colspan="5" style="text-align: center;">Identitas di Kartu Peserta</th>
						</tr>
						<tr>
							<th>NIK</th>
							<th>Nama</th>
							<th>Tempat Lahir</th>
							<th>Tanggal Lahir</th>
							<th>Alamat</th>
						</tr>
					</thead>
					<tbody>
						<?php	$i=1; foreach ($peserta[1] as $key=>$item): ?>
								<tr>
									<td><?=$i?></td>
									<td class='textx'><?=$item["nik"]?></td>
									<td class='textx'><?=$item["no_id_kartu"]?></td>
									<td><?=$item["nama"]?></td>
									<td><?=$item["info"]?></td>
									<td class='textx'>"<?=$item["kartu_nik"]?></td>
									<td><?=$item["kartu_nama"]?></td>
									<td><?=$item["kartu_tempat_lahir"]?></td>
									<td class='textx'><?= tgl_indo_out($item["kartu_tanggal_lahir"])?></td>
									<td><?=$item["kartu_alamat"]?></td>
								</tr>
							<?php $i++; endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
