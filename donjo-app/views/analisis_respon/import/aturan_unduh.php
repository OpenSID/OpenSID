<?php
$tgl =  date('d_m_Y');

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
td{
mso-number-format:"\@";
}
td,th{
	font-size:9pt;
	line-height:9px;
	border:0.5px solid #555;
	cell-padding:-2px;
	margin:0px;
}
</style>
<div id="body">
<table>
	<thead>
		<tr>
			<th>No</th>
			<th colspan="2">Pertanyaan</th>
			<th colspan="2">Jawaban</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($main as $data): ?>
		<tr>
			<td><?php echo $data['no']?></td>
			<td>
			<?php echo $data['pertanyaan']?><br>
			*<?php echo $data['tipe_indikator']?>
			</td>
			<td><?php echo $data['nomor']?></td>
			<?php if($data['id_tipe'] == 1) { ?>
			<td>
				<?php foreach($data['par'] as $par): ?>
				<?php echo $par['kode_jawaban']?>.<br>
				<?php endforeach; ?>
			</td>
			
			<td>
				<?php foreach($data['par'] as $par): ?>
				<?php echo $par['jawaban']?><br>
				<?php endforeach; ?>
			</td>
			<?php } else { ?>
			<td>-</td>
			<td>-</td>
			<?php } ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>