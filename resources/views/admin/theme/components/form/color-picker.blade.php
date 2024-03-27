<div class="input-group my-colorpicker2">
    <input
        type="text"
        class="form-control input-sm"
        id="input{{ $value['key'] }}"
        name="opsi[{{ $value['key'] }}]"
        placeholder="{{ $value['placeholder'] }}"
        value="{{ $value['default'] }}"
        {{ $value['readonly'] }}
        {!! $value['attributes'] !!}
    />
    <div class="input-group-addon input-sm"></div>
</div>
