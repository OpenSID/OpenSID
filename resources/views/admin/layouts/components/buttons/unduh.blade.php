@if (can('b'))
    @if ($modal)
        <a href="{{ site_url($url) }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="{{ $judul ?? 'Unduh' }}"><i class="fa fa-download"></i>
            {{ $judul ?? 'Unduh' }}</a>
    @else
        <a href="{{ site_url($url) }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="{{ $judul ?? 'Unduh' }} Data" target="_blank"><i class="fa fa-download "></i>{{ $judul ?? 'Unduh' }}</a>
    @endif
@endif
