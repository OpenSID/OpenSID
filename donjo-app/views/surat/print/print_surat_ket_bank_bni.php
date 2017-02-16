<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>

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
<div align="center"><u><h4 class="kop">SURAT KETERANGAN</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : 471.2/<?php echo $input['nomor']?>/PEM/<?php echo date("Y")?></h4></div>

<tr><td class="indentasi">Yang bertanda tangan dibawah ini <?php  echo unpenetration($input['jabatan'])?> <?php  echo unpenetration($desa['nama_desa'])?> Kecamatan <?php  echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php  echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa : </td></tr></tr>
<table>
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php  echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td><?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?> Desa <?php  echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php  echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr></tr>
<tr><td width="30%">Nomor KTP Lama</td><td width="3%">:</td><td width="64%"><?php  echo $input['ktplama']?></td></tr>
<tr><td width="30%">Nomor KTP Baru</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($data['nik'])?></td></tr>
<tr><td>Nama Sebelumnya</td><td>:</td><td><?php  echo $input['namasebelumnya']?></td></tr>
<tr><td>Nama Sekarang</td><td>:</td><td><?php  echo unpenetration($data['nama'])?></td></tr>
<table width="100%">
<td><b>Dengan ini Kami menyatakan :</td>
<tr>
<td>
<table border="0px">
<td width=10><td width=800>
</tr>
<td> 1.<td> Nama tersebut di atas adalah benar warga kami dan selama menjadi warga kami yang bersangkutan berkelakuan baik; </td>
</tr>
<td> 2.<td> Yang bersangkutan belum pernah tersangkut tindak kejahatan baik pidana maupun perdata;</td>
</tr>
<td> 3.<td> KTP dengan nomor tersebut di atas adalah asli dan tidak palsu atau dipalsukan;</td>
</tr>
</tr>
<td> 4.<td> Bahwa benar pemilik KTP No. NIK <?php  echo $input['ktplama']?> an. <?php  echo $input['namasebelumnya']?> adalah orang yang sama dengan pemilik KTP No. NIK <?php  echo $data['nik']?> an. <?php  echo $data['nama']?> yang merupakan Pemilik Rekening dengan Nomor : <?php  echo $input['rekening']?>;</td>
</tr>
<td> 5.<td> Kami membebaskan PT Bank Negara Indonesia (Persero) Tbk. dari segala resiko yang timbul dan tuntutan atau gugatan dari pihak manapun, bila ternyata dikemudian hari KTP dan Keterangan tersebut di atas palsu atau dipalsukan;</td>
</tr>
</table>
<tr></tr>
<tr></tr>
<tr><td class="indentasi">Surat Keterangan ini dibuat untuk Keperluan : <b><u><?php echo $input['keperluan']?></td></tr>
<tr></tr>
</table>
<tr></tr>
<tr><td class="indentasi">Demikian surat keterangan ini dibuat dengan sesungguhnya agar dapat dipergunakan sebagaimana mestinya</td></tr>
<tr></tr>
</table>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr></tr>
<table width="100%">
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">Pemegang Surat,</td><td width="30%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>,</td></tr>
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
<tr><td width="23%" align="center"><b><?php echo unpenetration($data['nama'])?> </td><td width="30%"></td><td align="center"><b><u><?php echo unpenetration($input['pamong'])?> </td></tr>    
<tr><td width="23%" align="center"><?php echo unpenetration($data[''])?> </td><td width="30%"></td><td align="center"> <?php echo unpenetration($input['pamong_nip'])?> </td></tr>   
</table>  </div></div>
<tr></tr>
</table></div>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">No. Reg. : _______________________<?php echo unpenetration($desa[''])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">Tanggal : _______________________<?php echo unpenetration($desa[''])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">Mengetahui ;<?php echo unpenetration($desa[''])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center">Camat Terara, <?php echo unpenetration($desa[''])?></td></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center">______________________________ <?php echo unpenetration($desa[''])?></td></tr>
<tr>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>

