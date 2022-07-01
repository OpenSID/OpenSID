<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_page_area">
	<h2 class="post_titile" >Data Demografi Berdasar <?= $heading; ?></h2>
	<div class="box-body">
		<div class="table-responsive">
		<?php if(count($daftar_dusun) > 0) : ?>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th colspan="8">Wilayah / Ketua</th>
						<th class="center">KK</th>
						<th class="center">L+P</th>
						<th class="center">L</th>
						<th class="center">P</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($daftar_dusun as $key_dusun => $data_dusun): ?>
						<tr>
							<td align="center"><?= $key_dusun + 1; ?></td>
							<td align="left" colspan="8">
								<?= ucwords($this->setting->sebutan_dusun . ' ' . $data_dusun['dusun']); ?>
								<?php if ($data_dusun['nama_kadus']): ?>
									, Ketua <?= $data_dusun['nama_kadus']; ?>
								<?php endif ?>
							</td>
							<td align="right"><?= $data_dusun['jumlah_kk']; ?></td>
							<td align="right"><?= $data_dusun['jumlah_warga']; ?></td>
							<td align="right"><?= $data_dusun['jumlah_warga_l']; ?></td>
							<td align="right"><?= $data_dusun['jumlah_warga_p']; ?></td>
						</tr>

						<?php
							$no_rw = 1;
							foreach ($data_dusun['daftar_rw'] as $data_rw):
						?>
							<?php if ($data_rw['rw'] != '-'): ?>
								<tr>
									<td></td>
									<td align="center"><?= $no_rw++; ?></td>
									<td align="left" colspan="7">
										RW <?= $data_rw['rw']; ?>
										<?php if ($data_rw['nama_ketua']): ?>
											, Ketua <?= $data_rw['nama_ketua']; ?>
										<?php endif ?>
									</td>
									<td align="right"><?= $data_rw['jumlah_kk']; ?></td>
									<td align="right"><?= $data_rw['jumlah_warga']; ?></td>
									<td align="right"><?= $data_rw['jumlah_warga_l']; ?></td>
									<td align="right"><?= $data_rw['jumlah_warga_p']; ?></td>
								</tr>
							<?php endif ?>

							<?php
								$no_rt = 1;
								foreach ($data_rw['daftar_rt'] as $data_rt):
							?>
								<?php if ($data_rt['rt'] != '-'): ?>
									<tr>
										<td></td>
										<td></td>
										<td align="center"><?= $no_rt++; ?></td>
										<td align="left" colspan="6">
											RT <?= $data_rt['rt']; ?>
											<?php if ($data_rt['nama_ketua']): ?>
												, Ketua <?= $data_rt['nama_ketua']; ?>
											<?php endif ?>
										</td>
										<td align="right"><?= $data_rt['jumlah_kk']; ?></td>
										<td align="right"><?= $data_rt['jumlah_warga']; ?></td>
										<td align="right"><?= $data_rt['jumlah_warga_l']; ?></td>
										<td align="right"><?= $data_rt['jumlah_warga_p']; ?></td>
									</tr>
								<?php endif ?>
							<?php endforeach; ?>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr style="background-color:#e64946;font-weight:bold;">
						<td colspan="9" align="left"><label>TOTAL</label></td>
						<td align="right"><?= $total['total_kk']; ?></td>
						<td align="right"><?= $total['total_warga']; ?></td>
						<td align="right"><?= $total['total_warga_l']; ?></td>
						<td align="right"><?= $total['total_warga_p']; ?></td>
					</tr>
				</tfoot>
			</table>
			<?php else : ?>
				Belum ada data...
		<?php endif ?>
		</div>
	</div>
</div>