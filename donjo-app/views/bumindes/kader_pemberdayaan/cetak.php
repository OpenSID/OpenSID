<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>KADER PEMBERDAYAAN MASYARAKAT</title>
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
		
			<table>
				<tbody>
				<tr>
			<td>
				<?php if ($aksi != 'unduh'): ?>
					<img class="logo" src="<?= gambar_desa($config['logo']); ?>" alt="logo-desa">
				<?php endif; ?>
				<h1 class="judul"> 
					PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten'] . ' <br>' . $this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan'] . ' <br>' . $this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
				</h1>
			</td>
		</tr>
		<tr>
			<td><hr class="garis"></td>
		</tr>
					<tr>
						<td align="center" >
							<h3>BUKU KADER PEMBERDAYAAN MASYARAKAT<br>TAHUN <?php echo date('Y'); ?></h3>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<table class="border thick">
								<thead>
									<tr class="border thick">
										<th>No. Urut</th>
										<th>Nama</th>
										<th>Umur</th>
										<th>Jenis Kelamin</th>
										<th>Pendidikan / Kursus</th>
										<th>Bidang</th>
										<th>Alamat</th>
										<th>Keterangan</th>
										</tr>
										<tr class="border thick">
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>5</th>
							<th>6</th>
							<th>7</th>
							<th>8</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1; foreach ($pembangunan as $item): ?>
										<tr>
										<td align="center"><?= $i?></td>
											<td class="textx"><?= $item["nama"]?></td>
											<td align="center"><?= $item["umur"]?></td>
											<td align="center"><?= $item["jeniskelamin"] ?></td>
											<td><?= $item["pendidikankursus"] ?></td>
											<td><?= $item["pendidikanahli"] ?></td>
											<td><?= $item["alamat_sekarang"]?></td>
											<td><?= $item["keterangan"]?></td>
											
										</tr>
											
									<?php $i++;	endforeach;	?>
								</tbody>
							</table>
							
						</td>
					</tr>
				</tbody>
			</table>
	

