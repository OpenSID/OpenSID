<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li>Data Statistik</li>
  </ol>
</div>
<h1 class="text-h2">Data Penduduk Menurut <?= $heading ?></h1>

<div class="table-responsive content py-3">
  <table class="w-full text-sm">
    <thead>
        <tr>
          <th>No</th>
          <th colspan="8">Wilayah / Ketua</th>
          <th class="text-center">KK</th>
          <th class="text-center">L+P</th>
          <th class="text-center">L</th>
          <th class="text-center">P</th>
        </tr>
    </thead>
      <?php if(count($daftar_dusun) > 0) : ?>
        <tbody>
          <?php foreach ($daftar_dusun as $key_dusun => $data_dusun): ?>
          <tr>
            <td class="text-center"><?= $key_dusun + 1; ?></td>
            <td colspan="8">
              <?= ucwords($this->setting->sebutan_dusun . ' ' . $data_dusun['dusun']); ?>
              <?php if ($data_dusun['nama_kadus']): ?>
                , Ketua <?= $data_dusun['nama_kadus']; ?>
              <?php endif ?>
            </td>
            <td class="text-right"><?= $data_dusun['jumlah_kk']; ?></td>
            <td class="text-right"><?= $data_dusun['jumlah_warga']; ?></td>
            <td class="text-right"><?= $data_dusun['jumlah_warga_l']; ?></td>
            <td class="text-right"><?= $data_dusun['jumlah_warga_p']; ?></td>
          </tr>

          <?php
              $no_rt = 1;
              foreach ($data_rw['daftar_rt'] as $data_rt):
            ?>
            <?php if ($data_rt['rt'] != '-'): ?>
              <tr>
                <td></td>
                <td></td>
                <td class="text-center"><?= $no_rt++; ?></td>
                <td colspan="6">
                  RT <?= $data_rt['rt']; ?>
                  <?php if ($data_rt['nama_ketua']): ?>
                    , Ketua <?= $data_rt['nama_ketua']; ?>
                  <?php endif ?>
                </td>
                <td class="text-right"><?= $data_rt['jumlah_kk']; ?></td>
                <td class="text-right"><?= $data_rt['jumlah_warga']; ?></td>
                <td class="text-right"><?= $data_rt['jumlah_warga_l']; ?></td>
                <td class="text-right"><?= $data_rt['jumlah_warga_p']; ?></td>
              </tr>
            <?php endif ?>
          <?php endforeach; ?>
        <?php endforeach; ?>
        
        </tbody>
        <tfoot>
        <tr>
          <th colspan="9">TOTAL</th>
          <th class="text-right"><?= $total['total_kk'] ?></th>
          <th class="text-right"><?= $total['total_warga'] ?></th>
          <th class="text-right"><?= $total['total_warga_l'] ?></th>
          <th class="text-right"><?= $total['total_warga_p'] ?></th>
        </tr>
        </tfoot>
      <?php else : ?>
        <tr><td colspan="13" class="text-center">Daftar masih kosong</td></tr>
    <?php endif ?>
  </table>
</div>