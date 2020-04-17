<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename="."pantau_pemudik_covid19".".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Pemudik Saat Pandemi Covid-19</title>

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
				<h3> Daftar Pemantauan Pemudik Saat Pandemi Covid-19</h3>
				<p>
					<strong>Sasaran: </strong>Penduduk<br>
				</p>
			</div>
			<div id="table">
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>Data H+</th>
							<th>Tanggal Tiba</th>
							<th>Tanggal Jam</th>
							<th>NIK</th>
							<th>Nama</th>
							<th>Usia</th>
							<th>Jenis Kelamin</th>
							<th>Suhu</th>
							<th>Batuk</th>
							<th>Flu</th>
							<th>Sesak</th>
							<th>Keluhan</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php	$i=1;	foreach ($query_array as $key=>$item): ?>
							<tr>
								<td><?= $i?></td>
								<td><?= "H+".$item["date_diff"] ?></td>
								<td><?= $item["tanggal_datang"] ?></td>
								<td><?= $item["tanggal_jam"]?></td>
								<td><?= $item["nik"] ?></td>
								<td class='textx'><?= $item["nama"]?></td>
								<td><?= $item["umur"]?></td>
								<td><?= ($item["sex"]==='1' ? 'Lk' : 'Pr'); ?></td>
								<td><?= $item["suhu_tubuh"];?></td>
								<td><?= ($item["batuk"]==='1' ? 'Ya' : 'Tidak');?></td>
								<td><?= ($item["flu"]==='1' ? 'Ya' : 'Tidak');?></td>
								<td><?= ($item["sesak_nafas"]==='1' ? 'Ya' : 'Tidak');?></td>
								<td><?= $item["keluhan_lain"];?></td>
								<td><?= $item["status_covid"];?></td>
							</tr>
						<?php $i++;	endforeach;	?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>


