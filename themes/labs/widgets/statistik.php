<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title">
            <i class="si si-bar-chart"></i>
             Statistik Desa</h3>
        <div class="block-options mr-15">
        <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <?php
      $cowok1 = $this->db->query('SELECT sex FROM tweb_penduduk WHERE sex = 1');
      $cewek1 = $this->db->query('SELECT sex FROM tweb_penduduk WHERE sex = 2');
      $kk1 = $this->db->query('SELECT * FROM tweb_keluarga WHERE id_cluster != 0');
      
      $cowok = $cowok1->num_rows();
      $cewek = $cewek1->num_rows();
      $dua = $cowok+$cewek;
      $kk = $kk1->num_rows(); ?>
    <div class="block-content">
        <div class="progress push">
            <div
                class="progress-bar progress-bar-striped progress-bar-animated"
                role="progressbar"
                style="width: <?= $cowok/$dua*100; ?>%"
                aria-valuenow="30"
                aria-valuemin="0"
                aria-valuemax="100">
                <span class="progress-bar-label">Laki - Laki
                    <?= number_format($cowok);?>
                    Jiwa</span>
            </div>
        </div>
        <div class="progress push">
            <div
                class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                role="progressbar"
                style="width:  <?= $cewek/$dua*100; ?>%"
                aria-valuenow="50"
                aria-valuemin="0"
                aria-valuemax="100">
                <span class="progress-bar-label">Perempuan
                    <?= number_format($cewek);?>
                    Jiwa</span>
            </div>
        </div>
    </div>
    <div class="block-content">
        <div class="row">
            <div class="col-sm-6">
                <div class="block block-bordered block-rounded">
                    <div class="block-content block-content-full bg-earth">
                        <div class="pb-15 text-center">
                            <h6 class="font-w700 text-white mb-0">Jumlah Jiwa
                            </h6>
                            <div
                                class="h4 font-w700 text-white mb-0"
                                data-toggle="countTo"
                                data-to="<?= number_format($dua);?>"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="block block-bordered block-rounded">
                    <div class="block-content block-content-full bg-corporate">
                        <div class="pb-15 text-center">
                            <h6 class="font-w700 text-white mb-0">Jumlah KK</h6>
                            <div
                                class="h4 font-w700 text-white mb-0"
                                data-toggle="countTo"
                                data-to="<?= number_format($kk);?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
