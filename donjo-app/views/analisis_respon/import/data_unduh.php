<?php
    $tgl = date('d_m_Y');
    header('Content-type: application/octet-stream');
    header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_{$tgl}.xls");
    header('Pragma: no-cache');
    header('Expires: 0');
    ?>
<!-- TODO: Pindahkan ke external css -->
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
			<th><?= $judul['nomor'] ?></th>
			<th><?= $judul['nama'] ?></th>
			<?php if (in_array($subjek_tipe, [1, 2, 3, 4])): ?>
				<th>L/P</th>
			<?php endif; ?>
			<?php if (in_array($subjek_tipe, [1, 2, 3, 4, 7, 8])): ?>
				<th>Dusun</th>
				<?php if ($subjek_tipe != 6): ?>
					<th>RW</th>
					<?php if ($subjek_tipe != 7): ?>
						<th>RT</th>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<th style="background-color:#fefe00">Batas</th>
			<?php
                $tot = count($indikator);

    foreach ($indikator as $pt):
        if ($pt['par']):
            $w = '';
        else:
            $w = "width='80'";
        endif;
        ?>
				<?php	if ($pt['id_tipe'] == 1): ?>
					<td <?= $w?>>
						<?= $pt['no']?><br><?=$pt['pertanyaan']?>
						<?php if ($pt['par']):
						    foreach ($pt['par'] as $jb): ?>
								<br><?=$jb['kode_jawaban']?>&nbsp<?= $jb['jawaban']?>
							<?php endforeach;
						endif; ?>
					</td>
				<?php else:
				    if ($pt['id_tipe'] == 2): ?>
						<td <?= $w?> style='background-color:#aaaafe;'>
							<?= $pt['no']?><br><?= $pt['pertanyaan']?>
							<?php if ($pt['par']):
							    foreach ($pt['par'] as $jb): ?>
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
			<th colspan='<?= $span_kolom ?: 7?>' style="background-color:#fefe00"></th>
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
			<?php if (in_array($subjek_tipe, [1, 2, 3, 4])): ?>
				<td><?= $data['jk']?></td>
			<?php endif; ?>
			<?php if (in_array($subjek_tipe, [1, 2, 3, 4, 7, 8])): ?>
				<td><?= $data['dusun']?></td>
				<?php if ($subjek_tipe != 6): ?>
					<td><?= $data['rw']?></td>
					<?php if ($subjek_tipe != 7): ?>
						<td><?= $data['rt']?></td>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
			<td style="background-color:#fefe00"><?= $data['id']?></td>
			<?php
    if ($data['par'] == null):
        for ($j = 0; $j < $tot; $j++): ?>
					<td></td>
				<?php endfor;
    else:
        foreach ($indikator as $pt):
            //cumawarna
            $bx    = '';
            $false = 0;

            foreach ($data['par'] as $jawab):
                $isi = '';
                if ($pt['id'] == $jawab['id_indikator'] && $false == 0):
                    if ($pt['id_tipe'] == 1):
                        $isi = $jawab['kode_jawaban'];
                    elseif ($pt['id_tipe'] == 2):
                        $isi .= $jawab['kode_jawaban'];
                    else:
                        $isi = $jawab['jawaban'];
                    endif;

                //kosong dia
                if ($isi == ''):
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
            endforeach; ?>

					<td <?= $bx?>>

					<?php $false = 0;
            $isi  = '';

            foreach ($data['par'] as $jawab):
                if ($pt['id'] == $jawab['id_indikator'] && $false == 0):
                    if ($pt['id_tipe'] == 1):
                        $isi = $jawab['kode_jawaban'];
                    elseif ($pt['id_tipe'] == 2 && $pt['is_teks'] == 0):
                        $isi .= $jawab['kode_jawaban'] . ',';
                    elseif ($pt['id_tipe'] == 2 && $pt['is_teks'] == 1):
                        $isi .= $jawab['jawaban'] . ',';
                    else:
                        $isi = $jawab['jawaban'];
                    endif;

                //kosong dia
                if ($isi == ''):
                    $bx = "style='background-color:#bbffbb;'";
                endif;

                //koreksi
                if ($jawab['korek'] == -1):
                    $isi = 'xxx';
                    $bx  = "style='background-color:#ff9999;'";
                endif;

                if ($pt['id_tipe'] != 2):
                    $false = 1;
                endif;
                endif;
            endforeach;

            //DEL last koma
            if ($pt['id_tipe'] == 2):
                $jml = strlen($isi);
                $isi = substr($isi, 0, $jml - 1);
            endif; ?>

					<?= $isi; ?>
					</td <?=$bx?>>
				<?php endforeach;
		    endif;
		    ?>
		</tr>
		<?php endforeach; ?>
	</table>
</div>