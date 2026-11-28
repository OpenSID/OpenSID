<textarea
    class="form-control input-sm {!! $value['class'] ?? '' !!}"
    id="{{ $value['key'] }}"
    name="{{ $value['key'] }}"
    rows="5"
    {{ $value['readonly'] ?? '' }}
    {{ $value['disabled'] ?? '' }}
    {!! $value['attributes'] ?? '' !!}
>{{ $value['default'] }}</textarea>
