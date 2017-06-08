<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt=""  class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop"><?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT KETERANGAN KEMATIAN</h4></u></div>

<div align="center"><h4 class="kop">NO: <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($input['jabatan'])?>  <?php echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan bahwa: </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="35%">Nama</td><td width="3%">:</td><td ><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td >NIK</td><td width="3%">:</td><td ><?php echo $data['nik']?></td></tr>
<tr><td >Jenis Kelamin</td><td width="3%">:</td><td ><?php echo $data['sex']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo $data['tempatlahir']?>, <?php echo tgl_indo($data['tanggallahir'])?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['warganegara']?>/<?php echo $data['agama']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<td colspan="3">Telah meninggal dunia pada: </td>
<tr><td>Hari/ Tanggal/ Jam</td><td>:</td><td><?php echo $input['hari']?>/<?php echo  tgl_indo(tgl_indo_in($input['tanggal_mati']))?>/<?php echo $input['jam']?></td></tr>
<tr><td>Bertempat di</td><td>:</td><td><?php echo $input['tempat_mati']?></td></tr>
<tr><td>Penyebab Kematian</td><td>:</td><td><?php echo $input['sebab']?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3"> Surat keterangan ini dibuat berdasarkan keterangan pelapor : </td></tr>
<tr><td>Nama</td><td>:</td><td><?php echo unpenetration($input['nama'])?></td></tr>
<tr><td>NIK</td><td>:</td><td><?php echo $input['nik_pelapor']?></td></tr>
<tr><td>Tgl Lahir/</td><td>:</td><td><?php echo  tgl_indo(tgl_indo_in($input['tgl_lahir']))?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan']?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?php echo $input['alamat']?></td></tr>
<tr><td>Hubungan dengan yang mati</td><td>:</td><td><?php echo $input['hubungan']?></td></tr>
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
<tr></tr>
<tr><td><td></td><td td align="center">( <?php echo unpenetration($input['pamong'])?> )</td></tr>
<tr><td colspan="3">*)nama terang<td></td>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
