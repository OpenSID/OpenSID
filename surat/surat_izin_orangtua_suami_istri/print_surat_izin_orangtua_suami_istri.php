<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

</table>
<table width="100%">
</table>
<table width="100%">
<div align="center"><u><h5 class="kop">SURAT KETERANGAN IZIN <?php echo $input['selaku']?></h5></u></div>

</table>
<div class="clear"></div>

<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<td class="indentasi">Yang bertanda tangan/ Cap Jempol dibawah ini : </td></tr>
</table>
<div id="isi2">
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($pribadi['nama']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo ucwords(strtolower($pribadi['tempatlahir'])); ?>, <?php echo tgl_indo($pribadi['tanggallahir']); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $pribadi['wn']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo ucwords(strtolower($pribadi['agama'])); ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo ucwords(strtolower($pribadi['pek'])); ?></td></tr>
<tr><td>Alamat</td><td>:</td><td> <?php echo ucwords(strtolower($pribadi['alamat']))?> RT. <?php echo $pribadi['rt']?> RW. <?php echo $pribadi['rw']?> Dusun <?php echo ucwords(strtolower($pribadi['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>
</table>

<table width="100%">
<tr></tr>
<tr>
<td class="indentasi">Saya selaku sebagai <?php echo $input['selaku']?> dengan ini secara tulus dan Ikhlas mengizinkan serta menyetujui <?php echo $input['mengizinkan']?> saya di bawah ini :</td>
</tr> 
<tr></tr>
</table>
<table width="100%">
<tr><td width="30%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><b><?php echo unpenetration($input['nama']); ?></td></tr>
<tr><td>Tempat dan tanggal lahir</td><td>:</td><td><?php echo ucwords(strtolower($input['tempatlahir'])); ?>, <?php echo tgl_indo(tgl_indo_in($input['tanggallahir'])); ?></td></tr>
<tr><td>Warganegara</td><td>:</td><td><?php echo $input['wni']; ?></td></tr>
<tr><td>Agama</td><td>:</td><td><?php echo ucwords(strtolower($input['agama'])); ?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo ucwords(strtolower($input['pekerjaann'])); ?></td></tr>
<tr><td>Alamat</td><td>:</td><td><?php echo ucwords(strtolower($input['alamatt']))?> RT. <?php echo ($input['rtt'])?> RW. <?php echo ($input['rww'])?> Dusun <?php echo ucwords(strtolower($input['dusunn']))?> Desa <?php echo ($input['desaa'])?> Kecamatan <?php echo ($input['kecc'])?> Kabupaten <?php echo ($input['kabb'])?></tr><td>
</table>
<table width="100%">
<tr></tr>
<tr>
<td class="indentasi">Untuk melamar pekerjaan/ bekerja ke <?php echo ucwords(strtolower($input['negara_tujuan'])); ?>, melalui <?php echo ($input['nama_pptkis']); ?> sebagai <?php echo ($input['pekerja']); ?> dengan masa kontrak ______ tahun.</td>
</tr>
</table>
<table width="100%">
<tr>
<tr>
<td class="indentasi">Segala akibat yang timbul dikemudian hari dari pembuatan dan penggunaan surat izin ini sepenuhnya menjadi tanggung jawab saya baik secara hukum ataupun secara moril dan materil tanpa melibatkan pihak lainnya.</td>
</tr>
</table>
<table width="100%">
<tr>
<td class="indentasi">Demikianlah surat keterangan ini saya buat dengan sebenarnya untuk digunakan seperlunya, kepada yang berwajib/ berkepentingan agar menjadi maklum dan tahu adanya.</td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<table width="100%">
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">Yang diberi Izin,</td><td width="30%"></td><td align="center">Yang Memberi Izin,</td></tr>
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
<tr><td width="23%" align="center"><b><?php echo unpenetration($input['nama'])?> </td><td width="30%"></td><td align="center"><b><u><?php echo unpenetration($data['nama'])?> </td></tr>    
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
<tr><td width="0%"></td><td width="0%"></td><td align="center"><?php echo ($input['atas_nama'])?></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?>,</td></tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><b><u><?php echo unpenetration($input['pamong'])?> </u></td></tr>
<tr><td width="0%"></td><td width="0%"></td><td align="center"><?php echo ($input['pamong_nip'])?></td></tr>
<tr>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>
