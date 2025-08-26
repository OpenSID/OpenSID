@if($modal)
<a href="{{ site_url($url) }}"
class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
title="{{$judul ? $judul : 'Impor Data'}}"
data-target="{{ $modalTarget ?? '#impor' }}"
data-remote="false"
data-toggle="modal"
data-backdrop="false"
data-keyboard="false"
data-title="{{ $judul ?? 'Cetak' }} Data"
><i class="fa fa-upload"></i> Impor</a>
@else
<a href="{{ $url }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-download"></i> Impor</a>
@endif