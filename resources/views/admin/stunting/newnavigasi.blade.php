@push('css')
    <style>
        .small-box {
            border-radius: 5px;
        }
    </style>
@endpush

<div class="row">

    <a href="{{ ci_route('stunting.index') }}">
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="ion ion-location"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Posyandu</span>
                    <span class="info-box-number">{{ $posyandu ?? '0' }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b>{{ $semuaPosyandu ?? '0' }}</b></span>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ ci_route('stunting.anak') }}">
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-stats-bars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Anak Periksa Bulan ini</span>
                    <span class="info-box-number">{{ $anakBulanIni }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b>{{ $semuaAnak }}</b></span>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ ci_route('stunting.ibu_hamil') }}">
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="ion ion-stats-bars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ibu Hamil Bulan ini</span>
                    <span class="info-box-number">{{ $ibuHamilBulanIni }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b>{{ $semuaIbuHamil }}</b></span>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ ci_route('stunting.ibu_hamil') }}">
        <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-stats-bars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ibu Hamil Bulan ini</span>
                    <span class="info-box-number">{{ $ibuHamilBulanIni }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b>{{ $semuaIbuHamil }}</b></span>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Pemantauan</h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li class="@active($title === 'Pemantauan Bulanan Ibu Hamil')"><a href="{{ site_url('stunting/pemantauan_ibu_hamil') }}">Bulanan
                                Ibu
                                Hamil</a></li>
                        <li class="@active($title === 'Pemantauan Bulanan Anak 0 - 2 Tahun')"><a href="{{ site_url('stunting/bulanan_anak') }}">Bulanan
                                Anak
                                0-2
                                Tahun</a></li>
                        <li class="@active($title === 'Pemantauan Layanan dan Sasaran PAUD Anak > 2 - 6 Tahun')"><a href="{{ site_url('stunting/sasaran_paud') }}">Sasaran
                                Paud
                                Anak
                                2-6 tahun</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Rekapitulasi</h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li class="@active($title === 'Rekapitulasi Hasil Pemantauan 3 Bulananan Bagi Ibu Hamil')"><a href="{{ site_url('stunting/rekapitulasi_ibu_hamil') }}">3
                                Bulanan Ibu Hamil</a></li>
                        <li class="@active($title === 'Rekapitulasi Hasil Pemantauan 3 Bulananan Bagi Anak 0-2 Tahun')"><a href="{{ site_url('stunting/rekapitulasi_bulanan_anak') }}">3
                                Bulanan Anak 0-2 Tahun</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-body no-padding">
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li class="@active($title === 'Scorcard Konvergensi Desa')"><a href="{{ site_url('stunting/scorecard_konvergensi') }}">Scorecard
                                Konvergensi
                                Desa</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-lg-9">
        akas
    </div>
</div>
