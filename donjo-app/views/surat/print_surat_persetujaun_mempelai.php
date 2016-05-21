<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?=base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt=""  class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?=strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?=strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?=strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?=(unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>


<div align="center"><h4 class="kop">SURAT PERSETUJUAN MEMPELAI</h4></div>
<div align="center"><h4 class="kop"><u>NO: <?=$input['nomor']?></u></h4></div>
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
<? if($suami){?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?=unpenetration($suami['nama'])?></td></tr>
	<tr><td width="30%">Bin</td><td width="3%">:</td><td width="64%"><?=$suami['nama_ayah']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?=$suami['tempatlahir']?>, <?=tgl_indo($suami['tanggallahir'])?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?=$suami['wn']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?=$suami['agama']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?=$suami['pek']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?=$suami['rt']?>, RW. <?=$suami['rw']?>, Dusun <?=ununderscore($suami['dusun'])?>, Desa <?=$desa['nama_desa']?>, Kec. <?=$desa['nama_kecamatan']?>, Kab. <?=$desa['nama_kabupaten']?> </td></tr>
<? }else{?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?=unpenetration($input['nama_suami'])?></td></tr>
	<tr><td width="30%">Bin</td><td width="3%">:</td><td width="64%"><?=$input['bin_suami']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?=$input['tempatlahir_suami']?>, <?=tgl_indo(tgl_indo_in($input['tanggallahir_suami']))?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?=$input['wn_suami']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?=$input['agama_suami']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?=$input['pekerjaan_suami']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td><?=$input['tempat_tinggal_suami']?></td></tr>

<? }?>
</table>
<table width="100%">
<tr></tr>
<tr>
<td>II. Calon Istri</td>
</tr>
<tr></tr>
</table>
<table width="100%">
<? if($istri){?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?=unpenetration($istri['nama'])?></td></tr>
	<tr><td width="30%">Binti</td><td width="3%">:</td><td width="64%"><?=$istri['nama_ayah']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?=$istri['tempatlahir']?>, <?=tgl_indo($istri['tanggallahir'])?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?=$istri['wn']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?=$istri['agama']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?=$istri['pek']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?=$istri['rt']?>, RW. <?=$istri['rw']?>, Dusun <?=ununderscore($istri['dusun'])?>, Desa <?=$desa['nama_desa']?>, Kec. <?=$desa['nama_kecamatan']?>, Kab. <?=$desa['nama_kabupaten']?> </td></tr>
<? }else{?>
	<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?=unpenetration($input['nama_istri'])?></td></tr>
	<tr><td width="30%">Binti</td><td width="3%">:</td><td width="64%"><?=$input['binti_istri']?></td></tr>
	<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?=$input['tempatlahir_istri']?>, <?=tgl_indo(tgl_indo_in($input['tanggallahir_istri']))?></td></tr>
	<tr><td>Warganegara</td><td>:</td><td><?=$input['wn_istri']?></td></tr>
	<tr><td>Agama</td><td>:</td><td><?=$input['agama_istri']?></td></tr>
	<tr><td>Pekerjaan</td><td>:</td><td><?=$input['pekerjaan_istri']?></td></tr>
	<tr><td>Tempat Tinggal</td><td>:</td><td><?=$input['tempat_tinggal_istri']?></td></tr>

<? }?>
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
</table>
<table width="100%">
<tr></tr>
<tr><td width="25%"></td><td width="30%"></td><td  align="center"><?=$desa['nama_desa']?>, <?=$tanggal_sekarang?></td></tr>
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
