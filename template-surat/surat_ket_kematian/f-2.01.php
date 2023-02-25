<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>	
	.kotak {
		border: solid 1px #000000;
		width: 10px;
		height: 10px;
	}

	.padat {
		padding: 0px;
		margin: 0px;
	}

	.tengah {
		text-align: center;
	}

	.kanan {
		text-align: right;
	}

	.border-collapse {
		border-collapse: collapse;
	}
</style>

<page orientation="portrait" format="210x330" style="font-size: 9pt">	
	<table class="border-collapse" align="right" width="20%" border="2px">
		<tr>
			<td class="kanan" style="font-size: 170%">
				<strong>F-2.01</strong>
			</td>
		</tr>
	</table>

	<table>		
		<tr>
			<td>Provinsi</td>
			<td>:</td>
			<td colspan="7"><strong><?= $config['nama_propinsi'];?></strong></td>
		</tr>
		<tr>
			<td>Kabupaten/Kota</td>
			<td>:</td>
			<td colspan="7"><strong><?= $config['nama_kabupaten'];?></strong></td>
		</tr>
		<tr>
			<td>Kecamatan</td>
			<td>: </td>
			<td colspan="7"><?= $config['nama_kecamatan'];?></td>
		</tr>
		<tr>
			<td>Desa/Kelurahan</td>
			<td>: </td>
			<td colspan="7"><?= $config['nama_desa'];?></td>
		</tr>
		<tr>
			<td>Kode Wilayah</td>
			<td>:</td>
			<td>
				<table>
					<tr>
						<?php for ($i=0; $i<10; $i++): ?>
						<td class="kotak padat tengah">
							<?php if (isset($config['kode_desa'][$i])): ?>
							<?= $config['kode_desa'][$i];?>
							<?php else: ?>
							&nbsp;
							<?php endif; ?>
						</td>
						<?php endfor; ?>
					</tr>
				</table>
			</td>
		</tr>

	</table>
	<h4 style="text-align: center; margin: 0px; padding: 0px;">
		<strong>FORMULIR PELAPORAN PENCATATAN SIPIL DI DALAM WILAYAH NKRI</strong>
	</h4>	
	<h5>
		<strong>Jenis Pelaporan Pencatatan Sipil (Centang)</strong>
	</h5>

	<table style="width:100%;" cellpadding="5" cellspacing="0">
		<tr>
			<td>
				<table class="border-collapse">
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Kelahiran</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Lahir Mati</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Perkawinan</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pembatalan Perkawinan</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Perceraian</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pembatalan Perceraian</td>						
					</tr>
					<tr>
						<td class="kotak padat tengah">V</td>
						<td>Kematian</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pengangkatan Anak</td>
					</tr>
				</table>
			</td>
			<td style="width:60px">&nbsp;</td>
			<td>
				<table class="border-collapse">
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pengakuan Anak</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pengesahan Anak</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Perubahan Nama</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Perubahan Status Kewarganegaraan</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pencatatan Peristiwa Penting Lainnya</td>
					</tr>					
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pembetulan Akta</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pembatalan Akta</td>
					</tr>
					<tr>
						<td class="kotak padat tengah"></td>
						<td>Pelaporan Pencatatan Sipil Dari Luar Wilayah NKRI</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<td colspan=3><strong>DATA PELAPOR</strong></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>:</td>
			<td><?= $input['nama_pelapor'] ?></td>
		</tr>
		<tr>
			<td>NIK</td>
			<td>:</td>
			<td><?= $input['nik_pelapor'] ?></td>
		</tr>
		<tr>
			<td>Nomor Dokumen Perjalanan</td>
			<td>:</td>
			<td>-</td>
		</tr>
		<tr>
			<td>Nomor Kartu Keluarga</td>
			<td>:</td>
			<td>-</td>
		</tr>
		<tr>
			<td>Kewarganegaraan</td>
			<td>:</td>
			<td>WNI</td>
		</tr>

		<tr>
			<td colspan=3>&nbsp;</td>
		</tr>
		<!-- AWAL KEMATIAN -->
		<tr>
			<td colspan=3><strong>KEMATIAN</strong></td>
		</tr>
		<tr>
			<td>1.&nbsp;&nbsp;&nbsp;&nbsp;NIK </td>
			<td>:</td>
			<td>
				<table class="border-collapse">
					<tr>
						<?php for ($i=0; $i<16; $i++): ?>
						<td class="kotak padat tengah">
							<?php if (isset($individu['nik'][$i])): ?>
							<?= $individu['nik'][$i];?>
							<?php else: ?>
							&nbsp;
							<?php endif; ?>
						</td>
						<?php endfor; ?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>2.&nbsp;&nbsp;&nbsp;&nbsp;Nama Lengkap</td>
			<td>:</td>
			<td><?= $individu['nama'] ?></td>
		</tr>
		<tr>
			<td>3.&nbsp;&nbsp;Tanggal Kematian </td>
			<td>:</td>
			<?php $tgl = date('dd',strtotime($input['tanggal_mati']));
				$bln = date('mm',strtotime($input['tanggal_mati']));
				$thn = date('Y',strtotime($input['tanggal_mati']));
			?>
			<td>
				<table>
					<tr>
						<td class="kanan">Tgl : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<2; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($tgl[$j])): ?>
										<?= $tgl[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>

						<td class="kanan">Bln : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<2; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($bln[$j])): ?>
										<?= $bln[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>

						<td class="kanan">Thn : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<4; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($thn[$j])): ?>
										<?= $thn[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>

					</tr>
				</table>

			</td>
		</tr>
		<tr>
			<td>4.&nbsp;&nbsp;Pukul </td>
			<td>:</td>
			<td>
				<table>
					<tr>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($i=0; $i<2; $i++): ?>
									<?php if (isset($input['jam'][$i])): ?>
									<td class="kotak padat tengah">
										<?= $input['jam'][$i];?>
									</td>
									<?php endif; ?>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
						<td> : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($i=3; $i<5; $i++): ?>
									<?php if (isset($input['jam'][$i])): ?>
									<td class="kotak padat tengah">
										<?= $input['jam'][$i];?>
									</td>
									<?php endif; ?>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>5.&nbsp;&nbsp;Sebab Kematian </td>
			<td>:</td>
			<td>
				<table>
					<tr>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['sebab']) ? ($input['sebab'] == 1 ? 'V' : '') : '' ?></td>
								</tr>
							</table>
						</td>
						<td>Sakit biasa / tua</td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['sebab']) ? ($input['sebab'] == 2 ? 'V' : '') : '' ?></td>
								</tr>
							</table>
						</td>

						<td>Wabah Penyakit</td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['sebab']) ? ($input['sebab'] == 3 ? 'V' : '') : '' ?></td>
								</tr>
							</table>
						</td>


						<td>Kecelakaan</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan=2>&nbsp;</td>
			<td>
				<table>
					<tr>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['sebab']) ? ($input['sebab'] == 4 ? 'V' : '') : '' ?></td>
								</tr>
							</table>
						</td>
						<td>Kriminalitas</td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['sebab']) ? ($input['sebab'] == 5 ? 'V' : '') : '' ?></td>
								</tr>
							</table>
						</td>

						<td>Bunuh Diri</td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['sebab']) ? ($input['sebab'] == 6 ? 'V' : '') : '' ?></td>
								</tr>
							</table>
						</td>
						<td>Lainnya</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>6.&nbsp;&nbsp;Tempat Kematian </td>
			<td>:</td>
			<td><?= $input['tempat_mati'] ?></td>
		</tr>
		<tr>
			<td>7.&nbsp;&nbsp;Yang Menerangkan</td>
			<td>:</td>
			<td>
				<table>
					<tr>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['penolong_mati']) ? ($input['penolong_mati'] == 1 ? 'V' : '') : '' ?>
									</td>
								</tr>
							</table>
						</td>

						<td>1. Dokter </td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['penolong_mati']) ? ($input['penolong_mati'] == 2 ? 'V' : '') : '' ?>
									</td>
								</tr>
							</table>
						</td>

						<td>2. Tenaga Kesehatan </td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['penolong_mati']) ? ($input['penolong_mati'] == 3 ? 'V' : '') : '' ?>
									</td>
								</tr>
							</table>
						</td>

						<td>3. Kepolisian </td>
						<td>
							<table class="border-collapse">
								<tr>
									<td class="kotak padat tengah">
										<?= isset($input['penolong_mati']) ? ($input['penolong_mati'] == 4 ? 'V' : '') : '' ?>
									</td>
								</tr>
							</table>
						</td>
						<td>4. Lainnya </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><strong>DATA ORANG TUA<em>**(hanya diisi untuk keperluan pencatatan kelahiran, lahir mati
						dan kematian)</em></strong></td>
		</tr>
		<tr>
			<td>8.&nbsp;&nbsp;Nama Ayah</td>
			<td>:</td>
			<td><?= $input['nama_ayah'] ?></td>
		</tr>
		<tr>
			<td>9.&nbsp;&nbsp;NIK Ayah</td>
			<td>:</td>
			<td>
				<table class="border-collapse">
					<tr>
						<?php for ($i=0; $i<16; $i++): ?>
						<td class="kotak padat tengah">
							<?php if (isset($input['nik_ayah'][$i])): ?>
							<?= $input['nik_ayah'][$i];?>
							<?php else: ?>
							&nbsp;
							<?php endif; ?>
						</td>
						<?php endfor; ?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>10.&nbsp;Tempat Lahir Ayah</td>
			<td>:</td>
			<td><?= $input['tempat_lahir_ayah'] ?></td>
		</tr>
		<tr>
			<td>11.&nbsp;Tanggal Lahir Ayah</td>
			<td>:</td>
			<?php if (!empty($input['tanggal_lahir_ayah'])):
				$tgl = date('dd',strtotime($input['tanggal_lahir_ayah']));
				$bln = date('mm',strtotime($input['tanggal_lahir_ayah']));
				$thn = date('Y',strtotime($input['tanggal_lahir_ayah']));
			else:
				unset($tgl); unset($bln); unset($thn);
			endif;
			?>
			<td>
				<table>
					<tr>
						<td class="kanan">Tgl : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<2; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($tgl[$j])): ?>
										<?= $tgl[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
						<td class="kanan">Bln : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<2; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($bln[$j])): ?>
										<?= $bln[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>

						<td class="kanan">Thn : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<4; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($thn[$j])): ?>
										<?= $thn[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>12.&nbsp;Kewarganegaraan</td>
			<td>:</td>
			<td>WNI</td>
		</tr>
		<tr>
			<td>13.&nbsp;Nama Ibu</td>
			<td>:</td>
			<td><?= $input['nama_ibu'] ?></td>
		</tr>
		<tr>
			<td>14.&nbsp;NIK Ibu</td>
			<td>:</td>
			<td>
				<table>
					<tr>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($i=0; $i<16; $i++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($input['nik_ibu'][$i])): ?>
										<?= $input['nik_ibu'][$i];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>15.&nbsp;Tempat Lahir Ibu</td>
			<td>:</td>
			<td><?= $input['tempat_lahir_ibu'] ?></td>
		</tr>
		<tr>
			<td>16.&nbsp;Tanggal Lahir Ibu</td>
			<td>:</td>
			<?php if (!empty($input['tanggal_lahir_ibu'])):
				$tgl = date('dd',strtotime($input['tanggal_lahir_ibu']));
				$bln = date('mm',strtotime($input['tanggal_lahir_ibu']));
				$thn = date('Y',strtotime($input['tanggal_lahir_ibu']));
			else:
				unset($tgl); unset($bln); unset($thn);
			endif;
			?>
			<td>
				<table>
					<tr>
						<td class="kanan">Tgl : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<2; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($tgl[$j])): ?>
										<?= $tgl[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
						<td class="kanan">Bln : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<2; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($bln[$j])): ?>
										<?= $bln[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>

						<td class="kanan">Thn : </td>
						<td>
							<table class="border-collapse">
								<tr>
									<?php for ($j=0; $j<4; $j++): ?>
									<td class="kotak padat tengah">
										<?php if (isset($thn[$j])): ?>
										<?= $thn[$j];?>
										<?php else: ?>
										&nbsp;
										<?php endif; ?>
									</td>
									<?php endfor; ?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>17.&nbsp;Kewarganegaraan</td>
			<td>:</td>
			<td>WNI</td>
		</tr>
		<tr>
			<td colspan=3>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=3><strong>DATA SAKSI I</strong></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>:</td>
			<td><?= $input['nama_saksi1']; ?></td>
		</tr>
		<tr>
			<td>NIK</td>
			<td>:</td>
			<td><?= $input['nik_saksi1']; ?></td>
		</tr>
		<tr>
			<td>Nomor Kartu Keluarga</td>
			<td>:</td>
			<td>-</td>
		</tr>
		<tr>
			<td>Kewarganegaraan</td>
			<td>:</td>
			<td>WNI</td>
		</tr>
		<tr>
			<td colspan=3><strong>DATA SAKSI II</strong></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>:</td>
			<td><?= $input['nama_saksi2']; ?></td>
		</tr>
		<tr>
			<td>NIK</td>
			<td>:</td>
			<td><?= $input['nik_saksi2']; ?></td>
		</tr>
		<tr>
			<td>Nomor Kartu Keluarga</td>
			<td>:</td>
			<td>-</td>
		</tr>
		<tr>
			<td>Kewarganegaraan</td>
			<td>:</td>
			<td>WNI</td>
		</tr>
	</table>
	<table id="ttd" class="disdukcapil"
		style="margin-top: 5px; margin-bottom: 0px; padding: 0px; border: 0px; border-collapse: collapse;">
		<col span="48" style="width: 2.0833%;">
		<tr>
			<td colspan="33">&nbsp;</td>
			<td colspan="13" style="text-align: center;">
				<?= $config['nama_kabupaten'];?>, <?= tgl_indo(date('Y m d',time()));?>
			</td>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;">Mengetahui</td>
			<td colspan="15">&nbsp;</td>
			<td colspan="10" style="text-align: center;">Pelapor</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;"><?= $this->penandatangan_lampiran($data);?></td>
			<td colspan="15">&nbsp;</td>
			<td colspan="10" style="text-align: center;">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="48" style="height:50px">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;">
				<strong>(&nbsp;<?= padded_string_center(strtoupper($input['pamong']),30)?>&nbsp;)</strong></td>
			<td colspan="13">&nbsp;</td>
			<td colspan="14" style="text-align: center;">
				<strong>(&nbsp;<?= padded_string_center(strtoupper($input['nama_pelapor']),30)?>&nbsp;)</strong></td>
			<td colspan="1">&nbsp;</td>
		</tr>
	</table>
</page>