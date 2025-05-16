<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="col-default">
	<div class="bodypage bgwhite bordergrey1">
		<div class="center-head bggrad-color2 flexcenter">
			<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M21 10V9L15 3H5C3.89 3 3 3.89 3 5V19C3 20.11 3.9 21 5 21H11V19.13L19.39 10.74C19.83 10.3 20.39 10.06 21 10M14 4.5L19.5 10H14V4.5M22.85 14.19L21.87 15.17L19.83 13.13L20.81 12.15C21 11.95 21.33 11.95 21.53 12.15L22.85 13.47C23.05 13.67 23.05 14 22.85 14.19M19.13 13.83L21.17 15.87L15.04 22H13V19.96L19.13 13.83Z" /></svg></div>
			<h1>Suplemen</h1>
		</div>
		<div class="pagebox gridstyle artikelpage">
			<div class="statishead"><h1><?= $main['suplemen']['nama']; ?></h1></div>
			<table class="tablestatis" style="width:100%;">
				<tr>
					<td>Nama Data</td>
					<td style="width:10px;text-align:center;">:</td>
					<td><?= $main['suplemen']['nama']; ?></td>
				</tr>
				<tr>
					<td>Sasaran Terdata</td>
					<td style="width:10px;text-align:center;">:</td>
					<td><?= $sasaran[$main['suplemen']['sasaran']]; ?></td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td style="width:10px;text-align:center;">:</td>
					<td><?= $main['suplemen']['keterangan']; ?></td>
				</tr>
			</table>
			<div class="headborder bordergrey1 flexcenter" style="margin-top:20px;"><h2 class="bordercolor-1">Daftar Data <?= $main['suplemen']['nama']; ?></h2></div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered dataTable table-hover tablesmall-text" id="tabel-data">
					<thead class="bg-gray disabled color-palette">
						<tr>
							<th style="width:10%;text-align:center;vertical-align:top;">No</th>
							<th style="vertical-align:top;">PENDUDUK</th>
							<th style="vertical-align:top;text-align:center;">L/P</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main['terdata'] as $key => $data): ?>
							<tr>
								<td style="width:10%;text-align:center;vertical-align:top;"><?= ($key + 1); ?></td>
								<td style="vertical-align:top;">
								<b><?= $data['terdata_nama']; ?></b><br/>
								Lahir : <?= $data["tempat_lahir"]; ?><br/>
								Alamat : <?= $data["info"];?>
								</td>
								<td style="vertical-align:top;text-align:center;"><?= $data["sex"]; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>	
