				<table>
					<tr>
						<td colspan="<?= $total_col ?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?= $spasi_kiri ?>" style="width: 20%">&nbsp;</td>
						<td>Mengetahui</td>
						<td colspan="<?= $spasi_tengah ?>" style="width: 30%">&nbsp;</td>
						<td colspan="2" class="nowrap"><?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, <?= tgl_indo(date("Y m d"))?></td>
						<td style="width: 20%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?= $spasi_kiri ?>">&nbsp;</td>
						<td class="nowrap"><?= $pamong_ketahui['jabatan']?> <?= $desa['nama_desa']?></td>
						<td colspan="<?= $spasi_tengah ?>">&nbsp;</td>
						<td colspan="2" class="nowrap"><?= $pamong_ttd['jabatan']?> <?= $desa['nama_desa']?></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr><td colspan="<?= $total_col ?>">&nbsp;</td>
					<tr>
						<td colspan="<?= $spasi_kiri ?>">&nbsp;</td>
						<td class="underline nowrap"><?= $pamong_ketahui['nama']?></td>
						<td colspan="<?= $spasi_tengah ?>">&nbsp;</td>
						<td colspan="2" class="underline nowrap"><?= $pamong_ttd['nama']?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?= $spasi_kiri ?>">&nbsp;</td>
						<td><?= $this->setting->sebutan_nip_desa  ?>/NIP : <?= $pamong_ketahui['pamong_niap_nip']?></td>
						<td colspan="<?= $spasi_tengah ?>">&nbsp;</td>
						<td colspan="2"><?= $this->setting->sebutan_nip_desa  ?>/NIP : <?= $pamong_ttd['pamong_niap_nip']?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
