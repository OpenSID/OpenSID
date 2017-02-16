<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>

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
<div align="center"><u><h4 class="kop">SURAT KETERANGAN KEPEMILIKAN</h4></u></div>
<div align="center"><h4 class="kop2">Nomor : 593.7/ <?php echo $input['nomor']?>/PEM/ <?php echo date("Y")?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo $input['jabatan']?> <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa :  </td></tr>
</table>

<div id="isi3 td">
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Nomor KTP</td><td>:</td><td><?php  echo $data['nik']?></td></tr>
<tr><td>Tempat, Tgl. Lahir</td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Umur</td><td>:</td><td><?php echo $data['umur']?> Tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $data['pekerjaan']?></td></tr>
<tr><td>Alamat</td><td>:</td><td> <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php  echo $data['rw']?> Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?> Desa <?php  echo unpenetration($desa['nama_desa'])?> Kecamatan <?php  echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php  echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<tr>Bahwa yang namanya tersebut diatas memang benar memiliki satu unit <?php  echo unpenetration($input['jenis_kendaraan'])?> dengan ciri sebagai berikut :</tr>
<table width="100%">
<tr><td width="30%">Nomor Polisi</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($input['nomor_polisi'])?></td></tr>
<tr><td>Merk/ Type</td><td>:</td><td><?php echo ($input['merk_type'])?> </td></tr>
<tr><td>Jenis/ Model</td><td>:</td><td><?php echo $input['jenis_model']?></td></tr>
<tr><td>Tahun Pembuatan</td><td>:</td><td><?php  echo $input['tahun_pembuatan']?></td></tr>
<tr><td>Tahun Perakitan</td><td>:</td><td><?php  echo $input['tahun_perakitan']?></td></tr>
<tr><td>Isi Silinder</td><td>:</td><td><?php  echo $input['isi_silinder']?></td></tr>
<tr><td>Warna</td><td>:</td><td><?php  echo $input['warna']?></td></tr>
<tr><td>Nomor Rangka</td><td>:</td><td><?php  echo $input['nomor_rangka']?></td></tr>
<tr><td>Nomor Mesin</td><td>:</td><td><?php  echo $input['nomor_mesin']?></td></tr>
<tr><td>Nomor BPKB</td><td>:</td><td><?php  echo $input['nomor_bpkb']?></td></tr>
<tr><td>Atas Nama</td><td>:</td><td><?php echo ($input['nama_pemilik'])?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?php  echo $input['alamat_pemilik']?></td></tr>
</table>
<tr><td>
<table width=""100">
<table width="100%">
<td class="indentasi">Dan sampai saat ini barang tersebut tidak pernah dibuat sebagai jaminan pada pihak lain.</td></tr>
</table>
<table width="100%"> 
<td  class="indentasi" colspan="3">Surat Keterangan ini dibuat untuk Keperluan : <b><?php echo $input['keperluan']?></td></tr>
</table>
<table width="100%"> 
<td class="indentasi">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya</td></tr>
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
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>

</table></div>
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
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td></td><td width="55%"></td><td align="center"><u><b><?php echo unpenetration($input['pamong'])?> </u></td></tr>
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($input['pamong_nip'])?></td></tr>
</table>  </div></div>
</div>
<div id="aside">

</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>

