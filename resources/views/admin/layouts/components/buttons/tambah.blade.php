@if (can('u'))
    @if ($modal)
        <a
            href="{{ site_url($url) }}"
            class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="{{ $judul ?? 'Tambah' }} Data"
            data-target="#{{ $modalTarget ?? 'modalBox' }}"
            data-remote="false"
            data-toggle="modal"
            data-backdrop="false"
            data-keyboard="false"
            data-title="{{ $judul ?? 'Tambah' }} Data"
        ><i class="fa fa fa-plus"></i>
            {{ $judul ?? 'Tambah' }}</a>
    @else
        <a href="{{ site_url($url) }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="{{ $judul ?? 'Tambah' }} Data" target="_blank"><i class="fa fa-plus "></i>{{ $judul ?? 'Tambah' }}</a>
    @endif
@endif
