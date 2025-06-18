<div class="btn-group-vertical">
    <a class="btn btn-social btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down' title="Cetak/Unduh Data"></i> Cetak/Unduh</a>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="<?= site_url($cetak) ?>" class="btn btn-social btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" title="Cetak Data" data-title="Cetak Data"><i class="fa fa-print"></i> Cetak</a>
        </li>
        <li>
            <a href="<?= site_url($unduh) ?>" class="btn btn-social btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" title="Unduh Data" data-title="Unduh Data"><i class="fa fa-download"></i> Unduh</a>
        </li>
    </ul>
</div>
