<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
	table.disdukcapil {
		font-size: 9pt;
		width: 100%;
	}

	table.disdukcapil td {
		padding: 1px 1px 1px 3px;
	}

	table.disdukcapil td.satu {
		width: 10px;
		text-align: center;
	}

	table.disdukcapil td.padat {
		padding: 0px;
		margin: 0px;
		font-size: 9pt;
	}

	table.disdukcapil td.kotak {
		border: solid 1px #000000;
	}

	table.disdukcapil td.kanan {
		text-align: right;
	}

	table.disdukcapil td.tengah {
		text-align: center;
	}

	table.pengikut {
		margin-left: 28px;
		font-size: 9pt;
		border-collapse: collapse;
		border: solid 1px black;
		width: 96%;
	}

	table.pengikut td,
	th {
		border: solid 1px black;
		padding: 1px 1px 1px 3px;
	}

	table.pengikut th {
		text-align: center;
		vertical-align: middle;
	}

	table.pengikut td.tengah {
		text-align: center;
	}

	table.kode_format {
		font-size: 12pt;
		padding: 5px 20px;
		border: solid 1px black;
	}

	table.ttd {
		font-size: 8.5pt;
		margin-top: 5px;
		width: 100%;
		border-collapse: collapse;
		padding: 0px;
	}

	table.ttd td {
		text-align: center;
	}
</style>

