<table>
	<tbody>
		<tr>
			<td align="center">
				<?php if ($aksi != 'unduh'): ?>
					<img src="<?= gambar_desa($config['logo']);?>" alt="" style="width:100px; height:auto">
				<?php endif; ?>
				<h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten); ?> <?= strtoupper($config['nama_kabupaten']); ?> </h1>
				<h1 style="text-transform: uppercase;"></h1>
				<h1><?= strtoupper($this->setting->sebutan_kecamatan); ?> <?= strtoupper($config['nama_kecamatan']); ?> </h1>
				<h1><?= strtoupper($this->setting->sebutan_desa)." ".strtoupper($config['nama_desa']); ?></h1>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<hr style="border-bottom: 2px solid #000000; height:0px;">
			</td>
		</tr>
			<td align="center" >
				<h4><u>DATA KELOMPOK</u></h4>
			</td>
		</tr>
		<tr></tr>
		<tr>
			<td style="padding: 5px 20px;">
				<table border=1 class="border thick">
					<thead>
						<tr class="border thick">
							<th>NO</th>
							<th>NAMA</th>
							<th>NAMA KETUA</th>
							<th>KATEGORI KELOMPOK</th>
							<th>JUMLAH ANGGOTA</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td><?= $data['no']; ?></td>
								<td><?= $data['nama']; ?></td>
								<td><?= $data['ketua']; ?></td>
								<td><?= $data['master']; ?></td>
								<td><?= $data['jml_anggota']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>