@if ($modal)
    <a
        href="{{ site_url($url) }}"
        class="btn btn-social {{ $type }} btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
        title="{{ $judul }}"
        data-target="#{{ $modalTarget ?? 'modalBox' }}"
        data-remote="false"
        data-toggle="modal"
        data-backdrop="false"
        data-keyboard="false"
        data-title="{{ $judul }}"
    ><i class="{{ $icon }}"></i>
        {{ $judul }}</a>
@else
<a href="{{ site_url($url) }}" class="btn btn-social {{ $type }} btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="{{ $judul }} Data" @if ($blank) target="_blank" @endif><i class="{{ $icon }} "></i>{{ $judul }}</a>
@endif