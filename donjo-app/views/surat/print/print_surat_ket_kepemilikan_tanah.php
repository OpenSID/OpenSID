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
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<div align="center"><u><h4 class="kop">SURAT KETERANGAN KEPEMILIKAN TANAH</h4></u></div>
<div align="center"><h4  class="kop3">Nomor : 593/<?php echo $input['nomor']?>/PEM/ <?php echo date("Y")?></h4></div>

<div id="isi3 td">
<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo $input['jabatan']?> <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa :  </td></tr>
</table>
<div id="isi3 td">
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat, Tgl. Lahir</td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Umur</td><td>:</td><td><?php echo $data['umur']?> Tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $data['pekerjaan']?></td></tr>
<tr><td>Nomor KTP/Domisili</td><td>:</td><td><?php  echo $data['nik']?></td></tr>
<tr><td>Alamat</td><td>:</td><td> <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php  echo $data['rw']?> Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?> Desa <?php  echo unpenetration($desa['nama_desa'])?> Kecamatan <?php  echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php  echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<tr>Orang tersebut diatas memang benar memiliki dan menguasai sebidang tanah yang terletak di :</tr>
<table width="100%">
<tr><td width="30%">Jalan</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($input['jalan'])?></td></tr>
<tr><td>RT/RW</td><td>:</td><td><?php echo ($input['rtrw'])?> </td></tr>
<tr><td>Desa/Kelurahan</td><td>:</td><td><?php echo $input['desalurah']?></td></tr>
<tr><td>Kecamatan</td><td>:</td><td><?php  echo $input['camat-2']?></td></tr>
<tr><td>Kabupaten/Kota</td><td>:</td><td><?php  echo $input['kab-2']?></td></tr>
<tr><td>N I B</td><td>:</td><td><?php  echo $input['nib']?></td></tr>
<tr><td>Luas</td><td>:</td><td><?php  echo $input['luashak']?> M2</td></tr>
<tr><td>Status Tanah</td><td>:</td><td><?php  echo $input['statustanah']?></td></tr>
<tr><td>Dipergunakan</td><td>:</td><td><?php  echo $input['tanahuntuk']?></td></tr>
<tr></tr>
<tr><td>
<br>
<tr><td><b>Batas-batas Tanah :</b></td><tr>
<tr><td width="30%">Sebalah Utara</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($input['utara'])?></td></tr>
<tr><td>Sebelah Timur</td><td>:</td><td><?php echo ($input['timur'])?></td></tr>
<tr><td>Sebelah Selatan</td><td>:</td><td><?php  echo $input['selatan']?></td></tr>
<tr><td>Sebelah Barat</td><td>:</td><td><?php  echo $input['barat']?></td></tr>
</table>
<tr><td>
<table width=""100">
<tr>Bidang tanah tersebut diperoleh dari : <?php  echo $input['peroleh']?> sejak tahun <?php echo $input['perolehtahun']?> yang sampai saat ini dikuasai secara terus menerus dan tidak dijadikan / menjadi suatu jaminan dan tidak dalam keadaan sengketa. </tr>
<tr></tr><br>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Demikian Surat Keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan dimana mestinya</td></tr>
<table width="100%">
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