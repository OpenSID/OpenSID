<a
    href="#"
    data-href="{{ $url }}"
    class="btn {{ $type }} btn-sm"
    title="{{ $judul }}"
    data-toggle="modal"
    data-target="#{{ $target }}"
    @if ($confirmMessage) data-body="{{ $confirmMessage }}" @endif
><i class="{{ $icon }}"></i></a>