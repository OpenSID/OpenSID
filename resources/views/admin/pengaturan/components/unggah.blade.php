@php
    $previewUrl = $value['raw_attr']['preview_url'] ?? url('kehadiran/latar-kehadiran');
@endphp

<div class="input-group">
    <input
        type="text"
        class="form-control input-sm {!! $value['class'] ?? '' !!}"
        id="file_path_{{ $value['key'] }}"
        name="{{ $value['key'] }}"
        value="{{ $value['default'] }}"
        readonly
        {{ $value['disabled'] ?? '' }}
        {!! $value['attributes'] ?? '' !!}
    >
    <input type="file" class="hidden" id="file_{{ $value['key'] }}" name="{{ $value['key'] }}">
    <span class="input-group-btn">
        <button type="button" class="btn btn-info btn-sm" id="file_browser_{{ $value['key'] }}"><i class="fa fa-search"></i>&nbsp;</button>
        @if (!empty($previewUrl))
            <a href="{{ $previewUrl }}" class="btn btn-danger btn-sm" title="Lihat Gambar" target="_blank"><i class="fa fa-eye"></i>&nbsp;</a>
        @endif
    </span>
</div>
