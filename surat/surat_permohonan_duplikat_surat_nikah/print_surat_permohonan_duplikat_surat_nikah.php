<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

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


<div align="center"><u><h4 class="kop">SURAT PERMOHONAN DUPLIKAT SURAT NIKAH</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?>,
<?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa:  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td width="23%">NIK/ No. KTP</td><td width="3%">:</td><td width="64%"><?php echo $data['nik']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir (Umur)</td><td>:</td><td><?php echo ($data['tempatlahir'])?> , <?php echo tgl_indo($data['tanggallahir'])?> (<?php echo $data['umur']?> Tahun)</td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $data['agama']?></td></tr>
<tr><td>Status</td><td>:</td><td><?php echo $data['status_kawin']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>Pendidikan</td><td>:</td><td><?php echo $data['pendidikan']?></td></tr>
<tr><td>Kewarganegaraan </td><td>:</td><td><?php echo $data['warganegara']?></td></tr>
<tr><td>Keterangan </td><td>:</td><td>Bahwa orang tersebut adalah benar-benar warga kami yang bertempat tinggal di Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, Rt. <?php echo $data['rt']?>, <?php echo unpenetration($desa['nama_desa'])?>, <?php echo unpenetration($desa['nama_kecamatan'])?>, <?php echo unpenetration($desa['nama_kabupaten'])?> tercatat dalam
No. KK: <?php echo $data['no_kk']?> dengan NIK: <?php echo $data['nik']?>, kepala keluarga : <?php echo unpenetration($data['kepala_kk'])?> dan telah menikah di KUA Kecamatan <?php echo $input['kecamatan_kua']?> pada tanggal <?php echo tgl_indo(tgl_indo_in($input['tgl_nikah']))?> dengan seseorang yang bernama <?php echo unpenetration($input['nama_pasangan'])?>.</td></tr>
</table>
<table width="100%">
<tr></tr>
<td  class="indentasi">Berdasarkan data di atas mohon untuk dibuatkan Duplikat Surat Nikah orang tersebut di atas. </td><tr></tr>
<td class="indentasi">Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinya. </td>

<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>
</div>
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
<tr><td> <td></td><td align="center">( <?php echo unpenetration($input['pamong'])?> )</td></tr>
</table>  </div>
<div id="aside">
</div>
</div>
</body>
</html>
