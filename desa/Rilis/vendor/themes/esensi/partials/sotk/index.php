<link rel="stylesheet" href="<?= asset('css/bagan.css') ?>">
<div class="content py-1">
    <div class="box box-danger" style="padding-bottom: 2rem;">
        <div class="box-header with-border" style="margin-bottom: 15px;">
            <h class="box-title">Struktur Organisasi dan Tata Kerja <?= setting('sebutan_pemerintah_desa') ?></h>
        </div>
        <div class="box-body">
            <center>
            <figure class="highcharts-figure" style="max-width: 100%;">
            <div id="container"></div>
            </figure>
            </center>
        </div>
    </div>
</div>
<?php include APPPATH . 'views/bagan/chart_bagan.php'; ?>