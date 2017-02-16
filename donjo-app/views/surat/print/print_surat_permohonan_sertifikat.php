<?php $this->load->view('print/headjs.php');?>

<body>
<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div id="isi2">
<table><br>
<h5>
	<table style="border solid ;" width="110%">
	<td align="left">Lampiran :</td>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;Kepada </td>
<tr>
</tr>
	<td align="left">Perihal &nbsp&nbsp&nbsp&nbsp&nbsp;: Permohonan ...........................................</td>
	<td align="left">  Yth : Bapak Kepala Kantor Pertanahan</td>
<tr>
</tr>
	<th align="left"></td>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> </td>
<tr>
</tr>

	<th align="left"></td>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;di </td>
<tr>
</tr>
	<th></th> <br>
	<td align="left"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;<?php echo $input['alamatkantor']?></th>
<tr>
</tr>
</table></div>
</tr>
<tr>
<table width="100%">
<tr><td> Dengan Hormat, </td></tr>
<tr><td> Yang bertanda tangan dibawah ini :</td></tr>

<div id="isi2">
<table width="100%">
<tr></tr>
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($input['nama_2'])?></td></tr>
<tr><td>Tempat,Tgl. Lahir (Umur)</td><td>:</td><td><?php echo ($input['tempatlahir_2'])?> </td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat_2']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $input['pekerjaan_2']?></td></tr>
<tr><td>Nomor KTP</td><td>:</td><td><?php  echo $input['ktp_2']?></td></tr>
<tr><td>Nomor Telpon/HP</td><td>:</td><td><?php  echo $input['hpkuasa']?></td></tr>
</table>
<tr><td>Dengan ini bertindak untuk dan atas nama diri sendiri/selaku kuasa</td><td>:</td></tr>
<table width="100%"></div>
<tr></tr>
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Tempat, Tgl. Lahir (Umur)</td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> (<?php echo $data['umur']?> Tahun)</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $data['pekerjaan']?></td></tr>
<tr><td>Nomor KTP</td><td>:</td><td><?php  echo $data['nik']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td>RW. <?php  echo $data['rw']?>, RT. <?php echo $data['rt']?>, Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?>, Desa <?php  echo unpenetration($desa['nama_desa'])?>, Kec. <?php  echo unpenetration($desa['nama_kecamatan'])?>, Kab. <?php  echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Nomor Telpon/HP</td><td>:</td><td><?php  echo $input['nomor_hp']?></td></tr>
</table>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td colspan="3">Berdasarkan Surat Kuasa Nomor : <?php echo $input['nomor_2']?>, Tanggal <?php echo $input['tanggal_3']?> dengan ini mengajukan permohonan :<br>
<table width="100%">
<tr><td width="50%">1. Pengukuran</td><td width="50%">8. Pemececahan / Penggabungan Hak</td></tr>
<tr><td> 2. Konversi / Pendaftaran Hak</td><td>9. Pendaftaran Hak Tanggungan</td></tr>
<tr><td> 3. Pendaftaran Hak Milik Sarusun</td><td>10. Roya atas Hak Tanggungan</td></tr>
<tr><td> 4. Pendaftaran Tanah Wakaf</td><td>11. Penerbitas atas Sertifikat Pengganti</td></tr>
<tr><td> 5. Pendaftaran Peralihan Hak</td><td>12. Pendaftaran Hak Tanggungan</td></tr>
<tr><td> 6. Pendaftaran Pemindahan Hak</td><td>13. Pengecekan Sertifikat</td></tr>
<tr><td> 7. Pendaftaran Perubahan Hak</td><td></td></tr>
<tr><td> Atas bidang tanah hak / tanah negara :</td></tr>
</table>
<table width="100%">
<tr><td width="30%">Terletak di</td><td width="3%">:</td><td width="64%"><?php  echo unpenetration($input['letak'])?></td></tr>
<tr><td>Desa / Kelurahan</td><td>:</td><td><?php echo ($input['desalurah'])?></td></tr>
<tr><td>Kecamatan</td><td>:</td><td><?php  echo $input['camat-2']?></td></tr>
<tr><td>Kabupaten / Kota</td><td>:</td><td><?php  echo $input['kab-2']?></td></tr>
<tr><td>Nomor Hak</td><td>: </td><td><?php  echo $input['nomor_hak']?>, Luas. <?php echo $input['luashak']?> M2 </td></tr>
</table></div>
<tr></tr>
</td>
<td>Dengan ini menyatakan :</td>
<tr>
<td>
<table border="0px">
<td width=10><td width=800>
</tr>
<td> 1.<td> Bahwa tanah yang kami mohon sampai dengan saat ini secara fisik dikuasi dan dipergunakan / dimanfaatkan untuk Perumahan / Industri / Pertanian.</td>
</tr>
<td> 2.<td> Bahwa tanah yang kami mohon tidak dalam sengketa, baik sengketa hak maupun batas-batasnya (dan tidak dijadikan / menjadi jaminan suatu hutang.</td>
</tr>
<td> 3.<td> Bahwa penggunaan tanah yang kami mohon diperuntukan untuk <?php  echo $input['tanahuntuk']?>.</td>
</tr>
<tr>
</table></div>
</td>
<tr>
<table width="100%">
<tr><td colspan="3">Untuk melengkapi permohonan dimaksud, bersama ini kami lampirkan :</tr>
<tr>
<tr><td>1. Foto copy  KTP</td><td>
<tr><td>2. Surat Pernyataan Penguasaan Bidang Fisik Tanah (SPORADIK) </td><td>
<tr><td>3. Surat Keterangan Tanah Bekas Milik Adat</td><td>
<tr><td>4. Surat Pernyataan Tanah Bekas Milik Adat</td><td>
<tr><td>5. Surat Pernyataan Jual Beli</td><td>
<tr><td>6. SPPT Tahun <?php echo date("Y")?></td><td>

</tr>
</table></div>
</tr>
<tr>
<br>
<br>
</tr>
<table width="100%">
<tr><td></td><td width="55%"></td><td align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td></td><td width="55%"></td><td align="center">Hormat Kami,</td></tr>
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
<tr><td></td><td width="55%"></td><td align="center">(<u> <?php  echo unpenetration($data['nama'])?> </u>)</td></tr>
</table>  </div></div>
<div id="aside">
</div>
</div>
</body>
</html>