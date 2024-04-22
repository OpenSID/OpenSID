<div class="btn-group-vertical">
    <a class="btn btn-social btn-flat bg-navy btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Impor / Ekspor</a>
    <ul class="dropdown-menu" role="menu">
        <?php if (can('u')) : ?>
            <li>
                <a href="<?= site_url($impor); ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#impor" data-title="Impor Data <?= $detail; ?>"><i class="fa fa-upload"></i> Impor Data</a>
            </li>
        <?php endif; ?>
        <li>
            <a href="<?= site_url($ekspor); ?>" target="_blank" class="btn btn-social btn-flat btn-block btn-sm" title="Ekspor Data <?= $detail; ?>"><i class="fa fa-download"></i> Ekspor Data</a>
        </li>
    </ul>
</div>