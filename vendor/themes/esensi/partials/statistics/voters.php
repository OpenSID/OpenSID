<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li>Data Statistik</li>
  </ol>
</div>
<h1 class="text-h2">Daftar Calon Pemilih Berdasarkan Wilayah (pada tgl pemilihan <?= $tanggal_pemilihan ?>)</h1>

<div class="content py-3 table-responsive">
  <table class="w-full text-sm">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Dusun</th>
        <th>RW</th>
        <th>Jiwa</th>
        <th>Lk</th>
        <th>Pr</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=0; ?>
        <?php foreach($main as $data): ?>
          <tr>
            <td class="text-center"><?= $data['no'] ?></td>
            <td class="text-right"><?= strtoupper($data['dusun']) ?></td>
            <td class="text-right"><?= strtoupper($data['rw']) ?></td>
            <td class="text-right"><?= $data['jumlah_warga'] ?></td>
            <td class="text-right"><?= $data['jumlah_warga_l'] ?></td>
            <td class="text-right"><?= $data['jumlah_warga_p'] ?></td>
          </tr>
        <?php $i = $i+$data['jumlah']; ?>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr class="font-bold">
        <td colspan="3" class="text-left">TOTAL</td>
        <td class="text-right"><?= $total['total_warga']; ?></td>
        <td class="text-right"><?= $total['total_warga_l']; ?></td>
        <td class="text-right"><?= $total['total_warga_p']; ?></td>
      </tr>
    </tfoot>
  </table>
</div>