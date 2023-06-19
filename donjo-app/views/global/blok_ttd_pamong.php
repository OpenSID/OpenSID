				<table>
					<tr>
						<td colspan="<?= $total_col ?>">&nbsp;</td>
					</tr>
					<tr class="text-center">
						<td colspan="<?= $spasi_kiri ?>" style="width: 20%">&nbsp;</td>
						<td>MENGETAHUI</td>
						<td colspan="<?= $spasi_tengah ?>" style="width: 30%">&nbsp;</td>
						<td colspan="2" class="nowrap"><?= strtoupper($desa['nama_desa'] . ', ' . tgl_indo(date('Y m d'))) ?></td>
						<td style="width: 20%">&nbsp;</td>
					</tr>
					<tr class="text-center">
						<td colspan="<?= $spasi_kiri ?>">&nbsp;</td>
						<td class="nowrap"><?= strtoupper($pamong_ketahui['jabatan'] . ' ' . $desa['nama_desa']) ?></td>
						<td colspan="<?= $spasi_tengah ?>">&nbsp;</td>
						<td colspan="2" class="nowrap"><?= strtoupper($pamong_ttd['jabatan'] . ' ' . $desa['nama_desa']) ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr class="text-center">
						<td colspan="<?= $spasi_kiri ?>">&nbsp;</td>
						<td class="nowrap"><u><?= $pamong_ketahui['nama']?></u></td>
						<td colspan="<?= $spasi_tengah ?>">&nbsp;</td>
						<td colspan="2" class="nowrap"><u><?= $pamong_ttd['nama']?></u></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="text-center">
						<td colspan="<?= $spasi_kiri ?>">&nbsp;</td>
						<td><?= $pamong_ketahui['sebutan_pamong_niap_nip']?> <?= $pamong_ketahui['pamong_niap_nip']?></td>
						<td colspan="<?= $spasi_tengah ?>">&nbsp;</td>
						<td colspan="2"><?= $pamong_ttd['sebutan_pamong_niap_nip']?> <?= $pamong_ttd['pamong_niap_nip']?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
