<?php $this->load->view('print/headjs.php');?>
<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">
	<table style="border:2px solid ;" width="10%">
	<th> F-2.1 </th>
	</table>
	<br></br>
	<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<tr></tr>
<?php { ?>
</tr>
</table>
<?php  } ?>
<div>
</tr>
<div align="center"><B><h4  class="kop1">FORMULIR PERMOHONAN KARTU TANDA PENDUDUK (KTP) WARGA NEGARA INDONESIA</h4></B></div>
</table>
<br></br>
<tr></tr>
<?php { ?>
</table>
<?php  } ?>
<table style="border:1px solid ;" width="100%">
	<thead>
		<tr>
			<th align="left" >Perhatian</td></tr>
			<th align="left" > 1. Harap diisi dengan huruf cetak dan menggunakan tinta hitam. </td></tr>
			<th align="left" > 2 Untuk kolom pilihan, harap memberi tanda silang (X) pada kotak pilihan. </td></tr>
			<th align="left" > 3 Setelah formulir ini diisi dan ditandatangani, harap diserahkan kembali ke kantor Desa/Kelurahan. </td></tr>
		</tr>
<table>
<br></br>
<th><table border=1 width="152%" cellspacing="0">
<td align="left"> &nbsp PEMERINTAH PROPINSI</td>
<th>&nbsp&nbsp;:&nbsp&nbsp;</td>
<th>&nbsp&nbsp;<?php echo $desa['kode_propinsi']?>&nbsp;</td>
<td align="left">&nbsp&nbsp;<?php echo strtoupper ($desa['nama_propinsi'])?></th></tr>
<tr>
</tr>
<td align="left"> &nbsp PEMERINTAH KABUPATEN / KOTA</td>
<th>&nbsp&nbsp;:&nbsp&nbsp;</td>
<th>&nbsp&nbsp;<?php echo $desa['kode_kabupaten']?>&nbsp;</td>
<td align="left">&nbsp&nbsp;<?php echo strtoupper ($desa['nama_kabupaten'])?></td></tr>
<tr>
</tr>
<td align="left"> &nbsp PEMERINTAH KECAMATAN</td>
<th>&nbsp&nbsp;:&nbsp&nbsp;</td>
<th>&nbsp&nbsp;<?php echo $desa['kode_kecamatan']?>&nbsp;</td>
<td align="left">&nbsp&nbsp;<?php echo strtoupper ($desa['nama_kecamatan'])?></td></tr>
<tr>
</tr>
<td align="left"> &nbsp PEMERINTAH KELURAHAN / DESA</td>
<th>&nbsp&nbsp;:&nbsp&nbsp;</td>
<th>&nbsp&nbsp;<?php echo $desa['kode_desa']?>&nbsp;</td>
<td align="left">&nbsp&nbsp;<?php echo strtoupper ($desa['nama_desa'])?></td></tr>
</table>
<tr>
<th><table border=1 width="152%" cellspacing="0">
<td align="left"> &nbsp PERMOHONAN KTP</td><th>&nbsp&nbsp;<?php echo $input['baru']?>&nbsp&nbsp;</td><th> A. Baru</td>
<th>&nbsp&nbsp;<?php echo $input['perpanjang']?>&nbsp&nbsp;</td><th> B. Perpanjang</td>
<th>&nbsp&nbsp;<?php echo $input['penggantian']?>&nbsp&nbsp;</td><th> C. Penggantian</td></tr>
</table>
<tr>
<th><table border=1 width="152%" cellspacing="0">
<td align="left"> &nbsp 1. NAMA LENGKAP </td><td align="left">&nbsp&nbsp;<?php echo $data['nama']?></td></tr>
<tr>
</tr>
<td align="left"> &nbsp 2. NOMOR KK </td><td align="left">&nbsp&nbsp;<?php echo $data['no_kk']?></td></tr>
<tr>
</tr>
<td align="left"> &nbsp 3. N I K </td><td align="left">&nbsp&nbsp;<?php echo $data['nik']?></td></tr>
<tr>
</tr>
<td align="left"> &nbsp 4. ALAMAT </td><td align="left">&nbsp&nbsp;<?php echo ununderscore(unpenetration($data['alamat']))?> Dusun <?php echo ununderscore(unpenetration($data['dusun']))?> </td></tr>
</table>
<tr>
<th><table border=1 width="152%" cellspacing="0">
		<td> &nbsp R W </td><th>&nbsp&nbsp;<?php echo $data['rw']?>&nbsp&nbsp;</td>
		<th>&nbsp&nbsp&nbsp;<th> R T </td><th>&nbsp&nbsp;<?php echo $data['rt']?>&nbsp&nbsp&nbsp;</td>
		<th>&nbsp&nbsp&nbsp;<th> Kode Pos </td><th>&nbsp&nbsp;<?php echo $desa['kode_pos']?></td><tr>
</table>
<tr></tr>
<tr></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
<tr><td></td><td width="55%"></td><td align="center"><?php echo $input['atas_nama']?></td></tr>
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
</table>
<table width="100%">
<tr><td></td><td width="55%"></td><td align="center"><b><u> <?php echo unpenetration($input['pamong'])?> </u></td></tr>
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($input['pamong_nip'])?></td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>
