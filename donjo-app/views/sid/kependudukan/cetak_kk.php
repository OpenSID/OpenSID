		<!-- Print Body -->
		<div id="body">
			<div align="center">
				<h3>KARTU KELUARGA</h3>
				<h4>SALINAN</h4>
				<h5>No. <?= get_nokk($kepala_kk['no_kk'])?> </h4>
			</div>
			<br>
			<table width="100%" cellpadding="3" cellspacing="4">
				<tr>
					<td width="100">Nama KK</td>
					<td width="600">: <?= strtoupper($kepala_kk['nama']) ?></td>
					<td width="160"><?= ucwords($this->setting->sebutan_kecamatan)?></td>
					<td width="150">: <?= strtoupper($desa['nama_kecamatan']) ?></td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td>: <?= strtoupper($kepala_kk['alamat_plus_dusun']) ?> </td>
					<td>Kabupaten/Kota</td>
					<td>: <?= $desa['nama_kabupaten'] ?></td>
				</tr>
				<tr>
					<td>RT / RW</td>
					<td>: <?= $kepala_kk['rt']  ?> / <?= $kepala_kk['rw']  ?></td>
					<td>Kode Pos</td>
					<td>: <?= strtoupper($desa['kode_pos']) ?></td>
				</tr>
				<tr>
					<td>Kelurahan/<?= ucwords($this->setting->sebutan_desa)?></td>
					<td>: <?= strtoupper($desa['nama_desa']) ?></td>
					<td>Provinsi</td>
					<td>: <?= strtoupper($desa['nama_propinsi']) ?></td>
				</tr>
			</table>

			<br>

			<table class="border thick ">
				<thead>
				<tr class="border thick">
					<th class="text-center" width="7">No</th>
					<th class="text-center" width='180'>Nama</th>
					<th class="text-center" width='100'>NIK</th>
					<th class="text-center" width='100'>Jenis Kelamin</th>
					<th class="text-center" width='100'>Tempat Lahir</th>
					<th class="text-center" width='120'>Tanggal Lahir</th>
					<th class="text-center" width='100'>Agama</th>
					<th class="text-center" width='100'>Pendidikan</th>
					<th class="text-center" width='100'>Pekerjaan</th>
					<th class="text-center" width='70'>Golongan darah</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($main as $key => $data): ?>
					<tr class="data">
						<td class="text-center" width="2"><?= $key + 1?></td>
						<td><?= strtoupper($data['nama'])?></td>
						<td><?= $data['nik']?></td>
						<td><?= $data['sex']?></td>
						<td><?= $data['tempatlahir']?></td>
						<td><?= tgl_indo_out($data['tanggallahir'])?></td>
						<td><?= $data['agama']?></td>
						<td><?= $data['pendidikan']?></td>
						<td><?= $data['pekerjaan']?></td>
						<td align="center"><?= $data['golongan_darah']?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<br>

			<table class="border thick ">
				<thead>
					<tr class="border thick">
						<th class="text-center" width="7">No</th>
						<th class="text-center" width='150'>Status Perkawinan</th>
						<th class="text-center" width='150'>Tanggal Perkawinan</th>
						<th class="text-center" width="130">Tanggal Perceraian</th>
						<th class="text-center" width='240'>Status Hubungan dalam Keluarga</th>
						<th class="text-center" width='140'>Kewarganegaraan</th>
						<th class="text-center" width='100'>No. Paspor</th>
						<th class="text-center" width='100'>No. KITAS / KITAP</th>
						<th class="text-center" width='170'>Nama Ayah</th>
						<th class="text-center" width='170'>Nama Ibu</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($main as $key => $data): ?>
						<tr class="data">
							<td class="text-center" width="2"><?= $key + 1?></td>
							<td><?= $data['status_kawin']?></td>
							<td class="text-center"><?= tgl_indo_out($data['tanggalperkawinan'])?></td>
							<td class="text-center"><?= tgl_indo_out($data['tanggalperceraian'])?></td>
							<td><?= $data['hubungan']?></td>
							<td><?= $data['warganegara']?></td>
							<td><?= $data['dokumen_pasport']?></td>
							<td><?= $data['dokumen_kitas']?></td>
							<td><?= strtoupper($data['nama_ayah'])?></td>
							<td><?= strtoupper($data['nama_ibu'])?></td>
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
					<td width="25%" align="center">KEPALA KELUARGA</td>
					<td width="50%"></td>
					<td align="center" width="150"><?= strtoupper($this->setting->sebutan_kepala_desa . ' ' . $desa['nama_desa']) ?></td>
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
