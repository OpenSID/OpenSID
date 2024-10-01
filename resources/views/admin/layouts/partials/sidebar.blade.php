<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= gambar_desa($desa['logo']) ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <strong><?= ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) ?></strong>
                </br>

                <?php
                $seb_kec = setting('sebutan_kecamatan');
                $nam_kec = $desa['nama_kecamatan'];
                $seb_kab = setting('sebutan_kabupaten');
                $nam_kab = $desa['nama_kabupaten'];
                ?>

                <?php if (strlen($nam_kec) <= 12 && strlen($nam_kab) <= 12): ?>
                <?= ucwords($seb_kec . ' ' . $nam_kec) ?>
                </br>
                <?= ucwords($seb_kab . ' ' . $nam_kab) ?>
                <?php else: ?>
                <?= ucwords(substr($seb_kec, 0, 3) . '. ' . $nam_kec) ?>
                </br>
                <?= ucwords(substr($seb_kab, 0, 3) . '. ' . $nam_kab) ?>
                <?php endif ?>

            </div>
        </div>

        <div class="sidebar-form">
            <div class="input-group mb-0">
                <input type="text" id="cari-menu" class="form-control" placeholder="Pencarian...">
                <span class="input-group-btn">
                    <button type="button" name="search" id="search-btn" class="btn btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <?php $modul = admin_menu(); ?>
            <?php foreach ($modul as $mod): ?>
            <?php if (is_array($mod['childrens']) && count($mod['childrens']) > 0): ?>
            <li class="treeview <?= jecho($modul_ini, $mod['slug'], 'active') ?>">
                <a href="<?= ci_route($mod['url']) ?>">
                    <i class="fa <?= $mod['ikon'] ?> <?= jecho($modul_ini, $mod['slug'], 'text-aqua') ?>"></i><span><?= $mod['modul'] ?></span>
                    <span class="pull-right-container"><i class='fa fa-angle-left pull-right'></i></span>
                </a>
                <ul class="treeview-menu <?= jecho($modul_ini, $mod['slug'], 'active') ?>">

                    <?php foreach ($mod['childrens'] as $submod): ?>
                    <li class="<?= jecho($sub_modul_ini, $submod['slug'], 'active') ?>">
                        <a href="<?= ci_route($submod['url']) ?>">
                            <i class="fa <?= $submod['ikon'] != null ? $submod['ikon'] : 'fa-circle-o' ?> <?= jecho($sub_modul_ini, $submod['slug'], 'text-red') ?>"></i>
                            <?= $submod['modul'] ?>
                        </a>
                    </li>
                    <?php endforeach ?>

                </ul>
            </li>
            <?php elseif (! empty($mod['url'])): ?>
            <li class="<?= jecho($modul_ini, $mod['slug'], 'active') ?>">
                <a href="<?= ci_route($mod['url']) ?>">
                    <i class="fa <?= $mod['ikon'] ?> <?= jecho($modul_ini, $mod['slug'], 'text-aqua') ?>"></i><span><?= $mod['modul'] ?></span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            <?php endif ?>
            <?php endforeach ?>

        </ul>

    </section>
</aside>
