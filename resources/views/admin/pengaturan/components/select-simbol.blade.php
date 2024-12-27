{{-- prettier-ignore-start --}}
<select class="form-control input-sm select2-icon-img {!! $value['class'] !!}" name="{{ $value['key'] }}" {{ $value['readonly'] }} {!! $value['attributes'] !!}
    @php
    $referensiData = (new $value['option']['model']())
        ->get()
        ->pluck($value['option']['label'], $value['option']['value'])
        ->toArray();
    @endphp
    @foreach ($referensiData as $index => $val)
        <option value="{{ $index }}" @selected(base_url(LOKASI_SIMBOL_LOKASI . $index) == $value['default'])>{{ $val }}</option>
    @endforeach
</select>
{{-- prettier-ignore-end --}}

@push('scripts')
    <script>
        $(document).ready(function() {
            function format_icon_img(state) {
                if (!state.id) {
                    return state.text;
                }
                let img = BASE_URL + "{{ LOKASI_SIMBOL_LOKASI }}" + state.id.toLowerCase();

                return '<span><img src="' + img + '" width="20px" />&nbsp;' + state.text + '</span>';
            }
            $('.select2-icon-img').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                },
                templateResult: format_icon_img,
                templateSelection: format_icon_img,
                escapeMarkup: function(m) {
                    return m;
                }
            });
        })
    </script>
@endpush
