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

	table.pengikut th.border-kolom {
		border-top: solid 1px white;
		border-bottom: solid 1px white;
		background-color: white;
	}

	table.pengikut td.border-kolom {
		border-top: solid 1px white;
		border-bottom: solid 1px white;
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
			<td><strong>F-1.03</strong></td>
		</tr>
	</table>
	<p style="text-align: center; margin-top: -20px">
		<strong style="font-size: 12pt;">FORMULIR PENDAFTARAN PERPINDAHAN PENDUDUK</strong>
	</p>

	<table class="disdukcapil">
		<col style="width: 3%;">
		<col style="width: 24.4%;">
		<col span="22" style="width: 3.3%">
		<tr>
			<td colspan=2><strong>Perhatian</strong></td>
			<td colspan=22>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=12>Harap diisi dengan huruf cetak dan menggunakan tinta hitam</td>
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
			<td>Nama Lengkap Pemohon</td>
			<td colspan=22 class="kotak"><?= $individu['kepala_kk']; ?></td>
		</tr>
		<tr>
			<td>3.</td>
			<td>NIK</td>
			<?php for ($i = 0; $i < 16; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['nik'][$i])) : ?>
						<?= $individu['nik'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=6>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="3">4.</td>
			<td rowspan="3">Jenis Permohonan</td>
			<td rowspan="3" class="kotak satu"><?= trim($input['jenis_permohonan_id'], "'"); ?></td>
			<td colspan=11 class="padat">1. Surat Keterangan Kependudukan</td>
			<td colspan=11 class="padat">4. Surat Keterangan Tempat Tinggal (SKTT) </td>
		</tr>
		<tr>
			<td colspan=11 class="padat">2. Surat Keterangan Pindah</td>
			<td colspan=11 class="padat">5. Bagi Orang Asing Tinggal Terbatas</td>
		</tr>
		<tr>
			<td colspan=11 class="padat">3. Surat Keterangan Pindah Luar Negeri (SKPLN)</td>
		</tr>
		<tr>
			<td>5.</td>
			<td>Alamat Asal</td>
			<td colspan=12 class="kotak"><?= $individu['alamat']; ?></td>
			<td colspan=2 style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;RT</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($individu['rt'][$i])) : ?>
						<?= $individu['rt'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=1>&nbsp;</td>
			<td colspan=1 style="text-align: center;">RW</td>
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
			<td colspan=1>&nbsp;</td>
			<td colspan=4>c. Kab/Kota</td>
			<td colspan=10 class="kotak"><?= $config['nama_kabupaten']; ?></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>b. Kecamatan</td>
			<td colspan=8 class="kotak"><?= $config['nama_kecamatan']; ?></td>
			<td colspan=1>&nbsp;</td>
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
		</tr>
		<tr>
			<td colspan=22>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="2">6.</td>
			<td rowspan="2">Klasifikasi Kepindahan</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['klasifikasi_pindah_id'], "'"); ?></td>
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
			<td>7.</td>
			<td>Alamat Tujuan Pindah</td>
			<td colspan=12 class="kotak"><?= $input['alamat_tujuan']; ?></td>
			<td colspan=2 style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;RT</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['rt_tujuan'][$i])) : ?>
						<?= $input['rt_tujuan'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2 style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;RW</td>
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
			<td colspan=8 class="kotak"><?= $input['desa_tujuan']; ?></td>
			<td colspan=1>&nbsp;</td>
			<td colspan=4>c. Kab/Kota</td>
			<td colspan=10 class="kotak"><?= $input['kabupaten_tujuan']; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>b. Kecamatan</td>
			<td colspan=8 class="kotak"><?= $input['kecamatan_tujuan']; ?></td>
			<td colspan=1>&nbsp;</td>
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
		</tr>

		<tr>
			<td rowspan="2">8.</td>
			<td rowspan="2">Alasan Pindah</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['alasan_pindah_id'], "'"); ?></td>
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
				<?php if ($input['sebut_alasan']) : ?>
					<span style='text-decoration: none; border-bottom: 1px dotted black;'><?= $input['sebut_alasan']; ?></span>
				<?php else : ?>
					..............................
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td rowspan="2">9.</td>
			<td rowspan="2">Jenis Kepindahan</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['jenis_kepindahan_id'], "'"); ?></td>
			<td colspan=11 class="padat">1. Kep. Keluarga</td>
			<td colspan=10 class="padat">3. Kep. Keluarga dan Sbg. Angg. Keluarga</td>
		</tr>
		<tr>
			<td colspan=11 class="padat">2. Kep. Keluarga dan Seluruh Angg. Keluarga</td>
			<td colspan=10 class="padat">4. Angg. Keluarga</td>
		</tr>

		<tr>
			<td rowspan="1">10.</td>
			<td rowspan="1">Anggota Keluarga Yang Tidak Pindah</td>
			<td rowspan="1" class="kotak satu"><?= $input['status_kk_tidak_pindah_id']; ?></td>
			<td colspan=8 class="padat">1. Numpang KK</td>
			<td colspan=8 class="padat">2. Membuat KK Baru</td>
		</tr>

		<tr>
			<td>11.</td>
			<td>Anggota Keluarga Yang Pindah</td>
			<td class="kotak satu"><?= $input['status_kk_pindah_id']; ?></td>
			<td colspan=8 class="padat">1. Numpang KK</td>
			<td colspan=8 class="padat">2. Membuat KK Baru</td>
		</tr>
		<tr>
			<td>12.</td>
			<td colspan=22>Daftar Anggota Keluarga Yang Pindah</td>
			<td colspan=22>&nbsp;</td>
		</tr>
	</table>

	<table class="pengikut">
		<tr bgcolor="#C2C2C2">
			<th colspan=2 style="width: 10%">NO.</th>
			<th colspan=1 class="border-kolom">&nbsp;&nbsp;</th>
			<th style="width: 45%" colspan=16>NIK</th>
			<th colspan=1 class="border-kolom">&nbsp;&nbsp;</th>
			<th style="width: 30%">NAMA LENGKAP</th>
			<th colspan=1 class="border-kolom">&nbsp;&nbsp;</th>
			<th colspan=2 style="width: 10%">SHDK</th>
		</tr>

		<?php
		for ($i = 0; $i < MAX_PINDAH; $i++) :
			$nomor = $i + 1;
			if ($i < count($input['id_cb'])) :
				$id = trim($input['id_cb'][$i], "'");
				$penduduk = $this->penduduk_model->get_penduduk($id, TRUE); ?>
				<tr>
					<?php $nourut = str_pad($nomor, 2, "0", STR_PAD_LEFT); ?>
					<?php for ($j = 0; $j < 2; $j++) : ?>
						<td class="tengah">
							<?= $nourut[$j]; ?>
						</td>
					<?php endfor; ?>
					<td colspan=1 class="border-kolom">&nbsp;&nbsp;</td>
					<?php for ($j = 0; $j < 16; $j++) : ?>
						<td class="tengah">
							<?php if (isset($penduduk['nik'][$j])) : ?>
								<?= $penduduk['nik'][$j]; ?>
							<?php else : ?>
								&nbsp;
							<?php endif; ?>
						</td>
					<?php endfor; ?>
					<td colspan=1 class="border-kolom">&nbsp;&nbsp;</td>
					<td><?= $penduduk['nama']; ?></td>
					<td colspan=1 class="border-kolom">&nbsp;&nbsp;</td>
					<?php $shdk = str_pad($penduduk['kk_level'], 2, "0", STR_PAD_LEFT); ?>
					<?php for ($j = 0; $j < 2; $j++) : ?>
						<td class="tengah">
							<?= $shdk[$j]; ?>
						</td>
					<?php endfor; ?>
				</tr>

			<?php else : ?>
				<tr>
					<td>&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;</td>
					<td colspan=1 class="border-kolom">&nbsp;&nbsp;</td>
					<?php for ($k = 0; $k < 16; $k++) : ?>
						<td>&nbsp;&nbsp;</td>
					<?php endfor; ?>
					<td colspan=1 class="border-kolom">&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;</td>
					<td colspan=1 class="border-kolom">&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;</td>
				</tr>
			<?php endif; ?>
		<?php endfor; ?>

	</table>

	<table class="disdukcapil">
		<col style="width: 3%;">
		<col style="width: 24.4%;">
		<col span="22" style="width: 3.3%">

		<tr>
			<td colspan=22>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=22><strong>Diisi oleh Penduduk (Orang Asing) Pemegang ITAS yang mengajukan SKTT dan OA Pemegang ITAP yang Mengajukan Surat Keterangan Kependudukan Lainnya</strong></td>
		</tr>

		<tr>
			<td>13.</td>
			<td>Nama Sponsor</td>
			<td colspan=20 class="kotak"><?= $input['nama_sponsor']; ?></td>
		</tr>

		<tr>
			<td rowspan="2">14.</td>
			<td rowspan="2">Tipe Sponsor</td>
			<td rowspan="2" class="kotak satu"><?= trim($input['tipe_sponsor_id'], "'"); ?></td>
			<td colspan=8 class="padat">1. Organisasi Internasional</td>
			<td colspan=5 class="padat">3. Pemerintah</td>
			<td colspan=5 class="padat">5. Perusahaan</td>
		</tr>
		<tr>
			<td colspan=8 class="padat">2. Perorangan</td>
			<td colspan=5 class="padat">4. Tanpa Sponsor</td>
			<td colspan=5 class="padat">&nbsp;</td>
		</tr>

		<tr>
			<td>15.</td>
			<td>Alamat Sponsor</td>
			<td colspan=20 class="kotak"><?= $input['alamat_sponsor']; ?></td>
		</tr>

		<tr>
			<td rowspan="2">16.</td>
			<td rowspan="2">Nomor dan tanggal KITAS & KITAP</td>
			<?php for ($i = 0; $i < 10; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['nomor_itas'][$i])) : ?>
						<?= $input['nomor_itas'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>&nbsp;</td>
			<?php $tgl = date('dd', strtotime($input['tanggal_itas']));
			$bln = date('mm', strtotime($input['tanggal_itas']));
			$thn = date('Y', strtotime($input['tanggal_itas']));
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
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($bln[$j])) : ?>
						<?= $bln[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan=4>&nbsp;</td>
			<td colspan=2>Nomor</td>
			<td colspan=8>&nbsp;</td>
			<td colspan=8>Tanggal Masa Berlaku</td>
		</tr>

		<tr>
			<td colspan=22>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=22><strong>Diisi oleh Penduduk yang Mengajukan Surat Keterangan Pindah Luar Negeri</strong></td>
		</tr>

		<tr>
			<td>17.</td>
			<td>Negara Tujuan</td>
			<td colspan=16 class="kotak"><?= $input['negara_tujuan']; ?></td>
			<td colspan=1>&nbsp;</td>
			<?php for ($i = 0; $i < 3; $i++) : ?>
				<td class="kotak satu">
					<?php if (isset($input['kode_negara'][$i])) : ?>
						<?= $input['kode_negara'][$i]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>

		<tr>
			<td colspan=19>&nbsp;</td>
			<td colspan=4>Kode Negara</td>
		</tr>

		<tr>
			<td>18.</td>
			<td>Alamat Tujuan</td>
			<td colspan=20 class="kotak"><?= $input['alamat_tujuan_luar_negeri']; ?></td>
		</tr>

		<tr>
			<td>19.</td>
			<td>Penanggung Jawab</td>
			<td colspan=20 class="kotak"><?= $input['penanggungjawab']; ?></td>
		</tr>

		<tr>
			<td>20.</td>
			<td>Rencana Pindah Tanggal</td>
			<?php $tgl = date('dd', strtotime($input['tanggal_pindah']));
			$bln = date('mm', strtotime($input['tanggal_pindah']));
			$thn = date('Y', strtotime($input['tanggal_pindah']));
			?>
			<td>Tgl</td>
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
			<td>Bln</td>
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
			<td>Thn</td>
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
			<td colspan=22>&nbsp;</td>
			<td colspan=22>&nbsp;</td>
		</tr>
	</table>

	<table class="ttd">
		<col style="width:50%; text-align: center; padding-left: 60px;">
		<col style="width:50%; text-align: center;">
		<tr class="pendek">
			<td>Mengetahui,</td>
			<td>&nbsp;</td>
		</tr>
		<tr class="pendek">
			<td>Kepala Dinas</td>
			<td>&nbsp;</td>
		</tr>
		<tr class="pendek">
			<td>Kependudukan dan Pencatatan Sipil</td>
			<td>.............,&nbsp;............. &nbsp;20...............</td>
		</tr>
		<tr class="pendek">
			<td>Kabupaten <?= $config['nama_kabupaten']; ?></td>
			<td>Pemohon</td>
		</tr>
		<tr style="font-size: 8mm; line-height: normal;">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr style="font-size: 8mm; line-height: normal;">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>(.........................................................)</td>
			<td><strong>(<?= padded_string_center(strtoupper($individu['kepala_kk']), 30) ?>)</strong></td>
		</tr>
	</table>

</page>