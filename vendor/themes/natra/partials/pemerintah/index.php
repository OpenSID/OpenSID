<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style>
    .image {
        width: 80%;
        margin-left: auto;
        margin-right: auto;
        overflow: hidden;
        transition: all 500ms ease;
        padding: 5px;
    }

    .pamong {
        padding: 20px;
    }

    .card {
        background-color: darkgrey;
        padding: 5px;
        border-radius: 10px;
    }

    .line {
        margin: 5px 0;
        height: 1px;
    }
</style>
<div class="single_category wow fadeInDown">
    <h2><span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text"><?= ucwords(setting('sebutan_pemerintah_desa')) ?></span></h2>
</div>

<div class="box box-primary">
    <div class="box-body">
        <br>
        <div class="row">
            <?php if ($pemerintah) : ?>
                <?php foreach ($pemerintah as $data) : ?>
                    <div class="col-sm-3 pamong">
                        <div class="card text-center">
                            <img width="auto" class="rounded-circle image" src="<?= $data['foto'] ?>" alt="Foto <?= $data['nama'] ?>" />
                            <hr class="line">
                            <b>
                                <?= $data['nama'] ?><br>
                                <?= $data['jabatan'] ?><br>
                                <?php if ($data['kehadiran'] == 1) : ?>
                                    <?php if ($this->setting->tampilkan_kehadiran && $data['status_kehadiran'] == 'hadir') : ?>
                                        <span class='label label-success'>Hadir</span>
                                    <?php elseif ($this->setting->tampilkan_kehadiran && $data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir') : ?>
                                        <span class='label label-danger'><?= ucwords($data['status_kehadiran']) ?></span>
                                    <?php elseif ($this->setting->tampilkan_kehadiran && $data['tanggal'] != date('Y-m-d')) : ?>
                                        <span class='label label-danger'>Belum Rekam Kehadiran</span>
                                    <?php else : ?>
                                        <br>
                                    <?php endif ?>
                                <?php else : ?>
                                    <br>
                                <?php endif ?>
                                <br>
                                <?php if (count($media_sosial ?? []) > 0) : ?>
                                    <?php  $sosmed_pengurus = json_decode($data['media_sosial'], true); ?>
                                    <?php foreach ($media_sosial as $value): ?>
                                        <?php if ($sosmed_pengurus[$value['id']]): ?>
                                            <a href="<?= $sosmed_pengurus[$value['id']] ?>" target="_blank" style="padding: 5px;">
                                                <span style="color:#fff;"><i class="fa fa-<?=$value['id']?> fa-2x"></i></span>
                                            </a>
                                        <?php else : ?>
                                            <a style="padding: 5px;">
                                                <span style="color:#fff;"><i class="fa fa-<?=$value['id']?> fa-2x"></i></span>
                                            </a>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else : ?>
                <h5><?= ucwords(setting('sebutan_pemerintah_desa')) ?> tidak tersedia.</h5>
            <?php endif ?>
        </div>
    </div>
</div>