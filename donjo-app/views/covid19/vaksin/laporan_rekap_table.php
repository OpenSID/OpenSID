<div class="table-responsive">
	<table id="tabel-data" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
		<thead class="bg-gray color-palette">
			<tr>
				<th rowspan="3">No</th>
				<th rowspan="3">Desa</th>
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
			<?php
                $i            = 0;
                $tot_penduduk = 0;
                $tot_sasaran  = 0;
                $tot_vaksin_1 = 0;
                $tot_vaksin_2 = 0;
                $tot_vaksin_3 = 0;
                $tot_belum    = 0;
            ?>
			<?php foreach ($main as $key => $data) : ?>
				<?php
                    // TODO : Perhitungan jangan simpan disini
                    $sasaran_vaksin   = $sasaran[$key];
                    $sasaran_vaksin_1 = count($sasaran_vaksin['vaksin_1']);
                    $sasaran_vaksin_2 = count($sasaran_vaksin['vaksin_2']);
                    $sasaran_vaksin_3 = count($sasaran_vaksin['vaksin_3']);
                    $sasaran_belum    = count($sasaran_vaksin['belum']);
                    $sasaran_total    = $sasaran_vaksin_1 + $sasaran_vaksin_2 + $sasaran_vaksin_3 + $sasaran_belum;
                    $sasaran_sudah    = $sasaran_vaksin_1 + $sasaran_vaksin_2 + $sasaran_vaksin_3;
                    $tot_pend_dusun   = count($data['vaksin_1']) + count($data['vaksin_2']) + count($data['vaksin_3']) + count($data['belum']);
                    $tot_penduduk     = $tot_penduduk + $tot_pend_dusun;
                    $tot_sasaran      = $tot_sasaran + $sasaran_total;
                    $tot_vaksin_1     = $tot_vaksin_1 + $sasaran_vaksin_1;
                    $tot_vaksin_2     = $tot_vaksin_2 + $sasaran_vaksin_2;
                    $tot_vaksin_3     = $tot_vaksin_3 + $sasaran_vaksin_3;
                    $tot_belum        = $tot_belum + $sasaran_belum;
                    $i++;
                ?>
				<tr>
					<td class="padat"><?= $i ?></td>
					<td><?= $key ?></td>
					<td class="padat"><?= count($data['vaksin_1']) + count($data['vaksin_2']) + count($data['vaksin_3']) + count($data['belum']) ?></td>
					<td class="padat"><?= $sasaran_total ?></td>
					<td class="padat"><?= $sasaran_vaksin_1 ?></td>
					<td class="padat"><?= $sasaran_vaksin_2 ?></td>
					<td class="padat"><?= $sasaran_vaksin_3 ?></td>
					<td class="padat"><?= $sasaran_sudah ?></td>
					<td class="padat"><?= $sasaran_belum ?></td>
					<td class="padat"><?= persen($sasaran_vaksin_1 / $sasaran_total, '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin_2 / $sasaran_total, '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin_3 / $sasaran_total, '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_belum / $sasaran_total, '', 2); ?></td>
				</tr>
			<?php endforeach ?>
			<tr class="text-bold" >
				<td colspan="2">JUMLAH</td>
				<td class="padat"><?= $tot_penduduk ?></td>
				<td class="padat"><?= $tot_sasaran ?></td>
				<td class="padat"><?= $tot_vaksin_1 ?></td>
				<td class="padat"><?= $tot_vaksin_2 ?></td>
				<td class="padat"><?= $tot_vaksin_3 ?></td>
				<td class="padat"><?= $tot_vaksin_1 + $tot_vaksin_2 + $tot_vaksin_3?></td>
				<td class="padat"><?=  $tot_belum ?></td>
				<td class="padat"><?=  persen($tot_vaksin_1 / $tot_sasaran, '', 2); ?></td>
				<td class="padat"><?=  persen($tot_vaksin_2 / $tot_sasaran, '', 2); ?></td>
				<td class="padat"><?=  persen($tot_vaksin_3 / $tot_sasaran, '', 2); ?></td>
				<td class="padat"><?=  persen($tot_belum / $tot_sasaran, '', 2); ?></td>
			</tr>
		</tbody>
	</table>
</div>