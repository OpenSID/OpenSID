<div class="table-responsive">
	<table id="tabel-data" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
		<thead class="bg-gray color-palette">
			<tr>
				<th rowspan="3">No</th>
				<th rowspan="3"><?= ucwords(setting('sebutan_dusun')) ?></th>
				<th rowspan="3">Jumlah Penduduk</th>
				<th rowspan="3">Jumlah Sasaran</th>
				<th rowspan="1" colspan="5">Sasaran Vaksin</th>
				<th rowspan="1" colspan="4">Presentase</th>
			</tr>
			<tr>
				<th colspan="4">Sudah</th>
				<th colspan="1" rowspan="2">Belum</th>
				<th colspan="1" rowspan="2">% V1</th>
				<th colspan="1" rowspan="2">% V2</th>
				<th colspan="1" rowspan="2">% V3</th>
				<th colspan="1" rowspan="2">% Belum Vaksin</th>
			</tr>
			<tr>
				<td>v1</td>
				<td>v2</td>
				<td>v3</td>
				<td>Jumlah</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($main['detail'] as $key => $data) : ?>
				<?php
                    $sasaran_vaksin = $sasaran['detail'][$key];
                $i++;
                ?>
				<tr>
					<td class="padat"><?= $i ?></td>
					<td><?= $key ?></td>
					<td class="padat"><?= $data['vaksin_1'] + $data['belum'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_1'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_2'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_3'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_1'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['belum'] ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['vaksin_1'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['vaksin_2'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['vaksin_3'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['belum'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
				</tr>
			<?php endforeach ?>
			<tr class="text-bold" >
				<td colspan="2">JUMLAH</td>
				<td class="padat"><?= $main['total_v1'] + $main['total_belum'] ?></td>
				<td class="padat"><?= $sasaran['total_v1'] + $sasaran['total_belum'] ?></td>
				<td class="padat"><?= $sasaran['total_v1'] ?></td>
				<td class="padat"><?= $sasaran['total_v2'] ?></td>
				<td class="padat"><?= $sasaran['total_v3'] ?></td>
				<td class="padat"><?= $sasaran['total_v1'] ?></td>
				<td class="padat"><?= $sasaran['total_belum'] ?></td>
				<td class="padat"><?= persen($sasaran['total_v1'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
				<td class="padat"><?= persen($sasaran['total_v2'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
				<td class="padat"><?= persen($sasaran['total_v3'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
				<td class="padat"><?= persen($sasaran['total_belum'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
			</tr>
		</tbody>
	</table>
</div>