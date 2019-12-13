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
		background-color: #FFFF00
	}
</style>

<table class='blueTable' width='100%'>
	<thead>
		<tr>
			<th colspan='4'>Uraian</th>
			<th>Anggaran (Rp)</th>
			<th>Realisasi (Rp)</th>
			<th>Lebih/(Kurang) (Rp)</th>
			<th>Persentase (%)</th>
		</tr>
	</thead>

	<?php foreach ($pendapatan as $l): ?>
		<tr class='bold highlighted'>
			<td colspan='4'><?= $l['Akun'] ." ". $l['Nama_Akun']?></td>
			<td align='right'><?= number_format($l['total_anggaran'][0]['pagu']/2)?></td>
			<td align='right'><?= number_format($l['total_realisasi'][0]['realisasi'])?></td>
			<td align='right'><?= number_format(($l['total_anggaran'][0]['pagu']/2) - $l['total_realisasi'][0]['realisasi'])?></td>
			<td align='right'><?= number_format($l['total_realisasi'][0]['realisasi']/($l['total_anggaran'][0]['pagu']/2)*100, 2)?> </td>
			<?php foreach ($l['sub_pendapatan'] as $s): ?>
				<tr class='bold'>
					<td><?= $s['Kelompok']?></td>
					<td colspan='3'><?= $s['Nama_Kelompok'] ?></td>
					<td align='right'><?= number_format($s['anggaran'][0]['pagu'])?></td>
					<td align='right'><?= number_format($s['realisasi'][0]['realisasi'])?></td>
					<td align='right'><?= number_format($s['anggaran'][0]['pagu']-$s['realisasi'][0]['realisasi']) ?></td>
					<td align='right'><?= number_format($s['realisasi'][0]['realisasi']/$s['anggaran'][0]['pagu']*100, 2) ?></td>
				</tr>
				<?php foreach ($s['sub_pendapatan2'] as $q): ?>
					<tr>
						<td></td>
						<td colspan='2'><?= $q['Jenis'] ?></td>
						<td><?= $q['Nama_Jenis'] ?></td>
						<td align='right'><?= number_format($q['anggaran'][0]['pagu']) ?></td>
						<td align='right'><?= number_format($q['realisasi'][0]['realisasi']) ?></td>
						<td align='right'><?= number_format($q['anggaran'][0]['pagu']-$q['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?= number_format($q['realisasi'][0]['realisasi']/$q['anggaran'][0]['pagu']*100, 2)?></td>
					</tr>
				<?php endforeach ?>
			<?php endforeach ?>
		</tr>
		<?php endforeach ?>

		<?php foreach ($belanja as $b): ?>
		  <tr class='bold highlighted'>
		    <td colspan='4'><?= $b['Akun'] ." ". $b['Nama_Akun']?></td>
		    <td align='right'><?= number_format($b['total_anggaran'][0]['pagu']/2)?></td>
		    <td align='right'><?= number_format($b['total_realisasi'][0]['realisasi'])?></td>
		    <td align='right'><?= number_format(($b['total_anggaran'][0]['pagu']/2) - $b['total_realisasi'][0]['realisasi'])?></td>
		    <td align='right'><?= number_format($b['total_realisasi'][0]['realisasi']/($b['total_anggaran'][0]['pagu']/2)*100, 2)?> </td>
		    <?php foreach ($b['sub_belanja'] as $b1): ?>
		      <tr class='bold'>
		        <td><?= $b1['Kelompok']?></td>
		        <td colspan='3'><?= $b1['Nama_Kelompok'] ?></td>
		        <td align='right'><?= number_format($b1['anggaran'][0]['pagu'])?></td>
		        <td align='right'><?= number_format($b1['realisasi'][0]['realisasi'])?></td>
		        <td align='right'><?= number_format($b1['anggaran'][0]['pagu']-$b1['realisasi'][0]['realisasi']) ?></td>
		        <td align='right'><?= number_format($b1['realisasi'][0]['realisasi']/$b1['anggaran'][0]['pagu']*100, 2) ?></td>
		      </tr>
		      <?php foreach ($b1['sub_belanja2'] as $b2): ?>
		        <tr>
		          <td></td>
		          <td colspan='2'><?= $b2['Jenis'] ?></td>
		          <td><?= $b2['Nama_Jenis'] ?></td>
		          <td align='right'><?= number_format($b2['anggaran'][0]['pagu']) ?></td>
		          <td align='right'><?= number_format($b2['realisasi'][0]['realisasi']) ?></td>
		          <td align='right'><?= number_format($b2['anggaran'][0]['pagu']-$b2['realisasi'][0]['realisasi'])?></td>
		          <td align='right'><?= number_format($b2['realisasi'][0]['realisasi']/$b2['anggaran'][0]['pagu']*100, 2)?></td>
		        </tr>
		      <?php endforeach ?>
		    <?php endforeach ?>
		  </tr>
		  <?php endforeach ?>

	<tr class='bold highlighted'>
		<td colspan='4' align='center'>TOTAL</td>
		<td align='right'><?= number_format(($l['total_anggaran'][0]['pagu']/2)-($b['total_anggaran'][0]['pagu']/2)) ?></td>
		<td align='right'><?= number_format($l['total_realisasi'][0]['realisasi']-$b['total_realisasi'][0]['realisasi']) ?></td>
		<td align='right'><?= number_format((($l['total_anggaran'][0]['pagu']/2)-($b['total_anggaran'][0]['pagu']/2))-($l['total_realisasi'][0]['realisasi']-$b['total_realisasi'][0]['realisasi']))?></td>
		<td align='right'><?= number_format((($l['total_anggaran'][0]['pagu']/2)-($b['total_anggaran'][0]['pagu']/2))/($l['total_realisasi'][0]['realisasi']-$b['total_realisasi'][0]['realisasi'])*100, 2)?></td>
	</tr>
</table>
