@push('css')
    <style>
        .small-box {
            border-radius: 5px;
            padding-bottom: 27px;
        }

        .small-box .icon {
            top: -5px;
        }

        .small-box:hover {
            transform: scale(1.01);
            transition: 0.3s;
        }
    </style>
@endpush

<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $bulanIniIbuHamil }}</h3>
                <p>Ibu Hamil Periksa Bulan ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-location"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $bulanIniAnak }}</h3>
                <p>Anak Periksa Bulan ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $totalIbuHamil }}</h3>
                <p>Total Periksa Ibu Hamil</p>
            </div>
            <div class="icon">
                <i class="ion ion-woman"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $totalAnak }}</h3>
                <p>Total Periksa Anak</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div>

</div>
