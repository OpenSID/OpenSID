<?php $this->load->view('print/headjs.php');?>
<body>
	<div id="content" class="container_12 clearfix">
		<div id="content-main" class="grid_7">
			<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
			<div>
				<table width="100%">
					<div>
						
						<div align="center"><td rowspan="18" align="center"><?php if($penduduk['foto']){?>
							<img src="<?php echo base_url()?>assets/files/user_pict/kecil_<?php echo $penduduk['foto']?>" alt=""/>
							<?php }?>
						</td>
					</div>
						<div align="center">
							<h3 style="text-decoration: underline;">BIODATA PENDUDUK WARGANEGARA INDONESIA</h3>
						</div>
					<br>
					<div style="display: inline-block;">
						<table width="100%">
							<tr>
								<td width="90">Kabupaten</td><td width="2px">:</td>
								<td width="300"><?php echo $desa['desa']['nama_kabupaten']?></td>
								<td width="90">Desa</td><td width="2px">:</td>
								<td><?php echo $desa['desa']['nama_desa']?></td>
							</tr>
							<tr>
								<td>Kecamatan</td><td >:</td>
								<td><?php echo $desa['desa']['nama_kecamatan']?></td>
								<td width="90">Dusun</td><td width="2px">:</td>
								<td><?php echo strtoupper($penduduk['dusun'])?></td>
							</tr>

						</table>
					</div>
					<hr></hr>
					<table width="100%">
						<tr><td style="font-weight:bold; line-height: 2em;" colspan="2">DATA PERSONAL</td></tr>
						<tr>
							<td width="2px">1.</td>
							<td width="300">Nama</td><td width="2px">:</td>
							<td><?php echo strtoupper($penduduk['nama'])?></td>
							
							<tr>
							<td width="2px">2.</td>
							<td>NIK</td><td >:</td>
							<td><?php echo strtoupper($penduduk['nik'])?></td>
							</tr>
							
							<tr>
							<td width="2px">3.</td>
							<td>Dusun</td><td >:</td>
							<td><?php echo strtoupper(ununderscore($penduduk['dusun']))?></td>
							</tr>
							
							<tr>
							<td width="2px">4.</td>
							<td>RT/ RW</td><td >:</td>
							<td><?php echo strtoupper($penduduk['rt'])?> / <?php echo $penduduk['rw']?></td>
							</tr>
							
							<tr>
							<td width="2px">5.</td>
							<td>Jenis Kelamin</td><td >:</td>
							<td><?php echo strtoupper($penduduk['sex'])?></td></tr>
							
							<tr>
							<td width="2px">6.</td>
							<td>Tempat / Tanggal Lahir</td><td >:</td>
							<td><?php echo strtoupper($penduduk['tempatlahir'])?> / <?php echo strtoupper($penduduk['tanggallahir'])?></td>
							</tr> 
							
							<tr>
							<td width="2px">7.</td>
							<td>Agama</td><td >:</td>
							<td><?php echo strtoupper($penduduk['agama'])?></td></tr> 
							<tr>
							<td width="2px">8.</td>
							<td>Pendidikan</td><td >:</td>
							<td><?php echo strtoupper($penduduk['pendidikan'])?></td></tr>
							<tr>
							<td width="2px">9.</td>
							<td>Pekerjaan</td><td >:</td>
							<td><?php echo strtoupper($penduduk['pekerjaan'])?></td></tr> 

							<tr>
							<td width="2px">10.</td>
							<td>Status Kawin</td><td >:</td>
							<td><?php echo strtoupper($penduduk['kawin'])?></td></tr>
							<tr>
							<td width="2px">11.</td>
							<td>Warga Negara</td><td >:</td>
							<td><?php echo strtoupper($penduduk['warganegara'])?></td></tr> 
							
							<tr>
							<td width="2px">12.</td>
							<td>Alamat Sekarang</td><td >:</td>
							<td><?php echo strtoupper($penduduk['alamat_sekarang'])?></td>
							</tr>
							<tr>
							<td width="2px">13.</td>
							<td>Akta perkawinan</td><td >:</td>
							<td><?php echo strtoupper($penduduk['akta_perkawinan'])?></td>
							</tr>
							
							<tr>
							<td width="2px">14.</td>
							<td>Data Orang Tua</td></tr> 
							<tr>
							<td width="2px">15.</td>
							<td>NIK Ayah</td><td >:</td>
							<td><?php echo strtoupper($penduduk['ayah_nik'])?></td></tr> 
							<tr>
							<td width="2px">16.</td>
							<td>Nama Ayah</td><td >:</td>
							<td><?php echo strtoupper($penduduk['nama_ayah'])?></td></tr> 
							<tr>
							<td width="2px">17.</td>
							<td>NIK Ibu</td><td >:</td>
							<td><?php echo strtoupper($penduduk['ibu_nik'])?></td></tr>
							<tr>
							<td width="2px">18.</td>
							<td>Nama Ibu</td><td >:</td>
							<td><?php echo strtoupper($penduduk['nama_ibu'])?></td></tr>
							<tr>
							<td width="2px">19.</td>
							<td>Status</td><td >:</td>
							<td><?php echo strtoupper($penduduk['status'])?></td></tr>

							<tr><td colspan="2" style="font-weight:bold; line-height: 2em;">DATA KEPEMILIKAN DOKUMEN</td></tr>
							<tr>
							<td width="2px">1.</td>
							<td>Nomor Kartu Keluarga (No.KK)</td><td >:</td>
							<td><?php echo strtoupper($penduduk['no_kk'])?></td>
							</tr> 
							<tr>
							<td width="2px">2.</td>
							<td>Nomor Akta Kelahiran</td><td >:</td>
							<td><?php echo strtoupper($penduduk['akta_lahir'])?></td>
							</tr> 
							<tr>
							<td width="2px">3.</td>
							<td>Dokumen Pasport</td><td >:</td>
							<td><?php echo strtoupper($penduduk['dokumen_pasport'])?></td></tr>
							<tr>
							<td width="2px">4.</td>
							<td>Dokumen Kitas</td><td >:</td>
							<td><?php echo strtoupper($penduduk['dokumen_kitas'])?></td></tr>
							<tr>
							<td width="2px">5.</td>
							<td>Nomor Paspor</td><td >:</td>
							<td><?php echo strtoupper($penduduk['no_paspor'])?></td>
							</tr> 
							
							<tr>
							<td width="2px">6.</td>
							<td>Nomor Perkawinan</td><td >:</td>
							<td><?php echo strtoupper($penduduk['nomor_kawin'])?></td>
							</tr>
							<tr>
							<td width="2px">7.</td>
							<td>Tanggal perkawinan</td><td >:</td>
							<td><?php echo strtoupper($penduduk['tanggalperkawinan'])?></td>
							</tr>
							<tr>
							<td width="2px">8.</td>
							<td>Akta perceraian</td><td >:</td>
							<td><?php echo strtoupper($penduduk['akta_perceraian'])?></td>
							</tr>
							<tr>
							<td width="2px">9.</td>
							<td>Tanggal perceraian</td><td >:</td>
							<td><?php echo strtoupper($penduduk['tanggalperceraian'])?></td>
							</tr>
							
							</table>
																						</div>
																					</div>       


																				</div>
																				<div style="float:right;">
																					<label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>																				</div>
																				</div>
																			</body>
																			</html>
