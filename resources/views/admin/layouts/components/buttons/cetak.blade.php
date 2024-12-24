@if (can('b'))
    @if ($modal)
        <a href="{{ site_url($url) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="{{ $judul ?? 'Cetak' }} Data"><i class="fa fa-print "></i>
            {{ $judul ?? 'Cetak' }}</a>
    @else
        <a href="{{ site_url($url) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="{{ $judul ?? 'Cetak' }} Data" target="_blank"><i class="fa fa-print "></i>{{ $judul ?? 'Cetak' }}</a>
    @endif
@endif
