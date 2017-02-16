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

</table>
<table width="100%">
</table>
<table width="100%">
<div align="center"><h4 class="kop">SURAT PENGANTAR PINDAH</h4></div>
<div align="center"><u><h4 class="kop">ANTAR KABUPATEN/KOTA ATAU ANTAR PROVINSI</h4></u></div>
<div align="center"><h4 class="kop3">Nomor : 471.2/<?php echo $input['nomor']?>/PEM/<?php echo date("Y")?></h4></div>
<tr>
<div class="clear"></div>
</table>
<table width="100%">

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?> Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan Permohonan Pindah Penduduk WNI dengan data sebagai berikut :  </td></tr>
</table>

<div id="isi3">

<table width="100%">
<tr><td width="23%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td >NIK</td><td>:</td><td><?php echo $data['nik']?></td></tr>
<tr><td >Nomor Kartu Keluarga</td><td>:</td><td><?php echo $data['no_kk']?> </td></tr>
<tr><td >Nama Kepala Keluarga</td><td>:</td><td ><?php echo unpenetration($data['kepala_kk'])?></td></tr>
<tr><td>Alamat Sekarang</td><td>:</td><td><?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo unpenetration(ununderscore($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
<tr><td>Alamat yang dituju</td><td>:</td><td><?php echo $input['alamat_tujuan']?> RT. <?php echo $input['rt_tujuan']?> RW. <?php echo $input['rw_tujuan']?> Dusun <?php echo $input['dusun_tujuan']?> Desa <?php echo unpenetration($input['kelurahan_tujuan'])?> Kecamatan <?php echo unpenetration($input['kecamatan_tujuan'])?> Kabupaten <?php echo unpenetration($input['kabupaten_tujuan'])?></td></tr>
<tr><td>Jumlah Keluarga yang Pindah</td><td>:</td><td><?php echo $input['jumlah']?> orang</td></tr>
<table width="100%">
<tr><td>Adapun Permohonan Pindah Penduduk WNI yang bersangkutan sebagaimana terlampir.</td></tr>

</table>

<table width="100%">
<tr>
<td class="indentasi">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</td>
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
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="10%"></td><td width="43%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="10%"></td><td width="43%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
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
</table>
<table width="100%">
<tr><td></td><td width="50%"></td><td  align="center"><b><u><?php echo unpenetration($input['pamong'])?></td></tr>
<tr><td></td><td width="50%"></td><td align="center"><?php echo unpenetration($input['pamong_nip'])?></td></tr>					
</table>  </div></div>

</div>
<div id="aside">

</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>

