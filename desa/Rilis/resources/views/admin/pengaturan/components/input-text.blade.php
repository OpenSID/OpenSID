<input
    type="text"
    class="form-control input-sm {!! $value['class'] !!}"
    id="input_{{ $value['key'] }}"
    name="{{ $value['key'] }}"
    value="{{ $value['default'] }}"
    {{ $value['readonly'] }}
    {!! $value['attributes'] !!}
>
