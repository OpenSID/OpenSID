<?php
	if ($aksi == 'unduh')
	{
		$file = namafile("Laporan Hasil Analisis ".$judul['asubjek']);

		header("Content-type: application/xls");
		header("Content-Disposition: attachment; filename=".$file.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Analisis</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
			<table>
				<tbody>
					<tr>
						<td colspan='6'>
							<?php if ($aksi != 'unduh'): ?>
								<img class="logo" src="<?= gambar_desa($config['logo']);?>" alt="logo-desa">
							<?php endif; ?>
							<h1 class="judul">
								PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten'] . ' <br>' . $this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan'] . ' <br>' . $this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
							<h1>
						</td>
					</tr>
					<tr>
						<td colspan='6'><hr class="garis"></td>
					</tr>
					<tr>
						<td colspan='6' class="text-center">
							<h4><u>Laporan Hasil Analisis <?= $judul['asubjek'] ?></u></h4>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th width="10">NO</th>
							<th align="left"><?= strtoupper($judul['nomor']) ?></th>
							<?php if($analisis_master['subjek_tipe'] != 4): ?>
								<th align="left"><?= strtoupper($judul['nomor_kk']) ?></th>
							<?php endif;?>
							<th align="left"><?= strtoupper($judul['nama']) ?></th>
							<th align="left">L/P</th>
							<th align="left">ALAMAT</th>
							<th align="left">NILAI</th>
							<th align="left">KLASIFIKASI</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($main as $data): ?>
							<tr>
								<td align="center" width="2"><?= $data['no'] ?></td>
								<td class="textx" ><?= $data['uid'] ?></td>
								<?php if($analisis_master['subjek_tipe'] != 4): ?>
									<td class="textx"><?= $data['kk'] ?></td>
								<?php endif;?>
								<td><?= $data['nama'] ?></td>
								<td align="center"><?= $data['jk'] ?></td>
								<td><?= strtoupper($data['alamat'] . " "  .  "RT/RW ". $data['rt']."/".$data['rw'] . " - " . $this->setting->sebutan_dusun . " " . $data['dusun']) ?></td>
								<td align="right"><?= $data['nilai'] ?></td>
								<td align="right"><?= $data['klasifikasi'] ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<br />
			<table>
				<tr>
					<td width="10%">&nbsp;</td>
					<td width="20%">
						Mengetahui
						<br><?= $pamong_ketahui['jabatan'] . ' ' . $config['nama_desa']?>
						<br><br><br><br>
						<br><u>( <?= $pamong_ketahui['pamong_nama']?> )</u>
						<br><?= $this->setting->sebutan_nip_desa  ?>/NIP : <?= $pamong_ketahui['pamong_nip']?>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td width="20%" nowrap>
						<?= ucwords($this->setting->sebutan_desa) . ' ' . $config['nama_desa']?>, <?= tgl_indo(date("Y m d"))?>
						<br><?= $pamong_ttd['jabatan'] . ' ' . $config['nama_desa']?>
						<br><br><br><br>
						<br><u>( <?= $pamong_ttd['pamong_nama']?> )</u>
						<br><?= $this->setting->sebutan_nip_desa  ?>/NIP : <?= $pamong_ketahui['pamong_nip']?>
					</td>
					<td width="10%">&nbsp;</td>
				</tr>
			</table>
		</div>
	</body>
</html>