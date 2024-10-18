<select class="form-control {{ $value['class'] }}" id="input-{{ $value['key'] }}" name="opsi[{{ $value['key'] }}]" {{ $value['readonly'] }} {!! $value['attributes'] !!}>
    @foreach ($value['options'] as $key => $option)
        <option value="{{ $key }}" @selected($key == $value['default'])>{{ $option }}</option>
    @endforeach
</select>
