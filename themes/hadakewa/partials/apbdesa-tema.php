<?php
    $thn = date('Y');
    $data = $this->keuangan_grafik_model->grafik_keuangan_hakadewa($thn);
?>

<style type="text/css">
    .progress-bar span{
      position: absolute;
      right: 20px;
      color: #002C6C;
    }
    
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
        foreach ($data['data_widget'] as $subdata_name => $subdatas):
            $tahun = $data['tahun'];
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
                if($subdata['judul'] != NULL):
    ?>
        <div class="progress-group">
            <?= $subdata['judul']; ?><br>
            <b>Rp. <?= number_format($subdata['realisasi']); ?> / Rp. <?= number_format($subdata['anggaran']); ?></b>
            <div class="progress progress-bar-striped" align="right" style="background-color: #27b2c8"><small></small>
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"><span><?= $subdata['persen'] ?> %</span></div>
            </div>
        </div>
    <?php
                endif;
            endforeach;
    ?>
        </div>
    <?php
        endforeach;
    ?>
</div>