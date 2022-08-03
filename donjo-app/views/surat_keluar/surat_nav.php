<div class="box box-info">
    <div class="box-body no-padding">
        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li class="<?= jecho($this->tab_ini, 11, 'active') ?>"><a href="<?= site_url($this->controller . '/clear/masuk')?>">Permohonan Surat</a></li>
            </ul>

            <ul class="nav nav-stacked">
                <li class="<?= jecho($this->tab_ini, 10, 'active') ?>"><a href="<?= site_url($this->controller . '/clear')?>">Arsip</a></li>
            </ul>

            <?php if ($operator && (setting('verifikasi_kades') == 1 || setting('verifikasi_sekdes') == 1)): ?>
                <ul class="nav nav-stacked">
                    <li class="<?= jecho($this->tab_ini, 12, 'active') ?>"><a href="<?= site_url($this->controller . '/clear/ditolak')?>">Tolak</a></li>
                </ul>
            <?php endif ?>
        </div>
    </div>
</div>