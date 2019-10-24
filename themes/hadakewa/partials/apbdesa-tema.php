<style type="text/css">
  .progress-bar span{
    position: absolute;
    right: 20px;
    color: #000000;
    font-weight: bold;
  }
</style>
<div class="container" id="transparansi-footer" style="width: 100%; padding-top: 20px; background: #fff; color: #222">
  <?php
    foreach ($data_widget as $subdata_name => $subdatas):
      switch ($subdata_name)
      {
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