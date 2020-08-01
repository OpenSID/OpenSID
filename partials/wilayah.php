<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="single_page_area">
	<div class="single_page_area">
		<h2 class="post_titile" >Data Demografi Berdasar <?=$heading?></h2>
	</div>
	<div class="table-responsive">
		<?php if(count($main) > 0):?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Dusun</th>
						<th>Nama Kepala Dusun</th>
						<th>Jumlah RT</th>
						<th>Jumlah KK</th>
						<th>Jiwa</th>
						<th>L</th>
						<th>P</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($main as $data):?>
						<tr>
							<tr>
							<td><?= $data['no'] ?></td>
							<td><?= strtoupper($data['dusun']) ?></td>
							<td><?= strtoupper($data['nama_kadus']) ?></td>
							<td class="angka"><?= $data['jumlah_rt'] ?></td>
							<td class="angka"><?= $data['jumlah_kk'] ?></td>
							<td class="angka"><?= $data['jumlah_warga'] ?></td>
							<td class="angka"><?= $data['jumlah_warga_l'] ?></td>
							<td class="angka"><?= $data['jumlah_warga_p'] ?></td>
						</tr>
						</tr>
					<?php endforeach;?>
				</tbody>
				<tfooter>
					<tr>
						<td class="text-center" colspan="3">TOTAL</td>
						<td class="angka"><?= $total['total_rt'] ?></td>
						<td class="angka"><?= $total['total_kk'] ?></td>
						<td class="angka"><?= $total['total_warga'] ?></td>
						<td class="angka"><?= $total['total_warga_l'] ?></td>
						<td class="angka"><?= $total['total_warga_p'] ?></td>
					</tr>
				</tfooter>
			</table>
		<?php else:?>
			<div>Belum ada data</div>
		<?php endif;?>
	</div>
</div>
