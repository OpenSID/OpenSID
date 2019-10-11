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
					</table>
					<div class="clear"></div>
						<table width="100%">
							<tr>
								<td width="10%"></td><td></td><td width="43%" align="left">  </td>
								<td align="left"><?= $desa['nama_desa']?>, <?= $tanggal_sekarang?></td>
							</tr>
							<tr>
								<td width="10%">Nomor</td><td>:</td><td width="43%" align="left"><?= $input['nomor'] ?>  </td>
								<td align="left"></td>
							</tr>
							<tr>
								<td width="10%">Perihal</td><td>:</td><td width="43%" align="left">Permohonan Cerai </td>
								<td align="left"></td>
							</tr>
							<tr>
								<td width="10%"></td><td></td><td width="43%" align="left">  </td>
								<td align="left"><p>Kepada Yth. Kepala Kepala Pengadilan Agama <?= ucwords($this->setting->sebutan_kabupaten)?>  <?= $desa['nama_kabupaten']?></td>
							</tr>
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
						<table width="100%">
							<tr><td colspan="3">Dengan ini kami kirimkan dengan hormat permohonan cerai dari pasangan suami istri : </td></tr>
							<tr><td width="30%">A. SUAMI</td><td>:</td><td></td></tr>
							<tr><td>Nama</td><td>:</td><td><?= $pribadi['nama']?></td></tr>
							<tr><td>NIK</td><td>:</td><td><?= $pribadi['nik']?></td></tr>
							<tr><td>Tempat dan Tanggal Lahir </td><td>:</td><td><?= $pribadi['tempatlahir']?>, <?= tgl_indo(($pribadi['tanggallahir']))?> </td></tr>
							<tr><td>Pekerjaan</td><td>:</td><td><?= $pribadi['pek']?></td></tr>
							<tr><td>Agama</td><td>:</td><td><?= $pribadi['agama']?></td></tr>
							<tr><td>Alamat</td><td>:</td><td>RT. <?= $pribadi['rt']?>, RW. <?= $pribadi['rw']?>, Dusun <?= $pribadi['dusun']?>, <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, Kec. <?= $desa['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat)?> <?= $desa['nama_kabupaten']?></td></tr>
							<tr><td>B. ISTRI</td><td>:</td><td></td></tr>
							<tr><td>Nama</td><td>:</td><td><?= $istri['nama']?></td></tr>
							<tr><td>NIK</td><td>:</td><td><?= $istri['nik']?></td></tr>
							<tr><td>Tempat dan Tanggal Lahir </td><td>:</td><td><?= $istri['tempatlahir']?> <?= tgl_indo(($istri['tanggallahir']))?> </td></tr>
							<tr><td>Pekerjaan</td><td>:</td><td><?= $istri['pek']?></td></tr>
							<tr><td>Agama</td><td>:</td><td><?= $istri['agama']?></td></tr>
							<tr><td>Alamat</td><td>:</td><td>RT. <?= $istri['rt']?>, RW. <?= $istri['rw']?>, Dusun <?= $istri['dusun']?>, <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, Kec. <?= $desa['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat)?> <?= $desa['nama_kabupaten']?></td></tr>
							<tr></tr>
							<tr><td colspan="3">Adapun sebab-sebab menurut keterangannya sebagai berikut :</td></tr>
							<tr></tr>
							<tr><td><?= $input['sebab']?></td></tr>
							<tr></tr>
							<tr></tr>
							<tr><td colspan="3">Demikian untuk menjadikan periksa dan dipergunakan seperlunya.</td></tr>
						</table>
						<table width="100%">
							<tr></tr>
							<tr><td width="23%"></td><td width="30%"></td><td align="center"><?= $input['jabatan']?> <?= $desa['nama_desa']?></td></tr>
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
							<tr><td> <td></td><td align="center"><?= $input['pamong']?></td></tr>
						</table>
					</div>
				</div>
			</div>
			<div id="aside"></div>
		</div>
	</body>
</html>
