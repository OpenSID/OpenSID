<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?=base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt="" class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?=strtoupper($desa['nama_kabupaten'])?> </h4>
<h4 class="kop">KECAMATAN <?=strtoupper($desa['nama_kecamatan'])?> </h4>
<h4 class="kop">DESA <?=strtoupper($desa['nama_desa'])?></h4>
<h5 class="kop2"><?=($desa['alamat_kantor'])?> </h5>

<div style="text-align: center;">
<hr /></div></div>




<div align="center"><h4>SURAT KETERANGAN PENDUDUK</h4></div>
<div align="center"><h4><u>NO: <?=$input['nomor']?></u></h4></div>
<tr>

<td>Yang bertanda tangan dibawah ini <?=$pamong['jabatan']?> <?=$desa['nama_desa']?>, Kecamatan <?=$desa['nama_kecamatan']?>,
Kabupaten <?=$desa['nama_kabupaten']?>, Provinsi <?=$desa['nama_propinsi']?> menerangkan bahwa:  </td></tr>
</table>
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?=$data['nama']?></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?=$data['sex']?></td></tr>
<tr><td>Tempat/Tgl. Lahir (Umur)</td><td>:</td><td><?=$data['tanggallahir']?> / <?=$data['tanggallahir']?> (<?=$data['umur']?> Tahun)</td></tr>
<tr><td>Status</td><td>:</td><td><?=$data['status_kawin']?></td></tr>
<tr><td>Kewarganegaraan / Agama</td><td>:</td><td><?=$data['warganegara_id']?> / <?=$data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?=$data['pekerjaan']?></td></tr>
<tr><td>No KTP</td><td>:</td><td><?=$data['nik']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?=$data['rt']?>, RW. <?=$data['rw']?>, Dusun <?=ununderscore($data['dusun'])?>, Desa <?=$desa['nama_desa']?>, Kec. <?=$desa['nama_kecamatan']?>, Kab. <?=$desa['nama_kabupaten']?></td></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
<tr>
<td>Orang tersebut adalah benar-benar warga kami yang bertempat tinggal di Dusun <?=ununderscore($data['dusun'])?>, Rt. <?=$data['rt']?>, <?=$desa['nama_desa']?>, <?=$desa['nama_kecamatan']?>, <?=$desa['nama_kabupaten']?> tercatat dalam
No. KK: <?=$data['no_kk']?> dengan NIK: <?=$data['nik']?>, kepala keluarga : <?=$data['kepala_kk']?>.</td>
</tr>
<tr></tr>
<tr></tr>
<tr><td>Surat keterangan ini diterbitkan sebagai <?=$input['keterangan']?>.</td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td>Demikianlah surat ini kami buat dengan sesungguhnya semoga dapat dipergunakan sebagaimana mestinya.</td></tr>
</table>
<table width="100%">
<tr></tr>
<tr><td width="23%"></td><td width="43%"></td><td>Diterbitkan di:  <?=$desa['nama_desa']?>,</td></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%"></td><td width="43%"></td><td>Tanggal       : <?=$tanggal_sekarang?></td></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%">Pemegang</td><td width="43%"></td><td><?=$pamong['jabatan']?> <?=$desa['nama_desa']?></td></tr>
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
<tr><td><?=$pamong['pamong_nama']?> <td></td><td><?=$pamong['jabatan']?></td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
