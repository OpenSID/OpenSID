<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
	<?php include(FCPATH . "/assets/css/lampiran-surat.css"); ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 8pt">
	<table class="disdukcapil" style="margin-top: 0px;">
		<col span="48" style="width: 2.0833%;">
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="46">
				<table align="right" style="padding: 5px 15px; border: solid 1px black;">
					<tr><td><strong>Kode. F-2.12</strong></td></tr>
				</table>
			</td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="13" class="left"><strong>PEMERINTAH KABUPATEN/KOTA</strong></td>
			<td>:</td>
			<td colspan="33"><strong><?= $config['nama_kabupaten'] ?? str_pad(".", 60, ".", STR_PAD_LEFT);  ?></strong></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="13" class="left"><strong>DINAS/KANTOR</strong></td>
			<td>:</td>
			<td colspan="23"><strong><?= $config['nama_desa'] ?? str_pad(".", 60, ".", STR_PAD_LEFT); ?></strong></td>
			<td colspan="5">Kode Wilayah</td>
			<?php for ($i=0; $i<4; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_desa'][$i])): ?>
						<?= substr($config['kode_desa'], 6, 10)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
	</table>

	<table class="disdukcapil" style="margin-top: 0px;">
		<col span="48" style="width: 2.0833%;">
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<table align="center" style="padding: 5px 15px; border: solid 1px black;">
					<tr><td><strong style="font-size: 12pt;">FORMULIR PENCATATAN PERKAWINAN</strong></td></tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="46">
				<strong>I. DATA SUAMI</strong>
			</td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">1.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_pria'][$i])): ?>
						<?= $input['nik_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">2.</td>
			<td colspan="27" class="kotak">Nomor Kartu Keluarga (Nomor KK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['kk_pria'][$i])): ?>
						<?= $input['kk_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">3.</td>
			<td colspan="27" class="kotak">Nomor Paspor</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['dokumen_pasport_pria'][$i])): ?>
						<?= $input['dokumen_pasport_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">4.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">5.</td>
			<td colspan="9" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="18" class="kotak"><?= $input['tempat_lahir_pria']?></td>
			<?php
				$tgl = date('dd', strtotime($input['tanggal_lahir_pria']));
				$bln = date('mm', strtotime($input['tanggal_lahir_pria']));
				$thn = date('Y', strtotime($input['tanggal_lahir_pria']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">6.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_pria']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_pria'][$i])): ?>
						<?= $input['rt_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_pria'][$i])): ?>
						<?= $input['rw_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_pria'][$i])): ?>
						<?= $input['telepon_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desapria'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecpria'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabpria'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsipria'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">7.</td>
			<td colspan="9" class="kotak">Pendidikan Terakhir</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="34"><?= $input['pendidikan_pria']?></td>
			<!-- <td>&nbsp;</td>
			<td colspan="9" class="left">1. Tidak/Belum Sekolah</td>
			<td colspan="9" class="left">2. Belum Tamat SD</td>
			<td colspan="9" class="left">3. SD/Sederajat</td> -->
		</tr>
		<!-- <tr>
			<td colspan="15" >&nbsp;</td>
			<td colspan="9" class="left">4. SLTP/Sederajat</td>
			<td colspan="9" class="left">5. SLTA/Sederajat</td>
			<td colspan="9" class="left">6. Diploma I/II</td>
		</tr>
		<tr>
			<td colspan="15" >&nbsp;</td>
			<td colspan="9" class="left">7. Akademi/Diploma III/S. Muda</td>
			<td colspan="9" class="left">8. Diploma IV/Strata I</td>
		</tr>
		<tr>
			<td colspan="15" >&nbsp;</td>
			<td colspan="9" class="left">9. Strata II</td>
			<td colspan="9" class="left">10. Strata III</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">8.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak"  colspan="31"><?= $input['agama_pria']?></td>
			<!-- <td>&nbsp;</td>
			<td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">9.</td>
			<td colspan="15" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="28" class="kotak"><?= $input['penghayat_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">10.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_pria'][$j])) : ?>
						<?= $input['pekerjaanid_pria'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">11.</td>
			<td colspan="9" class="kotak">Anak ke</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['anak_ke_pria'][$j])) : ?>
						<?= $input['anak_ke_pria'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">12.</td>
			<td colspan="12" class="kotak">Status Perkawinan Sebelum Nikah</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak"  colspan="31"><?= $input['status_kawin_pria']?></td>
			<!-- <td>&nbsp;</td>
			<td colspan="8" class="left">1. Belum kawin</td>
			<td colspan="8" class="left">2. Kawin</td>
			<td colspan="8" class="left">3. Cerai Hidup </td>
			<td colspan="8" class="left">4. Cerai Mati </td> -->
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">13.</td>
			<td colspan="9" class="kotak">Perkawinan Yang Ke</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['kawin_ke_pria'][$j])) : ?>
						<?= $input['kawin_ke_pria'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">14.</td>
			<td colspan="13" class="kotak">Istri Yang Ke (bagi yang poligami)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['istri_ke_bagi_pria'][$j])) : ?>
						<?= $input['istri_ke_bagi_pria'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="30">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">15.</td>
			<td colspan="9" class="kotak">Kewarganegaraan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="34"><?= $input['wn_pria']?></td>
			<!-- <td>&nbsp;</td> -->
			<!-- <td colspan="8" class="left">1. WNI</td>
			<td colspan="8" class="left">2. WNA</td> -->
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">16.</td>
			<td colspan="9" class="kotak">Kebangsaan (bagi WNA)</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['bangsa_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>II. DATA AYAH DARI SUAMI</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">17.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_ayah_pria'][$i])): ?>
						<?= $input['nik_ayah_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">18.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_ayah_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">19.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_ayah_pria']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">20.</td>
			<td colspan="12" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['penghayat_ayah_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">21.</td>
			<td colspan="18" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['tempat_lahir_ayah_pria']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_ayah_pria']));
			$bln = date('mm', strtotime($input['tanggal_lahir_ayah_pria']));
			$thn = date('Y', strtotime($input['tanggal_lahir_ayah_pria']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">22.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_ayah_pria']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_ayah_pria'][$i])): ?>
						<?= $input['rt_ayah_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_ayah_pria'][$i])): ?>
						<?= $input['rw_ayah_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_ayah_pria'][$i])): ?>
						<?= $input['telepon_ayah_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desaayah_pria'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecayah_pria'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabayah_pria'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsiayah_pria'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">23.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_ayah_pria'][$j])) : ?>
						<?= $input['pekerjaanid_ayah_pria'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>III. DATA IBU DARI SUAMI</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">24.</td>
			<td colspan="27" class="kotak">24. Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_ibu_pria'][$i])): ?>
						<?= $input['nik_ibu_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">25.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_ibu_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">26.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_ibu_pria']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">27.</td>
			<td colspan="12" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['penghayat_ibu_pria']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">28.</td>
			<td colspan="18" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['tempat_lahir_ibu_pria']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_ibu_pria']));
			$bln = date('mm', strtotime($input['tanggal_lahir_ibu_pria']));
			$thn = date('Y', strtotime($input['tanggal_lahir_ibu_pria']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">29.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_ibu_pria']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_ibu_pria'][$i])): ?>
						<?= $input['rt_ibu_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_ibu_pria'][$i])): ?>
						<?= $input['rw_ibu_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_ibu_pria'][$i])): ?>
						<?= $input['telepon_ibu_pria'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desaibu_pria'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecibu_pria'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabibu_pria'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsiibu_pria'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">30.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_ibu_pria'][$j])) : ?>
						<?= $input['pekerjaanid_ibu_pria'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>IV. DATA ISTRI</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">31.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_wanita'][$i])): ?>
						<?= $input['nik_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">32.</td>
			<td colspan="27" class="kotak">Nomor Kartu Keluarga (Nomor KK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['kk_wanita'][$i])): ?>
						<?= $input['kk_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">33.</td>
			<td colspan="27" class="kotak">Nomor Paspor</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['dokumen_pasport_wanita'][$i])): ?>
						<?= $input['dokumen_pasport_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">34.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">35.</td>
			<td colspan="9" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="18" class="kotak"><?= $input['tempat_lahir_wanita']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_wanita']));
			$bln = date('mm', strtotime($input['tanggal_lahir_wanita']));
			$thn = date('Y', strtotime($input['tanggal_lahir_wanita']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">36.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_wanita']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_wanita'][$i])): ?>
						<?= $input['rt_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_wanita'][$i])): ?>
						<?= $input['rw_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_wanita'][$i])): ?>
						<?= $input['telepon_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desawanita'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecwanita'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabwanita'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsiwanita'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">37.</td>
			<td colspan="9" class="kotak">Pendidikan Terakhir</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="34"><?= $input['pendidikan_wanita']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="9" class="left">1. Tidak/Belum Sekolah</td>
			<td colspan="9" class="left">2. Belum Tamat SD</td>
			<td colspan="9" class="left">3. SD/Sederajat</td> -->
		</tr>
		<!-- <tr>
			<td colspan="15" >&nbsp;</td>
			<td colspan="9" class="left">4. SLTP/Sederajat</td>
			<td colspan="9" class="left">5. SLTA/Sederajat</td>
			<td colspan="9" class="left">6. Diploma I/II</td>
		</tr>
		<tr>
			<td colspan="15" >&nbsp;</td>
			<td colspan="9" class="left">7. Akademi/Diploma III/S. Muda</td>
			<td colspan="9" class="left">8. Diploma IV/Strata I</td>
		</tr>
		<tr>
			<td colspan="15" >&nbsp;</td>
			<td colspan="9" class="left">9. Strata II</td>
			<td colspan="9" class="left">10. Strata III</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">38.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_wanita']?></td>
			<td>&nbsp;</td><!-- 
			<td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">39.</td>
			<td colspan="16" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="27" class="kotak"><?= $input['penghayat_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">40.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_wanita'][$j])) : ?>
						<?= $input['pekerjaanid_wanita'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">41.</td>
			<td colspan="9" class="kotak">Anak ke</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['anak_ke_wanita'][$j])) : ?>
						<?= $input['anak_ke_wanita'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">42.</td>
			<td colspan="12" class="kotak">Status Perkawinan Sebelum Nikah</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['status_kawin_wanita']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Belum kawin</td>
			<td colspan="8" class="left">2. Kawin</td>
			<td colspan="8" class="left">3. Cerai Hidup </td>
			<td colspan="8" class="left">4. Cerai Mati </td> -->
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">43.</td>
			<td colspan="9" class="kotak">Perkawinan Yang Ke</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['kawin_ke_wanita'][$j])) : ?>
						<?= $input['kawin_ke_wanita'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">44.</td>
			<td colspan="9" class="kotak">Kewarganegaraan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="34"><?= $input['wn_wanita']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. WNI</td>
			<td colspan="8" class="left">2. WNA</td> -->
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">45.</td>
			<td colspan="9" class="kotak">Kebangsaan (bagi WNA)</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['bangsa_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>V. DATA AYAH DARI ISTRI</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">46.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_ayah_wanita'][$i])): ?>
						<?= $input['nik_ayah_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">47.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_ayah_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">48.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_ayah_wanita']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">49.</td>
			<td colspan="12" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['penghayat_ayah_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">50.</td>
			<td colspan="18" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['tempat_lahir_ayah_wanita']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_ayah_wanita']));
			$bln = date('mm', strtotime($input['tanggal_lahir_ayah_wanita']));
			$thn = date('Y', strtotime($input['tanggal_lahir_ayah_wanita']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">51.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_ayah_wanita']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_ayah_wanita'][$i])): ?>
						<?= $input['rt_ayah_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_ayah_wanita'][$i])): ?>
						<?= $input['rw_ayah_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_ayah_wanita'][$i])): ?>
						<?= $input['telepon_ayah_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desaayah_wanita'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecayah_wanita'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabayah_wanita'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsiayah_wanita'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">52.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_ayah_wanita'][$j])) : ?>
						<?= $input['pekerjaanid_ayah_wanita'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>VI. DATA IBU DARI ISTRI</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">53.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_ibu_wanita'][$i])): ?>
						<?= $input['nik_ibu_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">54.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_ibu_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">55.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_ibu_wanita']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">56.</td>
			<td colspan="12" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['penghayat_ibu_wanita']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">57.</td>
			<td colspan="18" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['tempat_lahir_ibu_wanita']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_ibu_wanita']));
			$bln = date('mm', strtotime($input['tanggal_lahir_ibu_wanita']));
			$thn = date('Y', strtotime($input['tanggal_lahir_ibu_wanita']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">58.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_ibu_wanita']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_ibu_wanita'][$i])): ?>
						<?= $input['rt_ibu_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_ibu_wanita'][$i])): ?>
						<?= $input['rw_ibu_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_ibu_wanita'][$i])): ?>
						<?= $input['telepon_ibu_wanita'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desaibu_wanita'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecibu_wanita'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabibu_wanita'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsiibu_wanita'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">59.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_ibu_wanita'][$j])) : ?>
						<?= $input['pekerjaanid_ibu_wanita'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td>
		</tr>
		
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>VII. DATA SAKSI</strong>
			</td>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>DATA SAKSI I</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">60.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_saksi1'][$i])): ?>
						<?= $input['nik_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">61.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_saksi1']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">62.</td>
			<td colspan="18" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['tempat_lahir_saksi1']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_saksi1']));
			$bln = date('mm', strtotime($input['tanggal_lahir_saksi1']));
			$thn = date('Y', strtotime($input['tanggal_lahir_saksi1']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">63.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_saksi1']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">64.</td>
			<td colspan="12" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['penghayat_saksi1']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">65.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_saksi1']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_saksi1'][$i])): ?>
						<?= $input['rt_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_saksi1'][$i])): ?>
						<?= $input['rw_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_saksi1'][$i])): ?>
						<?= $input['telepon_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desasaksi1'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecsaksi1'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabsaksi1'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsisaksi1'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">66.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="34"><?= $input['pekerjaanid_saksi1']?></td>
			<td>&nbsp;</td>
			<!-- <?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_saksi1'][$j])) : ?>
						<?= $input['pekerjaanid_saksi1'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td> -->
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>DATA SAKSI II</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">67.</td>
			<td colspan="27" class="kotak">Nomor Induk Kependudukan (NIK)</td>
			<td>&nbsp;&nbsp;</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_saksi2'][$i])): ?>
						<?= $input['nik_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">68.</td>
			<td colspan="9" class="kotak">Nama Lengkap</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="34" class="kotak"><?= $input['nama_saksi2']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">69.</td>
			<td colspan="18" class="kotak">Tempat/Tanggal Lahir</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['tempat_lahir_saksi2']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lahir_saksi2']));
			$bln = date('mm', strtotime($input['tanggal_lahir_saksi2']));
			$thn = date('Y', strtotime($input['tanggal_lahir_saksi2']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">70.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="31"><?= $input['agama_saksi2']?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">71.</td>
			<td colspan="12" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['penghayat_saksi2']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">72.</td>
			<td colspan="9" class="kotak">Alamat</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="22" class="kotak"><?= $input['alamat_saksi2']?></td>
			<td colspan="3" class="tengah">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_saksi2'][$i])): ?>
						<?= $input['rt_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_saksi2'][$i])): ?>
						<?= $input['rw_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="13">&nbsp;</td>
			<td colspan="7" class="left">Kode Pos</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">&nbsp;</td>
			<td colspan="5" class="left">Telepon</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['telepon_saksi2'][$i])): ?>
						<?= $input['telepon_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">a. Desa/Kelurahan</td>
			<td colspan="11" class="kotak"><?= $input['desasaksi2'];?></td>
			<td colspan="5" class="left">b. Kecamatan</td>
			<td colspan="11" class="kotak"><?= $input['kecsaksi2'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="13" >&nbsp;</td>
			<td colspan="7" class="left">c. Kabupaten/Kota</td>
			<td colspan="11" class="kotak"><?= $input['kabsaksi2'];?></td>
			<td colspan="5" class="left">d. Propinsi</td>
			<td colspan="11" class="kotak"><?= $input['provinsisaksi2'];?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">73.</td>
			<td colspan="9" class="kotak">Pekerjaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="34"><?= $input['pekerjaanid_saksi2']?></td>
			<td>&nbsp;</td>
			<!-- <?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($input['pekerjaanid_saksi2'][$j])) : ?>
						<?= $input['pekerjaanid_saksi2'][$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="33"> Lihat tata cara pengisian formulir X JENIS PEKERJAAN No. 1 s/d 88</td> -->
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="48">
				<strong>VIII. DATA PERKAWINAN</strong>
			</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">74.</td>
			<td colspan="9" class="kotak">Tanggal Pemberkatan Perkawinan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">Tgl</td>
			<?php $tgl = date('dd', strtotime($input['tanggal_pemberkatan']));
			$bln = date('mm', strtotime($input['tanggal_pemberkatan']));
			$thn = date('Y', strtotime($input['tanggal_pemberkatan']));
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">75.</td>
			<td colspan="12" class="kotak">Hari, Tanggal, Bulan dan Tahun Melapor</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['hari_lapor']?></td>
			<?php $tgl = date('dd', strtotime($input['tanggal_lapor']));
			$bln = date('mm', strtotime($input['tanggal_lapor']));
			$thn = date('Y', strtotime($input['tanggal_lapor']));
			?>
			<td colspan="2" class="tengah">Tgl</td>
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">76.</td>
			<td colspan="9" class="kotak">Pukul</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><?= $input['jam_lapor']?></td>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">77.</td>
			<td colspan="12" class="kotak">Agama/Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td class="kotak" colspan="32"><?= $input['agama_kawin']?></td>
			<td>&nbsp;</td>
			<!-- <td colspan="8" class="left">1. Islam</td>
			<td colspan="8" class="left">2. Kristen</td>
			<td colspan="8" class="left">3. Katholik</td>
			<td colspan="8" class="left">4. Hindu</td> -->
		</tr>
		<!-- <tr>
			<td colspan="18" >&nbsp;</td>
			<td colspan="8" class="left">5. Budha</td>
			<td colspan="8" class="left">6. Khonghucu</td>
			<td colspan="8" class="left">7. Penghayat Kepercayaan</td>
			<td colspan="8" class="left">8. Lainnya</td>
		</tr> -->
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">78.</td>
			<td colspan="15" class="kotak">Nama Organisasi Penghayat Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="28" class="kotak"><?= $input['penghayat_kawin']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">79.</td>
			<td colspan="12" class="kotak">Nama Badan Peradilan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['badan_peradilan']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">80.</td>
			<td colspan="12" class="kotak">Nomor Putusan Penetapan Pengadilan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['nomor_putusan']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">81.</td>
			<td colspan="12" class="kotak">Tanggal Putusan Penetapan Pengadilan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">Tgl</td>
			<?php $tgl = date('dd', strtotime($input['tanggal_putusan']));
			$bln = date('mm', strtotime($input['tanggal_putusan']));
			$thn = date('Y', strtotime($input['tanggal_putusan']));
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
			<td colspan="2" class="tengah">Bln</td>
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
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					<?php if (isset($thn[$j])) : ?>
						<?= $thn[$j]; ?>
					<?php else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">82.</td>
			<td colspan="12" class="kotak">Nama Pemuka Agama/Pghyt Kepercayaan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['nama_pemuka']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">83.</td>
			<td colspan="12" class="kotak">Ijin Perwakilan bagi WNA / Nomor</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['ijin_wna']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">84.</td>
			<td colspan="12" class="kotak">Jumlah Anak Yang Telah Diakui dan Disahkan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $input['jumlah_anak']?></td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">85.</td>
			<td colspan="6" class="kotak">Nama Anak</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">1.</td>
			<td colspan="35" class="kotak"><?= $input['nama_anak1']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9"></td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">2.</td>
			<td colspan="35" class="kotak"><?= $input['nama_anak2']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9"></td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">3.</td>
			<td colspan="35" class="kotak"><?= $input['nama_anak3']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9"></td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">4.</td>
			<td colspan="35" class="kotak"><?= $input['nama_anak4']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9"></td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">5.</td>
			<td colspan="35" class="kotak"><?= $input['nama_anak5']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9"></td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">6.</td>
			<td colspan="35" class="kotak"><?= $input['nama_anak6']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>

		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">86.</td>
			<td colspan="9" class="kotak">No, Tgl. Akta Kelahiran</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">1. No.</td>
			<td colspan="15" class="kotak"><?= $input['no_akta_anak1']?></td>
			<td colspan="2" class="tengah">Tgl.</td>
			<td colspan="15" class="kotak"><?= $input['tgl_akta_anak1']?></td>
		</tr>
		<tr>
			<td colspan="13"></td>
			<td colspan="2" class="tengah">2. No.</td>
			<td colspan="15" class="kotak"><?= $input['no_akta_anak2']?></td>
			<td colspan="2" class="tengah">Tgl.</td>
			<td colspan="15" class="kotak"><?= $input['tgl_akta_anak2']?></td>
		</tr>
		<tr>
			<td colspan="13"></td>
			<td colspan="2" class="tengah">3. No.</td>
			<td colspan="15" class="kotak"><?= $input['no_akta_anak3']?></td>
			<td colspan="2" class="tengah">Tgl.</td>
			<td colspan="15" class="kotak"><?= $input['tgl_akta_anak3']?></td>
		</tr>
		<tr>
			<td colspan="13"></td>
			<td colspan="2" class="tengah">4. No.</td>
			<td colspan="15" class="kotak"><?= $input['no_akta_anak4']?></td>
			<td colspan="2" class="tengah">Tgl.</td>
			<td colspan="15" class="kotak"><?= $input['tgl_akta_anak4']?></td>
		</tr>
		<tr>
			<td colspan="13"></td>
			<td colspan="2" class="tengah">5. No.</td>
			<td colspan="15" class="kotak"><?= $input['no_akta_anak5']?></td>
			<td colspan="2" class="tengah">Tgl.</td>
			<td colspan="15" class="kotak"><?= $input['tgl_akta_anak5']?></td>
		</tr>
		<tr>
			<td colspan="13"></td>
			<td colspan="2" class="tengah">6. No.</td>
			<td colspan="15" class="kotak"><?= $input['no_akta_anak6']?></td>
			<td colspan="2" class="tengah">Tgl.</td>
			<td colspan="15" class="kotak"><?= $input['tgl_akta_anak6']?></td>
		</tr>
		<tr><td colspan="48" class="">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="9" class="kotak"><strong>DATA ADMINISTRASI</strong></td>
			<td colspan="37"></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="10" class=""><strong><i>Hanya Diisi oleh petugas</i></strong></td>
			<td colspan="36"></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">87.</td>
			<td colspan="9" class="kotak">Persyaratan</td>
			<td colspan="35"></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Surat Keterangan Perkawinan dari Pemuka Agama / Penghayat Kepercayaan / Salinan Penetapan Pengadilan</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Fotocopy Kutipan Akta Kelahiran</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Surat Keterangan dari Desa/Kelurahan</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Fotocopy KTP/KK yang Dilegalisir Pejabat yang Berwenang</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Pas Photo Berdampingan ukuran 4x6 cm sebanyak 3 (tiga) lembar</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">2 (dua) orang saksi yang telah berusia 21 tahun keatas</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Fotocopy Kutipan Akta Kelahiran Anak yang akan diakui / disyahkan.</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Fotocopy Akta Perceraian/Kematian jika yang bersangkutan telah pernah kawin</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Ijin dari Komandan bagi anggota TNI/POLRI</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Perjanjian Perkawinan</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">STMD dari Kepolisian</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Surat ijin dari Isteri bagi yang berpoligami.</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Surat ijin dari Pengadilan Negeri bagi yang berpoligami.</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Surat ijin dari Perwakilan Negara Asing yang bersangkutan</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">Paspor / dokumen Keimigrasian</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="kotak"></td>
			<td colspan="45">SKTT dari Dinas Kependudukan dan Catatan Sipil</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">88.</td>
			<td colspan="12" class="kotak">Nomor Akta Perkawinan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $individu['alamat']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">89.</td>
			<td colspan="12" class="kotak">Tanggal Akta Perkawinan</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">Tgl</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
						&nbsp;
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="2" class="tengah">Bln</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">90.</td>
			<td colspan="12" class="kotak">Tanggal Cetak Kutipan Akta</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">Tgl</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="2" class="tengah">Bln</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">91.</td>
			<td colspan="12" class="kotak">Nama Petugas Entri Data</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="31" class="kotak"><?= $individu['alamat']?></td>
			<td>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="kotak padat tengah">92.</td>
			<td colspan="12" class="kotak">Tanggal Entri Data</td>
			<td>&nbsp;&nbsp;</td>
			<td colspan="2" class="tengah">Tgl</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="2" class="tengah">Bln</td>
			<?php for ($j = 0; $j < 2; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td>&nbsp;</td>
			<td colspan="2" class="tengah">Thn</td>
			<?php for ($j = 0; $j < 4; $j++) : ?>
				<td class="kotak tengah">
					&nbsp;
				</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
		</tr>


		<tr><td colspan="46" class="">&nbsp;</td></tr>

		<tr>
			<td colspan="46" style="text-align: right">
				<?= str_pad(".",40,".",STR_PAD_LEFT);?>, <?= str_pad(".", 60, ".", STR_PAD_LEFT);?>
			</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;">Mengetahui</td>
			<td colspan="15">&nbsp;</td>
			<td colspan="12" style="text-align: center;">Pelapor</td>
			<td>&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="11" style="text-align: center;"><?= $this->penandatangan_lampiran($data);?></td>
			<td>&nbsp;</td>
			<td colspan="13" style="text-align: center;">Petugas Pencatat</td>
			<td colspan="22">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="11" style="text-align: center;"><strong>(<?= padded_string_center(strtoupper($kepala_desa['nama']), 30)?>)</strong></td>
			<td>&nbsp;</td>
			<td colspan="13" style="text-align: center;"><strong>(<?= padded_string_center(strtoupper($individu['nama']), 30)?>)</strong></td>
			<td colspan="9">&nbsp;</td>
			<td colspan="12" style="text-align: center;"><strong>(<?= padded_string_center(strtoupper($individu['nama']), 30)?>)</strong></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="11" style="text-align: center;"><?= "NIP&nbsp;&nbsp;:&nbsp;".str_pad("",40*6,"&nbsp;",STR_PAD_LEFT)?></td>
			<td>&nbsp;</td>
			<td colspan="13" style="text-align: center;"><?= "NIP&nbsp;&nbsp;:&nbsp;".$kepala_desa['pamong_nip']?></td>
			<td colspan="22">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<?php for ($i=0; $i<48; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>

		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr><td colspan="48">&nbsp;</td></tr>
	</table>
</page>
