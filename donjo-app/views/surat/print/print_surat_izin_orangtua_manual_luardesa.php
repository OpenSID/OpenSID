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
<div align="center"><u><h4 class="kop">SURAT KETERANGAN IZIN ORANG TUA (N-5)</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : 474.2/<?php echo $input['nomor']?>/KESRA/<?php echo date("Y")?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini : </td></tr>
</table>
<div id="isi2">
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_ayah']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['tempatlahir_ayah']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_ayah'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ayah']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ayah']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_ayah']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat_ayah']?></td></tr>
<tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_ibu']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['tempatlahir_ibu']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_ibu'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_ibu']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_ibu']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_ibu']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td> <?php echo $input['alamat_ibu']?></td></tr>
</table>
<table width="100%">
<tr></tr>
<tr>
<td class="indentasi">Adalah ayah kandung dan ibu kandung dari :</td>
</tr>
   <tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['ttl']; ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $pribadi['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $pribadi['pek']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td> <?php echo $pribadi['alamat']?> RT. <?php echo $pribadi['rt']?> RW. <?php echo $pribadi['rw']?> Dusun <?php echo unpenetration(ununderscore($pribadi['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<table width="100%">
<tr></tr>
<tr>
					<td class="indentasi">Memberikan izin kepadanya untuk melakukan pernikahan dengan :</td>
				</tr>
   <tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_pasangan']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['tempatlahir_pasangan']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_pasangan'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn_pasangan']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_pasangan']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_pasangan']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat_pasangan']?></td></tr>
</table>

<table width="100%">
<tr>
<td class="indentasi">Demikianlah surat izin ini dibuat dengan kesadaran tanpa ada paksaan dari siapapun juga dan dipergunakan seperlunya.</td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table></div>
<table width="100%">
<tr></tr>
<tr><td width="30%"></td><td width="33%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="30%" align="center">I. Ayah</td><td width="33%"></td><td align="center">II. Ibu</td></tr>
<tr><td>
</td></tr>
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
<tr><td align="center">
<tr><td align="center"><b><?php echo unpenetration($input['nama_ayah']);?><td></td><td align="center"><b><?php echo unpenetration($input['nama_ibu']);?></td></tr>
</td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
