<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="text-center space-y-5 mx-auto w-11/12 py-5">
  <img src="<?= theme_asset('images/empty.svg')?>" class="w-full mx-auto w-1/4 lg:w-1/4"/>
  <div class="space-y-1">
    <span class="block text-heading"><strong><?= $judulPesan ?: 'Menu Tidak terdaftar'; ?></strong></span>
    <span class="block text-sm"><?= $isiPesan ?: "Silahkan tambah menu terlebih dahulu.<br>Anda bisa melihat panduan membuat menu di link <a href='https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu' target='_blank'>Panduan</a>"; ?></span>
  </div>
</div>