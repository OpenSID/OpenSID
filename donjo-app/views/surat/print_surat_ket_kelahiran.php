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


<div align="center"><u><h4 class="kop">SURAT KETERANGAN KELAHIRAN</h4></u></div>
<div align="center"><h4 class="kop">NO: <?=$input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<td class="indentasi">Yang bertanda tangan dibawah ini <?=unpenetration($input['jabatan'])?> <?=unpenetration($desa['nama_desa'])?>, Kecamatan <?=unpenetration($desa['nama_kecamatan'])?>, Kabupaten
    <?=unpenetration($desa['nama_kabupaten'])?>, Provinsi <?=unpenetration($desa['nama_propinsi'])?> menerangkan bahwa pada: </td></tr>
</table>
<div id="isi">
<table width="100%">
<tr><td width="35%">Hari</td><td width="3%">:</td><td width="64%"><?=$input['hari']?></td></tr>
<tr><td width="35%">Tanggal</td><td width="3%">:</td><td width="64%"><?=tgl_indo(tgl_indo_in($input['tanggal']))?></td></tr>
<tr><td>Pukul </td><td>:</td><td><?=$input['jam']?></td></tr>
<tr><td>Tempat Kelahiran</td><td>:</td><td><?=$input['tempat_lahir_bayi']?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3">Telah lahir seorang anak <?=$input['sex_bayi']?> bernama : <?=unpenetration($input['nama_bayi'])?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3">Dari seorang ibu :</td></tr>

tr><td>Nama Lengkap</td><td>:</td><td><?=unpenetration($ibu['nama'])?></td></tr>
<tr><td>NIK</td><td>:</td><td><?=$ibu['nik']?></td></tr>
<tr><td>Umur</td><td>:</td><td><?=$ibu['umur']?> tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?=$ibu['pek']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?=$ibu['rt']?>, RW. <?=$ibu['rw']?>, Dusun <?=unpenetration(ununderscore($ibu['dusun']))?>, Desa <?=unpenetration($desa['nama_desa'])?>, Kec. <?=unpenetration($desa['nama_kecamatan'])?>, Kab. <?=unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3">Istri dari :</td></tr>

<tr><td>Nama Lengkap</td><td>:</td><td><?=unpenetration($ayah['nama'])?></td></tr>
<tr><td>NIK</td><td>:</td><td><?=$ayah['nik']?></td></tr>
<tr><td>Umur</td><td>:</td><td><?=$ayah['umur']?> tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?=$ayah['pek']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?=$ayah['rt']?>, RW. <?=$ayah['rw']?>, Dusun <?=unpenetration(ununderscore($ayah['dusun']))?>, Desa <?=unpenetration($desa['nama_desa'])?>, Kec. <?=unpenetration($desa['nama_kecamatan'])?>, Kab. <?=unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Hubungan pelapor dengan bayi :</td><td>:</td><td><?=$input['hubungan']?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>

<tr><td colspan="3">Surat keterangan ini dibuat berdasarkan keterangan pelapor :</td></tr>
<tr><td>Nama Lengkap</td><td>:</td><td><?=unpenetration($input['nama_pelapor'])?></td></tr>
<tr><td>NIK</td><td>:</td><td><?=$input['nik_pelapor']?></td></tr>
<tr><td>Umur</td><td>:</td><td><?=$input['umur_pelapor']?> tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?=$input['pek_pelapor']?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?=$input['alamat_pelapor']?></td></tr>
<tr><td>Hubungan pelapor dengan bayi </td><td>:</td><td><?=$input['hubungan']?></td></tr>
</table>
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
<tr><td> <td></td><td align="center">( <?=unpenetration($input['pamong'])?> )</td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
