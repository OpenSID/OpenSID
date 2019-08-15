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
			<th>Sisa Anggaran (Rp)</th>
			<th>Persentase (%)</th>
		</tr>
	</thead>
	<?php foreach ($data['pendapatan'] as $l)
	{
	?>
		<tr class='bold highlighted'>
			<td colspan='4'><?php echo $l['Akun'] ." ". $l['Nama_Akun']?></td>
			<td align='right'><?php echo number_format($l['anggaran'][0]['pagu'])?></td>
			<td align='right'><?php echo number_format($l['realisasi'][0]['realisasi'])?></td>
			<td align='right'><?php echo number_format($l['anggaran'][0]['pagu'] - $l['realisasi'][0]['realisasi'])?></td>
			<td align='right'><?php echo number_format($l['realisasi'][0]['realisasi']/$l['anggaran'][0]['pagu']*100, 2)?> </td>
			<?php foreach ($l['sub_pendapatan'] as $s)
			{
			?>
				<tr class='bold'>
					<td><?php echo $s['Kelompok']?></td>
					<td colspan='3'><?php echo $s['Nama_Kelompok'] ?></td>
					<td align='right'><?php echo number_format($s['anggaran'][0]['pagu'])?></td>
					<td align='right'><?php echo number_format($s['realisasi'][0]['realisasi'])?></td>
					<td align='right'><?php echo number_format($s['anggaran'][0]['pagu']-$s['realisasi'][0]['realisasi']) ?></td>
					<td align='right'><?php echo number_format($s['realisasi'][0]['realisasi']/$s['anggaran'][0]['pagu']*100, 2) ?></td>
				</tr>
				<?php foreach ($s['sub_pendapatan2'] as $q)
				{
				?>
					<tr>
						<td></td>
						<td colspan='2'><?php echo $q['Jenis'] ?></td>
						<td><?php echo $q['Nama_Jenis'] ?></td>
						<td align='right'><?php echo number_format($q['anggaran'][0]['pagu']) ?></td>
						<td align='right'><?php echo number_format($q['realisasi'][0]['realisasi']) ?></td>
						<td align='right'><?php echo number_format($q['anggaran'][0]['pagu']-$q['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?php echo number_format($q['realisasi'][0]['realisasi']/$q['anggaran'][0]['pagu']*100, 2)?></td>
					</tr>
				<?php };
				$j++;
			}; ?>
		</tr>
		<?php }
	foreach ($data['belanja'] as $b)
	{
	?>
		<tr class='bold highlighted'>
			<td colspan='4' class='bold highlighted'><?php echo $b['Akun']." ".$b['Nama_Akun']?></td>
			<td align='right' class='bold highlighted'><?php echo number_format($b['anggaran'][0]['pagu'])?></td>
			<td align='right' class='bold highlighted'><?php echo number_format($b['realisasi'][0]['realisasi'])?></td>
			<td align='right' class='bold highlighted'><?php echo number_format($b['anggaran'][0]['pagu'] - $b['realisasi'][0]['realisasi'])?></td>
			<td align='right' class='bold highlighted'><?php echo number_format($b['realisasi'][0]['realisasi']/$b['anggaran'][0]['pagu']*100, 2)?></td>
			<?php foreach ($b['sub_belanja'] as $sb)
			{
			?>
				<tr class='bold'>
					<td align='right'><?php echo substr($sb['Kd_Bid'], 6)."."?></td>
					<td colspan='3'><?php echo $sb['Nama_Bidang']?></td>
					<td align='right'><?php echo number_format($sb['anggaran'][0]['pagu'])?></td>
					<td align='right'><?php echo number_format($sb['realisasi'][0]['realisasi']) ?></td>
					<td align='right'><?php echo number_format($sb['anggaran'][0]['pagu']-$sb['realisasi'][0]['realisasi'])?></td>
					<td align='right'><?php echo number_format($sb['realisasi'][0]['realisasi']/$sb['anggaran'][0]['pagu']*100, 2)?></td>
				</tr>
				<?php
				foreach ($sb['sub_belanja2'] as $sb2)
				{
				?>
					<tr class='bold'>
						<td></td>
						<td align='right'><?php echo substr($sb2['Kd_Keg'], 6)?></td>
						<td colspan='2'><?php echo $sb2['Nama_Kegiatan']?></td>
						<td align='right'><?php echo number_format($sb2['anggaran'][0]['pagu'])?></td>
						<td align='right'><?php echo number_format($sb2['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?php echo number_format($sb2['anggaran'][0]['pagu']-$sb2['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?php echo number_format($sb2['realisasi'][0]['realisasi']/$sb2['anggaran'][0]['pagu']*100, 2)?></td>
					</tr>
					<?php
					foreach ($sb2['sub_belanja3'] as $sb3)
					{
					?>
						<tr>
							<td></td>
							<td></td>
							<td align='right'><?php echo $sb3['Jenis']?></td>
							<td colspan='1'><?php echo $sb3['Nama_Jenis']?></td>
							<td align='right'><?php echo number_format($sb3['anggaran'][0]['pagu'])?></td>
							<td align='right'><?php echo number_format($sb3['realisasi'][0]['realisasi'])?></td>
							<td align='right'><?php echo number_format($sb3['anggaran'][0]['pagu']-$sb3['realisasi'][0]['realisasi'])?></td>
							<td align='right'><?php echo number_format($sb3['realisasi'][0]['realisasi']/$sb3['anggaran'][0]['pagu']*100, 2)?></td>
						</tr>
						<?php	};
				};
			}; ?>
		</tr>
		<?php }
	foreach ($data['pembiayaan'] as $a)
	{
	?>
		<tr class='bold highlighted'>
			<td colspan='4'><?php echo $a['Akun']." ".$a['Nama_Akun']?></td>
			<td align='right'><?php echo number_format($a['anggaran'][0]['pagu'])?></td>
			<td align='right'><?php echo number_format($a['realisasi'][0]['realisasi'])?></td>
			<td align='right'><?php echo number_format($a['anggaran'][0]['pagu'] - $l['realisasi'][0]['realisasi'])?></td>
			<td align='right'><?php echo number_format($a['realisasi'][0]['realisasi']/$a['anggaran'][0]['pagu']*100, 2)?></td>
			<?php foreach ($a['sub_pembiayaan'] as $b)
			{
			?>
				<tr class='bold'>
					<td><?php echo $b['Kelompok']?></td>
					<td colspan='3'><?php echo $b['Nama_Kelompok']?></td>
					<td align='right'><?php echo number_format($b['anggaran'][0]['pagu'])?></td>
					<td align='right'><?php echo number_format($b['realisasi'][0]['realisasi'])?></td>
					<td align='right'><?php echo number_format($b['anggaran'][0]['pagu']-$b['realisasi'][0]['realisasi'])?></td>
					<td align='right'><?php echo number_format($b['realisasi'][0]['realisasi']/$b['anggaran'][0]['pagu']*100, 2)?></td>
				</tr>
				<?php foreach ($b['sub_pendapatan2'] as $c)
				{
				?>
					<tr>
						<td></td>
						<td colspan='2'><?php echo $c['Jenis']?></td>
						<td><?php echo $c['Nama_Jenis']?></td>
						<td align='right'><?php echo number_format($c['anggaran'][0]['pagu'])?></td>
						<td align='right'><?php echo number_format($c['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?php echo number_format($c['anggaran'][0]['pagu']-$c['realisasi'][0]['realisasi'])?></td>
						<td align='right'><?php echo number_format($c['realisasi'][0]['realisasi']/$c['anggaran'][0]['pagu']*100, 2)?></td>
					</tr>
				<?php };
			}; ?>
		</tr>
		<?php } ?>
	<tr class='bold highlighted'>
		<td colspan='4' align='center'>TOTAL</td>
		<td align='right'><?php echo number_format($data['pendapatan'][0]['total_anggaran'][0]['pagu'])?></td>
		<td align='right'><?php echo number_format($data['pendapatan'][0]['total_realisasi'][0]['realisasi'])?></td>
		<td align='right'><?php echo number_format($data['pendapatan'][0]['total_anggaran'][0]['pagu']-$data['pendapatan'][0]['total_realisasi'][0]['realisasi'])?></td>
		<td align='right'><?php echo number_format($data['pendapatan'][0]['total_realisasi'][0]['realisasi']/$data['pendapatan'][0]['total_anggaran'][0]['pagu']*100, 2)?></td>
	</tr>
</table>