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
<div class="clear"></div>

<div id="isi3">

<table width="100%">
<tr>
<td width="10%"></td><td></td>
<tr>
<td ></td><td></td>
<td align="left"></td>
</tr><tr><td >Nomor</td><td>: </td><td align="left">435/<?php  echo $input['nomor'] ?>/Trantib/<?php echo date("Y")?></td></tr><tr>
</tr><tr><td >Lampiran</td><td>: </td><td>  -  </td></tr>
<td>Perihal</td><td>: </td><td><b>Mohon Pengawalan </td>
</tr>

</table>

</tr><tr>
<tr></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="65%"></td><td width="50%">Kepada, </td></tr>
<tr><td width="65%"></td><td width="50%">Yth. <b><?php  echo $input['tujuansurat'] ?></td></tr>
<tr><td width="65%"></td><td width="50%">di-</td></tr>
<tr><td width="67%"></td><td width="55%"><b><u><?php  echo unpenetration($input['alamattujuan']) ?></td></tr>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>
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
<table width="100%">
<tr><td><b><i>Bismillahirrohmanirrohim</td></tr>
<tr><td><b><i>Assalamualaikum Wr. Wb.</td></tr>
<table width="100%">
<tr><td class="indentasi">Dengan Hormat,</td></tr>
<tr><td class="indentasi">Menindaklanjuti permohonan izin Pengawalan secara Lisan dari Warga Kami bernama == <?php echo unpenetration($data['nama'])?> == Alamat   <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo unpenetration(ununderscore($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> dalam rangka acara == <?php echo unpenetration($input['acara'])?> ==. Oleh Karena itu Kami dari Pemerintah Desa Rarang Selatan memohon kehadiran Bapak dari KAPOLSEK Terara untuk mengawal acara <?php echo unpenetration($input['acara'])?> masyarakat kami nanti pada : </td></tr>
</table>

<table width="100%">
<tr><td width="35%">Hari</td><td width="3%">:</td><td ><b><?php echo unpenetration($input['hari'])?></td></tr>
<tr><td width="35%">Tanggal</td><td width="3%">:</td><td ><b><?php echo unpenetration($input['tanggal'])?></td></tr>
<tr><td>Waktu </td><td>:</td><td><b><?php echo $input['waktu']?> Wita s/d selesai</td></tr>
<tr><td>Tempat </td><td>:</td><td><b><?php echo $input['tempat']?> </td></tr>
<tr><td>Tujuan </td><td>:</td><td><b><?php echo $input['tujuan']?> </td></tr> 

<table width="100%">
<td class="indentasi" colspan="4">Mengingat Acara tersebut lintas <?php echo $input['lintas']?>, kehadiran Bapak dari Pihak <?php echo $input['tujuansurat']?> sangat kami harapkan untuk mengawal acara tersebut demi terciptanya suasana aman dan kondusif.</td></tr>

</table>
<table>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr>
<td class="indentasi">Demikian Permohonan Pengawalan ini Kami buat, atas kerjasama yang baik disampaikan terima kasih.</td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr><td><b><i>Wabillahitaufiq walhidayah</td></tr>
<tr><td><b><i>wassalamualaikum Wr. Wb.</td></tr>
<table width="100%">
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

<tr><td width="10%"></td><td width="30%"></td><td align="center"><b><u><?php echo unpenetration($input['pamong'])?> </td></tr>
<tr><td width="10%"></td><td width="30%"></td><td align="center"><?php echo unpenetration($input['pamong_nip'])?> </td></tr>  

</table>  </div></div>


<div id="aside">
</div>
</div>
</body>
</html>
