<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<h2 class="judul-artikel">Demografi Berdasar <?= $heading ?></h2>

<div class="stat">
	<div class="table-responsive mt-4">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<th class="align-middle">No</th>
				<th class="align-middle">Nama Dusun</th>
				<th class="align-middle">Nama Kepala Dusun</th>
				<th class="align-middle">Jumlah RT</th>
				<th class="align-middle">Jumlah KK</th>
				<th class="align-middle">Jiwa</th>
				<th class="align-middle">Lk</th>
				<th class="align-middle">Pr</th>
			</tr>
			</thead>
			<?php if(count($main) > 0) : ?>
				<tbody>

				<?php foreach($main as $data) : ?>
					<tr>
						<td><?= $data['no'] ?></td>
						<td><?= strtoupper(unpenetration(ununderscore($data['dusun']))) ?></td>
						<td><?= strtoupper(unpenetration($data['nama_kadus'])) ?></td>
						<td class="text-right"><?= $data['jumlah_rt'] ?></td>
						<td class="text-right"><?= $data['jumlah_kk'] ?></td>
						<td class="text-right"><?= $data['jumlah_warga'] ?></td>
						<td class="text-right"><?= $data['jumlah_warga_l'] ?></td>
						<td class="text-right"><?= $data['jumlah_warga_p'] ?></td>
					</tr>
				<?php endforeach ?>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="3" class="text-right">TOTAL</th>
					<th class="text-right"><?= $total['total_rt'] ?></th>
					<th class="text-right"><?= $total['total_kk'] ?></th>
					<th class="text-right"><?= $total['total_warga'] ?></th>
					<th class="text-right"><?= $total['total_warga_l'] ?></th>
					<th class="text-right"><?= $total['total_warga_p'] ?></th>
				</tr>
				</tfoot>
			<?php else : ?>
				<tr><td colspan="6" class='text-center'>Daftar masih kosong</td></tr>
			<?php endif; ?>
		</table>
	</div>
	<br>
</div>

