<html>
	<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');?>
	<?php $this->load->view('print/headjs.php');?>
	<body>
		<div id="content" class="container_12 clearfix">
			<div id="content-main" class="grid_7">
				<link href="<?= base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
				<div>
					<table width="100%">
						<tr> <img src="<?= LogoDesa($desa['logo']);?>" alt=""  class="logo"></tr>
						<div class="header">
							<h4 class="kop">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($desa['nama_kabupaten'])?> </h4>
							<h4 class="kop">KECAMATAN <?= strtoupper($desa['nama_kecamatan'])?> </h4>
							<h4 class="kop"><?= strtoupper($this->setting->sebutan_desa)?> <?= strtoupper($desa['nama_desa'])?></h4>
							<h5 class="kop2"><?= ($desa['alamat_kantor'])?> </h5>
							<div style="text-align: center;"><hr /></div>
						</div>
						<div align="center"><u><h4 class="kop">SURAT KETERANGAN KEMATIAN</h4></u></div>
						<div align="center"><h4 class="kop">NO: <?= $input['nomor']?></h4></div>
					</table>
					<div class="clear"></div>
					<table width="100%">
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr><td class="indentasi">Yang bertanda tangan dibawah ini <?= $input['jabatan']?>  <?= $desa['nama_desa']?>, Kecamatan <?= $desa['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_kabupaten)?> <?= $desa['nama_kabupaten']?>, Provinsi <?= $desa['nama_propinsi']?> menerangkan bahwa: </td></tr>
					</table>
					<div id="isi3">
						<table width="100%">
							<tr><td width="35%">Nama</td><td width="3%">:</td><td ><?= $data['nama']?></td></tr>
							<tr><td >NIK</td><td width="3%">:</td><td ><?= $data['nik']?></td></tr>
							<tr><td >Jenis Kelamin</td><td width="3%">:</td><td ><?= $data['sex']?></td></tr>
							<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?= $data['tempatlahir']?>, <?= tgl_indo($data['tanggallahir'])?></td></tr>
							<tr><td>Agama</td><td>:</td><td><?= $data['warganegara']?>/<?= $data['agama']?></td></tr>
							<tr><td>Alamat</td><td>:</td><td>RT. <?= $data['rt']?>, RW. <?= $data['rw']?>, Dusun <?= $data['dusun']?>, <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, Kec. <?= $desa['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat)?> <?= $desa['nama_kabupaten']?></td></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr><td colspan="3">Telah meninggal dunia pada: </td></tr>
							<tr><td>Hari/ Tanggal/ Jam</td><td>:</td><td><?= $input['hari']?>/<?=  tgl_indo(tgl_indo_in($input['tanggal_mati']))?>/<?= $input['jam']?></td></tr>
							<tr><td>Bertempat di</td><td>:</td><td><?= $input['tempat_mati']?></td></tr>
							<tr><td>Penyebab Kematian</td><td>:</td><td><?= $input['sebab']?></td></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr><td colspan="3"> Surat keterangan ini dibuat berdasarkan keterangan pelapor : </td></tr>
							<tr><td>Nama</td><td>:</td><td><?= $input['nama']?></td></tr>
							<tr><td>NIK</td><td>:</td><td><?= $input['nik_pelapor']?></td></tr>
							<tr><td>Tgl Lahir/</td><td>:</td><td><?=  tgl_indo(tgl_indo_in($input['tgl_lahir']))?></td></tr>
							<tr><td>Pekerjaan</td><td>:</td><td><?= $input['pekerjaan']?></td></tr>
							<tr><td>Alamat</td><td>:</td><td><?= $input['alamat']?></td></tr>
							<tr><td>Hubungan dengan yang mati</td><td>:</td><td><?= $input['hubungan']?></td></tr>
						</table>
						<table width="100%">
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
						</table>
					</div>
					<table width="100%">
						<tr></tr>
						<tr><td width="10%"></td><td width="30%"></td><td  align="center"><?= $desa['nama_desa']?>, <?= $tanggal_sekarang?></td></tr>
						<tr><td width="10%"></td><td width="30%"></td><td align="center"><?= $input['jabatan']?> <?= $desa['nama_desa']?></td></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr><td><td></td><td td align="center">( <?= $input['pamong']?> )</td></tr>
						<tr><td colspan="3">*)nama terang<td></td>
					</table>
				</div>
			</div>
			<div id="aside"></div>
		</div>
	</body>
</html>
