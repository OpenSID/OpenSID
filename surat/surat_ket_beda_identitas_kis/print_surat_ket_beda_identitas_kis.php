<html>
	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
	<?php $this->load->view('print/headjs.php');?>
	<body>
		<div id="content" class="container_12 clearfix">
			<div id="content-main" class="grid_7">
				<link href="<?= base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
				<div>
					<table width="100%">
						<tr> <img src="<?= LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>
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
					<link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
					<table width="100%">
						<tr>
							<td class="indentasi">
								Yang bertanda tangan dibawah ini <?= $input['jabatan']?> <?= $desa['nama_desa']?>, Kecamatan <?= $desa['nama_kecamatan']?>,
								<?= ucwords($this->setting->sebutan_kabupaten)?> <?= $desa['nama_kabupaten']?>, Provinsi <?= $desa['nama_propinsi']?> menerangkan dengan sebenarnya bahwa:
							</td>
						</tr>
					</table>
					<table class="border thick">
						<thead>
							<tr>
								<th align="center">No</th>
								<th align="center">Nama</th>
								<th align="center" >NIK</th>
								<th align="center">Jenis Kelamin</th>
								<th align="center">Tempat Tanggal Lahir</th>
								<th align="center" >Pekerjaan</th>
								<th align="center" >Alamat</th>
							</tr>
						</thead>
						<tbody>
							<?php	$id_cb = $this->input->post('id_cb');
							$pilih="";
							foreach ($id_cb as $nik):
								$pilih .= $nik.',';
							endforeach;
							$pilih = rtrim($pilih,',');
							$anggota = $this->keluarga_model->list_anggota($pribadi['id_kk'],array('pilih'=>$pilih));
							foreach ($anggota AS $key => $data1): ?>
								<tr>
									<td align="center"width="4"> <?= $key+1?></td>
									<td> <?= $data1['nama']?></td>
									<td align="center"><?= $data1['nik']?></td>
									<td align="center"><?= $data1['sex']?></td>
									<td align="left"> <?= $data1['tempatlahir']?>, <?= tgl_indo($data1['tanggallahir'])?></td>
									<td align="left"><?= $data1['pekerjaan']?></td>
									<td align="center"><?= $data['alamat']?></td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
					<table width="100%">
						<tr>
							<td class="indentasi">Nama tersebut di atas merupakan identitas yang tertera pada KTP dan Kartu Keluarga (KK) sedangkan pada Kartu Indonesia Sehat (KIS) tertulis : </td>
						</tr>
					</table>
					<table class="border thick">
						<thead>
							<tr class="border thick">
								<th align="center" width='10'>No</th>
								<th align="center" width='100'>No. Kartu</th>
								<th align="center" width='150'>Nama di Kartu</th>
								<th align="center" width='90'>NIK</th>
								<th align="center" width='150'>Alamat di Kartu</th>
								<th align="center" width='80'>Tanggal Lahir</th>
								<th align="center" width='80'>Faskes Tingkat I</th>
							</tr>
						</thead>
						<tbody>
							<?php for ($i=1; $i<MAX_ANGGOTA+1; $i++): ?>
								<?php if (!empty($input["nomor$i"])): ?>
									<tr>
										<td align="center"><?= $i?></td>
										<td align="center"><?= $input["kartu$i"]?></td>
										<td align="left"><?= $input["nama$i"]?></td>
										<td align="center"><?= $input["nik$i"]?></td>
										<td align="left"><?= $input["alamat$i"]?></td>
										<td align="left"><?= $input["tanggallahir$i"]?></td>
										<td align="center"><?= $input["faskes$i"]?></td>
									</tr>
								<?php endif; ?>
							<?php endfor; ?>
						</tbody>
					</table>
					<table width="100%">
						<tr>
							<td class="indentasi" style="padding-bottom: 1em;">Menurut pengamatan dan pengetahuan kami hingga saat dikeluarkannya surat keterangan ini bahwa yang namanya di atas merupakan orang yang satu / sama.</td>
						</tr>
						<tr>
							<td class="indentasi" style="padding-bottom: 1em;">Surat Keterangan ini dibuat untuk keperluan : <b><?= $input['keperluan']?>.</b></td>
						</tr>
						<tr>
							<td class="indentasi" style="padding-bottom: 1em;">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</td>
						</tr>
					</table>
					<table width="100%">
						<tr>
							<td width="55%"></td>
							<td align="center"><?= $desa['nama_desa']?>, <?= $tanggal_sekarang?></td>
						</tr>
						<tr>
							<td width="55%"></td>
							<td align="center"><?= ($input['atas_nama'])?></td>
						</tr>
						<tr>
							<td width="55%"></td>
							<td align="center"><?= $input['jabatan']?></td>
						</tr>
						<tr>
							<td width="55%"></td>
							<td align="center" style="padding-top: 7em;"><b><u><?= $input['pamong']?> </u></td>
						</tr>
						<tr>
							<td width="55%"></td>
							<td align="center"><?= ($input['pamong_nip'])?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
