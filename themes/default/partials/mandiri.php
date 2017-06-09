<div class="artikel">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
	<tr>
		<td height="30" colspan="3" align="center" valign="middle" bgcolor="#0066FF" scope="col"><b>KARTU KELUARGA PENDUDUK</b></td>
	</tr>
	<tr>
		<td height="50" colspan="3" align="center" scope="col"><a href="<?php echo site_url("first/cetak_kk/$penduduk[id]/1"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i> CETAK KARTU KELUARGA</button></a></td>
  </tr>
 </table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form" >
  <tr>
    <th height="30" colspan="3" align="center" bgcolor="#0066FF" scope="col">
    <div align="center"><b>BIODATA PENDUDUK</b></div></th>
  </tr>
  <tr>
    <td width="36%" height="30">&nbsp;&nbsp;Nama</td>
    <td width="2%" align="center" valign="middle">:</td>
    <td width="62%">&nbsp;&nbsp;<?php echo strtoupper(unpenetration($penduduk['nama']))?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;NIK</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo $penduduk['nik']?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;No KK</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo $penduduk['no_kk']?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Akta Kelahiran</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['akta_lahir'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;<?php echo ucwords(config_item('sebutan_dusun'))?></td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper(ununderscore(unpenetration($penduduk['dusun'])))?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;RT/RW</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['rt'])?>/<?php echo $penduduk['rw']?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Jenis Kelamin</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['sex'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Tempat, Tanggal Lahir</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['tempatlahir'])?>, <?php echo strtoupper($penduduk['tanggallahir'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Agama</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['agama'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Pendidikan Dalam KK</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['pendidikan_kk'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Pendidikan yang sedang ditempuh</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['pendidikan_sedang'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Pekerjaan</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['pekerjaan'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Status Perkawinan</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['kawin'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Warga Negara</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['warganegara'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Dokumen Paspor</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['dokumen_pasport'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Dokumen Kitas</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['dokumen_kitas'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Alamat Sebelumnya</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['alamat_sebelumnya'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Alamat Sekarang</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['alamat'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Akta Perkawinan</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['akta_perkawinan'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Tanggal Perkawinan</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['tanggalperkawinan'])?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Akta Perceraian</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['akta_perceraian'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Tanggal Perceraian</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['tanggalperceraian'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#0066FF">&nbsp;&nbsp;<b>Data Orang Tua</b></td>
    <td align="center" valign="middle" bgcolor="#0066FF">&nbsp;</td>
    <td bgcolor="#0066FF">&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;NIK Ayah</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['ayah_nik'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Nama Ayah</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper(unpenetration($penduduk['nama_ayah']))?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;NIK Ibu</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['ibu_nik'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Nama Ibu</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper(unpenetration($penduduk['nama_ibu']))?></td>
  </tr>
  <tr>
    <td height="30">&nbsp;&nbsp;Cacat</td>
    <td align="center" valign="middle">:</td>
    <td>&nbsp;&nbsp;<?php echo strtoupper($penduduk['cacat_id'])?></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#eee">&nbsp;&nbsp;Status</td>
    <td align="center" valign="middle" bgcolor="#eee">:</td>
    <td bgcolor="#eee">&nbsp;&nbsp;<?php echo strtoupper($penduduk['status'])?></td>
  </tr>
  <tr>
    <td height="50" colspan="3" align="center" scope="col"><a href="<?php echo site_url("first/cetak_biodata/$penduduk[id]"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i> CETAK BIODATA</button></a></td>
  </tr>
</table>

</div>
