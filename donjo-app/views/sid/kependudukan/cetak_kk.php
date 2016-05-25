<?php $this->load->view('print/headjs.php');?>



<body>
<div id="container">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
<!-- Print Body -->
<div id="body">

<div align="center">
	<h3>KARTU KELUARGA</h3>
	<h4>SALINAN</h4>
	<h5>No. <?php echo $kepala_kk['no_kk']?> </h4>
</div>

<br>

<table width="100%" cellpadding="3" cellspacing="4">
	<tr>
	<td width="100">Nama KK</td>
	<td width="600">: <?php echo strtoupper($kepala_kk['nama']) ?></td>
	<td width="160">Kecamatan</td>
	<td width="150">: <?php echo strtoupper($desa['nama_kecamatan']) ?></td>
	</tr>
	<tr>
	<td>Alamat</td>
	<td>: <?php echo strtoupper($kepala_kk['dusun']) ?> </td>
	<td>Kabupaten/Kota</td>
	<td>: <?php echo $desa['nama_kabupaten'] ?></td>
	</tr>
	<tr>
	<td>RT / RW</td>
	<td>: <?php echo $kepala_kk['rt']  ?> / <?php echo $kepala_kk['rw']  ?></td>
	<td>Kode Pos</td>
	<td>: <?php echo strtoupper($desa['kode_pos']) ?></td>
	</tr>
	<tr>
	<td>Kelurahan/Desa</td>
	<td>: <?php echo strtoupper($desa['nama_desa']) ?></td>
	<td>Provinsi</td>
	<td>: <?php echo strtoupper($desa['nama_propinsi']) ?></td>
	</tr>
</table>

<br>

<table class="border thick ">
	<thead>
	<tr class="border thick">
		<th width="7">No</th>
		<th width='180'>Nama</th>
		<th width='100'>NIK</th>
		<th width='100'>Jenis Kelamin</th>
		<th width='100'>Tempat Lahir</th>
		<th width='120'>Tanggal Lahir</th>
		<th width='100'>Agama</th>
		<th width='100'>Pendidikan</th>
		<th width='100'>Pekerjaan</th>
	</tr>
	</thead>
	<tbody>
	<?php  foreach($main as $data): ?>
	
	<tr class="data">
		<td align="center" width="2"><?php echo $data['no']?></td>
		<td><?php echo strtoupper($data['nama'])?></td>
		<td><?php echo $data['nik']?></td>
		<td><?php echo $data['sex']?></td>  
		<td><?php echo $data['tempatlahir']?></td> 
		<td><?php echo $data['tanggallahir']?></td> 
		<td><?php echo $data['agama']?></td> 
		<td><?php echo $data['pendidikan']?></td> 
		<td><?php echo $data['pekerjaan']?></td>
	</tr>
	
	<?php  endforeach; ?>
	</tbody>
</table>

<br>

<table class="border thick ">
<thead>
	<tr class="border thick">
		<th width="7">No</th>
		<th width='150'>Status Perkawinan</th>
		<th width='240'>Status Hubungan dalam Keluarga</th>
		<th width='140'>Kewarganegaraan</th>
		<th width='100'>No. Paspor</th>
		<th width='100'>No. KITAS / KITAP</th>
		<th width='170'>Nama Ayah</th>
		<th width='170'>Nama Ibu</th>
		<th width='70'>Golongan darah</th>
	</tr>
</thead>
<tbody>


<?php  foreach($main as $data): ?>

<tr class="data">
<td align="center" width="2"><?php echo $data['no']?></td>
<td><?php echo $data['status_kawin']?></td>
<td><?php echo $data['hubungan']?></td>
<td><?php echo $data['warganegara']?></td>  
<td><?php echo $data['dokumen_pasport']?></td>
<td><?php echo $data['dokumen_kitas']?></td> 
<td><?php echo strtoupper($data['nama_ayah'])?></td> 
<td><?php echo strtoupper($data['nama_ibu'])?></td> 
<td align="center"><?php echo $data['golongan_darah']?></td>
</tr>
<?php  endforeach; ?>
</tbody>
</table>

<br>

<table width="100%" cellpadding="3" cellspacing="4">

<tr>
<td width="100"></td>
<td width="400"></td>
<td align="center" width="150"><?php echo $desa['nama_desa'] ?>, <?php echo tgl_indo(date("Y m d"))?></td>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</tr><tr><td width="23%" align="center">KEPALA KELUARGA</td><td width="30%"></td>
<td width="50"></td>
</tr> 

<tr>
<td width="100"></td>
<td width="400"></td>
<td align="center" width="150">KEPALA DESA <?php echo strtoupper($desa['nama_desa']) ?></td>
<td width="50"></td>
</tr>

<tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td  align="center"><?php echo strtoupper($kepala_kk['nama'])?></td></tr>

<tr>
<td width="100"></td>
<td width="400"></td>
<td align="center" width="150"><?php echo strtoupper($desa['nama_kepala_desa']) ?></td>
<td width="50"></td>
</tr>

</table>

<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>

</div>
   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>


<div id="aside">
</div>
</div>
</body>
</html>
