<select id="{{ $value['key'] }}" name="{{ $value['key'] }}" class="form-control input-sm select2 {!! $value['class'] !!}" {{ $value['readonly'] }} {!! $value['attributes'] !!}>
    @foreach ($value['option'] as $key => $val)
        <option value="{{ $key }}" @selected($key == $value['default'])>
            {{ SebutanDesa($val) }}</option>
    @endforeach
</select>
