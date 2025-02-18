<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li>Data Statistik</li>
  </ol>
</div>
<h1 class="text-h2"><?= $heading ?></h1>

<div class="content py-3 table-responsive">
  <table class="w-full text-sm">
    <thead>
      <tr>
        <th>No</th>
        <th><?= ucwords(setting('sebutan_dusun')) ?></th>
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
            <td><?= strtoupper($data['dusun']) ?></td>
            <td class="text-center"><?= strtoupper($data['rw']) ?></td>
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
<p style="color: red">
  Tanggal Pemilihan : <?= $tanggal_pemilihan ?>
</p>