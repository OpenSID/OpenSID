<div class="table-responsive">
	<table class="table table-bordered dataTable table-hover">
		<thead class="bg-gray disabled color-palette">
			<tr>
				<th rowspan="2" width='5%' >No</th>
				<th rowspan="2" width='25%'>Rincian</th>
				<th rowspan="1" colspan="2">WNI</th>
				<th rowspan="1" colspan="2">WNA</th>
				<th rowspan="1" colspan="3" >Jumlah</th>
				<th rowspan="1" colspan="3" >Kepala Keluarga</th>
			</tr>
			<tr>
				<th>Lk</th>
				<th>Pr</th>
				<th>Lk</th>
				<th>Pr</th>
				<th>Lk</th>
				<th>Pr</th>
				<th>Jumlah</th>
				<th>Lk</th>
				<th> Pr</th>
				<th>Jumlah</th>
			</tr>
			<tr>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
				<th>5</th>
				<th>6</th>
				<th>7</th>
				<th>8</th>
				<th>9</th>
				<th>10</th>
				<th>11</th>
				<th>12</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>Awal bulan ini</td>
				<td><?= show_zero_as($penduduk_awal['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($penduduk_awal['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($penduduk_awal['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($penduduk_awal['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
				<td><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
				<td><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
				<td><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
			</tr>
			<tr>
				<td>2</td>
				<td>Kelahiran/Keluarga Baru bulan ini</td>
				<td><?= show_zero_as($kelahiran['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($kelahiran['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($kelahiran['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($kelahiran['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($kelahiran['WNI_L']+$kelahiran['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($kelahiran['WNI_P']+$kelahiran['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($kelahiran['WNI_L']+$kelahiran['WNA_L'])+($kelahiran['WNI_P']+$kelahiran['WNA_P']),'-')?></td>
				<td><?= show_zero_as($kelahiran['KK_L'],'-')?></td>
				<td><?= show_zero_as($kelahiran['KK_P'],'-')?></td>
				<td><?= show_zero_as($kelahiran['KK'],'-')?></td>
			</tr>
			<tr>
				<td>3</td>
				<td>Kematian bulan ini</td>
				<td><?= show_zero_as($kematian['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($kematian['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($kematian['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($kematian['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($kematian['WNI_L']+$kematian['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($kematian['WNI_P']+$kematian['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($kematian['WNI_L']+$kematian['WNA_L'])+($kematian['WNI_P']+$kematian['WNA_P']),'-')?></td>
				<td><?= show_zero_as($kematian['KK_L'],'-')?></td>
				<td><?= show_zero_as($kematian['KK_P'],'-')?></td>
				<td><?= show_zero_as($kematian['KK'],'-')?></td>
			</tr>
			<tr>
				<td>4</td>
				<td>Pendatang bulan ini</td>
				<td><?= show_zero_as($pendatang['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($pendatang['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($pendatang['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($pendatang['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($pendatang['WNI_L']+$pendatang['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($pendatang['WNI_P']+$pendatang['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($pendatang['WNI_L']+$pendatang['WNA_L'])+($pendatang['WNI_P']+$pendatang['WNA_P']),'-')?></td>
				<td><?= show_zero_as($pendatang['KK_L'],'-')?></td>
				<td><?= show_zero_as($pendatang['KK_P'],'-')?></td>
				<td><?= show_zero_as($pendatang['KK'],'-')?></td>
			</tr>
			<tr>
				<td>5</td>
				<td>Pindah/Keluarga Pergi bulan ini</td>
				<td><?= show_zero_as($pindah['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($pindah['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($pindah['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($pindah['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($pindah['WNI_L']+$pindah['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($pindah['WNI_P']+$pindah['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($pindah['WNI_L']+$pindah['WNA_L'])+($pindah['WNI_P']+$pindah['WNA_P']),'-')?></td>
				<td><?= show_zero_as($pindah['KK_L'],'-')?></td>
				<td><?= show_zero_as($pindah['KK_P'],'-')?></td>
				<td><?= show_zero_as($pindah['KK'],'-')?></td>
			</tr>
			<tr>
				<td>6</td>
				<td>Penduduk hilang bulan ini</td>
				<td><?= show_zero_as($hilang['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($hilang['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($hilang['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($hilang['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($hilang['WNI_L']+$hilang['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($hilang['WNI_P']+$hilang['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($hilang['WNI_L']+$hilang['WNA_L'])+($hilang['WNI_P']+$hilang['WNA_P']),'-')?></td>
				<td><?= show_zero_as($hilang['KK_L'],'-')?></td>
				<td><?= show_zero_as($hilang['KK_P'],'-')?></td>
				<td><?= show_zero_as($hilang['KK'],'-')?></td>
			</tr>
			<tr>
				<td>7</td>
				<td>Akhir bulan ini</td>
				<td><?= show_zero_as($penduduk_akhir['WNI_L'],'-') ?></td>
				<td><?= show_zero_as($penduduk_akhir['WNI_P'],'-') ?></td>
				<td><?= show_zero_as($penduduk_akhir['WNA_L'],'-') ?></td>
				<td><?= show_zero_as($penduduk_akhir['WNA_P'],'-') ?></td>
				<td><?= show_zero_as(($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L']),'-')?></td>
				<td><?= show_zero_as(($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P']),'-')?></td>
				<td><?= show_zero_as(($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L'])+($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P']),'-')?></td>
				<td><?= show_zero_as($penduduk_akhir['KK_L'],'-')?></td>
				<td><?= show_zero_as($penduduk_akhir['KK_P'],'-')?></td>
				<td><?= show_zero_as($penduduk_akhir['KK'],'-')?></td>
			</tr>
		</tbody>
	</table>
</div>