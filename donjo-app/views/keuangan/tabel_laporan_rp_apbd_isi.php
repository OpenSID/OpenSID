<style>
	table.blueTable {
		border: 1px solid #1C6EA4;
		background-color: #EEEEEE;
		width: 100%;
		text-align: left;
		border-collapse: collapse;
	}
	table.blueTable td, table.blueTable th {
		border: 1px solid #AAAAAA;
		padding: 3px 2px;
	}
	table.blueTable tbody td {
		font-size: 13px;
	}
	table.blueTable tr:nth-child(even) {
		background: #D0E4F5;
	}
	table.blueTable thead {
		background: #1C6EA4;
		background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
		background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
		background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
		border-bottom: 2px solid #444444;
	}
	table.blueTable thead th {
		font-size: 15px;
		font-weight: bold;
		color: #FFFFFF;
		text-align: center;
		border-left: 2px solid #D0E4F5;
	}
	table.blueTable thead th:first-child {
		border-left: none;
	}
	.bold{
		font-weight: bold;
	}
	.highlighted{
		background-color: #FFFF00 !important;
	}
</style>
<div class="table-responsive">
	<table class="table blueTable" width='100%'>
		<thead>
			<tr>
				<th colspan='4'>Uraian</th>
				<th>Anggaran      (Rp)</th>
				<th>Realisasi     (Rp)</th>
				<th>Lebih/(Kurang)(Rp)</th>
				<th>Persentase    (%)</th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ($pendapatan as $l): ?>
				<tr class='bold'>
					<td colspan='4'><?= $l['Akun'] . ' ' . $l['Nama_Akun']?></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
				</tr>
				<?php foreach ($l['sub_pendapatan'] as $s): ?>
					<?php if (! empty($s['anggaran'][0]['pagu']) || ! empty($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi'])): ?>
					<tr class='bold'>
						<td><?= $s['Kelompok']?></td>
						<td colspan='3'><?= $s['Nama_Kelompok'] ?></td>
						<td align='right'><?= number_format($s['anggaran'][0]['pagu'])?></td>
						<td align='right'><?= number_format($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi'])?></td>
						<td align='right'><?= number_format($s['anggaran'][0]['pagu'] - ($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi'])) ?></td>
						<td align='right'><?= $s['anggaran'][0]['pagu'] != 0 ? number_format(($s['realisasi'][0]['realisasi'] + $s['realisasi_bunga'][0]['realisasi'] + $s['realisasi_jurnal'][0]['realisasi']) / $s['anggaran'][0]['pagu'] * 100, 2) : 0 ?></td>
					</tr>
					<?php endif; ?>
					<?php foreach ($s['sub_pendapatan2'] as $q): ?>
						<?php if (! empty($q['anggaran'][0]['pagu']) || ! empty($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi'])): ?>
							<tr>
								<td></td>
								<td colspan='2'><?= $q['Jenis'] ?></td>
								<td><?= $q['Nama_Jenis'] ?></td>
								<td align='right'><?= number_format($q['anggaran'][0]['pagu']) ?></td>
								<td align='right'><?= number_format($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi']) ?></td>
								<td align='right'><?= number_format($q['anggaran'][0]['pagu'] - ($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi']))?></td>
								<td align='right'><?= $q['anggaran'][0]['pagu'] != 0 ? number_format(($q['realisasi'][0]['realisasi'] + $q['realisasi_bunga'][0]['realisasi'] + $q['realisasi_jurnal'][0]['realisasi']) / $q['anggaran'][0]['pagu'] * 100, 2) : 0?></td>
							</tr>
						<?php endif; ?>
					<?php endforeach ?>
				<?php endforeach ?>
				<tr class='bold highlighted'>
					<td colspan='4' align='center'>JUMLAH PENDAPATAN</td>
					<td align='right'><?= number_format($l['anggaran'][0]['pagu'])?></td>
					<td align='right'><?= number_format($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi'])?></td>
					<td align='right'><?= number_format(($l['anggaran'][0]['pagu']) - ($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']))?></td>
					<td align='right'><?= number_format(($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) / ($l['anggaran'][0]['pagu']) * 100, 2)?> </td>
				</tr>
			<?php endforeach ?>

			<?php foreach ($belanja as $b): ?>
				<tr class='bold'>
					<td colspan='4'><?= $b['Akun'] . ' ' . $b['Nama_Akun']?></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
				</tr>

				<?php if ($jenis != 'bidang'): ?>
					<!-- Belanja per kelompok -->
					<?php foreach ($b['sub_belanja'] as $b1): ?>
						<?php if (! empty($b1['anggaran'][0]['pagu']) || ! empty($b1['realisasi'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])): ?>
						<tr class='bold'>
							<td><?= $b1['Kelompok']?></td>
							<td colspan='3'><?= $b1['Nama_Kelompok'] ?></td>
							<td align='right'><?= number_format($b1['anggaran'][0]['pagu'])?></td>
							<td align='right'><?= number_format(($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi']) + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])?></td>
							<td align='right'><?= number_format(($b1['anggaran'][0]['pagu']) - (($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi']) + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])) ?></td>
							<td align='right'><?= $b1['anggaran'][0]['pagu'] != 0 ? number_format((($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi']) + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']) / $b1['anggaran'][0]['pagu'] * 100, 2) : 0 ?></td>
						</tr>
						<?php endif; ?>
						<?php foreach ($b1['sub_belanja2'] as $b2): ?>
							<?php if (! empty($b2['anggaran'][0]['pagu']) || ! empty($b2['realisasi'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi'])): ?>
								<tr>
									<td></td>
									<td colspan='2'><?= $b2['Jenis'] ?></td>
									<td><?= $b2['Nama_Jenis'] ?></td>
									<td align='right'><?= number_format($b2['anggaran'][0]['pagu']) ?></td>
									<td align='right'><?= number_format(($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi']) + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) ?></td>
									<td align='right'><?= number_format(($b2['anggaran'][0]['pagu']) - (($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi']) + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']))?></td>
									<td align='right'><?= $b2['anggaran'][0]['pagu'] != 0 ? number_format((($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi']) + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) / $b2['anggaran'][0]['pagu'] * 100, 2) : 0 ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach ?>
					<?php endforeach ?>
				<?php else: ?>
					<?php foreach ($belanja_bidang as $b1): ?>
						<?php if (! empty($b1['anggaran'][0]['pagu']) || ! empty($b1['realisasi'][0]['realisasi'] + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'])): ?>
						<tr class='bold'>
							<td><?= substr($b1['Kd_Bid'], 8) ?></td>
							<td colspan='3'><?= $b1['Nama_Bidang'] ?></td>
							<td align='right'><?= number_format($b1['anggaran'][0]['pagu'])?></td>
							<td align='right'><?= number_format(($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi']) + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])?></td>
							<td align='right'><?= number_format(($b1['anggaran'][0]['pagu']) - (($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi']) + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi'])) ?></td>
							<td align='right'><?= $b1['anggaran'][0]['pagu'] != 0 ? number_format((($b1['realisasi'][0]['realisasi'] - $b1['realisasi_um'][0]['realisasi']) + $b1['realisasi_spj'][0]['realisasi'] + $b1['realisasi_bunga'][0]['realisasi'] + $b1['realisasi_jurnal'][0]['realisasi']) / $b1['anggaran'][0]['pagu'] * 100, 2) : 0 ?></td>
						</tr>
						<?php endif; ?>
						<?php foreach ($b1['sub_belanja'] as $b2): ?>
							<?php if (! empty($b2['anggaran'][0]['pagu']) || ! empty($b2['realisasi'][0]['realisasi'] + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi'])): ?>
								<tr>
									<td></td>
									<td colspan='2'><?= substr($b2['Kd_Keg'], 8) ?></td>
									<td><?= $b2['Nama_Kegiatan'] ?></td>
									<td align='right'><?= number_format($b2['anggaran'][0]['pagu']) ?></td>
									<td align='right'><?= number_format(($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi']) + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) ?></td>
									<td align='right'><?= number_format(($b2['anggaran'][0]['pagu']) - (($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi']) + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']))?></td>
									<td align='right'><?= $b2['anggaran'][0]['pagu'] != 0 ? number_format((($b2['realisasi'][0]['realisasi'] - $b2['realisasi_um'][0]['realisasi']) + $b2['realisasi_spj'][0]['realisasi'] + $b2['realisasi_bunga'][0]['realisasi'] + $b2['realisasi_jurnal'][0]['realisasi']) / $b2['anggaran'][0]['pagu'] * 100, 2) : 0 ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach ?>
					<?php endforeach ?>

				<?php endif; ?>


				<tr class='bold highlighted'>
					<td colspan='4' align='center'>JUMLAH BELANJA</td>
					<td align='right'><?= number_format($b['anggaran'][0]['pagu'])?></td>
					<td align='right'><?= number_format(($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])?></td>
					<td align='right'><?= number_format(($b['anggaran'][0]['pagu']) - (($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']))?></td>
					<td align='right'><?= number_format((($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']) / ($b['anggaran'][0]['pagu']) * 100, 2)?> </td>
				</tr>
			<?php endforeach ?>

			<tr class='bold highlighted'>
				<td colspan='4' align='center'>SURPLUS / (DEFISIT)</td>
				<td align='right'><?= number_format(($l['anggaran'][0]['pagu']) - ($b['anggaran'][0]['pagu'])) ?></td>
				<td align='right'><?= number_format(($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) - (($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])) ?></td>
				<td align='right'><?= number_format((($l['anggaran'][0]['pagu']) - ($b['anggaran'][0]['pagu'])) - (($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) - (($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])))?></td>
				<td align='right'><?= number_format(
    (($l['anggaran'][0]['pagu']) - ($b['anggaran'][0]['pagu'])) / (($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) - ($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']) * 100,
    2
)?></td>
			</tr>
			<?php foreach ($pembiayaan as $p): ?>
				<tr class='bold'>
					<td colspan='4'><?= $p['Akun'] . ' ' . $p['Nama_Akun']?></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
					<td align='right'></td>
				</tr>

				<?php foreach ($p['sub_pembiayaan'] as $p1): ?>
					<?php if (! empty($p1['anggaran'][0]['pagu']) || ! empty($p1['realisasi'][0]['realisasi'])): ?>
					<tr class='bold'>
						<td><?= $p1['Kelompok']?></td>
						<td colspan='3'><?= $p1['Nama_Kelompok'] ?></td>
						<td align='right'><?= number_format($p1['anggaran'][0]['pagu'])?></td>
						<td align='right'><?= number_format($p1['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?= number_format(($p1['anggaran'][0]['pagu']) - ($p1['realisasi'][0]['realisasi']))?></td>
						<td align='right'></td>
					</tr>
					<?php endif; ?>
					<?php foreach ($p1['sub_pembiayaan2'] as $p2): ?>
						<?php if (! empty($p2['anggaran'][0]['pagu']) || ! empty($p2['realisasi'][0]['realisasi'])): ?>
							<tr>
								<td></td>
								<td colspan='2'><?= $p2['Jenis'] ?></td>
								<td><?= $p2['Nama_Jenis'] ?></td>
								<td align='right'><?= number_format($p2['anggaran'][0]['pagu'])?></td>
								<td align='right'><?= number_format($p2['realisasi'][0]['realisasi'])?></td>
								<td align='right'><?= number_format(($p2['anggaran'][0]['pagu']) - ($p2['realisasi'][0]['realisasi']))?></td>
								<td align='right'></td>
							</tr>
						<?php endif; ?>
					<?php endforeach ?>
				<?php endforeach ?>
			<?php endforeach ?>

			<?php foreach ($pembiayaan_keluar as $pk): ?>
				<?php foreach ($pk['sub_pembiayaan_keluar'] as $pk1): ?>
					<?php if (! empty($pk1['anggaran'][0]['pagu']) || ! empty($pk1['realisasi'][0]['realisasi'])): ?>
					<tr class='bold'>
						<td><?= $pk1['Kelompok']?></td>
						<td colspan='3'><?= $pk1['Nama_Kelompok'] ?></td>
						<td align='right'><?= number_format($pk1['anggaran'][0]['pagu'])?></td>
						<td align='right'><?= number_format($pk1['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?= number_format(($pk1['anggaran'][0]['pagu']) - ($pk1['realisasi'][0]['realisasi']))?></td>
						<td align='right'></td>
					</tr>
					<?php endif; ?>

					<?php foreach ($pk1['sub_pembiayaan_keluar2'] as $pk2): ?>
						<?php if (! empty($pk2['anggaran'][0]['pagu']) || ! empty($pk2['realisasi'][0]['realisasi'])): ?>
							<tr>
								<td></td>
								<td colspan='2'><?= $pk2['Jenis'] ?></td>
								<td><?= $pk2['Nama_Jenis'] ?></td>
								<td align='right'><?= number_format($pk2['anggaran'][0]['pagu'])?></td>
								<td align='right'><?= number_format($pk2['realisasi'][0]['realisasi'])?></td>
								<td align='right'><?= number_format(($pk2['anggaran'][0]['pagu']) - ($pk2['realisasi'][0]['realisasi']))?></td>
								<td align='right'></td>
							</tr>
						<?php endif; ?>
					<?php endforeach ?>
				<?php endforeach ?>
			<?php endforeach ?>

			<tr class='bold highlighted'>
				<td colspan='4' align='center'>PEMBIAYAAN NETTO</td>
				<td align='right'><?= number_format(($p1['anggaran'][0]['pagu']) - ($pk1['anggaran'][0]['pagu']))?></td>
				<td align='right'><?= number_format(($p1['realisasi'][0]['realisasi']) - ($pk1['realisasi'][0]['realisasi']))?></td>
				<td align='right'><?= number_format(($p1['anggaran'][0]['pagu']) - ($pk1['anggaran'][0]['pagu']) - (($p1['realisasi'][0]['realisasi']) - ($pk1['realisasi'][0]['realisasi'])))?></td>
				<td align='right'></td>
			</tr>

			<tr class='bold highlighted'>
				<td colspan='4' align='center'>SILPA/SiLPA TAHUN BERJALAN</td>
				<td align='right'><?= number_format((($l['anggaran'][0]['pagu']) - ($b['anggaran'][0]['pagu'])) + (($p1['anggaran'][0]['pagu']) - ($pk1['anggaran'][0]['pagu']))) ?></td>
				<td align='right'><?= number_format((($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) - (($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi'])) + (($p1['realisasi'][0]['realisasi']) - ($pk1['realisasi'][0]['realisasi']))) ?></td>
				<td align='right'><?= number_format(((($l['anggaran'][0]['pagu']) - ($b['anggaran'][0]['pagu'])) - (($l['realisasi'][0]['realisasi'] + $l['realisasi_bunga'][0]['realisasi'] + $l['realisasi_jurnal'][0]['realisasi']) - (($b['realisasi'][0]['realisasi'] - $b['realisasi_um'][0]['realisasi']) + $b['realisasi_spj'][0]['realisasi'] + $b['realisasi_bunga'][0]['realisasi'] + $b['realisasi_jurnal'][0]['realisasi']))) + (($p1['anggaran'][0]['pagu']) - ($pk1['anggaran'][0]['pagu']) - (($p1['realisasi'][0]['realisasi']) - ($pk1['realisasi'][0]['realisasi']))))?></td>
				<td align='right'></td>
			</tr>

		</tbody>
	</table>
</div>
