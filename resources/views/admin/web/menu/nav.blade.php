<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Kategori Menu</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li {{ jecho($tip, 1, "class='active'") }}><a href="{{ ci_route('menu') }}">Menu Statis</a></li>
            <li {{ jecho($tip, 2, "class='active'") }}><a href="{{ ci_route('kategori') }}">Menu Dinamis / Kategori</a></li>
        </ul>
    </div>
</div>
