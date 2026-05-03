<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Inventaris</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach (App\Enums\InventarisMenuEnum::getMenus($controller) as $menu)
                <li {{ jecho($tip, $menu->tip(), 'class="active"') }}>
                    <a href="{{ $menu->url($controller) }}"><i class="{{ $menu->icon() }}"></i> {{ $menu->label() }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Kategori Inventaris</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach (App\Enums\InventarisSubMenuEnum::all() as $key => $value)
                @php
                    $permission = ($key === 'laporan_inventaris')
                        ? 'laporan-inventaris'
                        : str_replace('_', '-', $key);
                @endphp
                @if (can('b', $permission))
                    <li {!! in_array($controller, [$key, $key . '_mutasi']) ? 'class="active"' : '' !!}><a href="{{ site_url($key) }}"><i class="fa fa-tags"></i> {{ $value }}</a></li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
