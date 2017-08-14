<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
	$this->load->view('print/headjs.php');?>

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

<div align="center"><u><h4 class="kop">BIODATA PENDUDUK</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<div id="isi">
<table width="100%">
<tr><td><b>I. DATA KELUARGA</b></td></tr>
<tr><td width="40%">Nama Kepala Keluarga</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($kk['kepala_kk'])?></td></tr>
<tr><td>Nomor Kartu Keluarga</td><td>:</td><td><?php echo $kk['no_kk']?></td></tr>
<tr><td>Alamat Keluarga</td><td>:</td><td>RT. <?php echo $kk['rt']?>, RW. <?php echo $kk['rw']?>, Dusun <?php echo ununderscore(unpenetration($kk['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?> </td></tr>
</table>
<br/>
<table width="100%">
<tr><td ><b>II.	DATA INDIVIDU</b></td></tr>
<tr><td width="40%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td >NIK</td><td>:</td><td><?php echo $data['nik']?></td></tr>
<tr><td>Alamat Sebelumnya</td><td>:</td><td><?php echo $input['alamat_sebelumnya']?></td></tr>
<tr><td>Nomor Paspor</td><td>:</td><td><?php echo $input['no_paspor']?></td></tr>
<tr><td>Tanggal Berakhir Paspor</td><td>:</td><td><?php echo tgl_indo(tgl_indo_in($input['tgl_berakhir_paspor']))?></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Tempat Lahir</td><td>:</td><td><?php echo $data['tempatlahir']?> </td></tr>
<tr><td>Tanggal Lahir</td><td>:</td><td> <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Akte Kelahiran /Surat Kelahiran</td><td>:</td><td><?php echo $input['akte_kelahiran']?></td></tr>
<tr><td>No Akte Kelahiran</td><td>:</td><td><?php echo $input['no_akte_kelahiran']?></td></tr>
<tr><td>Golongan Darah</td><td>:</td><td><?php echo $pribadi['gol_darah']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Status</td><td>:</td><td><?php echo $data['status_kawin']?></td></tr>
<tr><td>Akte Perkawinan /Buku Nikah</td><td>:</td><td><?php echo $input['akte_perkawinan']?></td></tr>
<tr><td>No Akte /Buku Nikah</td><td>:</td><td><?php echo $input['no_akte_perkawinan']?></td></tr>
<tr><td>Tgl. Akte /Buku Nikah</td><td>:</td><td><?php echo tgl_indo(tgl_indo_in($input['tgl_akte_perkawinan']))?></td></tr>
<tr><td>Akte Perceraian</td><td>:</td><td><?php echo $input['akte_perceraian']?></td></tr>
<tr><td>Tanggal Perceraian</td><td>:</td><td><?php echo tgl_indo(tgl_indo_in($input['tgl_perceraian']))?></td></tr>
<tr><td>Status Hubungan dalam Keluarga</td><td>:</td><td><?php echo $pribadi['hubungan']?></td></tr>
<tr><td>Kelainan Fisik / Mental</td><td>:</td><td><?php echo $pribadi['fis']?> / <?php echo $pribadi['men']?></td></tr>
<tr><td>Pendidikan Terakhir</td><td>:</td><td><?php echo $pribadi['pend']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $pribadi['pek']?></td></tr>

</table>
<br/>
<table width="100%">
<tr></tr>
<tr><td colspan="3"><b>III.	DATA ORANG TUA</b></td></tr>
<tr><td width="40%">Nama Ibu</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($pribadi['nama_ibu'])?></td></tr>
<tr><td>NIK Ibu</td><td>:</td><td><?php echo $pribadi['ibu_nik']?></td></tr>
<tr><td>Nama Ayah</td><td>:</td><td><?php echo unpenetration($pribadi['nama_ayah'])?> </td></tr>
<tr><td>NIK Ayah</td><td>:</td><td><?php echo $pribadi['ayah_nik']?> </td></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
</table>
<table width="100%">
<tr></tr>
<tr><td></td><td width="50%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td></td><td width="50%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
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
