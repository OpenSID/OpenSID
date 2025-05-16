<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="col-default">
	<div class="bodypage bgwhite bordergrey1">
		<div class="center-head bggrad-color2 flexcenter">
			<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M5 6C3.9 6 3 6.9 3 8S3.9 10 5 10 7 9.11 7 8 6.11 6 5 6M12 4C10.9 4 10 4.89 10 6S10.9 8 12 8 14 7.11 14 6 13.11 4 12 4M19 2C17.9 2 17 2.9 17 4S17.9 6 19 6 21 5.11 21 4 20.11 2 19 2M3.5 11C2.67 11 2 11.67 2 12.5V17H3V22H7V17H8V12.5C8 11.67 7.33 11 6.5 11H3.5M10.5 9C9.67 9 9 9.67 9 10.5V15H10V20H14V15H15V10.5C15 9.67 14.33 9 13.5 9H10.5M17.5 7C16.67 7 16 7.67 16 8.5V13H17V18H21V13H22V8.5C22 7.67 21.33 7 20.5 7H17.5Z" /></svg></div>
			<h1>Lembaga / Kelompok</h1>
		</div>
		<div class="pagebox gridstyle artikelpage">
			<div class="statishead">
				<h1><?= $detail['nama']; ?></h1>
			</div>
			<table class="tablestatis" style="width:100%;">
				<tr>
					<td style="text-align:center;"><?= $detail['keterangan']?></td>
				</tr>
			</table>
			<div class="headborder bordergrey1 flexcenter" style="margin-top:20px;"><h2 class="bordercolor-1">Daftar Pengurus</h2></div>
			<div class="table-responsive">
				<table class="table tablesmall-text">
					<thead>
						<tr>
							<th style="width:10%;text-align:center;">No</th>
							<th>NAMA</th>
							<th>JABATAN</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pengurus as $key => $data): ?>
						<tr>
							<td style="width:10%;text-align:center;"><?= $key + 1?></td>
							<td>
							<b><?= $data['nama']?></b>
							<br/>
							Alamat : <?= $data['alamat']?>
							</td>
							<td><?= $data['jabatan']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="headborder bordergrey1 flexcenter" style="margin-top:20px;"><h2 class="bordercolor-1">Daftar Anggota</h2></div>
			<div class="table-responsive">
				<table class="table tablesmall-text" id="tabel-data">
					<thead>
						<tr>
							<th style="width:10%;text-align:center;">No</th>
							<th>NAMA ANGGOTA</th>
							<th>L/P</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($anggota as $key => $data): ?>
						<tr>
							<td style="width:10%;text-align:center;"><?= $key + 1?></td>
							<td>
							No Anggota : <?= $data['no_anggota'] ?:'-'; ?><br/>
							<b><?= $data['nama']; ?></b><br/>
							Alamat : <?= $data['alamat']; ?>
							</td>
							<td>
							<?= $data['sex']; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>	
