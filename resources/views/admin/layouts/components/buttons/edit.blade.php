@if (can('u'))
    @if ($modal)
        <a
            @if ($url) href="{{ site_url($url) }}" @endif
            class="btn {{ $color ?? 'bg-orange' }} btn-sm {{ $canClass ?? '' }}"
            title="{{ $judul ?? 'Ubah' }}"
            data-target="#{{ $modalTarget ?? 'modalBox' }}"
            data-remote="false"
            data-toggle="modal"
            data-backdrop="false"
            data-keyboard="false"
            data-title="{{ $judul ?? 'Ubah' }}"
        ><i class="{{ $icon ?? 'fa fa-edit' }}"></i></a>
    @else
        <a href="{{ site_url($url) }}" class="btn {{ $color ?? 'bg-orange' }} btn-sm" title="{{ $judul ?? 'Ubah' }} Data" @if ($blank) target="_blank" @endif><i class="{{ $icon ?? 'fa fa-edit' }}"></i></a>
    @endif
@endif
