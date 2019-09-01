<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="rounded font-w600 p-10 mb-10 animated fadeIn ml-50 bg-primary-lighter text-primary-darker">
    <?php foreach ($teks_berjalan AS $teks): ?>
    <?php if ($teks['tautan']): ?>
    <a class="link-effect" href="<?= site_url('first/artikel/'.$teks['tautan']) ?>"><?= $teks['teks']?></a>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
