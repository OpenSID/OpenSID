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


<div align="center"><u><h4 class="kop">SURAT KETERANGAN TENTANG ORANG TUA</h4></u></div>
<div align="center"><h4 class="kop">NO: <?=$input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini menerangkan dengan sesungguhnya bahwa:  </td></tr>
</table>
 <div id="isi2">
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_ayah']); ?></td></tr>
<tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?php echo $input['tempat_lahir_ayah']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tgl_lahir_ayah'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ayah']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ayah']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_ayah']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['tempat_tinggal_ayah']; ?></td></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_ibu']); ?></td></tr>
<tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?php echo $input['tempat_lahir_ibu']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tgl_lahir_ibu'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ibu']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ibu']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_ibu']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['tempat_tinggal_ibu']; ?></td></tr>
</table>
   <table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr>
<td>adalah benar ayah dan ibu kandung dari seorang:</td>
</tr>
   <tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $pribadi['tempatlahir']; ?> <?php echo tgl_indo($pribadi['tanggallahir']); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $pribadi['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $pribadi['pek']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?php echo $pribadi['rt']; ?>, RW. <?php echo $pribadi['rw']; ?>, Dusun <?php echo unpenetration(ununderscore($pribadi['dusun'])); ?>, Kel. <?php echo unpenetration($desa['nama_desa']); ?>, Kec. <?php echo unpenetration($desa['nama_kecamatan']); ?>, Kab. <?php echo unpenetration($desa['nama_kabupaten']); ?></td></tr>
</table>

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
<tr><td width="10%"></td><td width="30%"></td><td  align="center"><?=unpenetration($desa['nama_desa'])?>, <?=$tanggal_sekarang?></td></tr>
<tr><td width="10%"></td><td width="30%"></td><td align="center"><?=unpenetration($input['jabatan'])?> <?=unpenetration($desa['nama_desa'])?></td></tr>
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
<tr><td><td></td><td td align="center">( <?=unpenetration($pamong['pamong_nama'])?> )</td></tr>
<tr><td colspan="3">*)nama lengkap<td></td>
</table></div></div>
<div id="aside">
</div>
</div>
</body>
</html>
