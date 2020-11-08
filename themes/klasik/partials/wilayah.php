<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Data Demografi Berdasar <?= $heading ?></h3>
	</div>
	<div class="box-body">
		<?php if(count($main) > 0) : ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama <?= ucwords($this->setting->sebutan_dusun); ?></th>
						<th>Nama Kepala <?= ucwords($this->setting->sebutan_dusun); ?></th>
						<th>Jumlah RW</th>
						<th>Jumlah RT</th>
						<th>Jumlah KK</th>
						<th>Jiwa</th>
						<th>Lk</th>
						<th>Pr</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($main as $data) : ?>
						<tr>
							<td><?= $data['no'] ?></td>
							<td><?= strtoupper($data['dusun']) ?></td>
							<td><?= strtoupper($data['nama_kadus']) ?></td>
							<td class="angka"><?= $data['jumlah_rw'] ?></td>
							<td class="angka"><?= $data['jumlah_rt'] ?></td>
							<td class="angka"><?= $data['jumlah_kk'] ?></td>
							<td class="angka"><?= $data['jumlah_warga'] ?></td>
							<td class="angka"><?= $data['jumlah_warga_l'] ?></td>
							<td class="angka"><?= $data['jumlah_warga_p'] ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3">TOTAL</td>
						<td class="angka"><?= $total['total_rw'] ?></td>
						<td class="angka"><?= $total['total_rt'] ?></td>
						<td class="angka"><?= $total['total_kk'] ?></td>
						<td class="angka"><?= $total['total_warga'] ?></td>
						<td class="angka"><?= $total['total_warga_l'] ?></td>
						<td class="angka"><?= $total['total_warga_p'] ?></td>
					</tr>
				</tfoot>
			</table>
			<?php else : ?>
				Belum ada data...
		<?php endif ?>
	</div>
</div>