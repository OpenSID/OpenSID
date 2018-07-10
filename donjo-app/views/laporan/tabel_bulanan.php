<div class="table-responsive">
	<table id="tfhover" class="table table-bordered dataTable table-hover tftable lap-bulanan">
		<thead class="bg-gray disabled color-palette">
			<tr>
				<th rowspan="2" width='2%' class="text-center">No</th>
				<th rowspan="2" width='30%' class="text-center">Rincian</th>
				<th rowspan="1" colspan="2" class="text-center">WNI</th>
				<th rowspan="1" colspan="2" class="text-center">WNA</th>
				<th rowspan="1" colspan="3" class="text-center">Jumlah</th>
				<th rowspan="1" colspan="3" class="text-center">Kepala Keluarga</th>
			</tr>
			<tr>
				<th class="text-center">L</th>
				<th class="text-center">P</th>
				<th class="text-center">L</th>
				<th class="text-center">P</th>
				<th class="text-center">L</th>
				<th class="text-center">P</th>
				<th class="text-center">L+P</th>
				<th class="text-center">L</th>
				<th class="text-center">P</th>
				<th class="text-center">L+P</th>
			</tr>
			<tr>
				<th class="text-center">1</th>
				<th class="text-center">2</th>
				<th class="text-center">3</th>
				<th class="text-center">4</th>
				<th class="text-center">5</th>
				<th class="text-center">6</th>
				<th class="text-center">7</th>
				<th class="text-center">8</th>
				<th class="text-center">9</th>
				<th class="text-center">10</th>
				<th class="text-center">11</th>
				<th class="text-center">12</th>
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