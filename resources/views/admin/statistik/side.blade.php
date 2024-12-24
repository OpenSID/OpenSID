<div id="penduduk" class="box box-info {{ $kategori == 'penduduk' ?: 'collapsed-box' }}">
    <div class="box-header with-border">
        <h3 class="box-title">Statistik Penduduk</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa {{ $kategori == 'penduduk' ? 'fa-minus' : 'fa-plus' }}"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach ($stat_penduduk as $id => $nama)
                <li {{ jecho((string) $id, $lap, 'class="active"') }}>{!! anchor("statistik/clear/{$id}", $nama) !!}</li>
            @endforeach
        </ul>
    </div>
</div>
<div id="keluarga" class="box box-info {{ $kategori == 'keluarga' ?: 'collapsed-box' }}">
    <div class="box-header with-border">
        <h3 class="box-title">Statistik Keluarga</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa {{ $kategori == 'keluarga' ? 'fa-minus' : 'fa-plus' }}"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach ($stat_keluarga as $id => $nama)
                <li {{ jecho($id, $lap, 'class="active"') }}>{!! anchor("statistik/clear/{$id}", $nama) !!}</li>
            @endforeach
        </ul>
    </div>
</div>
<div id="rtm" class="box box-info {{ $kategori == 'rtm' ?: 'collapsed-box' }}">
    <div class="box-header with-border">
        <h3 class="box-title">Statistik RTM</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa {{ $kategori == 'rtm' ? 'fa-minus' : 'fa-plus' }}"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach ($stat_rtm as $id => $nama)
                <li {{ jecho($id, $lap, 'class="active"') }}>{!! anchor("statistik/clear/{$id}", $nama) !!}</li>
            @endforeach
        </ul>
    </div>
</div>
<div id="bantuan" class="box box-info {{ $kategori == 'bantuan' ?: 'collapsed-box' }}">
    <div class="box-header with-border">
        <h3 class="box-title">Statistik Program Bantuan</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa {{ $kategori == 'bantuan' ? 'fa-minus' : 'fa-plus' }}"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach ($stat_kategori_bantuan as $id => $nama)
                <li {{ jecho($id, $lap, 'class="active"') }}>{!! anchor("statistik/clear/{$id}", $nama) !!}</li>
            @endforeach
            @foreach ($stat_bantuan as $bantuan)
                <li {{ jecho($bantuan['lap'], $lap, 'class="active"') }}>{!! anchor("statistik/clear/{$bantuan['lap']}", "{$bantuan['nama']} ({$bantuan['lap']})") !!}</li>
            @endforeach
        </ul>
    </div>
</div>
