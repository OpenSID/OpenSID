<style type="text/css">
	.italic {font-style: italic;}
</style>
<div class="table-responsive">
	<table id="tfhover" class="table table-bordered table-hover tftable lap-bulanan">
		<thead class="bg-gray">
			<tr>
				<th rowspan="3" width='2%' class="text-center">No</th>
				<th rowspan="3" colspan="2" width='30%' class="text-center">Perincian</th>
				<th colspan="7" width='45%' class="text-center">Penduduk</th>
				<th colspan="3" rowspan="2" width='23%'class="text-center">Keluarga (KK)</th>
			</tr>
			<tr>
				<th colspan="2" class="text-center">WNI</th>
				<th colspan="2" class="text-center">WNA</th>
				<th colspan="3" class="text-center">Jumlah</th>
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
				<th class="text-center italic">1</th>
				<th class="text-center italic" colspan="2">2</th>
				<th class="text-center italic">3</th>
				<th class="text-center italic">4</th>
				<th class="text-center italic">5</th>
				<th class="text-center italic">6</th>
				<th class="text-center italic">7</th>
				<th class="text-center italic">8</th>
				<th class="text-center italic">9</th>
				<th class="text-center italic">10</th>
				<th class="text-center italic">11</th>
				<th class="text-center italic">12</th>
			</tr
		</thead>
		<tbody>
			<tr>
				<td class="no_urut">1</td>
				<td colspan="2">Penduduk/Keluarga awal bulan ini</td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($penduduk_awal['WNI_L']+$penduduk_awal['WNA_L'])+($penduduk_awal['WNI_P']+$penduduk_awal['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_awal['KK'],'-')?></td>
			</tr>
			<tr>
				<td class="no_urut">2</td>
				<td colspan="2">Kelahiran/Keluarga baru bulan ini</td>
				<td class="bilangan"><?= show_zero_as($kelahiran['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($kelahiran['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($kelahiran['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($kelahiran['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($kelahiran['WNI_L']+$kelahiran['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($kelahiran['WNI_P']+$kelahiran['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($kelahiran['WNI_L']+$kelahiran['WNA_L'])+($kelahiran['WNI_P']+$kelahiran['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($kelahiran['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($kelahiran['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($kelahiran['KK'],'-')?></td>
			</tr>
			<tr>
				<td class="no_urut">3</td>
				<td colspan="2">Kematian bulan ini</td>
				<td class="bilangan"><?= show_zero_as($kematian['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($kematian['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($kematian['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($kematian['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($kematian['WNI_L']+$kematian['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($kematian['WNI_P']+$kematian['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($kematian['WNI_L']+$kematian['WNA_L'])+($kematian['WNI_P']+$kematian['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($kematian['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($kematian['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($kematian['KK'],'-')?></td>
			</tr>
			<tr>
				<td class="no_urut">4</td>
				<td colspan="2">Pendatang bulan ini</td>
				<td class="bilangan"><?= show_zero_as($pendatang['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($pendatang['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($pendatang['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($pendatang['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($pendatang['WNI_L']+$pendatang['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($pendatang['WNI_P']+$pendatang['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($pendatang['WNI_L']+$pendatang['WNA_L'])+($pendatang['WNI_P']+$pendatang['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($pendatang['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($pendatang['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($pendatang['KK'],'-')?></td>
			</tr>
			<tr>
				<td class="no_urut">5</td>
				<td colspan="2">Pindah/Keluarga pergi bulan ini</td>
				<td class="bilangan"><?= show_zero_as($pindah['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($pindah['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($pindah['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($pindah['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($pindah['WNI_L']+$pindah['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($pindah['WNI_P']+$pindah['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($pindah['WNI_L']+$pindah['WNA_L'])+($pindah['WNI_P']+$pindah['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($pindah['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($pindah['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($pindah['KK'],'-')?></td>
			</tr>
			<tr>
				<td class="no_urut">6</td>
				<td colspan="2">Penduduk hilang bulan ini</td>
				<td class="bilangan"><?= show_zero_as($hilang['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($hilang['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($hilang['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($hilang['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($hilang['WNI_L']+$hilang['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($hilang['WNI_P']+$hilang['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($hilang['WNI_L']+$hilang['WNA_L'])+($hilang['WNI_P']+$hilang['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($hilang['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($hilang['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($hilang['KK'],'-')?></td>
			</tr>
			<tr>
				<td class="no_urut">7</td>
				<td colspan="2">Penduduk/Keluarga akhir bulan ini</td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['WNI_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['WNI_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['WNA_L'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['WNA_P'],'-') ?></td>
				<td class="bilangan"><?= show_zero_as(($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as(($penduduk_akhir['WNI_L']+$penduduk_akhir['WNA_L'])+($penduduk_akhir['WNI_P']+$penduduk_akhir['WNA_P']),'-')?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['KK_L'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['KK_P'],'-')?></td>
				<td class="bilangan"><?= show_zero_as($penduduk_akhir['KK'],'-')?></td>
			</tr>
		</tbody>
	</table>
</div>