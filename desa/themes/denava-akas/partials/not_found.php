<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="no-found" id="artikel-blank">
    <div class="margin-min10 flexcenter">
        <div class="no-found-title">
            <div class="pd-lr-10">
                <h2>404! <?= $judulPesan ?: 'Menu Tidak terdaftar'; ?></h2>
                <h3><?= $isiPesan ?: "Silakan tambah menu terlebih dahulu.<br>Anda bisa melihat panduan membuat menu di link <a href='https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu' target='_blank'>Panduan</a>"; ?></h3>
            </div>
        </div>
        <div class="no-found-image">
            <div class="pd-lr-10">
                <img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nodata.png") ?>" alt=""/>
            </div>
        </div>
    </div>
</div>
