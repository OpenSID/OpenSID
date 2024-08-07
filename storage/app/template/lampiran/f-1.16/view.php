<?php if (! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
	table.disdukcapil
	{
		width: 100%;
		border: solid 1px #000000;
		/*border-collapse: collapse;*/
	}
	table.disdukcapil td
	{
		padding: 1px 1px 1px 3px;
	}
	table.disdukcapil td.padat
	{
		padding: 0px;
		margin: 0px;
	}
	table.disdukcapil td.anggota
	{
		border-left: solid 1px #000000;
		border-right: solid 1px #000000;
		border-top: dashed 1px #000000;
		border-bottom: dashed 1px #000000;
	}
	table.disdukcapil td.judul
	{
		border-left: solid 1px #000000;
		border-right: solid 1px #000000;
		border-top: double 1px #000000;
		border-bottom: double 1px #000000;
	}
	table.disdukcapil td.bawah {border-bottom: solid 1px #000000;}
	table.disdukcapil td.atas {border-top: solid 1px #000000;}
	table.disdukcapil td.tengah_blank
	{
		border-left: solid 1px #000000;
		border-right: solid 1px #000000;
	}
	table.disdukcapil td.pinggir_kiri {border-left: solid 1px #000000;}
	table.disdukcapil td.pinggir_kanan {border-right: solid 1px #000000;}
	table.disdukcapil td.kotak {border: solid 1px #000000;}
	table.disdukcapil td.abu {background-color: lightgrey;}
	table.disdukcapil td.kode {background-color: lightgrey;}
	table.disdukcapil td.kode div
	{
		margin: 0px 15px 0px 15px;
		border: solid 1px black;
		background-color: white;
		text-align: center;
	}
	table.disdukcapil td.pakai-padding
	{
		padding-left: 20px;
		padding-right: 2px;
	}
	table.disdukcapil td.kanan { text-align: right; }
	table.disdukcapil td.tengah { text-align: center; }
</style>

<page orientation="portrait" format="210x330" style="font-size: 8pt">
	<table align="right" style="padding: 5px 20px; border: solid 1px black;">
		<tr><td><strong style="font-size: 14pt;">F-1.16</strong></td></tr>
	</table>
	<p style="text-align: center; margin-top: 40px;">
			<strong style="font-size: 10pt;">FORMULIR PERMOHOHAN PERUBAHAN KARTU KELUARGA (KK) WARGA NEGARA INDONESIA</strong>
	</p>
	<table class="disdukcapil" style="margin-top: 0px;">
		<col span="48" style="width: 2.0833%;">
		<tr>
			<td class="kotak" colspan=48>
				<strong>Perhatian:</strong><br>
				1. Harap diisi dengan huruf cetak dan menggunakan tinta hitam<br>
				2. Setelah formulir ini diisi dan ditandatangani, harap diserahkan kembali ke Kantor Desa/Kelurahan
			</td>
		</tr>
		<tr>
			<td colspan="13" class="left"><strong>PEMERINTAH PROVINSI</strong></td>
			<td>:</td>
			<?php for ($i = 0; $i < 2; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_propinsi'][$i])): ?>
						<?= $config['kode_propinsi'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=3>&nbsp;</td>
			<td colspan="17" class="kotak"><?= $config['nama_propinsi']; ?></td>
			<td colspan=12>&nbsp;</td>
		</tr>

		<tr>
			<td colspan="13" class="left"><strong>PEMERINTAH KABUPATEN/KOTA</strong></td>
			<td>:</td>
			<?php for ($i = 0; $i < 2; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_kabupaten'][$i])): ?>
						<?= substr($config['kode_kabupaten'], 2, 4)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=3>&nbsp;</td>
			<td colspan="17" class="kotak"><?= $config['nama_kabupaten']; ?></td>
			<td colspan=12>&nbsp;</td>
		</tr>

		<tr>
			<td colspan="13" class=" left"><strong>KECAMATAN</strong></td>
			<td>:</td>
			<?php for ($i = 0; $i < 2; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_kecamatan'][$i])): ?>
						<?= substr($config['kode_kecamatan'], 4, 6)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=3>&nbsp;</td>
			<td colspan="17" class="kotak"><?= $config['nama_kecamatan']; ?></td>
			<td colspan=12>&nbsp;</td>
		</tr>

		<tr>
			<td colspan="13" class="left"><strong>KELURAHAN/DESA</strong></td>
			<td>:</td>
			<?php for ($i = 0; $i < 4; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_desa'][$i])): ?>
						<?= substr($config['kode_desa'], 6, 10)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="17" class="kotak"><?= $config['nama_desa']; ?></td>
			<td colspan=12>&nbsp;</td>
		</tr>
		<tr><td colspan=48 class="bawah">&nbsp;</td></tr>
		<tr><td colspan=48>&nbsp;</td></tr>

		<tr>
			<td colspan="10" class="kotak">1. Nama Lengkap Pemohon</td>
			<td>:</td>
			<?php for ($i = 0; $i < 33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['nama'][$i])): ?>
						<?= $individu['nama'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10" class="kotak">2. NIK Pemohon</td>
			<td>:</td>
			<?php for ($i = 0; $i < 16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['nik'][$i])): ?>
						<?= $individu['nik'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10" class="kotak">3. Nama Kepala Keluarga</td>
			<td>:</td>
			<?php for ($i = 0; $i < 33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['kepala_kk'][$i])): ?>
						<?= $individu['kepala_kk'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=10 class="kotak">4. No. KK </td>
			<td>:</td>
			<?php for ($i = 0; $i < 16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['no_kk'][$i])): ?>
						<?= $individu['no_kk'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21 class="left">*) Diisi oleh petugas</td>
		</tr>
		<tr>
			<td colspan="10" class="kotak">5. Alamat Pemohon</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $individu['alamat']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i = 0; $i < 3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rt'][$i])): ?>
						<?= $individu['rt'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i = 0; $i < 3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rw'][$i])): ?>
						<?= $individu['rw'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $config['nama_desa']; ?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $config['nama_kecamatan']; ?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $config['nama_kabupaten']; ?></td>
			<td colspan="5" class="left">d. Provinsi</td>
			<td colspan="12" class="kotak"><?= $config['nama_propinsi']; ?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i = 0; $i < 5; $i++): ?>
				<td class="kotak padat tengah">
					<?= str_split($config['kode_pos'])[$i] ?: '&nbsp;' ?>
				</td>
			<?php endfor; ?>
			<td colspan="7">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i = 0; $i < 8; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['telepon_kk'][$i])): ?>
						<?= $individu['telepon_kk'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10" class="kotak">6. Kepala Keluarga Lama</td>
			<td>:</td>
			<?php for ($i = 0; $i < 33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['kepala_kk'][$i])): ?>
						<?= $individu['kepala_kk'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=10 class="kotak">7. No. KK Lama</td>
			<td>:</td>
			<?php for ($i = 0; $i < 16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['no_kk'][$i])): ?>
						<?= $individu['no_kk'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21 class="left">*) Diisi oleh petugas</td>
		</tr>
		<tr>
			<td colspan="10" class="kotak">8. Alamat Keluarga Lama</td>
			<td>:</td>
			<?php info($individu) ?>
			<td colspan="23" class="kotak"><?= $individu['alamat']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i = 0; $i < 3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rt'][$i])): ?>
						<?= $individu['rt'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i = 0; $i < 3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rw'][$i])): ?>
						<?= $individu['rw'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $config['nama_desa']; ?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $config['nama_kecamatan']; ?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $config['nama_kabupaten']; ?></td>
			<td colspan="5" class="left">d. Provinsi</td>
			<td colspan="12" class="kotak"><?= $config['nama_propinsi']; ?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i = 0; $i < 5; $i++): ?>
				<td class="kotak padat tengah">
					<?= str_split($config['kode_pos'])[$i] ?: '&nbsp;' ?>
				</td>
			<?php endfor; ?>
			<td colspan="7">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i = 0; $i < 8; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['telepon_kk'][$i])): ?>
						<?= $individu['telepon_kk'][$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=10 class="kotak">9.&nbsp;&nbsp;Alasan Permohonan </td>
			<td>:</td>
			<?php for ($i = 0; $i < 1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['alasan_permohonan'])): ?>
						<?= $input['alasan_permohonan']; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=36>1. Karena Penambahan Anggota Keluarga (Kelahiran, Kedatangan)</td>
		</tr>
		<tr>
			<td colspan="12">&nbsp;</td>
			<td colspan=36>2. Karena Pengurangan Anggota Keluarga (Kematian, Kepindahan) </td>
		</tr>
		<tr>
			<td colspan="12">&nbsp;</td>
			<td colspan=6>3. Lainnya :</td>
			<td colspan=18 style="border-bottom:1px solid #000"><?= $input['alasan_lainnya']; ?></td>
		<td colspan=6> </td>
			<td colspan=6></td>
		</tr>
		<tr>
			<td colspan="10" class="kotak">10. Jumlah Anggota Keluarga</td>
			<td>&nbsp;</td>
			<?php $str_jml = str_pad((string) count($anggota_ikut ?? []), 2, '0', STR_PAD_LEFT); ?>
			<?php for ($j = 0; $j < 2; $j++): ?>
				<td class="kotak padat tengah"><?= $str_jml[$j]; ?></td>
			<?php endfor; ?>
			<td colspan="2" style="text-align: right" >orang</td>
			<td colspan="33">&nbsp;</td>
		</tr>
		<tr><td colspan="48" class="atas">&nbsp;</td></tr>

		<tr>
			<td colspan="48">
				11. <strong>DAFTAR ANGGOTA KELUARGA PEMOHON (hanya diisi anggota keluarga saja)</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2" class="abu">No.</td>
			<td>&nbsp;</td>
			<td colspan="16" class="abu">NIK</td>
			<td>&nbsp;</td>
			<td colspan="22" class="abu">Nama Lengkap</td>
			<td>&nbsp;</td>
			<td colspan="3" class="abu">SHDK*)</td>
			<td>&nbsp;</td>
		</tr>
		<?php for ($i = 0; $i < MAX_ANGGOTA_F116; $i++): ?>
			<tr>
				<td>&nbsp;</td>
				<?php $str_i = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?>
				<?php for ($j = 0; $j < 2; $j++): ?>
					<td class="kotak padat tengah"><?= $str_i[$j]; ?></td>
				<?php endfor; ?>
				<td>&nbsp;</td>
				<?php if ($i < count($anggota_ikut ?? [])): ?>
					<?php for ($j = 0; $j < 16; $j++): ?>
						<td class="kotak padat tengah">
							<?php if (isset($anggota_ikut[$i]['nik'][$j])): ?>
								<?= $anggota_ikut[$i]['nik'][$j]; ?>
							<?php else: ?>
								&nbsp;
							<?php endif; ?>
						</td>
					<?php endfor; ?>
					<td>&nbsp;</td>
					<td colspan="22" class="kotak"><?= $anggota_ikut[$i]['nama']?></td>
					<td>&nbsp;</td>
					<?php for ($j = 0; $j < 2; $j++): ?>
						<?php $str_kk_level = str_pad($anggota_ikut[$i]['kk_level'], 2, '0', STR_PAD_LEFT); ?>
						<td class="kotak padat tengah"><?= $str_kk_level[$j]; ?></td>
					<?php endfor; ?>
					<td colspan="2">&nbsp;</td>
				<?php else: ?>
					<?php for ($j = 0; $j < 16; $j++): ?>
						<td class="kotak padat ">&nbsp;</td>
					<?php endfor; ?>
					<td>&nbsp;</td>
					<td colspan="22" class="tengah kotak">&nbsp;</td>
					<td>&nbsp;</td>
					<?php for ($j = 0; $j < 2; $j++): ?>
						<td class="kotak padat tengah">&nbsp;</td>
					<?php endfor; ?>
					<td colspan="2">&nbsp;</td>
				<?php endif; ?>
			</tr>
		<?php endfor; ?>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td colspan="46" style="text-align: right">
				<?= $config['nama_desa'] ?>, <?= tgl_indo(date('Y-m-d')); ?>
			</td>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;">Mengetahui</td>
			<td colspan="15">&nbsp;</td>
			<td colspan="12" style="text-align: center;">Pemohon</td>
			<td>&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="11" style="text-align: center;">Camat</td>
			<td>&nbsp;</td>
			<td colspan="13" style="text-align: center;"><?= $penandatangan['atas_nama']?></td>
			<td colspan="22">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="11"><?= str_pad('.', 50, '.', STR_PAD_LEFT); ?></td>
			<td>&nbsp;</td>
			<td colspan="13" style="text-align: center;"><strong>(<?= padded_string_center(strtoupper($penandatangan['nama']), 30)?>)</strong></td>
			<td colspan="9">&nbsp;</td>
			<td colspan="12" style="text-align: center;"><strong>(<?= padded_string_center(strtoupper($individu['nama']), 30)?>)</strong></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="11" style="text-align: center;"><?= 'NIP&nbsp;&nbsp;:&nbsp;' . str_pad('', 40 * 6, '&nbsp;', STR_PAD_LEFT)?></td>
			<td>&nbsp;</td>
			<td colspan="13" style="text-align: center;"><?= 'NIP&nbsp;&nbsp;:&nbsp;' . $penandatangan['nip']?></td>
			<td colspan="22">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td colspan="26">&nbsp;</td>
			<td colspan="22"><strong>Tanggal Pemasukan Data</strong></td>
		</tr>
		<tr>
			<td colspan="26">&nbsp;</td>
			<td colspan="2" style="text-align: left;">Tgl.</td>
			<?php for ($j = 0; $j < 2; $j++): ?>
				<td class="kotak padat tengah">&nbsp;</td>
			<?php endfor; ?>
			<td colspan="2" style="text-align: center;">Bln.</td>
			<?php for ($j = 0; $j < 2; $j++): ?>
				<td class="kotak padat tengah">&nbsp;</td>
			<?php endfor; ?>
			<td colspan="2" style="text-align: center;">Thn.</td>
			<?php for ($j = 0; $j < 2; $j++): ?>
				<td class="kotak padat tengah">&nbsp;</td>
			<?php endfor; ?>
			<td colspan="12">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td colspan="26">&nbsp;</td>
			<td colspan="6" style="text-align: left;"><strong>Paraf Petugas</strong></td>
			<td colspan="6" class="kotak">&nbsp;</td>
			<td colspan="10">&nbsp;</td>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<?php for ($i = 0; $i < 48; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>
	</table>

</page>
