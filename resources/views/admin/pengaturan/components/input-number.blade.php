<input
    type="number"
    class="form-control input-sm"
    id="input_{{ $value['key'] }}"
    name="{{ $value['key'] }}"
    value="{{ $value['default'] }}"
    {{ $value['readonly'] }}
    {!! $value['attributes'] !!}
>
