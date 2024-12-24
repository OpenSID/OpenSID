<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-12 widget-surat">
        <a href="{{ ci_route('surat_dinas_arsip.masuk') }}">
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
        <a href="{{ ci_route('surat_dinas_arsip') }}">
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
            <a href="{{ ci_route('surat_dinas_arsip.ditolak') }}">
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
</div>
