<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	<?php include(FCPATH . "/assets/css/lampiran-surat.css"); ?>
</style>

<page orientation="portrait" format="215x330" style="font-size: 7pt">
	<table id="kode" align="right">
		<tr><td><strong>Kode . F-2.29</strong></td></tr>
	</table>
	<table id="kop" class="disdukcapil">
		<col span="48" style="width: 2.0833%;">
		<tr><td colspan=48>&nbsp;</td></tr>
		<tr>
			<td colspan="10">Pemerintah Desa/Kelurahan</td>
			<td>: </td>
			<td colspan="7"><?= $config['nama_desa'];?></td>
			<td colspan="13">&nbsp;</td>
			<td colspan="3">Ket : </td>
			<td colspan="4">Lembar 1</td>
			<td>: </td>
			<td colspan="9">Untuk Yang Bersangkutan</td>
		</tr>
		<tr>
			<td colspan="10">Kecamatan</td>
			<td>: </td>
			<td colspan="7"><?= $config['nama_kecamatan'];?></td>
			<td colspan="16">&nbsp;</td>
			<td colspan="4">Lembar 2</td>
			<td>: </td>
			<td colspan="9">Untuk UPTD/Instansi Pelaksana</td>
		</tr>
		<tr>
			<td colspan="10">Kabupaten/Kota</td>
			<td>:</td>
			<td colspan="7"><?= $config['nama_kabupaten'];?></td>
			<td colspan="16">&nbsp;</td>
			<td colspan="4">Lembar 3</td>
			<td>: </td>
			<td colspan="9">Untuk Desa/Kelurahan</td>
		</tr>
		<tr>
			<td colspan="11">&nbsp;</td>
			<?php for ($i=0; $i<10; $i++): ?>
				<td style="border-bottom: 1px solid black;">&nbsp;</td>
			<?php endfor; ?>
			<td colspan="13">&nbsp;</td>
			<td colspan="4">Lembar 4</td>
			<td>: </td>
			<td colspan="9">Untuk Kecamatan</td>
		</tr>
		<tr>
			<td colspan="10">Kode Wilayah</td>
			<td style="border-right: 1px solid black;">:</td>
			<?php for ($i=0; $i<10; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_desa'][$i])): ?>
						<?= $config['kode_desa'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="27">&nbsp;</td>
		</tr>
		<!-- Untuk memaksa penampilan setiap kolom -->
		<tr>
			<?php for ($i=0; $i<48; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>
	</table>
	<p style="text-align: center; margin-top: 2px;">
		<strong style="font-size: 10pt;">SURAT KETERANGAN KEMATIAN </strong>
	</p>
	<table class="disdukcapil" style="margin-top: -15px; border: 0px;">
		<col span="48" style="width: 2.0833%;">
		<!-- Untuk memaksa penampilan setiap kolom -->
		<tr>
			<?php for ($i=0; $i<48; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="10">Nama Kepala Keluarga</td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['kepala_kk'][$i])): ?>
						<?= $individu['kepala_kk'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">Nomor Kartu Keluarga</td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['no_kk'][$i])): ?>
						<?= $individu['no_kk'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21">&nbsp;</td>
		</tr>
	</table>
	<table class="disdukcapil" style="margin-top: 0px;">
		<col span="48" style="width: 2.0833%;">
		<!-- Jenazah -->
		<tr>
			<td colspan=48><strong>JENAZAH </strong></td>
		</tr>
		<tr>
			<td colspan="10">1.&nbsp;&nbsp;&nbsp;&nbsp;NIK </td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['nik'][$i])): ?>
						<?= $individu['nik'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">2.&nbsp;&nbsp;&nbsp;&nbsp;Nama Lengkap</td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['nama'][$i])): ?>
						<?= $individu['nama'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">3.&nbsp;&nbsp;&nbsp;&nbsp;Jenis Kelamin </td>
			<td>:</td>
				<td class="kotak padat tengah">
					<?= $individu['sex_id'];?>
				</td>
			<td colspan=5>1. Laki-laki </td>
			<td colspan=7>2. Perempuan </td>
			<td colspan=4>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">4.&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Lahir / Umur </td>
			<td>:</td>
				<?php $tgl = date('dd',strtotime($individu['tanggallahir']));
				$bln = date('mm',strtotime($individu['tanggallahir']));
				$thn = date('Y',strtotime($individu['tanggallahir']));
			?>
			<td colspan="4">Tanggal :</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5" class="kanan">Umur : </td>
			<?php for ($j=0; $j<3; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['umur'][$j])): ?>
						<?= $individu['umur'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=10>5.&nbsp;&nbsp;&nbsp;&nbsp;Tempat Lahir </td>
			<td>:</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['tempatlahir'][$i])): ?>
						<?= $individu['tempatlahir'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="25">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">6.&nbsp;&nbsp;&nbsp;&nbsp;Agama </td>
			<td>:</td>
			<td class="kotak padat tengah">
				<?php if (isset($individu['agama_id'][0])): ?>
					<?= $individu['agama_id'][0];?>
				<?php else: ?>
					&nbsp;
				<?php endif; ?>
			</td>
			<td colspan=4>1. Islam</td>
			<td colspan=6>2. Kristen </td>
			<td colspan=6>3. Katolik </td>
			<td colspan=5>4. Hindu </td>
			<td colspan=5>5. Budha </td>
			<td colspan=6>6. Lainnya </td>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">7.&nbsp;&nbsp;&nbsp;&nbsp;Pekerjaan</td>
			<td>:</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['pekerjaan_id'][$j])): ?>
						<?= $individu['pekerjaan_id'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21" class="kotak"><?= $individu['pekerjaan']?></td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">8.&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $individu['alamat'].ucwords(strtolower($this->setting->sebutan_dusun))." ".ucwords(strtolower($individu['dusun']))?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rt'][$i])): ?>
						<?= $individu['rt'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rw'][$i])): ?>
						<?= $individu['rw'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $config['nama_desa'];?></td>
			<td colspan="5">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $config['nama_kecamatan'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $config['nama_kabupaten'];?></td>
			<td colspan="5">d. Propinsi</td>
			<td colspan="12" class="kotak"><?= $config['nama_propinsi'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">9.&nbsp;&nbsp;&nbsp;&nbsp;Anak ke </td>
			<td>:</td>
			<td class="kotak padat tengah">
				<?= $input['anakke']; ?>
			</td>
			<td>&nbsp;</td>
			<td colspan=6>1,  2,  3,  4,  </td>
			<td colspan=29>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">10.&nbsp;&nbsp;Tanggal Kematian </td>
			<td>:</td>
			<?php $tgl = date('dd',strtotime($input['tanggal_mati']));
				$bln = date('mm',strtotime($input['tanggal_mati']));
				$thn = date('Y',strtotime($input['tanggal_mati']));
			?>
			<td colspan="4">Tanggal : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="12">&nbsp;</td>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">11.&nbsp;&nbsp;Pukul </td>
			<td>:</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['jam'][$i])): ?>
						<?= $input['jam'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=10>12.&nbsp;&nbsp;Sebab Kematian </td>
			<td>:</td>
			<?php for ($i=0; $i<1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['sebab'][$i])): ?>
						<?= $input['sebab'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=6>1. Sakit biasa / tua</td>
			<td colspan=6>2. Wabah Penyakit </td>
			<td colspan=6>3. Kecelakaan </td>
			<td colspan=18>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="12">&nbsp;</td>
			<td colspan=6>4. Kriminalitas </td>
			<td colspan=6>5. Bunuh Diri </td>
			<td colspan=6>6. Lainnya </td>
			<td colspan=18>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">13.&nbsp;&nbsp;Tempat Kematian </td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['tempat_mati'][$i])): ?>
						<?= $input['tempat_mati'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="10">14.&nbsp;&nbsp;Yang menerangkan </td>
			<td>:</td>
			<?php for ($i=0; $i<1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['penolong_mati'][$i])): ?>
						<?= $input['penolong_mati'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=4>1. Dokter </td>
			<td colspan=8>2. Tenaga Kesehatan </td>
			<td colspan=6>3. Kepolisian </td>
			<td colspan=5>4. Lainnya </td>
			<td colspan=5>&nbsp;</td>
		</tr>
		<tr><td colspan=48 class="bawah"></td></tr>
		<!-- AYAH -->
		<tr>
			<td colspan=48><strong>AYAH</strong></td>
		</tr>
		<tr>
			<td colspan="10">1.&nbsp;&nbsp;&nbsp;&nbsp;NIK</td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_ayah'][$i])): ?>
						<?= $input['nik_ayah'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">2.&nbsp;&nbsp;&nbsp;&nbsp;Nama </td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nama_ayah'][$i])): ?>
						<?= $input['nama_ayah'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">3.&nbsp;&nbsp;&nbsp;Tanggal Lahir </td>
			<td>:</td>
			<?php if (!empty($input['tanggal_lahir_ayah'])):
				$tgl = date('dd',strtotime($input['tanggal_lahir_ayah']));
				$bln = date('mm',strtotime($input['tanggal_lahir_ayah']));
				$thn = date('Y',strtotime($input['tanggal_lahir_ayah']));
			else:
				unset($tgl); unset($bln); unset($thn);
			endif;
			?>
			<td colspan="4">Tanggal :</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
		 <td colspan="5" class="kanan">Umur : </td>
			<?php for ($j=0; $j<3; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['umur_ayah'][$j])): ?>
						<?= $input['umur_ayah'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">4.&nbsp;&nbsp;&nbsp;&nbsp;Pekerjaan</td>
			<td>:</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['pekerjaanid_ayah'][$j])): ?>
						<?= $input['pekerjaanid_ayah'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21" class="kotak"><?= $input['pekerjaanayah']?></td>
			<td colspan="3" class="tengah">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">5.&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $input['alamat_ayah']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_ayah'][$i])): ?>
						<?= $input['rt_ayah'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_ayah'][$i])): ?>
						<?= $input['rw_ayah'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $input['desaayah'];?></td>
			<td colspan="5">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $input['kecayah'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $input['kabayah'];?></td>
			<td colspan="5">d. Propinsi</td>
			<td colspan="12" class="kotak"><?= $input['provinsiayah'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
<!-- AKHIR AYAH -->
		<tr>
			<td colspan=48 class="bawah"></td>
		</tr>
<!-- AWAL IBU -->
		<tr>
			<td colspan=48><strong>IBU</strong></td>
		</tr>
		<tr>
			<td colspan="10">1.&nbsp;&nbsp;&nbsp;&nbsp;NIK</td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_ibu'][$i])): ?>
						<?= $input['nik_ibu'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">2.&nbsp;&nbsp;&nbsp;&nbsp;Nama </td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nama_ibu'][$i])): ?>
						<?= $input['nama_ibu'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">3.&nbsp;&nbsp;&nbsp;&nbsp;Tanggal lahir </td>
			<td>:</td>
			<?php if (!empty($input['tanggal_lahir_ibu'])) {
				$tgl = date('dd',strtotime($input['tanggal_lahir_ibu']));
				$bln = date('mm',strtotime($input['tanggal_lahir_ibu']));
				$thn = date('Y',strtotime($input['tanggal_lahir_ibu']));
				}
				else
				{ unset($tgl); unset($bln); unset($thn); }
			?>
			<td colspan="4">Tanggal :</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">Umur : </td>
			<?php for ($j=0; $j<3; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['umur_ibu'][$j])): ?>
						<?= $input['umur_ibu'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">4.&nbsp;&nbsp;&nbsp;&nbsp;Pekerjaan</td>
			<td>:</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['pekerjaanid_ibu'][$j])): ?>
						<?= $input['pekerjaanid_ibu'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21" class="kotak"><?= $input['pekerjaanibu']?></td>
			<td colspan="3" class="tengah">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">5.&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $input['alamat_ibu']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_ibu'][$i])): ?>
						<?= $input['rt_ibu'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_ibu'][$i])): ?>
						<?= $input['rw_ibu'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $input['desaibu'];?></td>
			<td colspan="5">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $input['kecibu'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $input['kabibu'];?></td>
			<td colspan="5">d. Propinsi</td>
			<td colspan="12" class="kotak"><?= $input['provinsiibu'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
<!-- AKHIR IBU -->
		<tr>
			<td colspan=48 class="bawah"></td>
		</tr>
<!-- AWAL PELAPOR -->
		<tr>
			<td colspan=48><strong>PELAPOR</strong></td>
		</tr>
		<tr>
			<td colspan="10">1.&nbsp;&nbsp;&nbsp;&nbsp;NIK</td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_pelapor'][$i])): ?>
						<?= $input['nik_pelapor'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">2.&nbsp;&nbsp;&nbsp;&nbsp;Nama Lengkap</td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nama_pelapor'][$i])): ?>
						<?= $input['nama_pelapor'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">3.&nbsp;&nbsp;&nbsp;&nbsp;Tanggal lahir / Umur </td>
			<td>:</td>
				<?php $tgl = date('dd',strtotime($input['tanggal_lahir_pelapor']));
					$bln = date('mm',strtotime($input['tanggal_lahir_pelapor']));
					$thn = date('Y',strtotime($input['tanggal_lahir_pelapor']));
				?>
			<td colspan="4">Tanggal :</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5" class="kanan">Umur : </td>
			<?php for ($j=0; $j<3; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['umur_pelapor'][$j])): ?>
						<?= $input['umur_pelapor'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">4.&nbsp;&nbsp;&nbsp;&nbsp;Jenis Kelamin </td>
			<td>:</td>
				<td class="kotak padat tengah">
					<?= $input['jkpelapor'];?>
				</td>
			<td colspan=5>1. Laki-laki </td>
			<td colspan=7>2. Perempuan </td>
			<td colspan=4>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">5.&nbsp;&nbsp;&nbsp;&nbsp;Pekerjaan</td>
			<td>:</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['pekerjaanid_pelapor'][$j])): ?>
						<?= $input['pekerjaanid_pelapor'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21" class="kotak"><?= $input['pekerjaanpelapor']?></td>
			<td colspan="3" class="tengah">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">6.&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $input['alamat_pelapor']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_pelapor'][$i])): ?>
						<?= $input['rt_pelapor'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_pelapor'][$i])): ?>
						<?= $input['rw_pelapor'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $input['desapelapor'];?></td>
			<td colspan="5">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $input['kecpelapor'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $input['kabpelapor'];?></td>
			<td colspan="5">d. Propinsi</td>
			<td colspan="12" class="kotak"><?= $input['provinsipelapor'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
<!-- AKHIR PELAPOR -->
		<tr>
			<td colspan=48 class="bawah"></td>
		</tr>
<!-- AWAL SAKSI 1 -->
		<tr>
			<td colspan=48><strong>SAKSI 1 </strong></td>
		</tr>
		<tr>
			<td colspan="10">1.&nbsp;&nbsp;&nbsp;&nbsp;NIK</td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_saksi1'][$i])): ?>
						<?= $input['nik_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">2.&nbsp;&nbsp;&nbsp;&nbsp;Nama </td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nama_saksi1'][$i])): ?>
						<?= $input['nama_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">3.&nbsp;&nbsp;&nbsp;&nbsp;Tanggal lahir / Umur </td>
			<td>:</td>
				<?php
					$tgl = date('dd',strtotime($input['tanggal_lahir_saksi1']));
					$bln = date('mm',strtotime($input['tanggal_lahir_saksi1']));
					$thn = date('Y',strtotime($input['tanggal_lahir_saksi1']));
				?>
			<td colspan="4">Tanggal :</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5" class="kanan">Umur : </td>
			<?php for ($j=0; $j<3; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['umur_saksi1'][$j])): ?>
						<?= $input['umur_saksi1'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">4.&nbsp;&nbsp;&nbsp;&nbsp;Jenis Kelamin </td>
			<td>:</td>
				<td class="kotak padat tengah">
					<?= $input['jksaksi1'];?>
				</td>
			<td colspan=5>1. Laki-laki </td>
			<td colspan=7>2. Perempuan </td>
			<td colspan=4>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">5.&nbsp;&nbsp;&nbsp;&nbsp;Pekerjaan</td>
			<td>:</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['pekerjaanid_saksi1'][$j])): ?>
						<?= $input['pekerjaanid_saksi1'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21" class="kotak"><?= $input['pekerjaansaksi1']?></td>
			<td colspan="3" class="tengah">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">6.&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $input['alamat_saksi1']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_saksi1'][$i])): ?>
						<?= $input['rt_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_saksi1'][$i])): ?>
						<?= $input['rw_saksi1'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $input['desasaksi1'];?></td>
			<td colspan="5">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $input['kecsaksi1'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $input['kabsaksi1'];?></td>
			<td colspan="5">d. Propinsi</td>
			<td colspan="12" class="kotak"><?= $input['provinsisaksi1'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
<!-- AKHIR SAKSI 1 -->
		<!-- Untuk memaksa penampilan setiap kolom -->
		<tr>
			<?php for ($i=0; $i<48; $i++): ?>
				<td class="bawah">&nbsp;</td>
			<?php endfor; ?>
		</tr>
<!-- AWAL SAKSI 2 -->
		<tr>
			<td colspan=48><strong>SAKSI 2 </strong></td>
		</tr>
		<tr>
			<td colspan="10">1.&nbsp;&nbsp;&nbsp;&nbsp;NIK</td>
			<td>:</td>
			<?php for ($i=0; $i<16; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nik_saksi2'][$i])): ?>
						<?= $input['nik_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">2.&nbsp;&nbsp;&nbsp;&nbsp;Nama </td>
			<td>:</td>
			<?php for ($i=0; $i<33; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['nama_saksi2'][$i])): ?>
						<?= $input['nama_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=21>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">3.&nbsp;&nbsp;&nbsp;&nbsp;Tanggal lahir / Umur </td>
			<td>:</td>
				<?php $tgl = date('dd',strtotime($input['tanggal_lahir_saksi2']));
					$bln = date('mm',strtotime($input['tanggal_lahir_saksi2']));
					$thn = date('Y',strtotime($input['tanggal_lahir_saksi2']));
				?>
			<td colspan="4">Tanggal :</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($tgl[$j])): ?>
						<?= $tgl[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Bulan : </td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($bln[$j])): ?>
						<?= $bln[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4" class="kanan">Tahun : </td>
			<?php for ($j=0; $j<4; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($thn[$j])): ?>
						<?= $thn[$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5" class="kanan">Umur : </td>
			<?php for ($j=0; $j<3; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['umur_saksi2'][$j])): ?>
						<?= $input['umur_saksi2'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">4.&nbsp;&nbsp;&nbsp;&nbsp;Jenis Kelamin </td>
			<td>:</td>
				<td class="kotak padat tengah">
					<?= $input['jksaksi2'];?>
				</td>
			<td colspan=5>1. Laki-laki </td>
			<td colspan=7>2. Perempuan </td>
			<td colspan=4>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">5.&nbsp;&nbsp;&nbsp;&nbsp;Pekerjaan</td>
			<td>:</td>
			<?php for ($j=0; $j<2; $j++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['pekerjaanid_saksi2'][$j])): ?>
						<?= $input['pekerjaanid_saksi2'][$j];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="21" class="kotak"><?= $input['pekerjaansaksi2']?></td>
			<td colspan="3" class="tengah">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10">6.&nbsp;&nbsp;&nbsp;&nbsp;Alamat</td>
			<td>:</td>
			<td colspan="23" class="kotak"><?= $input['alamat_saksi2']?></td>
			<td colspan="3" class="tengah">RT:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rt_saksi2'][$i])): ?>
						<?= $input['rt_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="3" class="tengah">RW:</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['rw_saksi2'][$i])): ?>
						<?= $input['rw_saksi2'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">a. Desa/Kelurahan</td>
			<td colspan="12" class="kotak"><?= $input['desasaksi2'];?></td>
			<td colspan="5">b. Kecamatan</td>
			<td colspan="12" class="kotak"><?= $input['kecsaksi2'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="11" >&nbsp;</td>
			<td colspan="7">c. Kabupaten/Kota</td>
			<td colspan="12" class="kotak"><?= $input['kabsaksi2'];?></td>
			<td colspan="5">d. Propinsi</td>
			<td colspan="12" class="kotak"><?= $input['provinsisaksi2'];?></td>
			<td colspan="1">&nbsp;</td>
		</tr>
<!-- AKHIR SAKSI 2 -->
	</table>
	<table id="ttd" class="disdukcapil" style="margin-top: 5px; margin-bottom: 0px; padding: 0px; border: 0px; border-collapse: collapse;">
		<col span="48" style="width: 2.0833%;">
		<tr>
			<td colspan="33">&nbsp;</td>
			<td colspan="13" style="text-align: center;">
				<?= $config['nama_desa'];?>, <?= tgl_indo(date('Y m d',time()));?>
			</td>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;">Mengetahui</td>
			<td colspan="15">&nbsp;</td>
			<td colspan="10" style="text-align: center;">Pemohon</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;"><?= $this->penandatangan_lampiran($data);?></td>
			<td colspan="15">&nbsp;</td>
			<td colspan="10" style="text-align: center;">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr><td colspan="48">&nbsp;</td></tr>
		<tr>
			<?php for ($i=0; $i<48; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td colspan="16" style="text-align: center;"><strong>(&nbsp;<?= padded_string_center(strtoupper($input['pamong']),30)?>&nbsp;)</strong></td>
			<td colspan="13">&nbsp;</td>
			<td colspan="14" style="text-align: center;"><strong>(&nbsp;<?= padded_string_center(strtoupper($input['nama_pelapor']),30)?>&nbsp;)</strong></td>
			<td colspan="1">&nbsp;</td>
		</tr>
	</table>
</page>
