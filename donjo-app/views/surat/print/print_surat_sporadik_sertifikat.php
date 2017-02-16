<?php $this->load->view('print/headjs.php');?>

<body>
	<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<tr></tr>
<?php { ?>
</tr>
</table>
<?php  } ?>
<div>
</tr>
<div aligen="centre"><h4 class="kop">SURAT PERNYATAAN PENGUASAAN FISIK </h4></div>
<div aligen="centre"><h4 class="kop">BIDANG TANAH (SPORADIK)</h4></div>
<div aligen="centre"><h4 class="kop3">PP. No. 24/1997 jo PMNA/KBPN No.3/1977 </h4></div>
<div style="text-align: center;">
<hr/></div>
</table>
<br>
<br>
<div id="isi3 td">
<table width="100%">
<tr> Yang bertanda tangan dibawah ini  :</tr>
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat, Tgl. Lahir (Umur)</td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> (<?php echo $data['umur']?> Tahun)</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $data['pekerjaan']?></td></tr>
<tr><td>Nomor KTP/Domisili</td><td>:</td><td><?php  echo $data['nik']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RW. <?php  echo $data['rw']?>, RT. <?php echo $data['rt']?>, Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?>, Desa <?php  echo unpenetration($desa['nama_desa'])?>, Kec. <?php  echo unpenetration($desa['nama_kecamatan'])?>, Kab. <?php  echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>
<tr>Dengan ini menyatakan bahwa saya dengan itikad baik telah menguasai sebidangg tanah yang terletak di :</tr>
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
<tr>Bidang tanah tersebut saya peroleh : <?php  echo $input['peroleh']?> sejak tahun <?php echo $input['perolehtahun']?> yang sampai saat ini saya kuasai secara terus menerus
, tidak dijadikan / menjadi suatu jaminan dan tidak dalam keadaan sengketa. </tr>
<tr></tr><br>
<td class="indentasi">Surat pernyataan ini saya buat sendiri dengan penuh tanggung jawab, dan saya bersedia untuk mengangkat sumpah bila diperlukan. Apabila pernyataan ini tidak dengan sebenarnya,
  saya bersedia dituntut dihadapan yang berwenang.</tr>
</table></div>
<tr></tr>
</td>
<tr>
<table border="0">
<td  width=10><td width=800>
</tr>
<td> 
</tr>
</table></div>
<tr>
</tr>
	<table style="border solid ;" width="105%">
	<th align="left">SAKSI - SAKSI</th>
	<td align="centre"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></th>
<tr>
</tr>
	<td align="left">1. N a m a &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : &nbsp&nbsp&nbsp&nbsp; <?php echo unpenetration($input['namasaksi1'])?></td>
	<td align="centre"> Yang Membuat Pernyataan,</th>
<tr>
</tr>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp; U m u r &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp; : &nbsp&nbsp&nbsp&nbsp <?php echo unpenetration($input['umursaksi1'])?></td>
	<td align="centre"></th>
<tr>
</tr>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp; Pekerjaan &nbsp&nbsp; : &nbsp&nbsp&nbsp&nbsp <?php echo unpenetration($input['pekerjaansaksi1'])?></td>
	<td align="centre"></th>
<tr>
</tr>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp; Alamat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp; : &nbsp&nbsp&nbsp&nbsp <?php echo unpenetration($input['alamatsaksi1'])?></td>
	<td align="centre"></td>
<tr>
</tr>
	<td></td>
	<td align="centre"><?php  echo unpenetration($data['nama'])?></td>
<tr>
</tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<tr>
<tr>
</tr>
	<td align="left">2. N a m a &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : &nbsp&nbsp&nbsp&nbsp; <?php echo unpenetration($input['namasaksi2'])?></td>
<tr>
</tr>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp; U m u r &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp; : &nbsp&nbsp&nbsp&nbsp <?php echo unpenetration($input['umursaksi2'])?></td>
	<td align="centre"> Mengetahui/Memberikan ;</td>
<tr>
</tr>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp; Pekerjaan &nbsp&nbsp; : &nbsp&nbsp&nbsp&nbsp <?php echo unpenetration($input['pekerjaansaksi1'])?></td>
	<td align="centre"> No. Reg : ....................................</td>
<tr>
</tr>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp; Alamat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp; : &nbsp&nbsp&nbsp&nbsp <?php echo unpenetration($input['alamatsaksi1'])?></td>
	<td align="centre"> Tanggal : ....................................</td>
<tr>
</tr>
	</table>
	<table width="100%">
<tr>
</tr>
	<th align="left">Tanda Tangan :</th>
	<td align="left"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td>
<tr>
</tr>
	<td>                                                               </td>
	<td align="left"> <?php echo unpenetration($input['atas_nama'])?></td>
<tr>
</tr>
	<td>1. .......................................(.......................................)</td>
	<td align="center"> </th>
<tr>
</tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
<tr>
</tr>
	<td>&nbsp;</td>
	<td  align="left">(<u> <?php echo unpenetration($input['pamong'])?> <u>)</td>
<tr>
</tr>
	<td>2. .......................................(.......................................)</td>
	<td align="left"> <?php echo unpenetration($input['pamong_nip'])?></td>
<tr>
</tr>
	</table>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>