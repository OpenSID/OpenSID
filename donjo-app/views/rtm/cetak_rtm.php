<?php $this->load->view('print/headjs.php'); ?>
	<body>
		<div id="container">
			<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
			<!-- Print Body -->
			<div id="body">
				<div align="center">
					<h3>KARTU RUMAH TANGGA</h3>
					<h4>SALINAN</h4>
					<h5>No. <?= $kepala_kk['no_kk']?> </h5>
				</div>
				<br>
				<table width="100%" cellpadding="3" cellspacing="4">
					<tr>
						<td width="100">Nama KK</td>
						<td width="600">: <?= strtoupper($kepala_kk['nama']) ?></td>
						<td width="160">Kecamatan</td>
						<td width="150">: <?= strtoupper($desa['nama_kecamatan']) ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>: <?= strtoupper($kepala_kk['dusun']) ?> </td>
						<td>Kabupaten/Kota</td>
						<td>: <?= $desa['nama_kabupaten'] ?></td>
					</tr>
					<tr>
						<td>RT / RW</td>
						<td>: <?= $kepala_kk['rt'] ?> / <?= $kepala_kk['rw'] ?></td>
						<td>Kode Pos</td>
						<td>: <?= strtoupper($desa['kode_pos']) ?></td>
					</tr>
					<tr>
						<td>Kelurahan/Desa</td>
						<td>: <?= strtoupper($desa['nama_desa']) ?></td>
						<td>Provinsi</td>
						<td>: <?= strtoupper($desa['nama_propinsi']) ?></td>
					</tr>
				</table>
				<br>
				<table class="border thick ">
					<thead>
						<tr class="border thick">
							<th width="7">No</th>
							<th width='180'>Nama</th>
							<th width='100'>NIK</th>
							<th width='100'>NOMOR KK</th>
							<th width='100'>Jenis Kelamin</th>
							<th width='100'>Tempat Lahir</th>
							<th width='120'>Tanggal Lahir</th>
							<th width='100'>Agama</th>
							<th width='100'>Pendidikan</th>
							<th width='100'>Pekerjaan</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
						<tr class="data">
							<td align="center" width="2"><?= $data['no']?></td>
							<td><?= strtoupper($data['nama'])?></td>
							<td><?= $data['nik']?></td>
							<td><?= $data['no_kk']?></td>
							<td><?= $data['sex']?></td>
							<td><?= $data['tempatlahir']?></td>
							<td><?= $data['tanggallahir']?></td>
							<td><?= $data['agama']?></td>
							<td><?= $data['pendidikan']?></td>
							<td><?= $data['pekerjaan']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<br>
				<table class="border thick ">
					<thead>
						<tr class="border thick">
							<th width="7">No</th>
							<th width='150'>Status Perkawinan</th>
							<th width='240'>Status Hubungan dalam Keluarga</th>
							<th width='140'>Kewarganegaraan</th>
							<th width='170'>Nama Ayah</th>
							<th width='170'>Nama Ibu</th>
							<th width='70'>Golongan darah</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr class="data">
								<td align="center" width="2"><?= $data['no']?></td>
								<td><?= $data['status_kawin']?></td>
								<td><?= $data['hubungan']?></td>
								<td><?= $data['warganegara']?></td>
								<td><?= strtoupper($data['nama_ayah'])?></td>
								<td><?= strtoupper($data['nama_ibu'])?></td>
								<td align="center"><?= $data['golongan_darah']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<br>
				<table width="100%" cellpadding="3" cellspacing="4">
					<tr>
						<td width="25%"></td>
						<td width="50%"></td>
						<td width="25%" align="center"><?= $desa['nama_desa'] ?>, <?= tgl_indo(date('Y m d'))?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="25%" align="center">KEPALA RUMAH TANGGA</td>
						<td width="50%"></td>
						<td align="center" width="150">KEPALA DESA <?= strtoupper($desa['nama_desa']) ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td width="25%" align="center"><?= strtoupper($kepala_kk['nama'])?></td>
						<td width="50%"></td>
						<td width="25%" align="center" width="150"><?= strtoupper($desa['nama_kepala_desa']) ?></td>
					</tr>
				</table>
			</div>
 			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date('Y m d'))?>
		</div>
		<div id="aside"></div>
	</div>
	</body>
</html>