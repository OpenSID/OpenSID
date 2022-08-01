<div class="table-responsive">
	<table id="tabel-data" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
		<thead class="bg-gray color-palette">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">No KK</th>
				<th rowspan="2">Nama</th>
				<th rowspan="2">Nik</th>
				<th rowspan="2">Alamat Dusun</th>
				<th rowspan="2">Jenis Kelamin</th>
				<th rowspan="2">Tempat Lahir</th>
				<th rowspan="2">Tanggal Lahir</th>
				<th rowspan="2">Umur</th>
				<th colspan="6">Vaksin</th>
			</tr>
			<tr>
				<td>I</td>
				<td>II</td>
				<td>III</td>
				<td>Belum</td>
				<td>Tunda</td>
				<td>Ket</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($main as $key => $data) : ?>
				<tr>
					<td class="padat"><?= $key + 1 ?></td>
					<td><?= $data->no_kk ?></td>
					<td><?= $data->nama ?></td>
					<td><?= $data->nik ?></td>
					<td><?= $data->dusun ?></td>
					<td><?= $data->jenis_kelamin ?></td>
					<td><?= $data->tempatlahir ?></td>
					<td><?= rev_tgl($data->tanggallahir) ?></td>
					<td class="padat"><?= $data->umur ?></td>
					<td class="padat">
						<?php if ($data->vaksin_1 == 1 && $data->tunda == 0) : ?>
							<i class="fa fa-check" aria-hidden="true"></i>
						<?php endif ?>
					</td>
					<td class="padat">
						<?php if ($data->vaksin_2 == 1 && $data->tunda == 0) : ?>
							<i class="fa fa-check" aria-hidden="true"></i>
						<?php endif ?>
					</td>
					<td class="padat">
						<?php if ($data->vaksin_3 == 1 && $data->tunda == 0) : ?>
							<i class="fa fa-check" aria-hidden="true"></i>
						<?php endif ?>
					</td>
					<td class="padat">
						<?php if ($data->vaksin_1 == null || ($data->tunda == 0 && $data->vaksin_1 == 0)) : ?>
							<i class="fa fa-check" aria-hidden="true"></i>
						<?php endif ?>
					</td>
					<td class="padat">
						<?php if ($data->tunda == 1) : ?>
							<i class="fa fa-check" aria-hidden="true"></i>
						<?php endif ?>
					</td>
					<td>
						<?php if ($data->tunda == 1) : ?>
							<?= $data->keterangan ?>
						<?php endif ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>