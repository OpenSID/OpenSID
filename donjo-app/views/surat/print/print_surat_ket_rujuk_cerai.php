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
</table>
<table width="100%">
<div align="center"><u><h4 class="kop">SURAT KETERANGAN RUJUK/CERAI</h4></u></div>
<tr><th align="center"><?< class="kop">Nomor : 474.2/<?php echo $input['nomor']?>/KESRA/<?php echo date("Y")?></th></tr>
</table>
<div class="clear"></div>

<table width="100%">
</table>
<table width="100%">
<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>  Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa :  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="23%">Nama</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td width="23%">Bin</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($pribadi['nama_ayah']); ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $pribadi['tempatlahir']; ?>, <?php echo tgl_indo($pribadi['tanggallahir']); ?></td></tr>
<tr><td width="23%">Warga Negara</td><td width="3%">:</td><td width="64%"><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $pribadi['agama']; ?></td></tr>
<tr><td width="23%">Pekerjaan</td><td width="3%">:</td><td width="64%"><?php echo $pribadi['pek']; ?></td></tr>
<tr><td width="23%">Alamat</td><td width="3%">:</td><td width="64%"> <?php echo $pribadi['alamat']?> RT. <?php echo $pribadi['rt']?> RW. <?php echo $pribadi['rw']?> Dusun <?php echo unpenetration(ununderscore($pribadi['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr><td colspan="3"><b>telah rujuk/ cerai *) dengan : </td></tr>
<tr><td width="23%">Nama</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama_pasangan']); ?></td></tr>
<tr><td width="23%">Binti</td><td width="3%">:</td><td width="64%"><?php echo $input['nama_ayah_pasangan']; ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $input['tempatlahir_pasangan']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_pasangan'])); ?></td></tr>
<tr><td width="23%">Warga Negara</td><td width="3%">:</td><td width="64%"><?php echo $input['wn_pasangan']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_pasangan']; ?></td></tr>
<tr><td width="23%">Pekerjaan</td><td width="3%">:</td><td width="64%"><?php echo $input['pekerjaan_pasangan']; ?></td></tr>
<tr><td width="23%">Alamat</td><td width="3%">:</td><td width="64%"><?php echo $input['alamat_pasangan']; ?></td></tr>

</table>
<table width="100%">
<tr></tr>
<tr></tr>
<tr>
					<td class="indentasi">Demikianlah, surat keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.</td>
				</tr>
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
<tr><td><td></td><td td align="center"><b><u><?php echo unpenetration($input['pamong'])?> </td></tr>
<tr><td><td></td><td td align="center"><?php echo unpenetration($input['pamong_nip'])?> </td></tr>
<tr><td colspan="3">*)coret yang tidak perlu<td></td>
</table>  </div></div>


<div id="aside">
</div>
</div>
</body>
</html>
