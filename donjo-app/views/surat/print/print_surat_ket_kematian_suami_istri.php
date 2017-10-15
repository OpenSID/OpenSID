<?php $this->load->view('print/headjs.php');?>
<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">
<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">
<tr> <img src="<?php echo base_url()?>assets/files/logo/<?php echo $desa['logo']?>" alt="" class="logo"></tr>
<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>
<div align="center"><u><h4 class="kop">SURAT KETERANGAN KEMATIAN SUAMI/ISTRI</h4></u></div>
<div align="center"><h4 class="kop-nomor">No: <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini menerangkan dengan sesungguhnya bahwa:</td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($input['nama']); ?></td></tr>
<tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?php echo $input['tempat_lahir']; ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggal_lahir'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan']; ?></td></tr>
				<tr><td>Tempat Tinggal</td><td>:</td><td><?php echo $input['tempat_tinggal']; ?></td></tr>
<tr><td>telah meninggal dunia pada tanggal</td><td>:</td><td><?php echo tgl_indo(tgl_indo_in($input['tgl_meninggal'])); ?></td></tr>
<tr><td>di</td><td>:</td><td><?php echo $input['tempat_meninggal']; ?></td></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td>Tempat dan Tgl. Lahir</td><td>:</td><td><?php echo $pribadi['tempatlahir']; ?>, <?php echo tgl_indo($pribadi['tanggallahir']); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $pribadi['agama']; ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $pribadi['pek']; ?></td></tr>
				<tr><td>Tempat Tinggal</td><td>:</td><td>RT. <?php echo $pribadi['rt']; ?>, RW. <?php echo $pribadi['rw']; ?>, Dusun <?php echo unpenetration(ununderscore($pribadi['dusun'])); ?>, Kel. <?php echo unpenetration($desa['nama_desa']); ?>, Kec. <?php echo unpenetration($desa['nama_kecamatan']); ?>, Kab. <?php echo unpenetration($desa['nama_kabupaten']); ?></td></tr>
</table>
<table width="100%">
<tr></tr>
<td>adalah <?php if($pribadi['sex']==1){ echo "istri";} else { echo "suami";} ?> orang yang telah meninggal tersebut di atas.</td>
<tr>
<td class="indentasi">Demikianlah, surat keterangan ini dibua dengan mengingat sumpah jabatan dan untuk dipergunakan seperlunya.</td>
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
<tr><td width="10%"></td><td width="30%"></td><td align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
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
<tr><td><td></td><td td align="center">( <?php echo unpenetration($input['pamong'])?> )</td></tr>
<tr><td colspan="3">*)nama terang<td></td>
</table> </div></div>
<div id="aside">
</div>
</div>
</body>
</html>