<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
	table.disdukcapil
	{
		width: 100%;
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
	table.disdukcapil td.kotak
	{
		border: solid 1px #000000;
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
	table.disdukcapil td.bawah
	{
		border-left: solid 1px #000000;
		border-right: solid 1px #000000;
		border-top: dashed 1px #000000;
		border-bottom: double 1px #000000;
	}
	table.disdukcapil td.abu
	{
		background-color: lightgrey;
	}
	table.disdukcapil td.kode {
		background-color: lightgrey;
	}
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

	table.ttd
	{
		margin-top: 20px;
		width: 100%;
	}

	table.ttd td { text-align: center; }
	table.ttd td.left { text-align: left;  }
	table.ttd td div
	{
		display: inline-block;
		width: auto;
		border-bottom: 1px solid black;
		padding-bottom: 3px;
	}
</style>
<page orientation="landscape" format="A3" style="font-size: 8pt">
	<table align="right" style="padding: 5px 20px; border: solid 1px black;">
		<tr><td><strong style="font-size: 16pt;">F-1.01</strong></td></tr>
	</table>
	<p style="text-align: center; margin-top: -10px; margin-bottom: 0px; padding-bottom: 0px;">
			<strong style="font-size: 16pt;">PEMERINTAH KABUPATEN/KOTA <?= strtoupper($config['nama_kabupaten'])?><br>
			DINAS KEPENDUDUKAN DAN CATATAN SIPIL<br>
			FORMULIR ISIAN BIODATA PENDUDUK UNTUK WNI (PER KELUARGA)</strong>
	</p>
	<table class="disdukcapil" style="margin-top: 0px; border: 0px;">
		<col style="width: 12.5%;">
		<col span="13" style="width: 1.17%;">
		<col style="width: 3%;">
		<col span="3" style="width: 1.17%;">
		<col style="width: 10%;">
		<col span="2" style="width: 1.17%;">
		<col style="width: 2.5%;">
		<col style="width: 15%;">
		<col span="5" style="width: 1.17%;">
		<col style="width: 30%";>
		<tr>
			<?php for ($i=0; $i<29; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>

		<tr>
			<td colspan="22" class="abu kotak left">PERHATIAN: Isilah Formulir ini dengan huruf cetak dan jelas serta mengikuti "TATA CARA PENGISIAN FORMULIR" pada halaman sebaliknya</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="5" class="left">Diisi Oleh Petugas</td>
		</tr>
		<tr>
			<td colspan="22" style="font-size: 9pt"><strong>DATA KEPALA KELUARGA</strong></td>
			<td class="pakai-padding">Kode-Nama Provinsi</td>
			<td>:</td>
			<?php for ($i=0; $i<2; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_propinsi'][$i])): ?>
						<?= $config['kode_propinsi'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>&nbsp;</td>
			<td class="kotak"><?= $config['nama_propinsi'];?></td>
		</tr>

		<tr>
			<td class="pakai-padding">Nama Kepala Keluarga</td>
			<td>:</td>
			<td colspan="20" class="kotak"><div><?= $individu['kepala_kk']?></div></td>
			<td class="pakai-padding">Kode-Nama Kabupaten/Kota</td>
			<td>:</td>
			<?php for ($i=0; $i<2; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_kabupaten'][$i])): ?>
						<?= substr($config['kode_kabupaten'], 2, 4)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>&nbsp;</td>
			<td class="kotak"><?= $config['nama_kabupaten'];?></td>
		</tr>

		<tr>
			<td class="pakai-padding">Alamat</td>
			<td>:</td>
			<td colspan="20" class="kotak"><div><?= $individu['alamat_wilayah']; ?></div></td>
			<td class="pakai-padding">Kode-Nama Kecamatan</td>
			<td>:</td>
			<?php for ($i=0; $i<2; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_kecamatan'][$i])): ?>
						<?= substr($config['kode_kecamatan'], 4, 6)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan=2>&nbsp;</td>
			<td class="kotak"><?= $config['nama_kecamatan'];?></td>
		</tr>
		<tr>
			<td class="pakai-padding">Kode Pos</td>
			<td>:</td>
			<?php for ($i=0; $i<5; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_pos'][$i])): ?>
						<?= $config['kode_pos'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="4">RT</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['rt'][$i])): ?>
						<?= $individu['rt'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>RW</td>
			<?php for ($i=0; $i<3; $i++): ?>
				<td class="kotak tengah">
					<?php if (isset($individu['rw'][$i])): ?>
						<?= $individu['rw'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td>Jumlah Anggota Keluarga</td>
			<?php $jml = str_pad((string)count($anggota), 2, ' ', STR_PAD_LEFT);?>
			<?php for ($i=0; $i<2; $i++): ?>
				<td class="kotak padat tengah"><?= $jml[$i]?></td>
			<?php endfor; ?>
			<td>orang</td>
			<td class="pakai-padding">Kode-Nama Kelurahan/Desa</td>
			<td>:</td>
			<?php for ($i=0; $i<4; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($config['kode_desa'][$i])): ?>
						<?= substr($config['kode_desa'], 6, 10)[$i]; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td class="kotak"><?= $config['nama_desa'];?></td>
		</tr>

		<tr>
			<td class="pakai-padding">Telepon</td>
			<td>:</td>
			<?php for ($i=0; $i<12; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['telepon_kk'][$i])): ?>
						<?= $individu['telepon_kk'][$i];?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="8">&nbsp;</td>
			<td class="pakai-padding">Nama Dusun/Dukuh/Kampung</td>
			<td>:</td>
			<td colspan=5 class="kotak"><?= $individu['dusun'];?></td>
		</tr>

	</table>

	<table style="border-collapse: collapse;" class="disdukcapil">
		<col style="width: 2%;">
		<col style="width: 25%;">
		<col style="width: 18%;">
		<col style="width: 30%;">
		<col style="width: 10%;">
		<col style="width: 15%;">
<!--     <tr>
			<?php for ($i=0; $i<6; $i++): ?>
				<td>&nbsp;</td>
			<?php endfor; ?>
		</tr>
 -->    <tr>
			<td colspan="6" style="font-size: 9pt; border-bottom: double 1px #000000"><strong>DATA KELUARGA</strong></td>
		</tr>
		<tr>
			<td class="judul tengah">No.</td>
			<td class="judul tengah">Nama Lengkap</td>
			<td class="judul tengah">Nomor KTP/Nopen</td>
			<td class="judul tengah">Alamat Sebelumnya</td>
			<td class="judul tengah">Nomor Paspor</td>
			<td class="judul tengah">Tanggal Berakhir Paspor</td>
		</tr>
		 <tr>
			<td class="judul abu tengah">1</td>
			<td class="judul abu tengah">2</td>
			<td class="judul abu tengah">3</td>
			<td class="judul abu tengah">4</td>
			<td class="judul abu tengah">5</td>
			<td class="judul abu tengah">6</td>
		</tr>
		<?php for ($i=0; $i<MAX_ANGGOTA_F101; $i++): ?>
			<tr>
				<?php $class = ($i==10-1) ? "bawah" : "anggota";?>
				<td class="tengah <?= $class?>"><?= $i+1; ?></td>
				<?php if ($i < count($anggota)): ?>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['nama']?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['nik']?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['alamat_sebelumnya']?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['dokumen_pasport']?></td>
					<td class="tengah <?= $class?>"><?= tgl_indo_out($anggota[$i]['tanggal_akhir_paspor'])?></td>
				<?php else: ?>
					<?php for ($k=0; $k<5; $k++): ?>
						<td class="tengah <?= $class?>">&nbsp;</td>
					<?php endfor; ?>
				<?php endif; ?>
			</tr>
		<?php endfor; ?>
	</table>
	<table style="margin-top: 5px; border-collapse: collapse;" class="disdukcapil">
		<col style="width: 2%;">  <!--No-->
		<col style="width: 5%;">  <!--Jenis Kelamin-->
		<col style="width:12%;">  <!--Tempat Lahir-->
		<col style="width: 8%;">  <!--Tanggal/Bulan/Tahun Lahir-->
		<col style="width: 4%;">  <!--Umur-->
		<col style="width: 5%;">  <!--Akta Lahir/Surat Lahir-->
		<col style="width: 8%;">  <!--Nomor Akta Kelahiran/Surat Kenal Lahir-->
		<col style="width: 5%;">  <!--Golongan Darah-->
		<col style="width: 5%;">  <!--Agama/Kepercayaan-->
		<col style="width: 5%;">  <!--Status Perkawinan-->
		<col style="width: 5%;">  <!--Akta Perkwn/Buku Nikah*)-->
		<col style="width: 9%;">  <!--Nomor Akta Perkawinan/Buku Nikah*)-->
		<col style="width: 6%;">  <!--Tanggal Perkawinan*)-->
		<col style="width: 5%;">  <!--Akta Cerai/Surat Cerai*)-->
		<col style="width: 8%;">  <!--Nomor Akta Perceraian/Surat Cerai*)-->
		<col style="width: 8%;">  <!--Tanggal Perceraian*)-->

		<tr>
			<td class="judul tengah">No.</td>
			<td class="judul tengah">Jenis Kelamin</td>
			<td class="judul tengah">Tempat Lahir</td>
			<td class="judul tengah">Tanggal/Bulan/Tahun Lahir</td>
			<td class="judul tengah">Umur</td>
			<td class="judul tengah">Akta Lahir/ Surat Lahir</td>
			<td class="judul tengah">Nomor Akta Kelahiran/ Surat Kenal Lahir</td>
			<td class="judul tengah">Golongan Darah</td>
			<td class="judul tengah">Agama/ Kepercayaan</td>
			<td class="judul tengah">Status Perkawinan</td>
			<td class="judul tengah">Akta Perkwn/ Buku Nikah*)</td>
			<td class="judul tengah">Nomor Akta Perkawinan/ Buku Nikah*)</td>
			<td class="judul tengah">Tanggal Perkawinan*)</td>
			<td class="judul tengah">Akta Cerai/ Surat Cerai*)</td>
			<td class="judul tengah">Nomor Akta Perceraian/Surat Cerai*)</td>
			<td class="judul tengah">Tanggal Perceraian*)</td>
		</tr>
		 <tr>
			<td class="judul abu tengah"></td>
			<td class="judul abu tengah">7</td>
			<td class="judul abu tengah">8</td>
			<td class="judul abu tengah">9</td>
			<td class="judul abu tengah">10</td>
			<td class="judul abu tengah">11</td>
			<td class="judul abu tengah">12</td>
			<td class="judul abu tengah">13</td>
			<td class="judul abu tengah">14</td>
			<td class="judul abu tengah">15</td>
			<td class="judul abu tengah">16</td>
			<td class="judul abu tengah">17</td>
			<td class="judul abu tengah">18</td>
			<td class="judul abu tengah">19</td>
			<td class="judul abu tengah">20</td>
			<td class="judul abu tengah">21</td>
		</tr>
		<?php for ($i=0; $i<MAX_ANGGOTA_F101; $i++): ?>
			<tr>
				<?php $class = ($i==10-1) ? "bawah" : "anggota";?>
				<td class="tengah <?= $class?>"><?= $i+1; ?></td>
				<?php if ($i < count($anggota)): ?>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['sex_id']?></div></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['tempatlahir']?></td>
					<td class="tengah <?= $class?>"><?= tgl_indo($anggota[$i]['tanggallahir'])?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['umur']?></td>
					<td class="kode <?= $class?>"><div><?= ($anggota[$i]['akta_lahir'] ? '2' : '1')?></div></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['akta_lahir']?></td>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['golongan_darah_id']?></div></td>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['agama_id']?></div></td>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['status_kawin_id']?></div></td>
					<td class="kode <?= $class?>"><div><?= ($anggota[$i]['akta_perkawinan'] ? '2' : '1')?></div></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['akta_perkawinan']?></td>
					<td class="tengah <?= $class?>"><?= tgl_indo_out($anggota[$i]['tanggalperkawinan'])?></td>
					<td class="kode <?= $class?>"><div><?= ($anggota[$i]['akta_perceraian'] ? '2' : '1')?></div></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['akta_perceraian']?></td>
					<td class="tengah <?= $class?>"><?= tgl_indo_out($anggota[$i]['tanggalperceraian'])?></td>
				<?php else: ?>
					<?php for ($k=0; $k<15; $k++): ?>
						<td class="tengah <?= $class?>">&nbsp;</td>
					<?php endfor; ?>
				<?php endif; ?>
			</tr>
		<?php endfor; ?>
	</table>
	<table style="margin-top: 5px; border-collapse: collapse;" class="disdukcapil">
		<col style="width: 2%;">  <!--No-->
		<col style="width: 5%;">  <!--Status Hub. Dlm Keluarga-->
		<col style="width: 5%;">  <!--Kelainan Fisik & Mental<-->
		<col style="width: 5%;">  <!--Penyandang Cacat-->
		<col style="width: 5%;">  <!--Pendidikan Terakhir-->
		<col style="width: 5%;">  <!--Pekerjaan-->
		<col style="width: 17%;">  <!--NIK Ibu-->
		<col style="width: 19%;">  <!--Nama Lengkap Ibu-->
		<col style="width: 18%;">  <!--NIK Ayah-->
		<col style="width: 19%;">  <!--Nama Lengkap Ayah-->

		<tr>
			<td class="judul tengah">No.</td>
			<td class="judul tengah">Status Hub. Dlm Keluarga</td>
			<td class="judul tengah">Kelainan Fisik & Mental</td>
			<td class="judul tengah">Penyandang Cacat</td>
			<td class="judul tengah">Pendidikan Terakhir</td>
			<td class="judul tengah">Pekerjaan</td>
			<td class="judul tengah">NIK Ibu</td>
			<td class="judul tengah">Nama Lengkap Ibu</td>
			<td class="judul tengah">NIK Ayah</td>
			<td class="judul tengah">Nama Lengkap Ayah</td>
		</tr>
		<tr>
			<td class="judul abu tengah"></td>
			<td class="judul abu tengah">22</td>
			<td class="judul abu tengah">23</td>
			<td class="judul abu tengah">24</td>
			<td class="judul abu tengah">25</td>
			<td class="judul abu tengah">26</td>
			<td class="judul abu tengah">27</td>
			<td class="judul abu tengah">28</td>
			<td class="judul abu tengah">29</td>
			<td class="judul abu tengah">30</td>
		</tr>
		<?php for ($i=0; $i<MAX_ANGGOTA_F101; $i++): ?>
			<tr>
				<?php $class = ($i==10-1) ? "bawah" : "anggota";?>
				<td class="tengah <?= $class?>"><?= $i+1; ?></td>
				<?php if ($i < count($anggota)): ?>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['kk_level']?></div></td>
					<td class="kode <?= $class?>"><div><?= ($anggota[$i]['cacat_id'] ? '2' : '1')?></div></td>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['cacat_id']?></div></td>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['pendidikan_kk_id']?></div></td>
					<td class="kode <?= $class?>"><div><?= $anggota[$i]['pekerjaan_id']?></div></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['ibu_nik']?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['nama_ibu']?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['ayah_nik']?></td>
					<td class="tengah <?= $class?>"><?= $anggota[$i]['nama_ayah']?></td>
				<?php else: ?>
					<?php for ($k=0; $k<9; $k++): ?>
						<td class="tengah <?= $class?>">&nbsp;</td>
					<?php endfor; ?>
				<?php endif; ?>
			</tr>
		<?php endfor; ?>
	</table>

	<table class="ttd" style="margin-top: 15px">
		<col style="width:6%">
		<col style="width:2%">
		<col style="width:19%">
		<col style="width:22%">
		<col style="width:16%">
		<col style="width:15%">
		<col style="width:17%">
		<tr>
			<?php $pengisi = str_pad("",300,"&nbsp;");?>
			<td class="left">Nama Ketua RT</td>
			<td> : </td>
			<td class="left"><u><?= $pengisi.$pengisi?></u></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="left">.........................................., .............................20..</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>Petugas/Registrar</td>
			<td>Mengetahui</td>
			<td>&nbsp;</td>
			<td class="left">Kepala Keluarga</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(Kabupaten/Kota atau Kecamatan atau Kelurahan/Desa)</td>
			<td><?= $this->penandatangan_lampiran($data);?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="left">Nama Ketua RW</td>
			<td> : </td>
			<td class="left"><u><?= $pengisi.$pengisi?></u></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="left">Ttd / cap Jempol</td>
		</tr>
		<tr><td colspan="7" style="height: 20px;">&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="left" style="padding-left: 20px;"><div>Nama Lengkap:<?= str_pad("",390,"&nbsp;")?></div></td>
			<td class="left"><div>Nama Lengkap:<?= padded_string_fixed_length(strtoupper($kepala_desa['pamong_nama']),3,50)?></div></td>
			<td>&nbsp;</td>
			<td class="left"><div>Nama Jelas:<?= padded_string_fixed_length($individu['kepala_kk'],5,60)?></div></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="left" style="padding-left: 20px;"><?= "NIP".str_pad("",54,"&nbsp;").":"?></td>
			<td class="left"><?= "NIP".str_pad("",54,"&nbsp;").":&nbsp;&nbsp;&nbsp;".$kepala_desa['pamong_nip']?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<p style="margin-top: 0px;">
		<u>PERNYATAAN</u><br>
		Demikian Formulir ini saya/kami isi dengan sesungguhnya apabila keterangan tersebut tidak sesuai dengan<br>
		keadaan sebenarnya, saya bersedia dikenakan sanksi sesuai ketentuan peraturan perundang-undangan yang berlaku<br><br>
		Catatan: *) Hanya diisi oleh salah satu pasangan keluarga tersebut (suami/istri)
	</p>

</page>
