@if (theme_config('jam', true))
    <div id="jam"></div>
@endif

@if (theme_config('pintasan_masuk', true))
    <div class="single_bottom_rightbar">
        <h2><i class="fa fa-lock"></i>&ensp;MASUK</h2>
        <div class="tab-pane fade in active">
            <a href="{{ site_url('siteman') }}" class="btn btn-primary btn-block" rel="noopener noreferrer" target="_blank">ADMIN</a>
            @if ((bool) setting('layanan_mandiri'))
                <a href="{{ site_url('layanan-mandiri') }}" class="btn btn-success btn-block" rel="noopener noreferrer" target="_blank">LAYANAN MANDIRI</a>
            @endif
        </div>
    </div>
@endif

<!-- Tampilkan Widget -->
@if ($widgetAktif)
    @foreach ($widgetAktif as $widget)
        @php
            $judul_widget = [
                'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
            ];
        @endphp
        @if ($widget['jenis_widget'] == 3)
            <div class="single_bottom_rightbar">
                <h2><i class="fa fa-folder"></i>&ensp;{{ $judul_widget['judul_widget'] }}</h2>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        {!! html_entity_decode($widget['isi']) !!}
                    </div>
                </div>
            </div>
        @else
            @includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
        @endif
    @endforeach
@endif
