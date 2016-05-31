<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo base_url()?>assets/files/logo/<?php echo $desa['logo']?>" alt=""  class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT KETERANGAN ASAL - USUL</h4></u></div>
<div align="center"><h4 class="kop">NO: <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini menerangkan dengan sesungguhnya bahwa:</td></tr>
</table>
<div id="isi1">
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $data['tempatlahir']?>, <?php echo tgl_indo($data['tanggallahir'])?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $data['warganegara']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, Kel. <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, Kab. <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<table width="100%">
<tr></tr>

<tr>
<td>adalah benar anak kandung dari pernikahan seorang pria:</td>
</tr>
 <tr></tr>
</table>
<?php  if($ayah){?>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($ayah['nama'])?></td></tr>
<tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?php echo $ayah['tempatlahir']; ?> , <?php echo tgl_indo($ayah['tanggallahir']); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $ayah['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $ayah['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $ayah['pek']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?php echo $ayah['rt']; ?>, RW. <?php echo $ayah['rw']; ?>, Dusun <?php echo unpenetration(ununderscore($ayah['dusun'])); ?>, Kel. <?php echo unpenetration($desa['nama_desa']); ?>, Kec. <?php echo unpenetration($desa['nama_kecamatan']); ?>, Kab. <?php echo unpenetration($desa['nama_kabupaten']); ?></td></tr>
</table>
<?php  } else {?>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_ayah'])?></td></tr>
<tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?php echo $input['tempatlahir_ayah']; ?> , <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_ayah'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ayah']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ayah']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pek_ayah']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php  echo $input['alamat_ayah']; ?></td></tr>
</table>
<?php  }?>
<table width="100%">
<tr></tr>

<tr>
<td>dengan seorang wanita:</td>
</tr>
 <tr></tr>
</table>
<?php  if($ibu){?>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($ibu['nama'])?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $ibu['tempatlahir']; ?>, <?php echo tgl_indo($ibu['tanggallahir']); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $ibu['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $ibu['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $ibu['pek']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td>RW. <?php echo $ibu['rw']; ?>, RT. <?php echo $ibu['rt']; ?>, Dusun <?php echo unpenetration($ibu['dusun']); ?>, Kel. <?php echo unpenetration($desa['nama_desa']); ?>, Kec. <?php echo unpenetration($desa['nama_kecamatan']); ?>, Kab. <?php echo unpenetration($desa['nama_kabupaten']); ?></td></tr>
</table>
<?php  } else {?>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_ibu'])?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $input['tempatlahir_ibu']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_ibu'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ibu']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ibu']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pek_ibu']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php  echo $input['alamat_ibu']; ?></td></tr>
</table>
<?php  }?>
<table width="100%">
<tr></tr>
<tr></tr>
<tr>
<td class="indentasi">Demikianlah, surat keterangan ini dibuat dengan mengingat sumpah jabatan dan untuk dipergunakan seperlunya.</td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>

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
</table></div></div>
<div id="aside">
</div>
</div>
</body>
</html>
