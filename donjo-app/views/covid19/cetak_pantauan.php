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
							<h3><u>Daftar Pemantauan Pemudik Saat Pandemi Covid-19</u></h3>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<strong>Sasaran: </strong>Penduduk<br>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
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
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>

