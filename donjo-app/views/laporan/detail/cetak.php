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
			<td class="text-center">
				<h4><u> <?= strtoupper($main['title']) ?> </u></h4>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No</th>
							<th>Nama</th>
							<th>NIK</th>
							<th>Tempat Lahir</th>
							<th>Tanggal Lahir</th>
							<th>Nama Ayah</th>
							<th>Nama Ibu</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main['main'] as $key => $data): ?>
							<tr>
								<td><?= ($key + $paging->offset + 1); ?></td>
								<td><?= $data['nama'] ?></td>
								<td><?= $sensor_nik ? sensor_nik_kk($data['nik']) : $data['nik']?></td>
								<td><?= $data['tempatlahir'] ?></td>
								<td><?= $data['tanggallahir'] ?></td>
								<td><?= $data['nama_ayah'] ?></td>
								<td><?= $data['nama_ibu'] ?></td>
							</tr>
						<?php endforeach; ?>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</table>
