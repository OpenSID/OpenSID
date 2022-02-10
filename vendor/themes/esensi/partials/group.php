<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li aria-current="page">Data Kelompok</li>
  </ol>
</nav>

<h2 class="text-h2">Data Kelompok - <?= $detail['nama']; ?></h2>

<p class="py-4"><?= $detail['keterangan'] ?></p>

<h3 class="text-h4">Daftar Pengurus</h3>
<div class="table-responsive content">
  <table class="w-full text-sm">
    <thead>
      <tr>
        <th>No</th>
        <th>Jabatan</th>
        <th>Nama</th>
        <th>Alamat</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pengurus as $key => $data): ?>
        <tr>
          <td><?= $key + 1?></td>
          <td><?= $this->referensi_model->list_ref(JABATAN_KELOMPOK)[$data['jabatan']]?></td>
          <td nowrap><?= $data['nama']?></td>
          <td><?= $data['alamat']?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<h3 class="text-h4">Daftar Anggota</h3>
<div class="table-responsive content">
  <table class="w-full text-sm">
    <thead>
      <tr>
        <th>No</th>
        <th>No. Anggota</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Jenis Kelamin</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($anggota as $key => $data): ?>
      <tr>
        <td><?= ($key + 1) ?></td>
        <td><?= $data['no_anggota'] ?:'-' ?></td>
        <td nowrap><?= $data['nama'] ?></td>
        <td><?= $data['alamat'] ?></td>
        <td><?= $data['sex'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>