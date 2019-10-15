<?php
    $tahun = date('Y');
    $data = $this->keuangan_grafik_model->grafik_keuangan_hakadewa();
?>

<style type="text/css">



    /*Untuk menyembunyikan dan menampilkan menu.*/
    .transparansi-hidden{
        display: none;
    }

    .transparansi-show{
        display: show;
    }
</style>
<!-- Untuk menyembunyikan menu ini, ganti class transparansi-show dengan transparansi-hidden. -->
<div class="container transparansi-show" style="width: 100%; padding-top: 20px; background: #fff; color: #222">
    <?php
        foreach ($data as $subdata_name => $subdatas):
            switch ($subdata_name) {
                case 'res_pelaksanaan':
                    $subdata_name = 'Pelaksanaan Tahun '. $tahun;
                    break;

                case 'res_pendapatan':
                    $subdata_name = 'Pendapatan Tahun '. $tahun;
                    break;
                
                case 'res_belanja':
                    $subdata_name = 'Belanja Tahun '. $tahun;
                    break;

                default:
                    $subdata_name = '';
                    break;
            }
    ?>
    <div class="col-md-4">
        <div align="center"><h2><?= ($subdata_name)?></h2></div><hr/>
    <?php
            foreach ($subdatas as $subdata):
                $subdata['persen'] = round($subdata['persen'], 2);
    ?>
        <div class="progress-group">
            <?= $subdata['judul']; ?><br>
            <b>Rp. <?= number_format($subdata['realisasi']); ?> / Rp. <?= number_format($subdata['anggaran']); ?></b>
            <div class="progress progress-bar-striped" align="right" style="background-color: #27b2c8"><small><b><?= $subdata['persen'] ?> %</b></small>&nbsp;
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= 100 - $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

        </div>
    <?php
            endforeach;
    ?>
        </div>
    <?php
        endforeach;
    ?>
</div>