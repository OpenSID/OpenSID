<a
    href="#"
    data-href="{{ $url }}"
    class="btn {{ $type }} btn-sm"
    title="{{ $judul }}"
    data-toggle="modal"
    data-target="#{{ $target }}"
    @if (!empty($confirmMessage)) data-body="{{ $confirmMessage }}" @endif
    @if (!empty($method)) data-method="{{ $method }}" @endif
><i class="{{ $icon }}"></i></a>