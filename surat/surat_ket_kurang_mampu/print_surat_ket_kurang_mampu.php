<?php $this->load->view('print/headjs.php');?>
<body>
	<div id="content" class="container_12 clearfix">
		<div id="content-main" class="grid_7">
			<link href="<?= base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
			<div>
				<table width="100%">
					<tr> <img src="<?= LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>
					<div class="header">
						<h4 class="kop">PEMERINTAH KABUPATEN <?= strtoupper($desa['nama_kabupaten'])?> </h4>
						<h4 class="kop">KECAMATAN <?= strtoupper($desa['nama_kecamatan'])?> </h4>
						<h4 class="kop">DESA <?= strtoupper($desa['nama_desa'])?></h4>
						<h5 class="kop2"><?= ($desa['alamat_kantor'])?> <?= ($desa['email_desa'])?> Kode Pos : <?= ($desa['kode_pos'])?></h5>
						<div style="text-align: center;">
							<hr />
						</div>
					</div>
				</table>
				<table width="100%">
				</table>
				<table width="100%">
				</table>
				<table width="100%">
					<div align="center"><u><h4 class="kop">SURAT KETERANGAN TIDAK MAMPU</h4></u></div>
					<div align="center"><h4 class="kop3">Nomor : 467.1/<?= $input['nomor']?>/Kesra/<?= date("Y")?></h4></div>
				</table>
				<div class="clear"></div>
				<table width="100%">
					<tr><td class="indentasi">Yang bertanda tangan dibawah ini Kepala Desa <?= $desa['nama_desa']?> Kecamatan <?= $desa['nama_kecamatan']?> Kabupaten <?= $desa['nama_kabupaten']?> Provinsi <?= $desa['nama_propinsi']?> menerangkan dengan sebenarnya kepada :  </td></tr>
				</table>
				<div id="isi3">
					<table width="100%">
						<tr><td width="23%">1. Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?= $data['nama']?></td></tr>
						<tr><td width="23%">2. NIK/ No KTP</td><td width="3%">:</td><td width="64%"><?= $data['nik']?></td></tr>
						<tr><td>3. Tempat dan Tgl. Lahir </td><td>:</td><td><?= ($data['tempatlahir'])?>, <?= tgl_indo($data['tanggallahir'])?> </td></tr>
						<tr><td>4. Jenis Kelamin</td><td>:</td><td><?= $data['sex']?></td></tr>
						<tr><td>5. Agama</td><td>:</td><td><?= $data['agama']?></td></tr>
						<tr><td>6. Pekerjaan</td><td>:</td><td><?= $data['pekerjaan']?></td></tr>
						<tr><td>7. Kewarganegaraan </td><td>:</td><td><?= $data['warganegara']?></td></tr>
						<tr><td>8. Alamat/ Tempat Tinggal</td><td>:</td><td><?= ucwords(strtolower($data['alamat']))?> RT. <?= $data['rt']?> RW. <?= $data['rw']?> Dusun <?= $data['dusun']?> Desa <?= $desa['nama_desa']?> Kecamatan <?= $desa['nama_kecamatan']?> Kabupaten <?= $desa['nama_kabupaten']?></td></tr>
					</table>
					<table width="100%">
						<tr><td class="indentasi">Bahwa yang tersebut namanya diatas, sepanjang pengetahuan dan penelitian kami hingga saat dikeluarkannya surat keterangan ini memang benar Keluarga yang KURANG MAMPU dan tidak memiliki penghasilan tetap. </td></tr>
					</table>
					<table width="100%">
						<tr><td><center><u><b><i>DAFTAR TANGGUNGAN KELUARGA</td><td></td></tr>
						<tr>
							<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
							<table class="border thick">
								<thead>
									<tr class="border thick">
									<tr></tr>
									<?php if ($anggota): ?>
										<table width = 600 border =1>
											<thead>
												<tr>
													<th align="center" >No</th>
													<th align="center" >NIK</th>
													<th align="center" >Nama Lengkap</th>
													<th align="center" align="center">Jenis Kelamin</th>
													<th  align="center" >Tempat Tanggal Lahir</th>
													<th align="center" >SHDK</th>


												</tr>
											<tbody>
												<?php $i=0;?>
												<?php foreach ($anggota AS $data1):
													if ($data1['kk_level'] == 1) continue;
													$i++;?>
													<tr>
														<td align="center"> <?= $i?></td>
														<td align="center"><?= $data1['nik']?></td>
														<td> <?= $data1['nama']?></td>
														<td align="center"><?= $data1['sex']?></td>
														<td align="left"> <?= $data1['tempatlahir']?>, <?= tgl_indo($data1['tanggallahir'])?></td>
														<td align="center"><?= $data1['hubungan']?></td>
													</tr>
												<?php endforeach;?>
											</tbody>
											<tr></tr>
											<tr></tr>
									<?php endif; ?>
									<tr></tr>
							</table>
							<table width="100%">
								<tr></tr>
								<tr></tr>
								<tr><td class="indentasi">Surat Keterangan ini dibuat untuk Keperluan : <b><?= $input['keperluan']?>
								<tr></tr>
							</table>
							<table width="100%">
								<tr></tr>
								<tr><td class="indentasi">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya</td></tr>
								<tr></tr>
							</table>
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
							<tr><td></td><td width="55%"></td><td align="center"><?= $desa['nama_desa']?>, <?= $tanggal_sekarang?></td></tr>
							<tr><td></td><td width="55%"></td><td align="center"><?= ($input['atas_nama'])?></td></tr>
							<tr><td></td><td width="55%"></td><td align="center"><?= $input['jabatan']?>,</td></tr>
						</table>
					</div>
					<table width="100%">
					<table width="100%">
					<table width="100%">
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
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr><td></td><td width="55%"></td><td align="center"><b><u><?= $input['pamong']?> </u></td></tr>
						<tr><td></td><td width="55%"></td><td align="center"><?= ($input['pamong_nip'])?></td></tr>
					</table>
				</div>
			</div>
			<div id="aside"></div>
		</div>
	</body>
</html>
