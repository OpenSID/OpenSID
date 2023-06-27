<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
    <?= $this->setting->admin_title . ' ' . ucwords($this->setting->sebutan_desa) . (($config['nama_desa']) ? ' ' . $config['nama_desa']: '') . get_dynamic_title_page_from_path(); ?>
  </title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css')?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin-style.css')?>">
</head>

<body class="hold-transition verifikasi-page">
  <div class="verifikasi-box">
    <div class="verifikasi-box-body">
      <center>
        <img class="logo" src="<?= gambar_desa($config['logo']); ?>" alt="logo-desa">
        <h4>
          <b>
            Pemerintah <?= ucwords($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten']); ?><br />
            <?= ucwords($this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan']); ?><br />
            <?= ucwords($this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
          </b>
        </h4>
        <hr style="border-bottom: 2px solid #000000; height:0px;">
        <table>
          <tbody>
            <tr>
              <td colspan="3"><u><b>Menyatakan Bahwa :</b></u></td>
            </tr>
            <tr>
              <td width="30%">Nomor Surat</td>
              <td width="1%">:</td>
              <td><?= $surat->nomor_surat; ?></td>
            </tr>
            <tr>
              <td>Tanggal Surat</td>
              <td>:</td>
              <td><?= tgl_indo($surat->tanggal); ?></td>
            </tr>
            <tr>
              <td>Perihal</td>
              <td>:</td>
              <td><?= "Surat " . $surat->perihal; ?></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><?= "a/n " . $surat->nama_penduduk ?? $surat->nama_non_warga; ?></td>
            </tr>
            <tr>
              <td colspan="3"><u><b>Ditandatangani oleh :</b></u></td>
            </tr>
            <tr>
              <td>Nama</td>
              <td>:</td>
              <td><?= $surat->pamong_nama; ?></td>
            </tr>
            <tr>
              <td>Jabatan</td>
              <td>:</td>
              <td><?= $surat->pamong_jabatan; ?></td>
            </tr>
          </tbody>
        </table>
        <br />
        <div class="callout callout-success">
          <h5><b>Adalah benar dan tercatat dalam database sistem informasi kami.</b></h5>
        </div>
      </center>
    </div>
  </div>
</body>

</html>