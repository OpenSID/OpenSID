<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12" id="allstatus">
        <div class="info-box bg-default">
            <span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Semua</span>
                <span class="info-box-number">{{ $allstatus }}</span>

                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
                <span class="progress-description">Total bulan ini: <b>{{ $m_allstatus }}</b></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" id="status1">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Diproses</span>
                <span class="info-box-number">{{ $status1 }}</span>

                <div class="progress">
                    <div class="progress-bar" style="width: {{ persen2($status1, $allstatus) }}%"></div>
                </div>
                <span class="progress-description">Total bulan ini: <b>{{ $m_status1 }}</b></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" id="status2">
        <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sedang Diproses</span>
                <span class="info-box-number">{{ $status2 }}</span>

                <div class="progress">
                    <div class="progress-bar" style="width: {{ persen2($status2, $allstatus) }}%"></div>
                </div>
                <span class="progress-description">Total bulan ini: <b>{{ $m_status2 }}</b></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" id="status3">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai Diproses</span>
                <span class="info-box-number">{{ $status3 }}</span>

                <div class="progress">
                    <div class="progress-bar" style="width: {{ persen2($status3, $allstatus) }}%"></div>
                </div>
                <span class="progress-description">Total bulan ini: <b>{{ $m_status3 }}</b></span>
            </div>
        </div>
    </div>
</div>
