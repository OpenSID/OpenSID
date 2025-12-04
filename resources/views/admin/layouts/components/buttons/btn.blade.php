@if ($modal)
    @if($buttonOnly)
        <a href="{{$url}}" class="btn {{ $type }} btn-sm" @if ($onclick) onclick="{{ $onclick }}" @endif @if($dataHref) data-href="{{ $dataHref }}" @endif  @if($dataBody) data-body="{{ $dataBody }}" @endif {{ $attribut }} data-remote="false" data-toggle="modal" data-target="#{{ $modalTarget ?? 'modalBox' }}" data-title="{{ $judul }}" title="{{ $judul }}"><i class="{{ $icon }}"></i>{{ $withJudul ? ' '.$withJudul : '' }}</a>
    @elseif($confirm)
        <a href="#{{ $confirmTarget ?? 'confirm-delete' }}"
        onclick="{{ $onclick }}"
        class="btn btn-social {{ $type }} btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
        title="{{ $judul }}">
            <i class="{{ $icon }}"></i> {{ $judul }}
        </a>
    @else
        <a
        @if($url) href="{{ site_url($url) }}" @endif
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
    @endif
    
@else
    @if($buttonOnly)
        <a href="{{$url}}" {{ $attribut }} class="btn {{ $type }} btn-sm" @if ($blank) target="_blank" @endif  title="{{ $judul }}"><i class="{{ $icon }}"></i></a>
    @elseif($formAction)
        <a href="#" @if ($tooltip) title="{{ $tooltip }}" id="kirim" onclick="formAction('mainform','{{ $url }}')" @endif class="btn btn-social {{ $type }} btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block aksi-terpilih"><i class="{{ $icon }} "></i>{{ $judul }}</a>
    @else
        <a href="{{ $slug ? $url : ($file ? base_url($url) : ($url ? site_url($url) : '#')) }}" @if ($disabled) disabled @endif @if ($tooltip) title="{{ $tooltip }}" @endif class="btn btn-social {{ $type }} btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="{{ $judul }} Data" @if ($blank) target="_blank" @endif><i class="{{ $icon }} "></i>{{ $judul }}</a>
    @endif
@endif