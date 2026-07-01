@if (can('h'))
    @if ($confirmDelete)
        @if ($selectData)
            <a href="#confirm-delete" title="{{ $judul ?? 'Hapus' }} Data" onclick="deleteAllBox('mainform','{{ site_url($url) }}')" class="btn btn-social {{ $type ?? 'btn-danger' }} btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih">
                <i class="{{ $icon ?? 'fa fa-trash-o' }}"></i> {{ $judul ?? 'Hapus' }}
            </a>
        @else
            @if($visible)
            <a href="#confirm-delete" title="{{ $judul ?? 'Hapus' }} Data" onclick="deleteAllBox('mainform', '{{ site_url($url) }}')" class="btn btn-social {{ $type ?? 'btn-danger' }} btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                                class="{{ $icon ?? 'fa fa-trash-o' }}"
                            ></i> {{ $judul ?? 'Hapus' }}</a>
            @else
            <a href="#" data-href="{{ $url }}" class="btn bg-maroon btn-sm" title="{{ $judul ?? 'Hapus' }} Data" data-toggle="modal" data-target="#{{ $target ?? 'confirm-delete'}}" {{ $attributes }}>
                <i class="{{ $icon ?? 'fa fa-trash-o' }}"></i>
            </a>
            @endif
        @endif
    @else
        <a type="button" class="btn btn-sm {{ $type ?? 'btn-danger' }}"  {{$attribut}} onclick="{{ $onclick }}" title="{{ $judul ?? 'Hapus' }} Data">
            <i class="{{ $icon ?? 'fa fa-trash' }}"></i>
        </a>
    @endif
@endif

