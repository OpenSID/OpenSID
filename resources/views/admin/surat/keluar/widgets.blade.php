@if (setting('sinkronisasi_opendk'))
    <div class="alert alert-warning alert-dismissible">
        <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
        Fitur Sinkronisasi Surat TTE ke {{ setting('sebutan_kecamatan') }} saat ini masih berupa demo menunggu proses penyempurnaan dan terdapat {{ setting('sebutan_kecamatan') }} yang sudah mengimplentasikan TTE.
        Kami juga menghimbau kepada seluruh pengguna memberikan masukan terkait penyempurnaan fitur ini baik dari sisi OpenSID maupun OpenDK.
        Masukan dapat disampaikan di grup telegram, forum opendesa maupun issue di github.
    </div>
@endif
<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-12 widget-surat">
        <a href="{{ ci_route('keluar/masuk') }}">
            <div class="info-box bg-aqua {{ $tab_ini == 11 ? 'active' : '' }}">
                <span class="info-box-icon"><i class="fa fa-envelope-o fa-nav"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Permohonan</span>
                    <span class="info-box-number">{{ $widgets['suratMasuk'] }}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b>{{ $widgets['suratMasuk'] }}</b></span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12 widget-surat">
        <a href="{{ ci_route('keluar') }}">
            <div class="info-box bg-green {{ $tab_ini == 10 ? 'active' : '' }}">
                <span class="info-box-icon"><i class="fa fa-book fa-nav"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Arsip</span>
                    <span class="info-box-number">{{ $widgets['arsip'] }}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Total : <b>{{ $widgets['arsip'] }}</b></span>
                </div>
            </div>
        </a>
    </div>

    @if ($operator && (setting('verifikasi_kades') == 1 || setting('verifikasi_sekdes') == 1))
        <div class="col-lg-3 col-sm-6 col-xs-12 widget-surat">
            <a href="{{ ci_route('keluar/ditolak') }}">
                <div class="info-box bg-red {{ $tab_ini == 12 ? 'active' : '' }}">
                    <span class="info-box-icon"><i class="fa fa-window-close fa-nav"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ditolak</span>
                        <span class="info-box-number">{{ $widgets['tolak'] }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Total : <b>{{ $widgets['tolak'] }}</b></span>
                    </div>
                </div>
            </a>
        </div>
    @endif

    @if (setting('sinkronisasi_opendk'))
        <div class="col-lg-3 col-sm-6 col-xs-12 widget-surat">
            <a href="{{ ci_route('keluar.kecamatan') }}">
                <div class="info-box bg-orange {{ $tab_ini == 13 ? 'active' : '' }}">
                    <span class="info-box-icon"><i class="fa fa-share-square fa-nav"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ ucwords(setting('sebutan_kecamatan')) }}</span>
                        <span class="info-box-number">{{ $widgets['kecamatan'] }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Total : <b>{{ $widgets['kecamatan'] }}</b></span>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div>
