<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Data Analisis</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= asset('css/report.css') ?>" rel="stylesheet" type="text/css">
		<!-- TODO: Pindahkan ke external css -->
		<style>
			td
			{
				mso-number-format:"\@";
			}
			td,th
			{
				font-size:8pt;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3> DATA Analisis </h3>
				</div>
				<br>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th width="10">No</th>
							<th align="left">Pertanyaan</th>
							<th align="left">Total</th>
							<th align="left">Kode</th>
							<th align="left" colspan="2">Jawaban</th>
							<th align="left">Responden</th>
							<th align="left">Tipe Indikator</th>
							<th align="left">Kategori Indikator</th>
							<th align="left">Aksi Analisis</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td align="center" width="2"><?= $data['no']?></td>
								<td><?= $data['pertanyaan']?></a></td>
								<td align="right"><?= $data['bobot']?></td>
								<td><?= $data['nomor']?></td>
								<td align="right">
									<?php foreach ($data['par'] as $par): ?>
										<?= $par['kode_jawaban']?>.<br>
									<?php endforeach; ?>
								</td>
								<td>
									<?php foreach ($data['par'] as $par): ?>
										<?= $par['jawaban']?><br>
									<?php endforeach; ?>
								</td>
								<td align="right">
									<?php foreach ($data['par'] as $par): ?>
										<?= $par['jml_p']?><br>
									<?php endforeach; ?>
								</td>
								<td><?= $data['tipe_indikator']?></td>
								<td><?= $data['kategori']?></td>
								<td><?= $data['act_analisis']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
 			<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date('Y m d'))?>
		</div>
	</body>
</html>