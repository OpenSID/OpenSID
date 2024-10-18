<input
    type="number"
    class="form-control input-sm"
    id="input{{ $value['key'] }}"
    name="opsi[{{ $value['key'] }}]"
    placeholder="{{ $value['placeholder'] }}"
    value="{{ $value['default'] }}"
    {{ $value['readonly'] }}
    {!! $value['attributes'] !!}
>
