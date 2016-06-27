<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">


<div id="ktp">
<div class="header" align="center">
<h3>BIODATA PENDUDUK</h3>
<h2><?php echo $desa['desa']['nama_kabupaten']?>, Kec. <?php echo $desa['desa']['nama_kecamatan']?>, Desa <?php echo $desa['desa']['nama_desa']?></h2>
<h5>No. <?php echo $penduduk['nik']?></h5>
</div>
<br>
<br>
<br>
<table class="" width="100%">
<tr>
<td width="150">Nama</td><td width="1">:</td>
<td><?php echo strtoupper($penduduk['nama'])?></td><td rowspan="18"><?php if($penduduk['foto']){?>
<img src="<?php echo AmbilFoto($penduduk['foto'])?>" alt=""/>
<?php }?>
<tr>
<td>Akta lahir</td><td >:</td>
<td><?php echo strtoupper($penduduk['akta_lahir'])?></td>
</tr>

<tr>
<td>Dusun</td><td >:</td>
<td><?php echo strtoupper(ununderscore($penduduk['dusun']))?></td></tr>

<tr>
<td>RT/ RW</td><td >:</td>
<td><?php echo strtoupper($penduduk['rt'])?> / <?php echo $penduduk['rw']?></td></tr>

<tr>
<td>Jenis Kelamin</td><td >:</td>
<td><?php echo strtoupper($penduduk['sex'])?></td></tr>

<tr>
<td>Tempat / Tanggal Lahir</td><td >:</td>
<td><?php echo strtoupper($penduduk['tempatlahir'])?> / <?php echo strtoupper($penduduk['tanggallahir'])?></td></tr>

<tr>
<td>Agama</td><td >:</td>
<td><?php echo strtoupper($penduduk['agama'])?></td></tr>

<tr>
<td>Pendidikan</td><td >:</td>
<td><?php echo strtoupper($penduduk['pendidikan'])?></td></tr>

<tr>
<td>Pekerjaan</td><td >:</td>
<td><?php echo strtoupper($penduduk['pekerjaan'])?></td></tr>

<tr>
<td>Status Kawin</td><td >:</td>
<td><?php echo strtoupper($penduduk['kawin'])?></td></tr>

<tr>
<td>Warga Negara</td><td >:</td>
<td><?php echo strtoupper($penduduk['warganegara'])?></td></tr>

<tr>
<td>Dokumen Pasport</td><td >:</td>
<td><?php echo strtoupper($penduduk['dokumen_pasport'])?></td></tr>

<tr>
<td>Dokumen Kitas</td><td >:</td>
<td><?php echo strtoupper($penduduk['dokumen_kitas'])?></td></tr>

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
<td>Data Orang Tua</td></tr>

<tr>
<td>NIK Ayah</td><td >:</td>
<td><?php echo strtoupper($penduduk['ayah_nik'])?></td></tr>

<tr>
<td>Nama Ayah</td><td >:</td>
<td><?php echo strtoupper($penduduk['nama_ayah'])?></td></tr>

<tr>
<td>NIK Ibu</td><td >:</td>
<td><?php echo strtoupper($penduduk['ibu_nik'])?></td></tr>


<tr>
<td>Nama Ibu</td><td >:</td>
<td><?php echo strtoupper($penduduk['nama_ibu'])?></td></tr>

<tr>
<td>Status</td><td >:</td>
<td><?php echo strtoupper($penduduk['status'])?></td></tr>

</table>
</div>
</div>

   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

<div id="aside">
</div>
</div>
</body>
</html>