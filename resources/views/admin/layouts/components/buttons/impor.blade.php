@if (can('u'))
    @if ($modal)
    <a
        href="{{ site_url($url) }}"
        class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
        title="{{$judul ? $judul : 'Impor Data'}}"
        data-target="#impor"
        data-remote="false"
        data-toggle="modal"
        data-backdrop="false"
        data-keyboard="false"
    ><i class="fa fa-upload"></i> Impor</a>
    @else
    <a href="{{ site_url($url) }}" class="btn bg-navy btn-sm btn-import" title="Impor Data"><i class="fa fa-upload"></i></a>
    @endif
@endif