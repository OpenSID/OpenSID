<?php $this->load->view('print/headjs.php');?>

<body>
	<div id="content" class="container_12 clearfix">
		<div id="content-main" class="grid_7">

			<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
			<table width="100%" style="border: solid 2px black; text-align: center; padding-top: 20px; padding-bottom: 20px;">
				<tr>
					<td>
						<h2>BIODATA PENDUDUK</h3>
						<h2><?php echo $desa['desa']['nama_kabupaten']?>, <?php echo ucwords($this->setting->sebutan_kecamatan_singkat)?> <?php echo $desa['desa']['nama_kecamatan']?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo $desa['desa']['nama_desa']?></h2>
						<h4>No. <?php echo $penduduk['nik']?></h5>
					</td>
				</tr>
			</table>

			<table width="100%" style="border: solid 2px black; padding: 10px;">
				<tr>
					<td width="220">Nama</td><td width="1">:</td>
					<td><?php echo strtoupper($penduduk['nama'])?></td>
					<td rowspan="18" style="vertical-align: middle;">
						<?php if($penduduk['foto']){?>
							<img src="<?php echo AmbilFoto($penduduk['foto'])?>" alt="" style="border: solid 1px black; padding: 5px;"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>Akta lahir</td><td >:</td>
					<td><?php echo strtoupper($penduduk['akta_lahir'])?></td>
				</tr>
				<tr>
				  <td>Alamat</td><td >:</td>
				  <td><?php echo strtoupper(ununderscore($penduduk['alamat']))?></td>
				</tr>
				<tr>
					<td><?php echo ucwords($this->setting->sebutan_dusun)?></td><td >:</td>
					<td><?php echo strtoupper(ununderscore($penduduk['dusun']))?></td>
				</tr>
				<tr>
					<td>RT/ RW</td><td >:</td>
					<td><?php echo strtoupper($penduduk['rt'])?> / <?php echo $penduduk['rw']?></td>
				</tr>
				<tr>
					<td>Jenis Kelamin</td><td >:</td>
					<td><?php echo strtoupper($penduduk['sex'])?></td>
				</tr>
				<tr>
					<td>Tempat / Tanggal Lahir</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tempatlahir'])?> / <?php echo strtoupper($penduduk['tanggallahir'])?></td>
				</tr>
				<tr>
					<td>Agama</td><td >:</td>
					<td><?php echo strtoupper($penduduk['agama'])?></td>
				</tr>
				<tr>
				  <td>Pendidikan Dalam KK</td><td >:</td>
				  <td><?php echo strtoupper($penduduk['pendidikan_kk'])?></td>
				</tr>
				<tr>
				  <td>Pendidikan Sedang Ditempuh</td><td >:</td>
				  <td><?php echo strtoupper($penduduk['pendidikan_sedang'])?></td>
				</tr>
				<tr>
					<td>Pekerjaan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['pekerjaan'])?></td>
				</tr>
				<tr>
					<td>Status Kawin</td><td >:</td>
					<td><?php echo strtoupper($penduduk['kawin'])?></td>
				</tr>
				<tr>
					<td>Warga Negara</td><td >:</td>
					<td><?php echo strtoupper($penduduk['warganegara'])?></td>
				</tr>
				<tr>
					<td>Dokumen Pasport</td><td >:</td>
					<td><?php echo strtoupper($penduduk['dokumen_pasport'])?></td>
					</tr>
				<tr>
					<td>Dokumen Kitas</td><td >:</td>
					<td><?php echo strtoupper($penduduk['dokumen_kitas'])?></td>
				</tr>
				<tr>
					<td>Alamat Sekarang</td><td >:</td>
					<td><?php echo strtoupper($penduduk['alamat_sekarang'])?></td>
				</tr>
				<tr>
					<td>Akta perkawinan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['akta_perkawinan'])?></td>
				</tr>
				<tr>
					<td>Tanggal perkawinan</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tanggalperkawinan'])?></td>
				</tr>
				<tr>
					<td>Akta perceraian</td><td >:</td>
					<td><?php echo strtoupper($penduduk['akta_perceraian'])?></td>
				</tr>
				<tr>
					<td>Tanggal perceraian</td><td >:</td>
					<td><?php echo strtoupper($penduduk['tanggalperceraian'])?></td>
				</tr>

				<tr>
					<td colspan="4" style="padding-top: 15px;"><strong>Data Orang Tua</strong></td>
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
					<td>Status</td><td >:</td>
					<td><?php echo strtoupper($penduduk['status'])?></td>
				</tr>

				<tr>
					<td colspan="4" style="padding-top: 20px;"><label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?></td>
				</tr>

			</table>

		</div>
		<div id="aside">
		</div>
	</div>
</body>
