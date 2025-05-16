<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
  <div class="statistikstyle">
    <div class="container-page mb-20">
      <div class="headingpage border-grey-soft bg-gradient-hor flexleft" style="border-radius:5px 5px 0 0;">
        <div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location.svg") ?>" alt=""/></div>
        <div class="articlehome-head flexleft"><h1>STATISTIK</h1></div>
      </div>
      <div class="box-article mb-30 bg-white" style="border-radius:0 0 5px 5px;">
        <div class="relative-border p-15 border-grey-soft" style="border-radius:0 0 5px 5px;">
          <div class="gridview">
            <div class="sidebarright">
              <img style="width:100%;height:1px;display:block;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/empty-pic.png") ?>"/>
              <ul>
                <?php $this->load->view($folder_themes .'/partials/kependudukan/navigasi') ?>
              </ul>
            </div>
            <div class="head-content">
              <div style="text-align:center;">
                <h1>
                  <?php if (IS_PREMIUM && IS_240803) : ?>
                  <?= $heading; ?>
                  <?php else : ?>
                  Jumlah Penduduk berdasarkan Wilayah <?= !empty($this->setting->sebutan_dusun) ? ucwords($this->setting->sebutan_dusun) . ',' : ''; ?> RW, RT, dan Jenis Kelamin<br>di <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?><br>Tahun <?= date('Y') ?>
                  <?php endif; ?>
                </h1>
              </div>
              <?php if(count($daftar_dusun ?? []) > 0) : ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th rowspan="2" style="text-align:center">No.</th>
                      <th rowspan="2" colspan="8" style="text-align:center">Wilayah, <?= ucwords($this->setting->sebutan_singkatan_kadus) ?>/Ketua</th>
                      <th rowspan="2" class="text-center" style="text-align:center">KK</th>
                      <th rowspan="2" class="text-center" style="text-align:center">Jiwa</th>
                      <th colspan="2" class="text-center" style="text-align:center">Laki-Laki</th>
                      <th colspan="2" class="text-center" style="text-align:center">Perempuan</th>
                    </tr>
                    <tr>
                      <th style="text-align:center">Jiwa</th><th style="text-align:center">%</th>
                      <th style="text-align:center">Jiwa</th><th style="text-align:center">%</th>
                    </tr>
                  </thead>
                    <tbody>
                      <?php foreach ($daftar_dusun as $key_dusun => $data_dusun): ?>
                        <tr>
                          <th class="text-center"><?= $key_dusun + 1; ?></th>
                          <th colspan="8">
                            <?= ucwords($this->setting->sebutan_dusun . ' ' . $data_dusun['dusun']); ?><?php if ($data_dusun['nama_kadus']): ?>, <?= ucwords($this->setting->sebutan_singkatan_kadus) ?> <?= $data_dusun['nama_kadus']; ?><?php endif ?>
                          </th>
                          <th class="angka" style="text-align:center"><?= ribuan($data_dusun['jumlah_kk']); ?></th>
                          <th class="angka" style="text-align:center"><?= ribuan($data_dusun['jumlah_warga']); ?></th>
                          <th class="angka" style="text-align:center"><?= ribuan($data_dusun['jumlah_warga_l']); ?></th>
                          <th class="angka" style="text-align:center"><?= number_format(($data_dusun['jumlah_warga_l'] / ($data_dusun['jumlah_warga'] ?: 1) * 100), 2); ?>%</th>
                          <th class="angka" style="text-align:center"><?= ribuan($data_dusun['jumlah_warga_p']); ?></th>
                          <th class="angka" style="text-align:center"><?= number_format(($data_dusun['jumlah_warga_p'] / ($data_dusun['jumlah_warga'] ?: 1) * 100), 2); ?>%</th>
                        </tr>
                        <?php
                        $no_rw = 1;
                        foreach ($data_dusun['daftar_rw'] as $data_rw):
                          ?>
                          <?php if ($data_rw['rw'] != '-'): ?>
                            <tr>
                              <th></th>
                              <th class="text-center"><?= $no_rw++; ?></th>
                              <th colspan="7">
                                RW. <?= $data_rw['rw']; ?><?php if ($data_rw['nama_ketua']): ?>, Ketua <?= $data_rw['nama_ketua']; ?><?php endif ?>
                              </th>
                              <th class="angka" style="text-align:center"><?= ribuan($data_rw['jumlah_kk']); ?></th>
                              <th class="angka" style="text-align:center"><?= ribuan($data_rw['jumlah_warga']); ?></th>
                              <th class="angka" style="text-align:center"><?= ribuan($data_rw['jumlah_warga_l']); ?></th>
                              <th class="angka" style="text-align:center"><?= number_format(($data_rw['jumlah_warga_l'] / ($data_rw['jumlah_warga'] ?: 1) * 100), 2); ?>%</th>
                              <th class="angka" style="text-align:center"><?= ribuan($data_rw['jumlah_warga_p']); ?></th>
                              <th class="angka" style="text-align:center"><?= number_format(($data_rw['jumlah_warga_p'] / ($data_rw['jumlah_warga'] ?: 1) * 100), 2); ?>%</th>
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
                                  RT. <?= $data_rt['rt']; ?><?php if ($data_rt['nama_ketua']): ?>, Ketua <?= $data_rt['nama_ketua']; ?><?php endif ?>
                                </td>
                                <td class="angka" style="text-align:center"><?= ribuan($data_rt['jumlah_kk']); ?></td>
                                <td class="angka" style="text-align:center"><?= ribuan($data_rt['jumlah_warga']); ?></td>
                                <td class="angka" style="text-align:center"><?= ribuan($data_rt['jumlah_warga_l']); ?></td>
                                <td class="angka" style="text-align:center"><?= number_format(($data_rt['jumlah_warga_l'] / ($data_rt['jumlah_warga'] ?: 1) * 100), 2); ?>%</td>
                                <td class="angka" style="text-align:center"><?= ribuan($data_rt['jumlah_warga_p']); ?></td>
                                <td class="angka" style="text-align:center"><?= number_format(($data_rt['jumlah_warga_p'] / ($data_rt['jumlah_warga'] ?: 1) * 100), 2); ?>%</td>
                              </tr>
                            <?php endif ?>
                          <?php endforeach; ?>
                        <?php endforeach; ?>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="9">TOTAL</th>
                        <th class="angka" style="text-align:center"><?= ribuan($total['total_kk']) ?></th>
                        <th class="angka" style="text-align:center"><?= ribuan($total['total_warga']) ?></th>
                        <th class="angka" style="text-align:center"><?= ribuan($total['total_warga_l']) ?></th>
                        <th class="angka" style="text-align:center"><?= number_format(($total['total_warga_l'] / ($total['total_warga'] ?: 1) * 100), 2); ?>%</th>
                        <th class="angka" style="text-align:center"><?= ribuan($total['total_warga_p']) ?></th>
                        <th class="angka" style="text-align:center"><?= number_format(($total['total_warga_p'] / ($total['total_warga'] ?: 1) * 100), 2); ?>%</th>
                      </tr>
                    </tfoot>
                </table>
              </div>
              <?php else : ?>
                <div class="no-photo mb-20 flexcenter">
                  <p>Untuk sementara<br/>Data <?=$heading?> belum tersedia.</p>
                  <img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofdata1.png") ?>" alt=""/>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="small-screen"><?php $this->load->view($folder_themes .'/partials/kependudukan/navigasi') ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
