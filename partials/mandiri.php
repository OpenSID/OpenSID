<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
  table.table th {
    text-align: left;
  }
</style>

<div class="single_page_area">
<h2 class='post_titile'>Profil</h2>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
    <tr>
      <th colspan="3" class="post_titile" scope="col"><b>	<div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>KARTU KELUARGA PENDUDUK</h2> </div></b></th>
    </tr>
	  <tr>
		  <td colspan="3" class="button" scope="col"><a href="<?= site_url("first/cetak_kk/$penduduk[id]/1"); ?>" rel="noopener noreferrer" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i> CETAK KARTU KELUARGA</button></a></td>
    </tr>
  </table><br>

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form" >
    <tr>
      <th colspan="3" class="post_titile" scope="col"><b><div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>BIODATA PENDUDUK</h2> </div></b></th>
    </tr>
    <tr>
      <td width="36%">Nama</td>
      <td width="2%">:</td>
      <td width="62%"><?= strtoupper(unpenetration($penduduk['nama']))?></td>
    </tr>
    <tr class="shaded">
      <td>NIK</td>
      <td>:</td>
      <td><?= $penduduk['nik']?></td>
    </tr>
    <tr>
      <td>No KK</td>
      <td>:</td>
      <td><?= $penduduk['no_kk']?></td>
    </tr>
    <tr class="shaded">
      <td>Akta Kelahiran</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['akta_lahir'])?></td>
    </tr>
    <tr>
      <td><?= ucwords($this->setting->sebutan_dusun)?></td>
      <td>:</td>
      <td><?= strtoupper($penduduk['dusun'])?></td>
    </tr>
    <tr class="shaded">
      <td>RT/RW</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['rt'])?>/<?= $penduduk['rw']?></td>
    </tr>
    <tr>
      <td>Jenis Kelamin</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['sex'])?></td>
    </tr>
    <tr class="shaded">
      <td>Tempat, Tanggal Lahir</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['tempatlahir'])?>, <?= strtoupper($penduduk['tanggallahir'])?></td>
    </tr>
    <tr>
      <td>Agama</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['agama'])?></td>
    </tr>
    <tr class="shaded">
      <td>Pendidikan Dalam KK</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['pendidikan_kk'])?></td>
    </tr>
    <tr>
      <td>Pendidikan yang sedang ditempuh</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['pendidikan_sedang'])?></td>
    </tr>
    <tr class="shaded">
      <td>Pekerjaan</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['pekerjaan'])?></td>
    </tr>
    <tr>
      <td>Status Perkawinan</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['kawin'])?></td>
    </tr>
    <tr class="shaded">
      <td>Warga Negara</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['warganegara'])?></td>
    </tr>
    <tr>
      <td>Dokumen Paspor</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['dokumen_pasport'])?></td>
    </tr>
    <tr class="shaded">
      <td>Dokumen Kitas</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['dokumen_kitas'])?></td>
    </tr>
    <tr>
      <td>Alamat Sebelumnya</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['alamat_sebelumnya'])?></td>
    </tr>
    <tr class="shaded">
      <td>Alamat Sekarang</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['alamat'])?></td>
    </tr>
    <tr>
      <td>Akta Perkawinan</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['akta_perkawinan'])?></td>
    </tr>
    <tr class="shaded">
      <td>Tanggal Perkawinan</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['tanggalperkawinan'])?></td>
    </tr>
    <tr>
      <td>Akta Perceraian</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['akta_perceraian'])?></td>
    </tr>
    <tr class="shaded">
      <td>Tanggal Perceraian</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['tanggalperceraian'])?></td>
    </tr>
    <tr class="judul">
      <td><b>Data Orang Tua</b></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td>NIK Ayah</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['ayah_nik'])?></td>
    </tr>
    <tr class="shaded">
      <td>Nama Ayah</td>
      <td>:</td>
      <td><?= strtoupper(unpenetration($penduduk['nama_ayah']))?></td>
    </tr>
    <tr>
      <td>NIK Ibu</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['ibu_nik'])?></td>
    </tr>
    <tr class="shaded">
      <td>Nama Ibu</td>
      <td>:</td>
      <td><?= strtoupper(unpenetration($penduduk['nama_ibu']))?></td>
    </tr>
    <tr>
      <td>Cacat</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['cacat'])?></td>
    </tr>
    <tr class="shaded">
      <td>Status</td>
      <td>:</td>
      <td><?= strtoupper($penduduk['status'])?></td>
    </tr><br>
    <tr>
      <td colspan="3" class="button" scope="col"><a href="<?= site_url("first/cetak_biodata/$penduduk[id]"); ?>" rel="noopener noreferrer" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i> CETAK BIODATA</button></a></td>
    </tr>
  </table><br>

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped form">
      <tr>
        <th colspan="4" class="judul" scope="col"><b><div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>KEANGGOTAAN KELOMPOK</h2> </div></b></th>
      </tr>
      <tr>
        <th width="2">No</th>
        <th width="220">Nama Kelompok</th>
        <th width="360">Kategori Kelompok</th>
        <th> &nbsp;</th>
      </tr>
      <?php $no=1; foreach($list_kelompok as $kel){?>
        <tr>
          <td align="center" width="2"><?= $no;?></td>
          <td><?= $kel['nama']?></td>
          <td><?= $kel['kategori']?></td>
          <td></td>
        </tr>
        <?php $no++;
      }?>
  </table><br>

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped">
    <thead>
      <tr>
        <th colspan="5" class="judul" scope="col"><b><div class="single_bottom_rightbar wow fadeInDown animated"> 
	<h2>DOKUMEN / KELENGKAPAN PENDUDUK</h2></div></b></th>
      </tr>
      <tr>
        <th width="2">No</th>
        <th width="220">Nama Dokumen</th>
        <th width="360">Berkas</th>
        <th width="200">Tanggal Upload</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($list_dokumen as $data){?>
        <tr>
          <td align="center" width="2"><?= $data['no']?></td>
          <td><?= $data['nama']?></td>
          <td><a href="<?= base_url().LOKASI_DOKUMEN?><?= urlencode($data['satuan'])?>" ><?= $data['satuan']?></a></td>
          <td><?= tgl_indo2($data['tgl_upload'])?></td>
          <td></td>
        </tr>
      <?php }?>
    </tbody>
  </table>

</div>
