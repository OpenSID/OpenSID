<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>

<div style="text-align: center;">
<hr /></div></div>

</table>
<table width="100%">
</table>
<table width="100%">
<div align="center"><u><h4 class="kop">SURAT KETERANGAN</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : 471.2/<?php echo $input['nomor']?>/PEM/<?php echo date("Y")?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<tr><td class="indentasi">Yang bertanda tangan dibawah ini <?php  echo unpenetration($input['jabatan'])?> <?php  echo unpenetration($desa['nama_desa'])?> Kecamatan <?php  echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php  echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa:  </td></tr>
</table>
</table><div id="isi3">
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="1%">:</td><td width="60%"><b><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td width="23%">NIK/ No KTP</td><td width="1%">:</td><td width="60%"><?php echo $data['nik']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Status</td><td>:</td><td><?php echo $data['status_kawin']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Kewarganegaraan </td><td>:</td><td><?php echo $data['warganegara']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td> <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo ununderscore(unpenetration($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<table width="100%"> 
<td  class="indentasi" colspan="3">Orang tersebut diatas adalah benar-benar warga kami yang bertempat tinggal di <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo ununderscore(unpenetration($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> dan tercatat dengan
No. KK : <?php echo $data['no_kk']?> Kepala Keluarga : <?php echo unpenetration($data['kepala_kk'])?>.</td>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
<tr><td class="indentasi">Surat Keterangan ini dibuat untuk Keperluan : <b><?php echo $input['keperluan']?>
<tr></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
<tr><td class="indentasi">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya</td></tr>
<tr></tr>
</table>
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
</table></div>
<table width="100%">
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
</table>
<table width="100%">
</table>
<table width="100%">
<tr><td></td><td width="50%"></td><td  align="center"><b><u><?php echo unpenetration($input['pamong'])?></td></tr>
<tr><td></td><td width="50%"></td><td align="center"><?php echo unpenetration($input['pamong_nip'])?></td></tr>					
</table>  </div></div>
</div>
<div id="aside">

</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>

