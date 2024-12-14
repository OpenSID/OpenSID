@if (can('b', 'analisis-kategori') || can('b', 'analisis-indikator') || can('b', 'analisis-klasifikasi') || can('b', 'analisis-periode'))
    <div id="penduduk" class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Pengaturan Analisis</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @if (can('b', 'analisis-kategori'))
                    <li class="@active($selectedMenu == 'Data Kategori')"><a href="{{ ci_route('analisis_kategori', $analisis_master['id']) }}">Kategori / Variabel</a></li>
                @endif
                @if (can('b', 'analisis-indikator'))
                    <li class="@active($selectedMenu == 'Data Indikator')"><a href="{{ ci_route('analisis_indikator', $analisis_master['id']) }}">Indikator & Pertanyaan</a></li>
                @endif
                @if (can('b', 'analisis-klasifikasi'))
                    <li class="@active($selectedMenu == 'Data Klasifikasi')"><a href="{{ ci_route('analisis_klasifikasi', $analisis_master['id']) }}">Klasifikasi Analisis</a></li>
                @endif
                @if (can('b', 'analisis-periode'))
                    <li class="@active($selectedMenu == 'Data Periode')"><a href="{{ ci_route('analisis_periode', $analisis_master['id']) }}">Periode Sensus / Survei</a></li>
                @endif
            </ul>
        </div>
    </div>
@endif

@if (can('b', 'analisis-respon'))
    <div id="penduduk" class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Input Data Analisis</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @if (can('b', 'analisis-respon'))
                    <li class="@active($selectedMenu == 'Input Data')"><a href="{{ ci_route('analisis_respon', $analisis_master['id']) }}">Input Data Sensus / Survei</a></li>
                @endif
            </ul>
        </div>
    </div>
@endif

@if (can('b', 'analisis-laporan') || can('b', 'analisis-statistik-jawaban'))
    <div id="penduduk" class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Laporan Analisis</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @if (can('b', 'analisis-laporan'))
                    <li class="@active($selectedMenu == 'Laporan Analisis')"><a href="{{ ci_route('analisis_laporan', $analisis_master['id']) }}">Laporan Hasil Klasifikasi</a></li>
                @endif
                @if (can('b', 'analisis-statistik-jawaban'))
                    <li class="@active($selectedMenu == 'Statistik Jawaban')"><a href="{{ ci_route('analisis_statistik_jawaban', $analisis_master['id']) }}">Laporan Per Indikator</a></li>
                @endif
            </ul>
        </div>
    </div>
@endif
