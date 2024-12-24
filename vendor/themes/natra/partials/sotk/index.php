<link rel="stylesheet" href="<?= asset('css/bagan.css') ?>">
<div class="single_page_area">
    <h2 class="post_titile">Struktur Organisasi dan Tata Kerja <?= setting('sebutan_pemerintah_desa') ?></h2>
    <div class="box-body">
        <center>
        <figure class="highcharts-figure" style="max-width: 100%;">
        <div id="container"></div>
            <p class="highcharts-description"></p>
        </figure>
        </center>
    </div>
</div>
<?php include APPPATH . 'views/bagan/chart_bagan.php'; ?>