<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt=""  class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>


<div align="center"><h4 class="kop">SURAT PERSETUJUAN MEMPELAI</h4></div>
<div align="center"><h4 class="kop"><u>NO: <?php echo $input['nomor']?></u></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini :  </td></tr>
</table>
<div id="isi3">
<table width="100%">
<tr></tr>

<tr>
<td>I. Calon Suami</td>
</tr>
<tr></tr>
</table>
<table width="100%">
<?php  if($suami){?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($suami['nama'])?></td></tr>
	<tr><td width="30%">Bin</td><td width="3%">:</td><td width="64%"><?php echo $suami['nama_ayah']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $suami['tempatlahir']?>, <?php echo tgl_indo($suami['tanggallahir'])?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?php echo $suami['wn']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?php echo $suami['agama']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?php echo $suami['pek']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?php echo $suami['rt']?>, RW. <?php echo $suami['rw']?>, Dusun <?php echo ununderscore($suami['dusun'])?>, Desa <?php echo $desa['nama_desa']?>, Kec. <?php echo $desa['nama_kecamatan']?>, Kab. <?php echo $desa['nama_kabupaten']?> </td></tr>
<?php  }else{?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_suami'])?></td></tr>
	<tr><td width="30%">Bin</td><td width="3%">:</td><td width="64%"><?php echo $input['bin_suami']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['tempatlahir_suami']?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_suami']))?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_suami']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_suami']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_suami']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['tempat_tinggal_suami']?></td></tr>

<?php  }?>
</table>
<table width="100%">
<tr></tr>
<tr>
<td>II. Calon Istri</td>
</tr>
<tr></tr>
</table>
<table width="100%">
<?php  if($istri){?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($istri['nama'])?></td></tr>
	<tr><td width="30%">Binti</td><td width="3%">:</td><td width="64%"><?php echo $istri['nama_ayah']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $istri['tempatlahir']?>, <?php echo tgl_indo($istri['tanggallahir'])?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?php echo $istri['wn']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?php echo $istri['agama']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?php echo $istri['pek']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?php echo $istri['rt']?>, RW. <?php echo $istri['rw']?>, Dusun <?php echo ununderscore($istri['dusun'])?>, Desa <?php echo $desa['nama_desa']?>, Kec. <?php echo $desa['nama_kecamatan']?>, Kab. <?php echo $desa['nama_kabupaten']?> </td></tr>
<?php  }else{?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_istri'])?></td></tr>
	<tr><td width="30%">Binti</td><td width="3%">:</td><td width="64%"><?php echo $input['binti_istri']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['tempatlahir_istri']?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_istri']))?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_istri']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_istri']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_istri']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['tempat_tinggal_istri']?></td></tr>

<?php  }?>
</table>
<table width="100%">
<tr></tr>

<tr>
<td class="indentasi">Menyatakan dengan sesungguhnya bahwa atas dasar sukarela dengan kesadaran sendiri, tanpa paksaan dari siapapun untuk melangsungkan pernikahan.</td>
<tr></tr>
<td class="indentasi">Demikianlah surat persetujuan ini dibuat untuk dipergunakan seperlunya.</td>
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
<tr><td width="25%"></td><td width="30%"></td><td  align="center"><?php echo $desa['nama_desa']?>, <?php echo $tanggal_sekarang?></td></tr>
<tr></tr>
<tr><td align="center">I. Calon Suami</td><td></td><td align="center">II. Calon Istri</td></tr>
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
<tr><td align="center">( <?php if($suami){ echo unpenetration($suami['nama']);}else{ echo unpenetration($input['nama_suami']);}?> )</td><td></td>
<td align="center">( <?php if($istri){ echo unpenetration($istri['nama']);} else{ echo unpenetration($input['nama_istri']);}?> )</td></tr>

</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
