@if (can('u'))
    @if ($active == '0')
        <a href="{{ $url }}" class="btn bg-navy btn-sm btn-lock" title="Aktifkan"><i class="fa fa-lock"></i></a>
    @elseif ($active == '1')
        <a href="{{ $url }}" class="btn bg-navy btn-sm btn-lock" title="Nonaktifkan"><i class="fa fa-unlock"></i></a>
    @endif
@endif
