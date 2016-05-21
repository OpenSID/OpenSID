<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?=base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>
<table width="100%">

<tr> <img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt="" class="logo"></tr>

<div class="header">
<h4 class="kop">PEMERINTAH KABUPATEN <?=strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
<h4 class="kop">KECAMATAN <?=strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
<h4 class="kop">DESA <?=strtoupper(unpenetration($desa['nama_desa']))?></h4>
<h5 class="kop2"><?=unpenetration(($desa['alamat_kantor']))?> </h5>

<div style="text-align: center;">
<hr /></div></div>


<div align="center"><u><h4 class="kop">SURAT KETERANGAN PINDAH</h4></u></div>
<div align="center"><h4 class="kop">NO: <?=$input['nomor']?></h4></div>
<tr>
<div class="clear"></div>

<td class="indentasi">Yang bertanda tangan dibawah ini <?=unpenetration($pamong['jabatan'])?> <?=unpenetration($desa['nama_desa'])?>, Kecamatan <?=unpenetration($desa['nama_kecamatan'])?>,
Kabupaten <?=unpenetration($desa['nama_kabupaten'])?>, Provinsi <?=unpenetration($desa['nama_propinsi'])?> menerangkan bahwa:  </td></tr>
</table><div id="isi3">
<table width="100%">
<tr><td width="35%">Nama Lengkap</td><td width="3%">:</td><td width="64%"><?=unpenetration($data['nama'])?></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><?=$data['sex']?></td></tr>
<tr><td>Tempat dan Tgl. Lahir </td><td>:</td><td><?=$data['tempatlahir']?>, <?=tgl_indo($data['tanggallahir'])?> </td></tr>
<tr><td>Status</td><td>:</td><td><?=$data['status_kawin']?></td></tr>
<tr><td>Kewarganegaraan / Agama</td><td>:</td><td><?=$pribadi['wn']?> / <?=$data['agama']?></td></tr>
<tr><td>Pekerjaan</td><td>:</td><td><?=$data['pekerjaan']?></td></tr>
<tr><td>No KTP</td><td>:</td><td><?=$data['nik']?></td></tr>
<tr><td>Alamat</td><td>:</td><td>RT. <?=$data['rt']?>, RW. <?=$data['rw']?>, Dusun <?=ununderscore(unpenetration($data['dusun']))?>, Desa <?=unpenetration($desa['nama_desa'])?>, Kec. <?=unpenetration($desa['nama_kecamatan'])?>, Kab. <?=unpenetration($desa['nama_kabupaten'])?></td></tr>


<tr><td>Alamat yang dituju</td><td>:</td><td>RT. <?=$input['rt_tujuan']?>, RW. <?=$input['rw_tujuan']?>, Kampung <?=$input['kampung_tujuan']?>, Dusun <?=ununderscore(unpenetration($input['dusun_tujuan']))?>, Desa <?=unpenetration($input['desa_tujuan'])?>, Kec. <?=unpenetration($input['kecamatan_tujuan'])?>, Kab. <?=unpenetration($input['kabupaten_tujuan'])?></td></tr>

<tr><td>Alasan </td><td>:</td><td> <?=$input['alasan']?></td></tr>

<tr><td>Tanggal pindah </td><td>:</td><td> <?=tgl_indo(tgl_indo_in($input['awal']))?></td></tr>
<? 	$i=0;
	if($pengikut){
		foreach($pengikut AS $data1){
			$i++;
		}
	}
?>
<tr><td>Jumlah Pengikut </td><td>:</td><td> <?=$i;?> orang</td></tr>
</table>


<tr></tr>
<? if($pengikut){ ?>
<table style="border:1px solid ;" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th align="left" >NIK</th>
			<th align="left" >Nama</th>
			<th align="left" align="center">JK</th>
			<th  align="left" >Umur</th>
			<th align="left" >Status Kawin</th>      
		</tr>
	

	<tbody><? $i=0;?>
		<? foreach($pengikut AS $data1){$i++;?>
		<tr>
            <td align="center" width="2"><?=$i?></td>
			<td><?=$data1['nik']?></td>
			<td><?=unpenetration($data1['nama'])?></td>
			<td><?=$data1['sex']?></td>
			<td><?=$data1['umur']?></td>
			<td><?=$data1['status_kawin']?></td>
		</tr>  
		<? }?>
	</tbody>
</table>
<? } ?>
<tr></tr>

<tr>
<table width="100%">
<tr></tr>
<tr></tr>
<tr><td>Surat keterangan ini diterbitkan sebagai <?=$input['keterangan']?>.</td></tr>
<tr></tr>
<tr></tr>

<td class="indentasi">Demikianlah surat ini kami buat dengan sesungguhnya semoga dapat dipergunakan sebagaimana mestinya.</td>
</table>
<table width="100%">
<tr></tr>

<tr></tr>
<tr></tr>
<tr><td width="23%"></td><td width="30%"></td><td  align="center"><?=unpenetration($desa['nama_desa'])?>, <?=$tanggal_sekarang?></td></tr>
<tr><td width="23%" align="center">Pemegang Surat</td><td width="30%"></td><td align="center"><?=unpenetration($input['jabatan'])?> <?=unpenetration($desa['nama_desa'])?></td></tr>
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
<tr><td  align="center">( <?=unpenetration($data['nama'])?> )<td></td><td align="center">( <?=unpenetration($pamong['pamong_nama'])?> )</td></tr>
</table>  </div></div>

  </div></div>
</div>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>
