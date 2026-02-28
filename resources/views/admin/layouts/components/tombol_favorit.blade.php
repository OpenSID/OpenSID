@if (can('u'))
    @if ($show == '1')
        @if ($active == '0')
            <a href="{{ $url }}" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a>
        @elseif ($active == '1')
            <a href="{{ $url }}" class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a>
        @endif
    @endif
@endif
