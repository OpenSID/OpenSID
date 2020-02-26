<style type="text/css">
  .progress-bar span
  {
    position: absolute;
    right: 20px;
    color: #000000;
    font-weight: bold;
  }
  .box-info {
    border-top-width: 5px;
    padding-top: 0px
  }
</style>
<div class="container" id="transparansi-footer" style="width: 100%; padding-top: 10px; background: #fff; color: #222">
  <div class="box box-info">
    <?php foreach ($data_widget as $subdata_name => $subdatas): ?>
      <div class="col-md-4">
      <div align="center" style="height: 3.5em;"><h2><?= ($subdatas['laporan'])?></h2></div><hr/>
      <div align="center" style="height: 1em;"><h4>Realisasi | Anggaran</h4></div><hr/>
      <?php foreach ($subdatas as $key => $subdata): ?>
        <?php if($subdata['judul'] != NULL and $key != 'laporan'): ?>
          <div class="progress-group">
            <?= $subdata['judul']; ?><br>
            <b>Rp. <?= number_format($subdata['realisasi']); ?> | Rp. <?= number_format($subdata['anggaran']); ?></b>
            <div class="progress progress-bar-striped" align="right" style="background-color: #27b2c8"><small></small>
              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"><span><?= $subdata['persen'] ?> %</span></div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
