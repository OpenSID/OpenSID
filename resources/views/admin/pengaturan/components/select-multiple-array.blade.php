<input type="hidden" name="{{ $value['key'] }}" value="[]">
<select class="form-control input-sm select2 {!! $value['class'] !!}" name="{{ $value['key'] }}[]" multiple="multiple" {{ $value['readonly'] }} {!! $value['attributes'] !!}>
    @foreach ($value['option'] as $val)
        <option value="{{ $val['id'] }}" @selected(in_array($val['id'], json_decode($value['default']) ?? []))>
            {!! SebutanDesa($val['nama']) !!}</option>
    @endforeach
</select>
