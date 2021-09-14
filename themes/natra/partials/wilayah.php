<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="single_page_area">
	<h2 class="post_titile" >Data Demografi Berdasar <?=$heading?></h2>
	<div class="box-body">
		<div class="table-responsive">
		<?php if(count($main) > 0) : ?>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th><?= ucwords($this->setting->sebutan_dusun)?></th>
						<th>RW</th>
						<th>RT</th>
						<th>Nama Kepala/Ketua</th>
						<th class="center">KK</th>
						<th class="center">L+P</th>
						<th class="center">L</th>
						<th class="center">P</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($main as $indeks => $data): ?>
						<tr>
							<td align="center"><?= $indeks + 1?></td>
							<td><?= ($main[$indeks - 1]['dusun'] == $data['dusun']) ? '' : strtoupper($data['dusun'])?></td>
							<td><?= ($main[$indeks - 1]['rw'] == $data['rw']) ? '' : $data['rw']?></td>
							<td><?= $data['rt']?></td>
							<td><?= $data['nama_kepala']?></td>
							<td align="right"><?= $data['jumlah_kk']?></td>
							<td align="right"><?= $data['jumlah_warga']?></td>
							<td align="right"><?= $data['jumlah_warga_l']?></td>
							<td align="right"><?= $data['jumlah_warga_p']?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr style="background-color:#e64946;font-weight:bold;">
						<td colspan="5" align="left"><label>TOTAL</label></td>
						<td align="right"><?= $total['total_kk']?></td>
						<td align="right"><?= $total['total_warga']?></td>
						<td align="right"><?= $total['total_warga_l']?></td>
						<td align="right"><?= $total['total_warga_p']?></td>
					</tr>
				</tfoot>
			</table>
			<?php else : ?>
				Belum ada data...
		<?php endif ?>
		</div>
	</div>
</div>