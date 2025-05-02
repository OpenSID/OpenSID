<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Kategori</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach ($submenu as $id => $nama_menu)
                <li {{ jecho($kategori, $id, "class='active'") }}><a href="{{ ci_route('mailbox', $id) }}">{{ $nama_menu }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
