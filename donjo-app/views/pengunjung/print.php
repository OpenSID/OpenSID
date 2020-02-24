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
								<h1>LAPORAN DATA STATISTIK PENGUNJUNG WEBSITE <?= $main['judul'];?></h1>
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
										<?php $no = 1; $total = 0; foreach ($main['pengunjung'] as $data):
											$total = $total + $data['Total'];
										?>
										<tr>
											<td class="thick" align="center" width="2"><?= $no++;?></td>
											<td class="thick" align="center">
											<?php if($main['lblx']=='Bulan'):
													  echo getBulan($data['tgl'])." ".date('Y');
												  else :
													  echo tgl_indo2($data['tgl']);
												  endif;
											?>
											</td>
											<td class="thick" align="center"><?= $data['Total'];?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
									<tfoot class="bg-gray disabled color-palette">
										<tr>
											<th colspan="2" class="text-center">Total</th>
											<th class="text-center"><?= $total?></th>
										</tr>
									</tfoot>
								</table>								
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
