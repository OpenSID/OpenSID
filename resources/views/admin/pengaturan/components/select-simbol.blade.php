{{-- prettier-ignore-start --}}
<select class="form-control input-sm select2-icon-img {!! $value['class'] ?? '' !!}" id="{{ $value['key'] }}" name="{{ $value['key'] }}" {{ $value['readonly'] ?? '' }} {{ $value['disabled'] ?? '' }} {!! $value['attributes'] ?? '' !!}>
    @php
    $referensiData = [];
    if (!empty($value['option']['model']) && class_exists($value['option']['model'])) {
        $referensiData = (new $value['option']['model']())
            ->get()
            ->pluck($value['option']['label'], $value['option']['value'])
            ->toArray();
    }
    @endphp
    @foreach ($referensiData as $index => $val)
        <option value="{{ $index }}" @selected(base_url(LOKASI_SIMBOL_LOKASI . $index) == ($value['default'] ?? '') || (string) $index === (string) ($value['default'] ?? ''))>{{ $val }}</option>
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
                var baseUrl = "{{ rtrim(str_replace('/index.php', '', url('/')), '/') . '/' . LOKASI_SIMBOL_LOKASI }}";
                var img = baseUrl + state.id;

                return $('<span><img src="' + img + '" width="20px" style="margin-right: 5px; vertical-align: middle;" onerror="this.style.display=\'none\';" />' + state.text + '</span>');
            }

            $('.select2-icon-img').each(function() {
                var $select = $(this);
                var $modal = $select.closest('.modal');

                $select.select2({
                    dropdownParent: $modal.length ? $modal : $(document.body),
                    placeholder: function() {
                        return $(this).data('placeholder') || '-- Pilih Simbol --';
                    },
                    templateResult: format_icon_img,
                    templateSelection: format_icon_img,
                    escapeMarkup: function(m) {
                        return m;
                    }
                });
            });
        });
    </script>
@endpush
