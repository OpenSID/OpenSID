@if (can('h'))
    <a href="#confirm-delete" title="{{ $judul ?? 'Hapus' }} Data" onclick="deleteAllBox('mainform','{{ site_url($url) }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i>
        {{ $judul ?? 'Hapus' }}</a>
@endif
