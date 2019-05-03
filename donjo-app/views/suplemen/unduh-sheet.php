<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename="."suplemen_".urlencode($suplemen["nama"]).".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
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
		<!-- Print Body -->
		<div id="body">
			<div class="header">
				<label><?= get_identitas()?></label>
				<h3> Daftar Terdata Suplemen <?= $suplemen["nama"];?></h3>
				<p>
					<strong>Sasaran Suplemen: </strong><?= $sasaran[$suplemen["sasaran"]];?><br>
				</p>
				<div><?= $suplemen["keterangan"];?></div>
			</div>
			<div id="table">
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
								<td class='textx'><?= $item["terdata_nama"]?></td>
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
			</div>
		</div>
	</body>
</html>
