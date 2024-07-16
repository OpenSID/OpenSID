<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Kontak</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="@active($navigasi === 'Penduduk')"><a href="{{ ci_route('daftar_kontak/penduduk') }}"><i class="fa fa-list-alt"></i> Penduduk</a></li>
            <li class="@active($navigasi === 'Eksternal')"><a href="{{ ci_route('daftar_kontak') }}"><i class="fa fa-external-link"></i> Eksternal</a></li>
            <li class="@active($controller === 'grup_kontak')"><a href="{{ ci_route('grup_kontak') }}"><i class="fa fa-group"></i> Grup</a></li>
        </ul>
    </div>
</div>
