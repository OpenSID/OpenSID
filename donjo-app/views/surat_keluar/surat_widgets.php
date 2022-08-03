<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-6">
        <div class="small-box bg-purple">
          <div class="inner">
             <h3><?= $widgets['suratMasuk'] ?></h3>
            <p>Permohonan Surat</p>
          </div>
          <div class="icon" style="top:0px">
            <i class="ion ion-location"></i>
          </div>

        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= $widgets['arsip'] ?></h3>
                <p>Arsip</p>
            </div>
            <div class="icon" style="top:0px">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>

    <?php if ($operator && (setting('verifikasi_kades') == 1 || setting('verifikasi_sekdes') == 1)): ?>
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $widgets['tolak'] ?></h3>
                    <p>Ditolak</p>
                </div>
                <div class="icon" style="top:0px">
                    <i class="ion-ios-paper"></i>
                </div>
            </div>
        </div>
    <?php endif ?>


</div>