<div class="input-group input-group-sm date">
    <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </div>
    <input
        type="text"
        class="form-control input-sm pull-right datepicker tgl_indo {!! $value['class'] ?? '' !!}"
        id="{{ $value['key'] }}"
        name="{{ $value['key'] }}"
        value="{{ $value['default'] }}"
        placeholder="DD-MM-YYYY"
        {{ $value['readonly'] ?? '' }}
        {{ $value['disabled'] ?? '' }}
        {!! $value['attributes'] ?? '' !!}
    >
</div>
