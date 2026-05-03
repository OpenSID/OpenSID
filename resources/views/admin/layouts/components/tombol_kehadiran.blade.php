@if (can('u'))
    @if ($active == '0')
        <a href="{{ $url }}" class="btn bg-aqua btn-sm btn-lock" title="Aktifkan Kehadiran Perangkat"><i class="fa fa-ban"></i></a>
    @else
        <a href="{{ $url }}" class="btn bg-aqua btn-sm btn-lock" title="Nonaktifkan Kehadiran Perangkat"><i class="fa fa-check"></i></a>
    @endif
@endif
