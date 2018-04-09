<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop"><?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?php echo unpenetration(($desa['alamat_kantor']))?> </h5>

<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT KETERANGAN JUAL BELI</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<table width="100%">

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo $input['jabatan']?> <?php echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?>,
<?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa:  </td></tr>
</table>

<div id="isi3">
<table width="100%">
<tr></tr>
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat dan Tgl. Lahir (Umur)</td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> (<?php echo $data['umur']?> Tahun)</td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td>RW. <?php  echo $data['rw']?>, RT. <?php echo $data['rt']?>, Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?>, <?php echo ucwords($this->setting->sebutan_desa)?> <?php  echo unpenetration($desa['nama_desa'])?>, Kec. <?php  echo unpenetration($desa['nama_kecamatan'])?>, <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> <?php  echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $data['pekerjaan']?></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3">Yang bersangkutan hendak menjual <?php echo $input['barang']?>.
<?php echo $input['jenis']?> tersebut tidak dalam sengketa dengan pihak lain sehingga dapat dijual kepada pihak kedua yaitu:</td><td width="3%"></td><td width="64%"></td></tr>
<tr><td>Nama</td><td>:</td><td><?php  echo unpenetration($input['nama'])?></td></tr>
<tr><td>Tempat dan Tanggal Lahir<td>:</td><td><?php echo ($input['tempatlahir'])?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir']))?> </td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $input['sex']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan']?></td></tr>
<tr><td>Keterangan</td><td>:</td><td><?php echo $input['keterangan']?></td></tr>
</table>
<table width="100%">
<tr></tr>
<tr></tr>

<tr><td class="indentasi">Demikian surat keterangan ini dibuat dengan sesungguhnya agar dapat dipergunakan sebagaimana mestinya.</td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table></div>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="23%" align="center">Mengetahui,</td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">Ketua Adat <?php echo unpenetration($desa['nama_desa'])?></td><td width="30%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
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
<tr><td  align="center">( <?php echo unpenetration($input['ketua_adat'])?> )<td></td><td align="center">( <?php echo unpenetration($input['pamong'])?> )</td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
