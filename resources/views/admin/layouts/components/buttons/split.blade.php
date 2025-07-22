<div class="btn-group btn-group-vertical">
    <a class="btn btn-social {{ $type }} btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="{{ $icon }}"></i> {{ $judul }}</span>
    </a>
    <ul class="dropdown-menu" role="menu">
        @foreach ($list as $key => $value)
        <li>
            <a href="{{ site_url($value['url']) }}" class="btn btn-social btn-block btn-sm"
                title="{{ $value['judul'] }}" @if($value['target'])
                target="_blank" @endif @if($value['modal']) data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="{{ $value['judul'] }}"
                @endif>
                <i class="{{ $value['icon'] }}"></i> {{ $value['judul'] }}
            </a>
        </li>
        @endforeach
    </ul>
</div>