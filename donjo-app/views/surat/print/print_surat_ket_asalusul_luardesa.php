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

</table>
<table width="100%">
</table>
<table width="100%">
<div align="center"><u><h4 class="kop">SURAT KETERANGAN ASAL-USUL (N-2)</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : 474.2/<?php echo $input['nomor']?>/KESRA/<?php echo date("Y")?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini menerangkan dengan sesungguhnya bahwa :</td></tr>
</table>
<div id="isi1">
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $input['ttl']?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $data['warganegara']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td> <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo unpenetration(ununderscore($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<table width="100%">
<tr></tr>

<tr>
<td>adalah benar anak kandung dari pernikahan seorang pria :</td>
</tr>
 <tr></tr>
</table>
<?php  if($ayah){?>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_ayah'])?></td></tr>
<tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?php echo $input['tempatlahir_ayah']; ?> , <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_ayah'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ayah']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ayah']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pek_ayah']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td> <?php  echo $input['alamat_ayah']; ?></td></tr>
</table>
<?php  }?>
<table width="100%">
<tr></tr>

<tr>
<td>dengan seorang wanita :</td>
</tr>
 <tr></tr>
</table>
<?php  if($ibu){?>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_ibu'])?></td></tr>
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
<td class="indentasi">Demikianlah, surat keterangan ini dibuat dengan mengingat sumpah jabatan dan untuk digunakan seperlunya.</td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>

</table></div>
<table width="100%">
<tr></tr>
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center"></td><td width="30%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>,</td></tr>
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
<tr><td width="23%" align="center"></td><td width="30%"></td><td align="center"><b><u><?php echo unpenetration($input['pamong'])?> </td></tr>
<tr><td width="23%" align="center"></td><td width="30%"></td><td align="center"> <?php echo unpenetration($input['pamong_nip'])?> </td></tr>       
<tr></tr>
</table>  </div></div>
<tr></tr>
<div id="aside">
</div>
</div>
</body>
</html>

