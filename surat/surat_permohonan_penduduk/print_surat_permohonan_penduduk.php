<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper($desa['nama_kabupaten'])?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper($desa['nama_kecamatan'])?> </h4>
<h4 class="kop"><?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper($desa['nama_desa'])?></h4>
<h5 class="kop2"><?php echo ($desa['alamat_kantor'])?> </h5>

<div style="text-align: center;">
<hr /></div></div>


<div align="center"><h4 class="kop">SURAT KETERANGAN PENDUDUK</h4></div>
<div align="center"><h4 class="kop"><u>NO: <?php echo $input['nomor']?></u></h4></div>
</table>
<div class="clear"></div>

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo $pamong['jabatan']?> <?php echo $desa['nama_desa']?>, Kecamatan <?php echo $desa['nama_kecamatan']?>,
<?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo $desa['nama_kabupaten']?>, Provinsi <?php echo $desa['nama_propinsi']?> menerangkan bahwa:  </td></tr>
</table>
<div id="isi3">
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo $data['nama']?></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir (Umur)</td><td>:</td><td><?php echo $data['tanggallahir']?> <?php echo $data['tanggallahir']?> (<?php echo $data['umur']?> Tahun)</td></tr>
<tr><td>Status</td><td>:</td><td><?php echo $data['status_kawin']?></td></tr>
<tr><td>Kewarganegaraan / Agama</td><td>:</td><td><?php echo $data['warganegara_id']?> / <?php echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>No KTP</td><td>:</td><td><?php echo $data['nik']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo ununderscore($data['dusun'])?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo $desa['nama_desa']?>, Kec. <?php echo $desa['nama_kecamatan']?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo $desa['nama_kabupaten']?></td></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
<tr>
<td>Orang tersebut adalah benar-benar warga kami yang bertempat tinggal di Dusun <?php echo $data['dusun']?>, Rt. <?php echo $data['rt']?>, <?php echo $desa['nama_desa']?>, <?php echo $desa['nama_kecamatan']?>, <?php echo $desa['nama_kabupaten']?> tercatat dalam
No. KK: <?php echo $data['no_kk']?> dengan NIK: <?php echo $data['nik']?>, kepala keluarga : <?php echo $data['kepala_kk']?>.</td>
</tr>
<tr></tr>
<tr></tr>
<tr><td>Surat keterangan ini diterbitkan sebagai <?php echo $input['keterangan']?>.</td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td>Demikianlah surat ini kami buat dengan sesungguhnya semoga dapat dipergunakan sebagaimana mestinya.</td></tr>
</table>
<table width="100%">
<tr></tr>
<tr><td width="23%"></td><td width="43%"></td><td>Diterbitkan di:  <?php echo $desa['nama_desa']?>,</td></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%"></td><td width="43%"></td><td>Tanggal       : <?php echo $tanggal_sekarang?></td></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%">Pemegang</td><td width="43%"></td><td><?php echo $pamong['jabatan']?> <?php echo $desa['nama_desa']?></td></tr>
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
<tr><td><?php echo $input['pamong']?> <td></td><td><?php echo $pamong['jabatan']?></td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
