<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
  table.table th {
    text-align: left;
  }
</style>

<div class="box-header with-border">
  <span style="font-size: x-large; float: left"><strong>BIODATA PENDUDUK</strong></span>
  <div class="text-right">
    <div class="text-right"><a href="<?= site_url("first/cetak_biodata/$penduduk[id]"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i>CETAK BIODATA</button></a></div>
  </div>
</div>
<div class="artikel layanan">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form" >
    <tr>
      <td width="36%">Nama</td>
      <td width="2%">:</td>
      <td width="62%"><?php echo strtoupper($penduduk['nama'])?></td>
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
    <?php if ($penduduk['status_kawin'] <> 1): ?>
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
    <?php endif ?>
    <?php if ($penduduk['status_kawin'] <> 1 and $penduduk['status_kawin'] <> 2): ?>
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
    <?php endif ?>
  </table>
  <div class="box box-info" style="margin-top: 10px; margin-bottom: 0px; padding-bottom: 0px;">
    <div class="box-header with-border">
      <h4 class="box-title">Data Orang Tua</h4>
    </div>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
    <tr>
      <td width="36%">NIK Ayah</td>
      <td width="2%">:</td>
      <td width="62%"><?php echo strtoupper($penduduk['ayah_nik'])?></td>
    </tr>
    <tr class="shaded">
      <td>Nama Ayah</td>
      <td>:</td>
      <td><?php echo strtoupper($penduduk['nama_ayah'])?></td>
    </tr>
    <tr>
      <td>NIK Ibu</td>
      <td>:</td>
      <td><?php echo strtoupper($penduduk['ibu_nik'])?></td>
    </tr>
    <tr class="shaded">
      <td>Nama Ibu</td>
      <td>:</td>
      <td><?php echo strtoupper($penduduk['nama_ibu'])?></td>
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
  </table>

  <div class="box box-info" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h4 class="box-title">KEANGGOTAAN KELOMPOK</h4>
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#kelompok"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body" id="kelompok">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped form">
        <tr>
          <th width="2">No</th>
          <th width="220">Nama Kelompok</th>
          <th width="360">Kategori Kelompok</th>
          <th> &nbsp;</th>
        </tr>
        <?php $no=1; foreach($list_kelompok as $kel){?>
          <tr>
            <td align="center" width="2"><?php echo $no;?></td>
            <td><?php echo $kel['nama']?></td>
            <td><?php echo $kel['kategori']?></td>
            <td></td>
          </tr>
          <?php $no++;
        }?>
      </table>
    </div>
  </div>

  <div class="box box-info" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h4 class="box-title">DOKUMEN / KELENGKAPAN PENDUDUK</h4>
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#dokumen"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body" id="dokumen">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped">
        <thead>
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
              <td align="center" width="2"><?php echo $data['no']?></td>
              <td><?php echo $data['nama']?></td>
              <td><a href="<?php echo base_url().LOKASI_DOKUMEN?><?php echo urlencode($data['satuan'])?>" ><?php echo $data['satuan']?></a></td>
              <td><?php echo tgl_indo2($data['tgl_upload'])?></td>
              <td></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</div>
