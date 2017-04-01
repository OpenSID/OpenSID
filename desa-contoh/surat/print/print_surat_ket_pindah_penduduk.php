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
<h5 class="kop2"><?php echo unpenetration(($desa['alamat_kantor']))?> </h5>

<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT KETERANGAN PINDAH</h4></u></div>
<div align="center"><h4 class="kop">NO: <?php echo $input['nomor']?></h4></div>
<tr>
<div class="clear"></div>

<td class="indentasi">Yang bertanda tangan dibawah ini <?php echo unpenetration($pamong['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?>,
Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php echo unpenetration($desa['nama_propinsi'])?> menerangkan bahwa:  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="35%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?php echo unpenetration($data['nama'])?></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $data['sex']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?php echo $data['tempatlahir']?>, <?php echo tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Status</td><td>:</td><td><?php echo $data['status_kawin']?></td></tr>
<tr><td>Kewarganegaraan / Agama</td><td>:</td><td><?php echo $pribadi['wn']?> / <?php echo $data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?php echo $data['pekerjaan']?></td></tr>
<tr><td>No KTP</td><td>:</td><td><?php echo $data['nik']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo ununderscore(unpenetration($data['dusun']))?>, Desa <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, Kab. <?php echo unpenetration($desa['nama_kabupaten'])?></td></tr>


<tr><td>Alamat yang dituju</td><td>:</td><td>RT. <?php echo $input['rt_tujuan']?>, RW. <?php echo $input['rw_tujuan']?>, Kampung <?php echo $input['kampung_tujuan']?>, Dusun <?php echo ununderscore(unpenetration($input['dusun_tujuan']))?>, Desa <?php echo unpenetration($input['desa_tujuan'])?>, Kec. <?php echo unpenetration($input['kecamatan_tujuan'])?>, Kab. <?php echo unpenetration($input['kabupaten_tujuan'])?></td></tr>

<tr><td>Alasan </td><td>:</td><td> <?php echo $input['alasan']?></td></tr>

<tr><td>Tanggal pindah </td><td>:</td><td> <?php echo tgl_indo(tgl_indo_in($input['awal']))?></td></tr>
<?php
	$i=0;
	if($pengikut){
		foreach($pengikut AS $data1){
			$i++;
		}
	}
?>
<tr><td>Jumlah Pengikut </td><td>:</td><td> <?php echo $i;?> orang</td></tr>
</table>


<tr></tr>
<?php  if($pengikut){ ?>
<table style="border:1px solid ;" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th align="left" >NIK</th>
			<th align="left" >Nama</th>
			<th align="left" align="center">JK</th>
			<th  align="left" >Umur</th>
			<th align="left" >Status Kawin</th>
			<th align="left">Hubungan</th>
		</tr>


	<tbody><?php  $i=0;?>
		<?php  foreach($pengikut AS $data1){$i++;?>
		<tr>
            <td align="center" width="2"><?php echo $i?></td>
			<td><?php echo $data1['nik']?></td>
			<td><?php echo unpenetration($data1['nama'])?></td>
			<td><?php echo $data1['sex']?></td>
			<td><?php echo $data1['umur']?></td>
			<td><?php echo $data1['status_kawin']?></td>
			<td><?php echo $data1['hubungan']?></td>
		</tr>
		<?php  }?>
	</tbody>
</table>
<?php  } ?>
<tr></tr>

<tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr><td>Surat keterangan ini diterbitkan sebagai <?php echo $input['keterangan']?>.</td></tr>
<tr></tr>
<tr></tr>

<td class="indentasi">Demikianlah surat ini kami buat dengan sesungguhnya semoga dapat dipergunakan sebagaimana mestinya.</td>
</table>
<table width="100%">
<tr></tr>

<tr></tr>
<tr></tr>
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">Pemegang Surat</td><td width="30%"></td><td align="center"><?php echo unpenetration($input['jabatan'])?> <?php echo unpenetration($desa['nama_desa'])?></td></tr>
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
<tr><td  align="center">( <?php echo unpenetration($data['nama'])?> )<td></td><td align="center">( <?php echo unpenetration($input['pamong'])?> )</td></tr>
</table>  </div></div>

  </div></div>
</div>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>
