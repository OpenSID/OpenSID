<div class="input-group input-group-sm">
    <div class="input-group-addon">
        <i class="fa fa-clock-o"></i>
    </div>
    <input
        type="text"
        class="form-control input-sm pull-right jam {!! $value['class'] ?? '' !!}"
        id="{{ $value['key'] }}"
        name="{{ $value['key'] }}"
        value="{{ $value['default'] }}"
        placeholder="HH:mm"
        {{ $value['readonly'] ?? '' }}
        {{ $value['disabled'] ?? '' }}
        {!! $value['attributes'] ?? '' !!}
    >
</div>
