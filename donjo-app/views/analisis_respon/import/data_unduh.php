<?php
	$tgl =  date('d_m_Y');
	$subjek = $_SESSION['subjek_tipe'];
	$mas = $_SESSION['analisis_master'];
	$key = ($periode+3)*($mas+7)*($subjek*3);
	$key = "AN".$key;
	switch ($subjek):
		case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
		case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
		case 3: $sql = $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
		case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
		default: return null;
	endswitch;
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_$tgl.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<style>
	td
	{
		mso-number-format:"\@";
		vertical-align:top;
	}
	td,th
	{
		font-size:9pt;
		line-height:9px;
		border:0.5px solid #555;
		cell-padding:-2px;
		margin:0px;
	}
</style>
<div id="body">
	<table>
		<tr>
			<th>No</th>
			<th><?= $nomor ?></th>
			<th><?= $nama ?></th>
			<th>L/P</th>
			<th>Dusun</th>
			<th>RW</th>
			<th>RT</th>
			<th style="background-color:#fefe00">Batas</th>
			<?php
			$tot = count($indikator);
			foreach ($indikator as $pt):
				if ($pt['par']):
					$w = "";
				else:
					$w = "width='80'";
				endif;
			?>
				<?php	if ($pt['id_tipe'] == 1): ?>
					<td <?= $w?>>
						<?= $pt['no']?><br><?=$pt['pertanyaan']?>
						<?php if ($pt['par']):
							foreach ($pt['par'] AS $jb): ?>
								<br><?=$jb['kode_jawaban']?>&nbsp<?= $jb['jawaban']?>
							<?php endforeach;
						endif; ?>
					</td>
				<?php else:
					if ($pt['id_tipe'] == 2): ?>
						<td <?= $w?> style='background-color:#aaaafe;'>
							<?= $pt['no']?><br><?= $pt['pertanyaan']?>
							<?php if ($pt['par']):
								foreach ($pt['par'] AS $jb): ?>
									<br><?=$jb['kode_jawaban']?>&nbsp<?= $jb['jawaban']?>
								<?php endforeach;
							endif; ?>
						</td>
					<?php elseif ($pt['id_tipe'] == 3): ?>
						<td style='background-color:#00fe00;'>
							<?= $pt['no']?><br><?=$pt['pertanyaan']?>
						</td>
					<?php else: ?>
						<td style='background-color:#feaaaa;'>
							<?= $pt['no']?><br><?= $pt['pertanyaan']?>
						</td>
					<?php endif;
				endif;
			endforeach;
			?>
		</tr>
		<tr>
			<th colspan='7' style="background-color:#fefe00"></th>
			<th style="background-color:#fefe00"><?= $key?></th>
			<?php
			$tot = count($indikator);
			foreach ($indikator as $pt): ?>
				<td style='background-color:#fefe00'>
					<?= $pt['nomor']?>
				</td>
			<?php endforeach; ?>
		</tr>
		<?php foreach ($main as $data): ?>
		<tr>
			<td><?= $data['no']?></td>
			<td><?= $data['nid']?></td>
			<td><?= $data['nama']?></td>
			<td><?= $data['jk']?></td>
			<td><?= $data['dusun']?></td>
			<td><?= $data['rw']?></td>
			<td><?= $data['rt']?></td>
			<td style="background-color:#fefe00"><?= $data['id']?></td>
			<?php
			if ($data['par']==null):
				for ($j=0;$j<$tot;$j++): ?>
					<td></td>
				<?php endfor;
			else:
				foreach ($indikator as $pt):
					//cumawarna
					$bx = "";
					$false = 0;
					foreach ($data['par'] AS $jawab):
						$isi = "";
						if ($pt['id'] == $jawab['id_indikator'] AND $false == 0):
							if ($pt['id_tipe'] == 1):
								$isi = $jawab['kode_jawaban'];
							elseif ($pt['id_tipe'] == 2):
								$isi .= $jawab['kode_jawaban'];
							else:
								$isi = $jawab['jawaban'];
							endif;

							//kosong dia
							if ($isi == ""):
								$bx = "style='background-color:#bbffbb;'";
							endif;

							//koreksi
							if ($jawab['korek'] == -1):
								$bx = "style='background-color:#ff9999;'";
							endif;

							if ($pt['id_tipe'] != 2):
							$false = 1;
							endif;
						endif;
					endforeach;?>

					<td <?= $bx?>>

					<?php $false = 0;
					$isi = "";
					foreach ($data['par'] AS $jawab):
						if ($pt['id'] == $jawab['id_indikator'] AND $false == 0):
							if ($pt['id_tipe'] == 1):
								$isi = $jawab['kode_jawaban'];
							elseif ($pt['id_tipe'] == 2 AND $pt['is_teks'] == 0):
								$isi .= $jawab['kode_jawaban'].",";
							elseif ($pt['id_tipe'] == 2 AND $pt['is_teks'] == 1):
								$isi .= $jawab['jawaban'].",";
							else:
								$isi = $jawab['jawaban'];
							endif;

							//kosong dia
							if ($isi == ""):
								$bx = "style='background-color:#bbffbb;'";
							endif;

							//koreksi
							if ($jawab['korek'] == -1):
								$isi = "xxx";
								$bx = "style='background-color:#ff9999;'";
							endif;

							if ($pt['id_tipe'] != 2):
							$false = 1;
							endif;
						endif;
					endforeach;

					//DEL last koma
					if ($pt['id_tipe'] == 2):
						$jml = strlen($isi);
						$isi = substr($isi,0,$jml-1);
					endif; ?>

					<?= $isi;?>
					</td <?=$bx?>>
				<?php endforeach;
			endif;
			?>
		</tr>
		<?php endforeach; ?>
	</table>
</div>