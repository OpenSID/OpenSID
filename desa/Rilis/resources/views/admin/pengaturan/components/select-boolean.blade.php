<select class="form-control input-sm select2 {!! $value['class'] !!}" name="{{ $value['key'] }}" {{ $value['readonly'] }} {!! $value['attributes'] !!}>
    @foreach (\App\Enums\StatusEnum::all() as $key => $val)
        <option value="{{ $key }}" @selected($key == $value['default'])>{{ $val }}</option>
    @endforeach
</select>
