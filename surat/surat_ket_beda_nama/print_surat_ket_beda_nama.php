<htmL>
	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
	<?php $this->load->view('print/headjs.php');?>
	<body>
		<div id="content" class="container_12 clearfix">
			<div id="content-main" class="grid_7">
				<link href="<?= base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
				<div>
					<table width="100%">
						<tr><img src="<?= LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>
						<div class="header">
							<h4 class="kop">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($desa['nama_kabupaten'])?> </h4>
							<h4 class="kop">KECAMATAN <?= strtoupper($desa['nama_kecamatan'])?> </h4>
							<h4 class="kop"><?= strtoupper($this->setting->sebutan_desa)?> <?= strtoupper($desa['nama_desa'])?></h4>
							<h5 class="kop2"><?= ($desa['alamat_kantor'])?> </h5>
							<div style="text-align: center;"><hr /></div>
						</div>
						<div align="center"><u><h4 class="kop">SURAT KETERANGAN BEDA IDENTITAS</h4></u></div>
						<div align="center"><h4 class="kop3">Nomor : <?= $input['nomor']?></h4></div>
					</table>
					<div class="clear"></div>
					<table width="100%">
						<tr>
							<td class="indentasi">
								Yang bertanda tangan dibawah ini <?= $input['jabatan']?> <?= $desa['nama_desa']?>, Kecamatan <?= $desa['nama_kecamatan']?>,
								<?= ucwords($this->setting->sebutan_kabupaten)?> <?= $desa['nama_kabupaten']?>, Provinsi <?= $desa['nama_propinsi']?> menerangkan dengan sebenarnya bahwa:
							</td>
						</tr>
					</table>
					<div id="isi3">
						<table width="100%">
							<tr></tr>
							<tr><td colspan="3">I. Identitas dalam KK</td></tr>
							<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?= $data['nama']?></td></tr>
							<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?= ($data['tempatlahir'])?>, <?= tgl_indo($data['tanggallahir'])?> </td></tr>
							<tr><td>Jenis Kelamin</td><td>:</td><td><?= $data['sex']?></td></tr>
							<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td>RW. <?= $data['rw']?>, RT. <?= $data['rt']?>, Dusun <?= $data['dusun']?>, <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>, Kec. <?= $desa['nama_kecamatan']?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat)?> <?= $desa['nama_kabupaten']?></td></tr>
							<tr><td>Agama</td><td>:</td><td><?= $data['agama']?></td></tr>
							<tr><td>Pekerjaan</td><td>:</td><td><?= $data['pekerjaan']?></td></tr>
							<tr></tr>
							<tr></tr>
							<tr></tr>
							<tr><td colspan="3">II. Identitas dalam <?= $input['kartu']?></td><td width="3%"></td><td width="64%"></td></tr>
							<tr><td width="30%">No Identitas</td><td width="3%">:</td><td width="64%"><?= $input['identitas']?></td></tr>
							<tr><td>Nama</td><td>:</td><td><?= $input['nama']?></td></tr>
							<tr><td>Tempat dan Tanggal Lahir<td>:</td><td><?= ($input['tempatlahir'])?> <?= tgl_indo(tgl_indo_in($input['tanggallahir']))?> </td></tr>
							<tr><td>Jenis Kelamin</td><td>:</td><td><?= $input['sex']?></td></tr>
							<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td><?= $input['alamat']?></td></tr>
							<tr><td>Agama</td><td>:</td><td><?= $input['agama']?></td></tr>
							<tr><td>Pekerjaan</td><td>:</td><td><?= $input['pekerjaan']?></td></tr>
							<tr><td>Keterangan</td><td>:</td><td><?= $input['keterangan']?></td></tr>
						</table>
						<table width="100%">
							<tr></tr>
							<tr></tr>
							<tr><td class="indentasi">Adalah benar-benar warga <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?> dan merupakan orang yang sama namun terdapat perbedaan <?= $input['perbedaan'] ?> seperti tersebut di atas. Adapun data yang benar dan dipakai seperti yang tercantum di Kartu Keluarga (KK). </td></tr>
							<tr><td class="indentasi">Demikian surat keterangan ini dibuat dengan sesungguhnya agar dapat dipergunakan sebagaimana mestinya</td></tr>
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
						<tr></tr>
						<tr></tr>
						<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?= $desa['nama_desa']?>, <?= $tanggal_sekarang?></td></tr>
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
						<tr><td> <td></td><td align="center">( <?= $input['pamong']?> )</td></tr>
					</table>
				</div>
			</div>
			<div id="aside"></div>
		</div>
	</body>
</html>
