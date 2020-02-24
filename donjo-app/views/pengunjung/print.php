<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Laporan Statistik</title>
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
							<td align="center" >
								<?php if ($aksi != 'unduh'): ?>
									<img src="<?= LogoDesa($config['logo']);?>" alt="" style="width:100px; height:auto">
								<?php endif; ?>
								<h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($config['nama_kabupaten'])?> </h1>
								<h1 style="text-transform: uppercase;"></h1>
								<h1><?= strtoupper($this->setting->sebutan_kecamatan)?> <?= strtoupper($config['nama_kecamatan'])?> </h1>
								<h1><?= strtoupper($this->setting->sebutan_desa)." ".strtoupper($config['nama_desa'])?></h1>
								<h1>LAPORAN DATA STATISTIK PENGUNJUNG WEBSITE <?= $_SESSION['judul'];?></h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 5px 20px;">
								<table class="border thick data">
									<thead>
										<tr class="thick">
											<th class="thick">No</th>
											<th class="thick"><?= $_SESSION['lblx']?></th>
											<th class="thick">Pengunjung (Orang)</th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1; $total = 0; foreach ($main as $data):
											$total = $total + $data['Total'];
										?>
										<tr>
											<td class="thick" align="center" width="2"><?= $no++;?></td>
											<td class="thick" align="center">
											<?php
												if($_SESSION['lblx']=='Bulan'){
													echo getBulan($data['tgl']);
												}else{
													echo tgl_indo2($data['tgl']);
												}
											?>
											</td>
											<td class="thick" align="center"><?= $data['Total'];?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
								<br>
								<table class="noborder">
									<tbody>
										<tr>
											<td class="top">
												<div class="nowrap">
													<label>Laporan data statistik pengunjung website <?= strtolower($_SESSION['judul'])?> pada tanggal</label>
													<label>:</label>
													<strong><?= tgl_indo(date("Y m d"))?></strong>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
								<table class="noborder">
									<tbody>
										<tr>
											<td class="top" align="center" width="30%">
												<div class="nowrap"><label></label></div>
												<div class="nowrap"><label><br></label></div>
												<div style="height: 70px;"></div>
												<div class="nowrap"><strong style="text-transform: uppercase;"></strong></div>
												<div class="nowrap"><label></label>	</div>
											</td>
											<td class="top" align="center" width="40%">
												<div class="nowrap"><label>&nbsp;</label></div>
												<div class="nowrap"><label><br></label></div>
											</td>
											<td class="top" align="center" width="30%">
												<div class="nowrap"><label>&nbsp;</label></div>
												<div class="nowrap"><label><?= ucwords($this->setting->sebutan_desa)?> <?= $config['nama_desa']?>,
													<?= tgl_indo(date("Y m d"))?><br>KEPALA DESA/LURAH <?= (strtoupper($config['nama_desa']))?><br>
												</div>
												<div style="height: 50px;"></div>
												<div class="nowrap"><strong style="text-transform: uppercase;"></strong></div>
												<div class="nowrap">( <?= $pamong_ttd['pamong_nama']?> )<br>NIP <?= $pamong_ttd['pamong_niap_nip']?></div>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
