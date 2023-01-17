<style type="text/css">
  .progress-bar span
  {
    position: absolute;
    right: 20px;
    color: #000000;
    font-weight: bold;
  }

</style>
<div class="container" style="width: 100%; background: #fff; color: #222">
  <div class="box box-info">
    <?php foreach ($data_widget as $subdata_name => $subdatas): ?>
      <div class="col-md-4">
        <div align="center"><h4><?= ($subdatas['laporan'])?></h4></div><hr/>
        <div align="center"><h5>Realisasi | Anggaran</h5></div><hr/>
        <?php foreach ($subdatas as $key => $subdata): ?>
          <?php if ($subdata['judul'] != null && $key != 'laporan' && $subdata['realisasi'] != 0 || $subdata['anggaran'] != 0): ?>
            <div class="progress-group">
              <?= $subdata['judul']; ?><br>
              <b>Rp. <?= number_format($subdata['realisasi']); ?> | Rp. <?= number_format($subdata['anggaran'] + ($subdata['realisasi_jurnal'] ?? 0)); ?></b>
              <div class="progress progress-bar-striped" align="right" style="background-color: #FF0000"><small></small>
                <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"><span><?= $subdata['persen'] ?> %</span></div>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
