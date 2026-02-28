<textarea
    class="form-control input-sm {!! $value['class'] !!} required"
    id="input_{{ $value['key'] }}"
    name="{{ $value['key'] }}"
    rows="5"
    {{ $value['readonly'] }}
    {!! $value['attributes'] !!}
>{{ $value['default'] }}</textarea>
