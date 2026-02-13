<div class="btn-group btn-group-vertical">
    <a class="btn btn-social {{ $type }} btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="{{ $icon }}"></i> {{ $judul }}</span>
    </a>
    <ul class="dropdown-menu" role="menu">
        @foreach ($list as $key => $value)
        <li>
            <a href="{{ site_url($value['url']) }}" class="btn btn-social btn-block btn-sm"
                title="{{ $value['judul'] }}">
                <i class="fa fa-plus"></i> {{ $value['judul'] }}
            </a>
        </li>
        @endforeach
    </ul>
</div>