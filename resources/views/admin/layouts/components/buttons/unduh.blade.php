@if (can('b'))
    @if ($modal)
        <a href="{{ site_url($url) }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            data-remote="false"
            data-toggle="modal"
            data-target="{{ $modalTarget ?? '#modalBox' }}"
            data-title="{{ $judul ?? 'Unduh' }} Data">
            <i class="fa fa-download"></i>
            {{ $judul ?? 'Unduh' }} Data
        </a>
    @else
        @if($buttonOnly)
        <a href="{{ $url }}" class="btn bg-purple btn-sm"  title="Unduh" style="margin-right: 2px"><i class="fa fa-download"></i></a>
        @else
        <a href="{{ site_url($url) }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="{{ $judul ?? 'Unduh' }} Data" target="_blank"><i
            class="fa fa-download">
            </i>{{ $judul ?? 'Unduh' }} Data
        </a>
        @endif
    @endif
@endif
