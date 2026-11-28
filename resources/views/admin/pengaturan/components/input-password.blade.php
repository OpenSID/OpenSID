<div class="input-group input-group-sm">
    <input
        type="password"
        class="form-control {{ $value['class'] }}"
        id="input_{{ $value['key'] }}"
        name="{{ $value['key'] }}"
        data-password="{{ !empty($value['default']) ? 1 : 0 }}"
        value=""
        {{ $value['readonly'] }}
        {{ $value['disabled'] }}
        {!! $value['attributes'] !!}
    >

    <div class="input-group-addon show-hide-password" style="cursor:pointer;">
        <i class="fa fa-eye-slash"></i>
    </div>
</div>

@if (!empty($value['default']))
    <p class="help-block small text-red">
        Kosongkan jika tidak ingin mengubah Password.
    </p>
@endif