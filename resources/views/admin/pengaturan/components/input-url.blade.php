<input
    type="url"
    class="form-control input-sm {!! $value['class'] ?? '' !!}"
    id="{{ $value['key'] }}"
    name="{{ $value['key'] }}"
    value="{{ $value['default'] }}"
    {{ $value['readonly'] ?? '' }}
    {{ $value['disabled'] ?? '' }}
    {!! $value['attributes'] ?? '' !!}
>
