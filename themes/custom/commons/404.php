<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="container --text-center --mx-2">
	<img src="<?= base_url($this->theme_folder.'/'.$this->theme .'/assets/images/404.svg') ?>" alt="" class="content__image --mt-10 --mb-10 --mx-auto" style="max-width: 35%">
	<h2 class="content__heading --mt-2 --mb-2">UPS! HALAMAN TIDAK DITEMUKAN</h2>
	<p class="content__article --mb-4">Anda telah terdampar di halaman yang datanya tidak ada lagi di web ini. Mohon periksa kembali, atau laporkan kepada kami.</p>
	<a href="<?= site_url('first') ?>" class="button button--primary --mb-2">Kembali ke halaman utama</a>
</div>