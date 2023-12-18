<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Pengaturan Peta</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li {{ jecho($tip, 3, "class='active'") }}><a href="{{ site_url('plan') }}">Lokasi</a></li>
            <li {{ jecho($tip, 0, "class='active'") }}><a href="{{ site_url('point') }}">Tipe Lokasi</a></li>
            <li {{ jecho($tip, 6, "class='active'") }}><a href="{{ site_url('simbol') }}">Simbol Lokasi</a></li>
            <li {{ jecho($tip, 1, "class='active'") }}><a href="{{ site_url('garis') }}">Garis</a></li>
            <li {{ jecho($tip, 2, "class='active'") }}><a href="{{ site_url('line') }}">Tipe Garis</a></li>
            <li {{ jecho($tip, 4, "class='active'") }}><a href="{{ site_url('area') }}">Area</a></li>
            <li {{ jecho($tip, 5, "class='active'") }}><a href="{{ site_url('polygon') }}">Tipe Area</a></li>
        </ul>
    </div>
</div>
