@php
    $ekspor ??= '';
    $id ??= '';
    $targetBlank ??= true;
@endphp

@if (can('u'))
    <div class="btn-group-vertical">
        <a @if ($id) id="{{ $id }}" @else href="{{ site_url($ekspor) }}" @endif @if ($targetBlank) target="_blank" @endif class="btn btn-social bg-navy btn-sm">
            <i class='fa fa-file'></i> Ekspor
        </a>
    </div>
@endif
