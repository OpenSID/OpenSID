<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php if ($paging->num_rows > $paging->per_page): ?>
    <div style="width:100%;float:left;margin:12px 0;">
        <div class="flexcenter infopaging" style="font-size:90%;">Halaman <?= $paging->page ?> dari <?= $paging->end_link ?></div>
        <div class="pagination flexcenter" style="float:none !important;padding:0 !important;margin:0 auto !important;">
            <?php if ($paging->start_link): ?>
                <li><a href="<?= site_url("$paging_page/$paging->start_link" . $paging->suffix); ?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
            <?php endif; ?>
            <?php if ($paging->prev): ?>
                <li><a href="<?= site_url("$paging_page/$paging->prev" . $paging->suffix); ?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
            <?php endif; ?>
            <?php for ($i=$paging->start_link; $i<=$paging->end_link; $i++): ?>
                <li class="<?php ($paging->page != $i) or print('active'); ?>"><a href="<?=site_url("$paging_page/$i" . $paging->suffix); ?>" title="<?= 'Halaman ' . $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <?php if ($paging->next): ?>
                <li><a href="<?= site_url("$paging_page/$paging->next" . $paging->suffix); ?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
            <?php endif; ?>
            <?php if ($paging->end_link): ?>
                <li><a href="<?= site_url("$paging_page/$paging->end_link" . $paging->suffix); ?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
