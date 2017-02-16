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
<div align="center"><u><h4 class="kop">SURAT KETERANGAN LAHIR MATI</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : 471.2/<?php echo $input['nomor']?>/PEM/<?php echo date("Y")?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa seorang ibu :  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="40%">Nama Lengkap</td><td width="3%">:</td><td ><b><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td >NIK/ No KTP</td><td width="3%">:</td><td ><?php echo $data['nik']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td> <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo unpenetration(ununderscore($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Kewarganegaraan </td><td>:</td><td><?php echo $data['warganegara']?></td></tr>

<table width="100%">
<tr><td colspan="3">Telah lahir bayi dalam keadaan mati, setelah dikandungannya selama <?php echo $input['lama_kandungan']?> bulan : </td></tr>
<tr><td>Pada hari, tanggal</td><td>:</td><td> <?php echo $input['hari']?>,  <?php echo tgl_indo(tgl_indo_in($input['tanggal_mati']))?></td></tr>
<tr><td>di </td><td>:</td><td> <?php echo $input['tempat_mati']?></td></tr>
</table><div id="isi3">
</table>
<table width="100%">
<tr><td colspan="3">Surat keterangan ini dibuat berdasarkan keterangan pelapor : </td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="40%">Pelapor</td><td width="3%">:</td><td > <?php echo $input['pelapor']?></td></tr>
<tr><td>Hubungn dengan yang Lahir Mati </td><td>:</td><td> <?php echo $input['hubungan']?></td></tr>
<tr></tr>
</table>
<table width="100%">
<tr><td class="indentasi" colspan="3">Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</td></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
</table>
<table width="100%">
<tr><td width="23%"></td><td width="39%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center"></td><td width="39%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>,</td></tr>
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
<tr><td width="23%" align="center"></td><td width="39%"></td><td align="center"> <b><u><?php echo unpenetration($input['pamong'])?> </td></tr>
<tr><td width="23%" align="center"></td><td width="39%"></td><td align="center"> <?php echo unpenetration($input['pamong_nip'])?> </td></tr>       
<tr></tr>
</table>  </div></div>
<tr></tr>
<div id="aside">
</div>
</div>
</body>
</html>
