<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<div align="center"><u><h4 class="kop">SURAT KUASA PEMBUATAN AKTA KELAHIRAN</h4></u></div>
<table width="100%">
<table width="100%">

<tr><td class="indentasi">Yang bertanda tangan/ Cap Jempol dibawah ini :</tr></td>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td>:</td><td><b><?php  echo unpenetration($data['nama'])?></td></tr>
<tr><td>Nomor KTP</td><td>:</td><td><?php  echo $data['nik']?></td></tr>
<tr><td>Tempat, Tgl. Lahir</td><td>:</td><td><?php echo ($data['tempatlahir'])?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Umur</td><td>:</td><td><?php echo $data['umur']?> Tahun</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php  echo $data['pekerjaan']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td> <?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php  echo $data['rw']?> Dusun <?php  echo unpenetration(ununderscore($data['dusun']))?> Desa <?php  echo unpenetration($desa['nama_desa'])?> Kecamatan <?php  echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php  echo unpenetration($desa['nama_kabupaten'])?></tr></td>
<table width="100%">
<tr><td class="indentasi">Dalam hal ini bertindak untuk dan atas nama diri sendiri, selanjutnya disebut PIHAK PERTAMA (Pemberi Kuasa).</td></tr>

<table width="100%">
<tr><td width="30%">Nama</td><td>:</td><td><b><?php  echo $input['nama_2']?></td></tr>
<tr><td>NIK</td><td>:</td><td><?php  echo $input['ktp_2']?></td></tr>
<tr><td>Tempat dan Tanggal Lahir<td>:</td><td><?php echo ($input['tempatlahir_2'])?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_2']))?> </td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $input['sex_2']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_2']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_2']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat_2']?></tr></td>
<table width="100%">
<tr><td class="indentasi">Dalam hal ini bertindak untuk dan atas nama diri sendiri, selanjutnya disebut PIHAK KEDUA (Penerima Kuasa). </td></tr>
<table width="100%">

<tr><td class="indentasi">Saya PIHAK PERTAMA memberikan kuasa Kepada PIHAK KEDUA atas Pembuatan Akta Kelahiran anak saya bernama : </td></tr>
<table width="100%">
<tr><td width="30%">Nama</td><td>:</td><td><b><?php  echo $input['nama_3']?></td></tr>
<tr><td>NIK</td><td>:</td><td><?php  echo $input['ktp_3']?></td></tr>
<tr><td>Tempat dan Tanggal Lahir<td>:</td><td><?php echo ($input['tempatlahir_3'])?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir_3']))?> </td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $input['sex_3']?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo $input['agama_3']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $input['pekerjaan_3']?></td></tr>
<tr><td>Alamat/ Tempat Tinggal</td><td>:</td><td><?php echo $input['alamat_3']?></td></tr>
<table width="100%">

<tr><td class="indentasi">Demikian surat kuasa ini dibuat dengan sesungguhnya agar dapat dipergunakan sebagaimana mestinya</td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">PIHAK KEDUA,</td><td width="30%"></td><td align="center">PIHAK PERTAMA,</td></tr>
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
<tr><td width="23%" align="center"><b><?php echo unpenetration($input['nama_2'])?> </td><td width="30%"></td><td align="center"><b><u><?php echo unpenetration($data['nama'])?> </td></tr>    
</table>  </div></div>
<tr></tr>
</table></div>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">No. Reg. : <b>474.1/<?php echo unpenetration($input['nomor'])?>/PEM/<?php echo date("Y")?></td><td></h4></div></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">Tanggal : <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td  align="center">Mengetahui ;<?php echo unpenetration($desa[''])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>,</td></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><b><u> <?php echo unpenetration($input['pamong'])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"> <?php echo unpenetration($input['pamong_nip'])?></td></tr>
<tr>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>

