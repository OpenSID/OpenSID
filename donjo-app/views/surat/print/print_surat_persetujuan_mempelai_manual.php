<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">


</table>
<table width="100%">
<div align="center"><u><h4 class="kop">SURAT PERSETUJUAN MEMPELAI (N-3)</h4></u></div>
<tr><th align="center"><?< class="kop">Nomor : 474.2/<?php echo $input['nomor']?>/KESRA/<?php echo date("Y")?></th></tr>
</table>
<div class="clear"></div>

</table>
<table width="100%">
<td class="indentasi">Yang bertanda tangan dibawah ini :  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr></tr>
				<tr><td colspan="3"><b>I. CALON SUAMI  </td></tr>
<tr><td width="23%">Nama</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td width="23%">Bin</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($pribadi['nama_ayah']); ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $pribadi['tempatlahir']; ?>, <?php echo tgl_indo($pribadi['tanggallahir']); ?></td></tr>
<tr><td width="23%">Warga Negara</td><td width="3%">:</td><td width="64%"><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $pribadi['agama']; ?></td></tr>
<tr><td width="23%">Pekerjaan</td><td width="3%">:</td><td width="64%"><?php echo $pribadi['pek']; ?></td></tr>
<tr><td width="23%">Alamat</td><td width="3%">:</td><td width="64%"> <?php echo $pribadi['alamat']?> RT. <?php echo $pribadi['rt']?> RW. <?php echo $pribadi['rw']?> Dusun <?php echo unpenetration(ununderscore($pribadi['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
				
				<tr></tr>
				<tr><td colspan="3"><b>II. COLON ISTRI  </td></tr>
<tr><td width="23%">Nama</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_pasangan']); ?></td></tr>
<tr><td width="23%">Binti</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama_ayah_pasangan']); ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $input['tempatlahir_pasangan']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_pasangan'])); ?></td></tr>
<tr><td width="23%">Warga Negara</td><td width="3%">:</td><td width="64%">WNI</td></tr>
<tr><td>Agama</td><td>:</td><td> ISLAM </td></tr>
<tr><td width="23%">Pekerjaan</td><td width="3%">:</td><td width="64%"><?php echo $input['pekerjaan_pasangan']; ?></td></tr>
<tr><td width="23%">Alamat</td><td width="3%">:</td><td width="64%"> <?php echo $pribadi['alamat']?> </td></tr>

<tr></tr>
<table width="100%">
<tr></tr>
<td class="indentasi">Menyatakan dengan sesungguhnya bahwa atas dasar sukarela dengan kesadaran sendiri, tanpa paksaan dari siapapun untuk melangsungkan pernikahan .</td></tr>
<tr></tr>
<table width="100%">
<tr></tr>
<td class="indentasi">Demikian surat persetujuan ini dibuat untuk dipergunakan perlunya.</td></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
<tr>
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
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>

<tr><td align="center"><b><?php if($pribadi){ echo unpenetration($pribadi['nama']);}else{ echo unpenetration($pribadi['nama']);}?></td><td></td>
<td align="center"><b><?php if($istri){ echo unpenetration($istri['nama']);} else{ echo unpenetration($input['nama_pasangan']);}?> </td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>