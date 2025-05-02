@if (!$cek_kades && !empty($main->kode_desa))
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-warning">
                <h4><i class="fa fa-warning"></i>&nbsp;&nbsp;Informasi</h4>
                <p>Silahkan tentukan {{ ucwords(setting('sebutan_kepala_desa') . ' ' . $main->nama_desa) }} melalui
                    modul <a href="{{ ci_route('pengurus') }}"><b>{{ ucwords(setting('sebutan_pemerintah_desa')) }}</b></a>
                </p>
            </div>
        </div>
    </div>
@endif
