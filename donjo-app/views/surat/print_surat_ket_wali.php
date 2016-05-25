<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo base_url()?>assets/images/logo/<?php echo $desa['logo']?>" alt=""  class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT KETERANGAN WALI</h4></u></div>
<div align="center"><h4 class="kop">Nomor : <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo $input['jabatan']?> <?php echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?>,
Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa mempelai perempuan :  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td width="30%">NIK/ No KTP</td><td width="3%">:</td><td width="64%"><?php echo $data['nik']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, Desa <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, Kab. <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td colspan="3">Yang berhak menjadi wali adalah : </td></tr>
<tr><td>Nama </td><td>:</td><td> <?php echo $input['nama_wali']?></td></tr>
<tr><td>Tempat dan Tanggal Lahir </td><td>:</td><td> <?php echo $input['tempat_lahir_wali']?>, <?php echo tgl_indo(tgl_indo_in($input['tgl_lahir_wali']))?></td></tr>
<tr><td>Agama </td><td>:</td><td> <?php echo $input['agama_wali']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td> <?php echo $input['pekerjaan_wali']?></td></tr>
<tr><td>Tempat Tinggal </td><td>:</td><td> <?php echo $input['alamat_wali']?></td></tr>
<tr><td>Hubungan </td><td>:</td><td> <?php echo $input['hubungan_wali']?></td></tr>
<tr><td>Sebab-sebab </td><td>:</td><td> <?php echo $input['sebab_wali']?></td></tr>
</table>
<table width="100%">
<tr></tr><tr></tr>
<tr><td class="indentasi">Demikian Surat Keterangan ini kami buat untuk dapat digunakan sebagaimana mestinya.<td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>
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
<tr></tr>
<tr><td> <td></td><td align="center">( <?php echo unpenetration($pamong['pamong_nama'])?> )</td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
