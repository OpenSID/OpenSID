<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb">
    <ol>
        <li><a href="<?= site_url() ?>">Beranda</a></li>
        <li aria-current="page"><?= ucwords(setting('sebutan_pemerintah_desa')) ?></li>
    </ol>
</nav>
<h1 class="text-h2"><?= ucwords(setting('sebutan_pemerintah_desa')) ?></h1>
<?php if ($pemerintah) : ?>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-5">
        <?php foreach ($pemerintah as $data) : ?>
            <div class="space-y-3">
                <img class="h-44 w-full object-cover object-center bg-gray-300 dark:bg-gray-600" src="<?= $data['foto'] ?>" alt="Foto <?= $data['nama'] ?>" />
                <div class="space-y-1 text-sm text-center z-10">
                    <span class="text-h6"><?= $data['nama'] ?></span>
                    <span class="block"><?= $data['jabatan'] ?></span>
                    <?php if ($data['pamong_niap']) : ?>
                        <span class="block"><?= $this->setting->sebutan_nip_desa ?> : <?= $data['pamong_niap'] ?></span>
                    <?php endif ?>
                    <?php if ($data['kehadiran'] == 1) { ?>
                        <?php if ($data['status_kehadiran'] == 'hadir') : ?>
                            <span class="btn btn-primary w-auto mx-auto inline-block">Hadir</span>
                        <?php endif ?>
                        <?php if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir') : ?>
                            <span class="btn btn-danger w-auto mx-auto inline-block"><?= ucwords($data['status_kehadiran']) ?></span>
                        <?php endif ?>
                        <?php if ($data['tanggal'] != date('Y-m-d')) : ?>
                            <span class="btn btn-danger w-auto mx-auto inline-block">Belum Rekam Kehadiran</span>
                        <?php endif ?>
                    <?php } ?>
                    <?php
                        $data_sosmed = json_decode($data['media_sosial'], true);
                        foreach ($data_sosmed as $key => $value):
                    ?>
                        <a href="<?= $value ?>" target="_blank" class="inline-flex items-center justify-center bg-blue-600 h-8 w-8 rounded-full"><i class="fab fa-lg fa-<?=$key?>" style="color: #fff;"></i></a>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php else : ?>
    <p class="py-3"><?= ucwords(setting('sebutan_pemerintah_desa')) ?> tidak tersedia.</p>
<?php endif ?>