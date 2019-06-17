<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="stat">
	<h2 class="judul-artikel">Daftar Calon Pemilih Berdasarkan Wilayah (pada tgl pemilihan <?= $tanggal_pemilihan ?>)</h2>
	<div class="mt-4">
		<div class="table-responsive">
		<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Dusun</th>
				<th>RW</th>
				<th>Jiwa</th>
				<th>Lk</th>
				<th>Pr</th>
			</tr>
		</thead>
		<tbody>
		<?php $i=0; ?>
		<?php foreach($main as $data): ?>
			<tr>
				<td class="text-center"><?= $data['no'] ?></td>
				<td><?= strtoupper($data['dusun']) ?></td>
				<td><?= strtoupper($data['rw']) ?></td>
				<td><?= $data['jumlah_warga'] ?></td>
				<td><?= $data['jumlah_warga_l'] ?></td>
				<td><?= $data['jumlah_warga_p'] ?></td>
			</tr>
			<?php $i = $i+$data['jumlah']; ?>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
			
		</tfoot>
		</table>
		</div>
	</div>
</div>