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


<div align="center"><u><h4>SURAT DISPENSASI WAKTU</h4></u></div>
<div align="center"><h4>NO: <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<td class="indentasi">Berdasarkan permohonan yang bersangkutan tanggal <?php echo $tanggal_sekarang;?> dan pertimbangan dari kepala Kantor Urusan Agama
Kecamatan <?php echo $desa['nama_kecamatan']?> tanggal <?php echo $tanggal_kua;?>, maka kami Camat <?php echo $desa['nama_kecamatan']?> </td></tr>
</table>
<div id="isi3">
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo $data_anggota['nama']; ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $data['tempatlahir']?> <?php echo $data['tanggallahir']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo ununderscore($data['dusun'])?>, Kel. <?php echo $desa['nama_desa']?>, Kec. <?php echo $desa['nama_kecamatan']?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo $desa['nama_kabupaten']?></td></tr>
</table>
<table><tr><td>Untuk melakukan pernikahan <?php echo $keterangan;?></td></tr></table>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo $nama_calon; ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $tempat_lahir_calon; ?> <?php echo $tanggal_lahir_calon; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $pekerjaan_calon; ?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?php echo $alamat_calon; ?></td></tr>
</table>
<table><tr><td>Besok pada hari <?php echo $input['hari']?> tanggal <?php echo $input['tanggalperpanjangan']?></td></tr></table>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
<td class="indentasi">Demikian untuk menjadikan periksa.</td>
</tr>
<tr></tr>
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
<tr><td width="23%"></td><td width="43%"></td><td><?php echo $desa['nama_desa']?>, <?php echo $tanggal_sekarang;?></td></tr>
<tr><td width="23%"></td><td width="43%"></td><td>Camat <?php echo $desa['nama_kecamatan']?></td></tr>
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
<tr><td><td></td><td><?php echo $desa['nama_camat']?></td></tr>
<tr><td colspan="3">*)nama lengkap<td></td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
