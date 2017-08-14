<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="artikel layanan">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
  <tr>
    <th colspan="3" class="judul" scope="col"><b>KARTU KELUARGA PENDUDUK</b></th>
  </tr>
  <tr>
    <td colspan="3" class="button" scope="col"><a href="<?php echo site_url("first/cetak_kk/$penduduk[id]/1"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i> CETAK KARTU KELUARGA</button></a></td>
  </tr>
 </table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form" >
  <tr>
    <th colspan="3" class="judul" scope="col"><b>BIODATA PENDUDUK</b></th>
  </tr>
  <tr>
    <td width="36%">Nama</td>
    <td width="2%">:</td>
    <td width="62%"><?php echo strtoupper(unpenetration($penduduk['nama']))?></td>
  </tr>
  <tr class="shaded">
    <td>NIK</td>
    <td>:</td>
    <td><?php echo $penduduk['nik']?></td>
  </tr>
  <tr>
    <td>No KK</td>
    <td>:</td>
    <td><?php echo $penduduk['no_kk']?></td>
  </tr>
  <tr class="shaded">
    <td>Akta Kelahiran</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['akta_lahir'])?></td>
  </tr>
  <tr>
    <td><?php echo ucwords($this->setting->sebutan_dusun)?></td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['dusun'])?></td>
  </tr>
  <tr class="shaded">
    <td>RT/RW</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['rt'])?>/<?php echo $penduduk['rw']?></td>
  </tr>
  <tr>
    <td>Jenis Kelamin</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['sex'])?></td>
  </tr>
  <tr class="shaded">
    <td>Tempat, Tanggal Lahir</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['tempatlahir'])?>, <?php echo strtoupper($penduduk['tanggallahir'])?></td>
  </tr>
  <tr>
    <td>Agama</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['agama'])?></td>
  </tr>
  <tr class="shaded">
    <td>Pendidikan Dalam KK</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['pendidikan_kk'])?></td>
  </tr>
  <tr>
    <td>Pendidikan yang sedang ditempuh</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['pendidikan_sedang'])?></td>
  </tr>
  <tr class="shaded">
    <td>Pekerjaan</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['pekerjaan'])?></td>
  </tr>
  <tr>
    <td>Status Perkawinan</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['kawin'])?></td>
  </tr>
  <tr class="shaded">
    <td>Warga Negara</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['warganegara'])?></td>
  </tr>
  <tr>
    <td>Dokumen Paspor</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['dokumen_pasport'])?></td>
  </tr>
  <tr class="shaded">
    <td>Dokumen Kitas</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['dokumen_kitas'])?></td>
  </tr>
  <tr>
    <td>Alamat Sebelumnya</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['alamat_sebelumnya'])?></td>
  </tr>
  <tr class="shaded">
    <td>Alamat Sekarang</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['alamat'])?></td>
  </tr>
  <tr>
    <td>Akta Perkawinan</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['akta_perkawinan'])?></td>
  </tr>
  <tr class="shaded">
    <td>Tanggal Perkawinan</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['tanggalperkawinan'])?></td>
  </tr>
  <tr>
    <td>Akta Perceraian</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['akta_perceraian'])?></td>
  </tr>
  <tr class="shaded">
    <td>Tanggal Perceraian</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['tanggalperceraian'])?></td>
  </tr>
  <tr class="judul">
    <td><b>Data Orang Tua</b></td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td>NIK Ayah</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['ayah_nik'])?></td>
  </tr>
  <tr class="shaded">
    <td>Nama Ayah</td>
    <td>:</td>
    <td><?php echo strtoupper(unpenetration($penduduk['nama_ayah']))?></td>
  </tr>
  <tr>
    <td>NIK Ibu</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['ibu_nik'])?></td>
  </tr>
  <tr class="shaded">
    <td>Nama Ibu</td>
    <td>:</td>
    <td><?php echo strtoupper(unpenetration($penduduk['nama_ibu']))?></td>
  </tr>
  <tr>
    <td>Cacat</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['cacat'])?></td>
  </tr>
  <tr class="shaded">
    <td>Status</td>
    <td>:</td>
    <td><?php echo strtoupper($penduduk['status'])?></td>
  </tr>
  <tr>
    <td colspan="3" class="button" scope="col"><a href="<?php echo site_url("first/cetak_biodata/$penduduk[id]"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i> CETAK BIODATA</button></a></td>
  </tr>
</table>

</div>
