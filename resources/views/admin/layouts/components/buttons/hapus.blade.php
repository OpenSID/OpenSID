@if (can('h') && !isset($buttonOnly))
    {{-- Tombol default --}}
    <a href="#confirm-delete" title="{{ $judul ?? 'Hapus' }} Data" onclick="deleteAllBox('mainform','{{ site_url($url) }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih">
        <i class='fa fa-trash-o'></i> {{ $judul ?? 'Hapus' }}
    </a>
@elseif (can('h') && isset($buttonOnly) && $buttonOnly)
    {{-- Tombol custom --}}
    <a type="button" class="btn btn-sm btn-danger" onclick="{{ $onclick }}">
        <i class="fa fa-trash"></i>
    </a>
@endif
