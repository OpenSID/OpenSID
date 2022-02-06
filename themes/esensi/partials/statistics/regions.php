<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="breadcrumb">
  <ol>
    <li><a href="<?= site_url() ?>">Beranda</a></li>
    <li>Data Statistik</li>
  </ol>
</div>
<h2 class="text-h2">Data Penduduk Menurut <?= $heading ?></h2>

<div class="table-responsive content py-3">
  <table class="w-full text-sm">
    <thead>
      <?php if(IS_PREMIUM) : ?>
        <tr>
          <th>No</th>
          <th colspan="8">Wilayah / Ketua</th>
          <th class="text-center">KK</th>
          <th class="text-center">L+P</th>
          <th class="text-center">L</th>
          <th class="text-center">P</th>
        </tr>
        <?php else : ?>
          <tr>
            <th>No</th>
            <th><?= ucwords($this->setting->sebutan_dusun)?></th>
            <th>RW</th>
            <th>RT</th>
            <th>Nama Kepala/Ketua</th>
            <th class="center">KK</th>
            <th class="center">L+P</th>
            <th class="center">L</th>
            <th class="center">P</th>
          </tr>
      <?php endif ?>
    </thead>
    <?php if(IS_PREMIUM) : ?>
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
            $no_rw = 1;
            foreach ($data_dusun['daftar_rw'] as $data_rw):
          ?>
            <?php if ($data_rw['rw'] != '-'): ?>
              <tr>
                <td></td>
                <td class="text-center"><?= $no_rw++; ?></td>
                <td colspan="7">
                  RW <?= $data_rw['rw']; ?>
                  <?php if ($data_rw['nama_ketua']): ?>
                    , Ketua <?= $data_rw['nama_ketua']; ?>
                  <?php endif ?>
                </td>
                <td class="text-right"><?= $data_rw['jumlah_kk']; ?></td>
                <td class="text-right"><?= $data_rw['jumlah_warga']; ?></td>
                <td class="text-right"><?= $data_rw['jumlah_warga_l']; ?></td>
                <td class="text-right"><?= $data_rw['jumlah_warga_p']; ?></td>
              </tr>
            <?php endif ?>

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
      <?php endif; ?>
      <?php else : ?>
        <?php if(count($main) > 0) : ?>
          <tbody>
            <?php foreach ($main as $indeks => $data): ?>
              <tr>
                <td class="text-center"><?= $indeks + 1?></td>
                <td><?= ($main[$indeks - 1]['dusun'] == $data['dusun']) ? '' : strtoupper($data['dusun'])?></td>
                <td><?= ($main[$indeks - 1]['rw'] == $data['rw']) ? '' : $data['rw']?></td>
                <td><?= $data['rt']?></td>
                <td><?= $data['nama_kepala']?></td>
                <td class="text-right"><?= $data['jumlah_kk']?></td>
                <td class="text-right"><?= $data['jumlah_warga']?></td>
                <td class="text-right"><?= $data['jumlah_warga_l']?></td>
                <td class="text-right"><?= $data['jumlah_warga_p']?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
            <tfoot>
              <tr>
                <td colspan="5" class="text-left"><label>TOTAL</label></td>
                <td class="text-right"><?= $total['total_kk']?></td>
                <td class="text-right"><?= $total['total_warga']?></td>
                <td class="text-right"><?= $total['total_warga_l']?></td>
                <td class="text-right"><?= $total['total_warga_p']?></td>
              </tr>
            </tfoot>
          <?php else : ?>
            <tr><td colspan="9" class="text-center">Daftar masih kosong</td></tr>
        <?php endif ?>
    <?php endif ?>
  </table>
</div>