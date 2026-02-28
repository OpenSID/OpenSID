@if($modal)
    <a href="{{ $url }}" class="btn bg-info btn-sm" title="{{ $judul ?? 'Lihat Data' }}" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Detail Produk"><i class="fa fa-eye"></i></a>
@else
<a href="{{ $url }}" class="btn bg-info btn-sm" title="{{ $judul ?? 'Lihat Data' }}" @if ($blank) target="_blank" @endif>
    <i class="fa fa-eye fa-sm"></i>
</a>
@endif