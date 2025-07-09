<div id="penduduk" class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Grafik Laporan Keuangan</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="@active($submenu == 'Grafik Keuangan')"><a href="{{ ci_route('keuangan.laporan') }}?jenis=grafik-RP-APBD-manual&tahun={{ $tahun }}">Grafik Pelaksanaan APBDes</a></li>
        </ul>
    </div>
</div>
<div id="penduduk" class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Tabel Laporan (Belanja Per Bidang)</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="@active($submenu == 'Laporan Keuangan Akhir Bidang Manual')"><a href="{{ ci_route('keuangan.laporan') }}?jenis=rincian_realisasi_bidang_manual&tahun={{ $tahun }}">Laporan Pelaksanaan APBDes Manual</a></li>
        </ul>
    </div>
</div>