<page orientation="portrait" format="210x330" style="font-size: 10pt">
	<table class="kode_format" align="right">
		<tr>
			<td><strong>F-1.08</strong></td>
		</tr>
	</table>
	<p style="text-align: center; margin-top: -20px">
		<strong style="font-size: 12pt;">SURAT KETERANGAN PINDAH DATANG WNI</strong>
	</p>

	<table class="disdukcapil">
		<col style="width: 3%;">
		<col style="width: 24.4%;">
		<col span="22" style="width: 3.3%">
		<tr>
			<td colspan=2><strong>DATA DAERAH ASAL</strong></td>
			<td colspan=22>&nbsp;</td>
		</tr>
		<tr>
			<td>1.</td>
			<td>Nomor Kartu Keluarga</td>
			<?php for ($i = 0; $i < 16; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['no_kk'][$i])) : ?>
						<?= $individu['no_kk'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=6>&nbsp;</td>
		</tr>
		<tr>
			<td>2.</td>
			<td>Nama Kepala Keluarga</td>
			<td colspan=22 class="kotak"><?= $individu['kepala_kk']; ?></td>
		</tr>

		<tr>
			<td>3.</td>
			<td>Alamat</td>
			<td colspan=12 class="kotak"><?= $individu['alamat']; ?></td>
			<td colspan=2 style="text-align: center;">RT</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['rt'][$i])) : ?>
						<?= $individu['rt'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2 style="text-align: center;">RW</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['rw'][$i])) : ?>
						<?= $individu['rw'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>a. Desa/Kelurahan</td>
			<td colspan=8 class="kotak"><?= $config['nama_desa']; ?></td>
			<td colspan=4>c. Kab/Kota</td>
			<td colspan=10 class="kotak"><?= $config['nama_kabupaten']; ?></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>b. Kecamatan</td>
			<td colspan=8 class="kotak"><?= $config['nama_kecamatan']; ?></td>
			<td colspan=4>d. Provinsi</td>
			<td colspan=10 class="kotak"><?= $config['nama_propinsi']; ?></td>
		</tr>

		<tr>
			<td colspan=2>&nbsp;</td>
			<td colspan=3>Kode Pos</td>
			<?php for ($i = 0; $i < 5; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($config['kode_pos'][$i])) : ?>
						<?= $config['kode_pos'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>Telepon</td>
			<?php for ($i = 0; $i < 12; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['telepon'][$i])) : ?>
						<?= trim($input['telepon'][$i]); ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan=24>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=2><strong>DATA KEPINDAHAN</strong></td>
			<td colspan=22>&nbsp;</td>
		</tr>

		<tr>
			<td rowspan="2">1.</td>
			<td rowspan="2">Alasan Pindah</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['alasan_pindah'], "'"); ?></td>
			<td rowspan="2">&nbsp;</td>
			<td colspan=4 class="padat">1. Pekerjaan</td>
			<td colspan=4 class="padat">3. Keamanan</td>
			<td colspan=4 class="padat">5. Perumahan</td>
			<td colspan=8 class="padat">7. Lainnya (sebutkan)</td>
		</tr>

		<tr>
			<td colspan=4 class="padat">2. Pendidikan</td>
			<td colspan=4 class="padat">4. Kesehatan</td>
			<td colspan=4 class="padat">6. Keluarga</td>
			<td colspan=8 class="padat">
				<?php if ($input['alasan_lainnya']) : ?>
					<span style='text-decoration: none; border-bottom: 1px dotted black;'><?= $input['alasan_lainnya']; ?></span>
				<?php else : ?>
					..............................
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td>2.</td>
			<td>Alamat Tujuan Pindah</td>
			<td colspan=12 class="kotak"><?= $input['alamat_tujuan']; ?></td>
			<td colspan=2 style="text-align: center;">RT</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['rt_tujuan'][$i])) : ?>
						<?= $input['rt_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2 style="text-align: center;">RW</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['rw_tujuan'][$i])) : ?>
						<?= $input['rw_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>a. Desa/Kelurahan</td>
			<td colspan=8 class="kotak"><?= $input['desa_atau_kelurahan_tujuan']; ?></td>
			<td colspan=4>c. Kab/Kota</td>
			<td colspan=10 class="kotak"><?= $input['kabupaten_tujuan']; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>b. Kecamatan</td>
			<td colspan=8 class="kotak"><?= $input['kecamatan_tujuan']; ?></td>
			<td colspan=4>d. Provinsi</td>
			<td colspan=10 class="kotak"><?= $input['provinsi_tujuan']; ?></td>
		</tr>

		<tr>
			<td colspan=2>&nbsp;</td>
			<td colspan=3>Kode Pos</td>
			<?php for ($i = 0; $i < 5; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['kode_pos_tujuan'][$i])) : ?>
						<?= $input['kode_pos_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>Telepon</td>
			<?php for ($i = 0; $i < 12; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['telepon_tujuan'][$i])) : ?>
						<?= trim($input['telepon_tujuan'][$i]); ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td rowspan="2">3.</td>
			<td rowspan="2">Klasifikasi Pindah</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['klasifikasi_pindah'], "'"); ?></td>
			<td colspan=8 class="padat">1. Dalam satu Desa/Kelurahan</td>
			<td colspan=5 class="padat">3. Antar Kecamatan</td>
			<td colspan=5 class="padat">5. Antar Provinsi</td>
		</tr>
		<tr>
			<td colspan=8 class="padat">2. Antar Desa/Kelurahan</td>
			<td colspan=5 class="padat">4. Antar Kab/Kota</td>
			<td colspan=5 class="padat">&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="2">4.</td>
			<td rowspan="2">Jenis Kepindahan</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['jenis_kepindahan'], "'"); ?></td>
			<td colspan=11 class="padat">1. Kep. Keluarga</td>
			<td colspan=10 class="padat">3. Kep. Keluarga dan Sbg. Angg. Keluarga</td>
		</tr>
		<tr>
			<td colspan=11 class="padat">2. Kep. Keluarga dan Seluruh Angg. Keluarga</td>
			<td colspan=10 class="padat">4. Angg. Keluarga</td>
		</tr>

		<tr>
			<td rowspan="2">5.</td>
			<td rowspan="2">Status Nomor KK Bagi Yang Tidak Pindah</td>
			<td rowspan="2" class="kotak satu"><?= $input['status_kk_bagi_yang_tidak_pindah']; ?></td>
			<td colspan=8 class="padat">1. Numpang KK</td>
			<td colspan=13 class="padat">3. Tidak Ada Angg. Keluarga Yang Ditinggal</td>
		</tr>
		<tr>
			<td colspan=8 class="padat">2. Membuat KK Baru</td>
			<td colspan=13 class="padat">4. Nomor KK Tetap</td>
		</tr>

		<tr>
			<td>6.</td>
			<td>Status Nomor KK Bagi Yang Pindah</td>
			<td class="kotak satu"><?= $input['status_kk_bagi_yang_pindah']; ?></td>
			<td colspan=4 class="padat">1. Numpang KK</td>
			<td colspan=5 class="padat">2. Membuat KK Baru</td>
			<td colspan=13 class="padat">3. Nama Kep. Keluarga dan Nomor KK Tetap</td>
		</tr>
		<tr>
			<td>7.</td>
			<td>Rencana Tgl Pindah</td>
			<?php $tgl    = date('dd', strtotime($input['tanggal_pindah']));
            $bln = date('mm', strtotime($input['tanggal_pindah']));
            $thn = date('Y', strtotime($input['tanggal_pindah']));
            ?>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($tgl[$j])) : ?>
						<?= $tgl[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($bln[$j])) : ?>
						<?= $bln[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="12">&nbsp;</td>
		</tr>
		<tr>
			<td>8.</td>
			<td>Keluarga Yang Pindah</td>
			<td colspan=22>&nbsp;</td>
		</tr>
	</table>

	<table class="pengikut">
		<tr>
			<th style="width: 5%">NO.</th>
			<th style="width: 45%" colspan=16>NIK</th>
			<th style="width: 40%">NAMA</th>
			<th colspan=2 style="width: 10%">SHDK</th>
		</tr>

		<?php
        for ($i = 0; $i < MAX_PINDAH; $i++) :
            $nomor = $i + 1;
            if ($i < count($input['id_pengikut_pindah'] ?? [])) :
                $id       = trim($input['id_pengikut_pindah'][$i], "'");
                $penduduk = $this->penduduk_model->get_penduduk($id, true); ?>
				<tr>
					<td class="tengah"><?= $nomor; ?></td>
					<?php for ($j = 0; $j < 16; $j++) : ?>
						<td class="tengah">
							<?php if (isset($penduduk['nik'][$j])) : ?>
								<?= $penduduk['nik'][$j]; ?>
							<?php else : ?>
								&nbsp;
							<?php endif; ?>
						</td>
					<?php endfor; ?>
					<td><?= $penduduk['nama']; ?></td>
					<?php $shdk = str_pad($penduduk['kk_level'], 2, '0', STR_PAD_LEFT); ?>
					<?php for ($j = 0; $j < 2; $j++) : ?>
						<td class="tengah">
							<?= $shdk[$j]; ?>
						</td>
					<?php endfor; ?>
				</tr>

			<?php else : ?>
				<tr>
					<?php for ($k = 0; $k < 20; $k++) : ?>
						<td>&nbsp;</td>
					<?php endfor; ?>
				</tr>
			<?php endif; ?>
		<?php endfor; ?>

	</table>

	<table class="ttd">
		<col style="width:35%; text-align: left; padding-left: 60px;">
		<col style="width:30%">
		<col style="width:35%; text-align: left;">
		<tr class="pendek">
			<td>Diketahui oleh:</td>
			<td>&nbsp;</td>
			<td>Dikeluarkan oleh:</td>
		</tr>
		<tr class="pendek">
			<td>Camat</td>
			<td>Pemohon</td>
			<td><?= $penandatangan['atas_nama']?></td>
		</tr>
		<tr class="pendek">
			<td><?= str_replace(' ', '&nbsp;', 'No.                            .., tgl.       ., 20') ?></td>
			<td>&nbsp;</td>
			<td><?= str_replace(' ', '&nbsp;', "No. {$format_surat}, tgl.       ., 20") ?></td>
		</tr>
		<tr style="font-size: 8mm; line-height: normal;">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>(.........................................................)</td>
			<td><strong>(<?= padded_string_center(strtoupper($individu['kepala_kk']), 30) ?>)</strong></td>
			<td><strong>(<?= padded_string_center(strtoupper($penandatangan['nama']), 30) ?>)</strong></td>
		</tr>
		<tr>
			<td>NIP.</td>
			<td>&nbsp;</td>
			<td>NIP. <?=$penandatangan['nip']?></td>
		</tr>
	</table>

	<table class="disdukcapil">
		<col style="width: 3%;">
		<col style="width: 24.4%;">
		<col span="22" style="width: 3.3%">
		<tr>
			<td colspan=2><strong>DATA DAERAH TUJUAN</strong></td>
			<td colspan=22>&nbsp;</td>
		</tr>
		<tr>
			<td>1.</td>
			<td>Nomor Kartu Keluarga</td>
			<?php for ($i = 0; $i < 16; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['no_kk'][$i]) && $input['jenis_kepindahan_id'] != '4') : ?>
						<?= $individu['no_kk'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=6>&nbsp;</td>
		</tr>

		<tr>
			<td>2.</td>
			<td>Nama Kepala Keluarga</td>
			<td colspan=22 class="kotak"><?php if ($input['jenis_kepindahan_id'] != '4') : ?><?= $individu['kepala_kk']; ?><?php endif; ?></td>
		</tr>

		<tr>
			<td>3.</td>
			<td>NIK Kepala Keluarga</td>
			<?php for ($i = 0; $i < 16; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['nik_kk'][$i]) && $input['jenis_kepindahan_id'] != '4') : ?>
						<?= $individu['nik_kk'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=6>&nbsp;</td>
		</tr>
		<tr>
			<td>4.</td>
			<td>Status Nomor KK Bagi Yang Pindah</td>
			<td class="kotak satu"><?= $input['status_kk_bagi_yang_pindah']; ?></td>
			<td colspan=5 class="padat">1. Numpang KK</td>
			<td colspan=5 class="padat">2. Membuat KK Baru</td>
			<td colspan=11 class="padat">3. Nama Kep. Keluarga dan Nomor KK Tetap</td>
		</tr>
		<tr>
			<td>5.</td>
			<td>Tanggal Kedatangan</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($tgl[$j])) : ?>
						<?= $tgl[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($bln[$j])) : ?>
						<?= $bln[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="12">&nbsp;</td>
		</tr>
		<tr>
			<td>6.</td>
			<td>Alamat</td>
			<td colspan=12 class="kotak"><?= $input['alamat_tujuan']; ?></td>
			<td colspan=2 style="text-align: center;">RT</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['rt_tujuan'][$i])) : ?>
						<?= $input['rt_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2 style="text-align: center;">RW</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['rw_tujuan'][$i])) : ?>
						<?= $input['rw_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>a. Desa/Kelurahan</td>
			<td colspan=8 class="kotak"><?= $input['desa_atau_kelurahan_tujuan']; ?></td>
			<td colspan=4>c. Kab/Kota</td>
			<td colspan=10 class="kotak"><?= $input['kabupaten_tujuan']; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>b. Kecamatan</td>
			<td colspan=8 class="kotak"><?= $input['kecamatan_tujuan']; ?></td>
			<td colspan=4>d. Provinsi</td>
			<td colspan=10 class="kotak"><?= $input['provinsi_tujuan']; ?></td>
		</tr>

		<tr>
			<td colspan=2>&nbsp;</td>
			<td colspan=3>Kode Pos</td>
			<?php for ($i = 0; $i < 5; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['kode_pos_tujuan'][$i])) : ?>
						<?= $input['kode_pos_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>Telepon</td>
			<?php for ($i = 0; $i < 12; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['telepon_tujuan'][$i])) : ?>
						<?= trim($input['telepon_tujuan'][$i]); ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>7.</td>
			<td>Keluarga Yang Datang</td>
			<td colspan=22>&nbsp;</td>
		</tr>
	</table>

	<table class="pengikut">
		<tr>
			<th style="width: 5%">NO.</th>
			<th style="width: 45%" colspan=16>NIK</th>
			<th style="width: 40%">NAMA</th>
			<th colspan=2 style="width: 10%">SHDK</th>
		</tr>

		<?php
        for ($i = 0; $i < MAX_PINDAH; $i++) :
            $nomor = $i + 1;
            if ($i < count($input['id_pengikut_pindah'] ?? [])) :
                $id       = trim($input['id_pengikut_pindah'][$i], "'");
                $penduduk = $this->penduduk_model->get_penduduk($id, true); ?>

				<tr>
					<td class="tengah"><?= $nomor; ?></td>
					<?php for ($j = 0; $j < 16; $j++) : ?>
						<td class="tengah">
							<?php if (isset($penduduk['nik'][$j])) : ?>
								<?= $penduduk['nik'][$j]; ?>
							<?php else : ?>
								&nbsp;
							<?php endif; ?>
						</td>
					<?php endfor; ?>
					<td><?= $penduduk['nama']; ?></td>
					<?php $shdk = str_pad($penduduk['kk_level'], 2, '0', STR_PAD_LEFT); ?>
					<?php for ($j = 0; $j < 2; $j++) : ?>
						<td class="tengah">
							<?= $shdk[$j]; ?>
						</td>
					<?php endfor; ?>
				</tr>

			<?php else : ?>
				<tr>
					<?php for ($k = 0; $k < 20; $k++) : ?>
						<td>&nbsp;</td>
					<?php endfor; ?>
				</tr>
			<?php endif; ?>
		<?php endfor; ?>

	</table>

	<table class="ttd">
		<col style="width:30%">
		<col style="width:35%; text-align: left;">
		<col style="width:35%; text-align: left;">
		<tr>
			<td>&nbsp;</td>
			<td>Diketahui oleh:</td>
			<td>Diterima oleh:</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Camat</td>
			<td>Kepala Desa/Lurah</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><?= str_replace(' ', '&nbsp;', 'No                           .., tgl.       ., 20') ?></td>
			<td><?= str_replace(' ', '&nbsp;', 'No                           .., tgl.       ., 20') ?></td>
		</tr>
		<tr style="font-size: 8mm; line-height: normal;">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>(.........................................................)</td>
			<td>(.........................................................)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>NIP.</td>
			<td>NIP.</td>
		</tr>
	</table>
</page>