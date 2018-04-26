<?php $this->load->view('print/headjs.php');?>

<body>
	<div id="content" class="container_12 clearfix">
		<div id="content-main" class="grid_7">

			<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />

			<table width="100%" style="border: solid 0px black; text-align: left;">
				<tr>
					<td width="8%">NIK</td>
					<td width="2%">:</td>
					<td width="90%"><?php echo $penduduk['nik']?></td>

				</tr>
				<tr>
					<td width="8%">No.KK</td>
					<td width="2%">:</td>
					<td width="90%"><?php echo $penduduk['no_kk']?></td>
					</td>
				</tr>
			</table>
			<table width="100%" style="border: solid 0px black; text-align: center;">
				<tr>
					<td align="center"><img src="<?php echo LogoDesa($desa['logo']);?>" alt="<?php echo $desa['nama_desa']?>"  class="logo_mandiri">
				</tr>
				<tr>
					</td>
					<td>
						<h3>BIODATA PENDUDUK WARGA NEGARA INDONESIA</h3>
						<h5><?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo $desa['nama_kabupaten']?>, <?php echo ucwords($this->setting->sebutan_kecamatan_singkat)?> <?php echo $desa['nama_kecamatan']?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo $desa['nama_desa']?></h5>

					</td>
				</tr>
			</table>
			<table width="100%" style="border: solid 0px black; padding: 10px;">
				<tr>
					<td><b>DATA PERSONAL</b></td>
				</tr>
				<tr>
					<td width="220">Nama Lengkap</td><td width="2%">:</td>
					<td><?php echo strtoupper($penduduk['nama'])?></td>
					<td rowspan="18" style="vertical-align: middle;">
						<?php if($penduduk['foto']){?>
							<img src="<?php echo AmbilFoto($penduduk['foto'])?>" alt="" style="border: solid 1px black; padding: 5px;"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>Tempat Lahir</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tempatlahir'])?></td>
				</tr>
				<tr>
					<td>Tanggal Lahir</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tanggallahir'])?></td>
				</tr>
				<tr>
					<td>Jenis Kelamin</td><td >:</td>
					<td><?php echo strtoupper($penduduk['sex'])?></td>
				</tr>
				<tr>
					<td>Akta lahir</td><td >:</td>
					<td><?php echo strtoupper($penduduk['akta_lahir'])?></td>
				</tr>
				<tr>
					<td>Agama</td><td >:</td>
					<td><?php echo strtoupper($penduduk['agama'])?></td>
				</tr>
				<tr>
				  <td>Pendidikan Terakhir</td><td >:</td>
				  <td><?php echo strtoupper($penduduk['pendidikan_kk'])?></td>
				</tr>
				<tr>
					<td>Pekerjaan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['pekerjaan'])?></td>
				</tr>
				<tr>
					<td>Golongan Darah</td><td>:</td>
					<td><?php echo strtoupper($penduduk['golongan_darah'])?></td>
			    </tr>
				<tr>
					<td>Cacat</td>
					<td>:</td>
					<td><?php echo strtoupper($penduduk['cacat'])?></td>
			   </tr>
				<tr>
					<td>Status Kawin</td><td >:</td>
					<td><?php echo strtoupper($penduduk['kawin'])?></td>
				</tr>
				<tr>
					<td>Hubungan dalam Keluarga</td><td >:</td>
					<td><?php echo strtoupper($penduduk['hubungan'])?></td>
				</tr>
				<tr>
					<td>Warga Negara</td><td >:</td>
					<td><?php echo strtoupper($penduduk['warganegara'])?></td>
				</tr>
				<tr>
					<td>NIK Ayah</td><td >:</td>
					<td><?php echo strtoupper($penduduk['ayah_nik'])?></td>
				</tr>
				<tr>
					<td>Nama Ayah</td><td >:</td>
					<td><?php echo strtoupper($penduduk['nama_ayah'])?></td>
				</tr>
				<tr>
					<td>NIK Ibu</td><td >:</td>
					<td><?php echo strtoupper($penduduk['ibu_nik'])?></td>
				</tr>
				<tr>
					<td>Nama Ibu</td><td >:</td>
					<td><?php echo strtoupper($penduduk['nama_ibu'])?></td>
				</tr>
				<tr>
					<td>Status Kependudukan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['status'])?></td>
				</tr>
				<tr>
				  <td>Alamat</td><td >:</td>
				  <td><?php echo strtoupper(ununderscore($penduduk['alamat']))?><br>
				      RT. <?php echo strtoupper($penduduk['rt'])?> RW. <?php echo $penduduk['rw']?>
					   <?php echo ucwords($this->setting->sebutan_dusun)?> <?php echo strtoupper(ununderscore($penduduk['dusun']))?>
				  </td>
				</tr>
				<tr>
					<td colspan="4" style="padding-top: 15px;"><strong>DATA KEPEMILIKAN DOKUMEN</strong></td>
				</tr>
				<tr>
					<td>Nomor Kartu Keluarga (No.KK)</td><td >:</td>
					<td><?php echo $penduduk['no_kk']?></td>
				</tr>
				<tr>
					<td>Dokumen Paspor</td><td >:</td>
					<td><?php echo strtoupper($penduduk['dokumen_pasport'])?></td>
				</tr>
				<tr>
					<td>Dokumen Kitas</td><td >:</td>
					<td><?php echo strtoupper($penduduk['dokumen_kitas'])?></td>
				</tr>
				<tr>
					<td>Akta Perkawinan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['akta_perkawinan'])?></td>
				</tr>
				<tr>
					<td>Tanggal Perkawinan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tanggalperkawinan'])?></td>
				</tr>
				<tr>
					<td>Akta Perceraian</td><td >:</td>
					<td><?php echo strtoupper($penduduk['akta_perceraian'])?></td>
				</tr>
				<tr>
					<td>Tanggal Perceraian</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tanggalperceraian'])?></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="center" scope="col" width="40%">Yang Bersangkutan</td>
					<td align="center" scope="col" width="10%">&nbsp;</td>
					<td align="center" scope="col" width="50%"><?php echo ucwords($this->setting->sebutan_desa)?> <?php echo $desa['nama_desa']?>, <?php echo tgl_indo(date("Y m d"))?></td>
			  </tr>
			  <tr>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td align="center">Kepala <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo $desa['nama_desa']?></td>
			  </tr>
			  <tr>
					<td align="center" height="100">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
			  </tr>
			  <tr>
					<td align="center" height="100">( <?php echo strtoupper($penduduk['nama'])?> )</td>
					<td align="center">&nbsp;</td>
					<td align="center"><b>( <?php echo $desa['nama_kepala_desa']?> )</b></td>
			  </tr>
			</table>


		</div>
		<div id="aside">
		</div>
	</div>
</body>
