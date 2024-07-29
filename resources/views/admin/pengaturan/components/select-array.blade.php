<select name="{{ $value['key'] }}" class="form-control input-sm select2 {!! $value['class'] !!}" name="{{ $value['key'] }}" {{ $value['readonly'] }} {!! $value['attributes'] !!}>
    @foreach ($value['option'] as $key => $val)
        <option value="{{ $key }}" @selected($key == $value['default'])>
            {{ SebutanDesa($val) }}</option>
    @endforeach
</select>
