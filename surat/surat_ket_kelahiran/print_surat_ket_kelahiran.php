<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css"/>
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


<div align="center"><u><h4 class="kop">SURAT KETERANGAN KELAHIRAN</h4></u></div>
<div align="center"><h4 class="kop">NO: <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan bahwa pada: </td></tr>
</table>
<div id="isi">
<table width="100%">
<tr><td width="35%">Hari</td><td width="3%">:</td><td width="64%"><?php echo $input['hari']?></td></tr>
<tr><td width="35%">Tanggal</td><td width="3%">:</td><td width="64%"><?php echo tgl_indo(tgl_indo_in($input['tanggal']))?></td></tr>
<tr><td>Pukul </td><td>:</td><td><?php echo $input['jam']?></td></tr>
<tr><td>Tempat Kelahiran</td><td>:</td><td><?php echo $input['alamat_lahir_bayi']?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3">Telah lahir seorang anak <?php echo $input['sex']?> bernama : <?php echo unpenetration($input['nama_bayi'])?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3"><strong>Dari seorang ibu :</strong></td>
</tr>

tr><td>Nama Lengkap</td><td>:</td><td><?php echo unpenetration($data['nama'])?></td>
</tr>
<tr><td>NIK</td><td>:</td><td><?php echo $data['nik']?></td></tr>
<tr><td>Umur</td><td>:</td><td><?php echo $data['umur']?> tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3"><strong>Istri dari :</strong></td>
</tr>

<tr><td>Nama Lengkap</td><td>:</td><td><?php echo unpenetration($data['nama_suami'])?></td></tr>
<tr><td>NIK</td><td>:</td><td><?php echo $data['nik_suami']?></td></tr>
<tr><td>Umur</td><td>:</td><td><?php echo $data['umur_suami']?> tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pek_suami']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Hubungan pelapor dengan bayi :</td><td>:</td><td><?php echo $input['hubungan']?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>

<tr><td colspan="3">Surat keterangan ini dibuat berdasarkan keterangan pelapor :</td></tr>
<tr><td>Nama Lengkap</td><td>:</td><td><?php echo unpenetration($input['nama_pelapor'])?></td></tr>
<tr><td>NIK</td><td>:</td><td><?php echo $input['nik_pelapor']?></td></tr>
<tr><td>Umur</td><td>:</td><td><?php echo $input['umur_pelapor']?> tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekejaan_pelapor']?></td></tr>
<tr><td>Hubungan pelapor dengan bayi </td><td>:</td><td><?php echo $input['hubungan_pelapor']?></td></tr>
</table>
<table width="100%">
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
</table></div>
<table width="100%">
<tr></tr>
<tr><td width="10%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="10%"></td><td width="30%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
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
