<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2 class="content__heading">Demografi Berdasar <?= $heading ?></h2>
<hr class="--mt-2 --mb-2">
<section class="content__article">
	<div class="table">
		<table>
			<thead>
			<tr>
				<th>No</th>
				<th>Nama Dusun</th>
				<th>Nama Kepala Dusun</th>
				<th>Jumlah RT</th>
				<th>Jumlah KK</th>
				<th>Jiwa</th>
				<th>Lk</th>
				<th>Pr</th>
			</tr>
			</thead>
			<?php if(count($main) > 0) : ?>
				<tbody>

				<?php foreach($main as $data) : ?>
					<tr>
						<td class="--text-center"><?= $data['no'] ?></td>
						<td><?= strtoupper(unpenetration(ununderscore($data['dusun']))) ?></td>
						<td class="--text-center"><?= strtoupper(unpenetration($data['nama_kadus'])) ?></td>
						<td class="--text-center"><?= $data['jumlah_rt'] ?></td>
						<td class="--text-center"><?= $data['jumlah_kk'] ?></td>
						<td class="--text-center"><?= $data['jumlah_warga'] ?></td>
						<td class="--text-center"><?= $data['jumlah_warga_l'] ?></td>
						<td class="--text-center"><?= $data['jumlah_warga_p'] ?></td>
					</tr>
				<?php endforeach ?>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="3">TOTAL</th>
					<th><?= $total['total_rt'] ?></th>
					<th><?= $total['total_kk'] ?></th>
					<th><?= $total['total_warga'] ?></th>
					<th><?= $total['total_warga_l'] ?></th>
					<th><?= $total['total_warga_p'] ?></th>
				</tr>
				</tfoot>
			<?php else : ?>
				<tr><td colspan="6" class='text-center'>Daftar masih kosong</td></tr>
			<?php endif; ?>
		</table>
	</div>
</section>