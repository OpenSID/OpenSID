<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="btn btn-hero btn-rounded btn-alt-success">
    <?php foreach ($teks_berjalan AS $teks): ?>
    <span><?= $teks['teks']?></span>
        <a href="<?= site_url('first/artikel/'.$teks['tautan']) ?>"><?= $teks['judul_tautan']?> <strong><?php if ($teks['tautan']): ?></strong></a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
