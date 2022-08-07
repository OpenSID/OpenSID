<div class="row">
    <a href="<?= site_url($this->controller . '/clear/masuk')?>">
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="info-box bg-aqua <?= jecho($this->tab_ini, 11, 'active') ?>">
                <span class="info-box-icon"><i class="fa fa-envelope-o fa-nav"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Permohonan</span>
                    <span class="info-box-number"><?= $widgets['suratMasuk'] ?></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b><?= $widgets['suratMasuk'] ?></b></span>
                </div>
            </div>
        </div>
    </a>

    <a href="<?= site_url($this->controller . '/clear')?>">
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="info-box bg-green <?= jecho($this->tab_ini, 10, 'active') ?>">
                <span class="info-box-icon"><i class="fa fa-book fa-nav"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Arsip</span>
                    <span class="info-box-number"><?= $widgets['arsip'] ?></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b><?= $widgets['arsip'] ?></b></span>
                </div>
            </div>
        </div>
    </a>


    <?php if ($operator && (setting('verifikasi_kades') == 1 || setting('verifikasi_sekdes') == 1)): ?>
        <a href="<?= site_url($this->controller . '/clear/ditolak')?>">
            <div class="col-lg-3 col-sm-6 col-xs-6">
                <div class="info-box bg-red <?= jecho($this->tab_ini, 12, 'active') ?>">
                    <span class="info-box-icon"><i class="fa fa-window-close fa-nav"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ditolak</span>
                        <span class="info-box-number"><?= $widgets['tolak'] ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Total : <b><?= $widgets['tolak'] ?></b></span>
                    </div>
                </div>
            </div>
        </a>

    <?php endif ?>
</div>
