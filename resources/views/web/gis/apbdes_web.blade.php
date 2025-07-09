<style type="text/css">
    .progress-bar span {
        position: absolute;
        right: 20px;
        color: #000000;
        font-weight: bold;
    }
</style>
<div class="modal-body">
    <div class="container" id="transparansi-footer" style="width: 100%; padding-top: 20px; background: #fff; color: #222">
        <div class="box box-info">
            @foreach ($transparansi['data_widget'] as $subdatas)
                <div class="col-md-4">
                    <div align="center" style="height: 0.5em;">
                        <h4>{{ $subdatas['laporan'] }}</h4>
                    </div>
                    <hr />
                    <div align="center" style="height: 0.5em;">
                        <h5>Realisasi | Anggaran</h5>
                    </div>
                    <hr />
                    @foreach ($subdatas as $key => $subdata)
                        @if (($subdata['judul'] ?? null) != null && $key != 'laporan')
                            <div class="progress-group">
                                {{ $subdata['judul'] }}<br>
                                <b>Rp. {{ number_format($subdata['realisasi']) }} | Rp. {{ number_format($subdata['anggaran']) }}</b>
                                <div class="progress progress-bar-striped" align="right" style="background-color: #27b2c8">
                                    <small></small>
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: {{ $subdata['persen'] }}%" aria-valuenow="{{ $subdata['persen'] }}" aria-valuemin="0" aria-valuemax="100">
                                        <span>{{ $subdata['persen'] }} %</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
