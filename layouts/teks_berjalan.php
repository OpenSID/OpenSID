<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php foreach ($teks_berjalan AS $teks): ?>
    <span class="teks" style="font-family: Oswald; padding-right: 50px;">
        <?= $teks['teks']?>
        <?php if ($teks['tautan']): ?>
        <a href="<?= site_url('first/artikel/'.$teks['tautan']) ?>"><?= $teks['judul_tautan']?></a>
        <?php endif; ?>
    </span>
<?php endforeach; ?>
