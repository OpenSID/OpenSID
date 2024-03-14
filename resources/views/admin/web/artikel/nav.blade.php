<div>
    <div class="box box-info">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li class="@active($cat == -1)">
                    <a href='{{ ci_route('web', -1) }}'>
                        Semua Artikel Dinamis
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Kategori Artikel</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @foreach ($list_kategori as $data)
                    <li class="@active($cat == $data['id'])">
                        <a href='{{ ci_route('web', $data['id']) }}'>
                            {{ $data['kategori'] }}
                        </a>
                    </li>
                    @foreach ($data['children'] as $submenu)
                        <li class="@active($cat == $submenu['id'])">
                            <a href='{{ ci_route('web', $submenu['id']) }}'>
                                &emsp;{{ $submenu['kategori'] }}
                            </a>
                        </li>
                    @endforeach
                @endforeach
                <li class="@active($cat == '0')">
                    <a href='{{ ci_route('web', 0) }}'>
                        [Tidak Berkategori]
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Artikel Statis</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li class="@active((string) $cat === 'statis')"><a href="{{ ci_route('web.statis') }}">Halaman Statis</a></li>
                <li class="@active((string) $cat === 'agenda')"><a href="{{ ci_route('web.agenda') }}">Agenda</a></li>
                <li class="@active((string) $cat === 'keuangan')"><a href="{{ ci_route('web.keuangan') }}">Keuangan</a></li>
            </ul>
        </div>
    </div>
</div>
