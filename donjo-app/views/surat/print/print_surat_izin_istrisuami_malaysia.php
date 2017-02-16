<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

</table>
<table width="100%">
</table>
<table width="100%">
<div align="center"><u><h4 class="kop">SURAT KETERANGAN IZIN SUAMI/ ISTRI</h4></u></div>

</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini : </td></tr>
</table>
<div id="isi2">
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $pribadi['tempatlahir']; ?>, <?php echo tgl_indo($pribadi['tanggallahir']); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $pribadi['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $pribadi['pek']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td> <?php echo $pribadi['alamat']?> RT. <?php echo $pribadi['rt']?> RW. <?php echo $pribadi['rw']?> Dusun <?php echo unpenetration(ununderscore($pribadi['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>

<table width="100%">
<tr></tr>
<tr>
					<td class="indentasi">Selaku === <?php echo unpenetration($input['status']); ?> === dari nama yang tersebut dibawah ini : </td>
				</tr> 
	<tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo $input['tempatlahir']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan']; ?></td></tr>
<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat']?></td></tr>
</table>

<table width="100%">
<tr></tr>
<tr>
					<td class="indentasi">Dengan ini menyatakan bahwa saya tidak keberatan dan secara sadar serta ikhlas memberikan izin Kepada == <?php echo unpenetration($input['status_anak']); ?> == saya untuk bekerja/ menjadi == <?php echo unpenetration($input['pekerja']); ?> == keluar Negeri === <?php echo unpenetration($input['negara_tujuan']); ?> === yang akan diberangkatkan oleh :</td>
				</tr>
   <tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama PPTKIS</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_pptkis']); ?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?php echo $input['alamat_pptkis']; ?></td></tr>
</table>

<table width="100%">
<tr>
<td class="indentasi">Demikianlah surat keterangan izin ini dibuat dengan penuh tanggung jawab untuk dipergunakan sebagaimana perlunya.</td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">PIHAK KEDUA,</td><td width="30%"></td><td align="center">PIHAK PERTAMA,</td></tr>
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
<table width="100%">
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
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%" align="center"><b><?php echo unpenetration($input['nama'])?> </td><td width="30%"></td><td align="center"><b><u><?php echo unpenetration($data['nama'])?> </td></tr>    
</table>  </div></div>
<tr></tr>
</table></div>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">No. Reg. : <b>474.1/<?php echo unpenetration($input['nomor'])?>/PEM/<?php echo date("Y")?></td><td></h4></div></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">Tanggal : <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">Mengetahui ;<?php echo unpenetration($desa[''])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>,</td></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><b><u> <?php echo unpenetration($input['pamong'])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"> <?php echo unpenetration($input['pamong_nip'])?></td></tr>
<tr>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>

