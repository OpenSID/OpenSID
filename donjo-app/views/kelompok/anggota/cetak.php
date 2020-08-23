<?php
	if ($aksi == 'unduh')
	{
		$tgl =  date('d_m_Y');
		$klp = underscore(strtolower($kelompok['nama']));

		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=laporan_data_kelompok_$klp_$tgl.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Laporan Data Kelompok <?= $kelompok['nama']; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
		<style type="text/css">
			.textx, td {
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
							<h4><u>Daftar Anggota Kelopok <?= ucwords($kelompok['nama']); ?></u></h4>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<strong>Nama Kelompok : </strong><?= $kelompok['nama']; ?><br>
							<strong>Ketua Kelompok : </strong><?= $kelompok['nama_ketua']; ?><br>
							<strong>Kategori Kelompok : </strong><?= $kelompok['kategori']; ?><br>
							<strong>Keterangan : </strong><?= $kelompok['keterangan'];?>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px 20px;">
							<table class="border thick">
								<thead>
									<tr class="border thick">
										<th>No.</th>
										<th>No. Anggota</th>
										<th>NIK</th>
										<th>Nama</th>
										<th>Tempat / Tanggal Lahir</th>
										<th>Umur (Tahun)</th>
										<th>Jenis Kelamin</th>
										<th>Alamat</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $key => $data): ?>
									<tr>
										<td align="center"><?= ($key + 1)?></td>
										<td class="textx" align="center"><?= $data['no_anggota']?></td>
										<td class="textx"><?= $data['nik']?></td>
										<td><?= $data['nama']?></td>
										<td><?= strtoupper($data['tempatlahir'] . ' / ' . tgl_indo($data['tanggallahir']))?></td>
										<td class="textx" align="center"><?= $data['umur']?></td>
										<td><?= $data['sex']?></td>
										<td><?= $data['alamat']?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>
