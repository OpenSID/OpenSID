{{-- prettier-ignore-start --}}
<select class="form-control input-sm select2-icon-img required" name="{{ $key }}" {!! $attribute
    ? str_replace('class="', 'class="form-control input-sm ', $attribute)
    : 'class="form-control input-sm"' !!}>
    @php
    $referensiData = (new $option['model']())
        ->get()
        ->pluck($option['label'], $option['value'])
        ->toArray();
    @endphp
    @foreach ($referensiData as $index => $val)
        <option value="{{ $index }}" @selected(base_url(LOKASI_SIMBOL_LOKASI . $index) == $value)>{{ $val }}</option>
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
