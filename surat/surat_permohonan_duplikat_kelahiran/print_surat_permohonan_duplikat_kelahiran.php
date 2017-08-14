<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt=""  class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop"><?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT PERMOHONAN DUPLIKAT KELAHIRAN</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<td class="indentasi">Dengan ini kami mengajukan orang untuk mengadakan Permohonan Duplikat Kelahiran seperti tersebut di bawah ini :  </td></tr>
</table>
<div id="isi">
<table width="100%">
	<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($data['nama'])?></td></tr>
	<tr><td >NIK</td><td >:</td><td ><?php echo $data['nik']?></td></tr>
	<tr><td >Jenis Kelamin</td><td >:</td><td ><?php echo $data['sex']?></td></tr>
	<tr><td >Tanggal Lahir</td><td >:</td><td ><?php echo tgl_indo($data['tanggallahir'])?></td></tr>
	<tr><td >Agama</td><td >:</td><td ><?php echo $data['agama']?></td></tr>
	<tr><td >Alamat</td><td >:</td><td >RT. <?php echo unpenetration($data['rt'])?>, RW. <?php echo unpenetration($data['rw'])?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
	<tr><td >Telah lahir pada :</td><td ></td><td ></td></tr>
	<tr><td >Hari, Tanggal, Pukul</td><td >:</td><td ><?php echo $input['hari_bayi']?>,  <?php echo $input['jam_bayi']?></td></tr>
	<tr><td >Bertempat di</td><td >:</td><td ><?php echo $input['tempatlahir_bayi']?></td></tr>
	<tr></tr>
	<tr><td >Nama Ibu</td><td >:</td><td ><?php echo unpenetration($ibu['nama'])?></td></tr>
	<tr><td >NIK</td><td >:</td><td ><?php echo $ibu['nik']?></td></tr>
	<tr><td >Tanggal lahir</td><td >:</td><td ><?php echo tgl_indo($ibu['tanggallahir'])?></td></tr>
	<tr><td >Pekerjaan</td><td >:</td><td ><?php echo $ibu['pek']?></td></tr>
	<tr><td >Alamat</td><td >:</td><td >RT. <?php echo unpenetration($ibu['rt'])?>, RW. <?php echo unpenetration($ibu['rw'])?>, Dusun <?php echo unpenetration(ununderscore($ibu['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
	<tr><td >Nama Ayah</td><td >:</td><td ><?php echo unpenetration($ayah['nama'])?></td></tr>
	<tr><td >NIK</td><td >:</td><td ><?php echo $ayah['nik']?></td></tr>
	<tr><td >Tanggal lahir</td><td >:</td><td ><?php echo tgl_indo($ayah['tanggallahir'])?></td></tr>
	<tr><td >Pekerjaan</td><td >:</td><td ><?php echo $ayah['pek']?></td></tr>
	<tr><td >Alamat</td><td >:</td><td >RT. <?php echo unpenetration($ayah['rt'])?>, RW. <?php echo unpenetration($ayah['rw'])?>, Dusun <?php echo unpenetration(ununderscore($ayah['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<div id="isi2">
<table width="100%">
<tr>Surat Keterangan ini dibuat berdasarkan keterangan pelapor :</tr>
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_pelapor'])?></td></tr>
<tr><td width="23%">NIK/ No. KTP</td><td width="3%">:</td><td width="64%"><?php echo $input['nik_pelapor']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo ($input['tempatlahir_pelapor'])?> <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_pelapor']))?> </td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $input['sex_pelapor']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pek_pelapor']?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?php echo $input['alamat_pelapor']?></td></tr>
</table>
<table width="100%">
<tr></tr>
<td class="indentasi">Demikian surat keterangan ini dibuat, atas perhatian dan terkabulnya diucapkan terimakasih. </td>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table></div>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%"></td><td width="30%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
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
<tr><td> <td></td><td align="center">( <?php echo unpenetration($input['pamong'])?> )</td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
