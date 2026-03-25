@foreach ($list_setting as $key => $pengaturan)
    @if ($pengaturan->jenis != 'upload' && in_array($pengaturan->kategori, $pengaturan_kategori ?? []))
        @php
            $attrData = json_decode($pengaturan->attribute, true) ?? [];
            $requiredIf = $attrData['required_if'] ?? null;
            $requiredIfAttr = $requiredIf ? "data-required-if='" . json_encode($requiredIf) . "'" : '';
        @endphp
        <div class="form-group" id="form_{{ $pengaturan->key }}" {!! $requiredIfAttr !!}>
            <label class="col-sm-12 col-md-3" for="nama">{{ SebutanDesa($pengaturan->judul) }}</label>
            @if ($pengaturan->jenis == 'multiple-option')
                <div class="col-sm-12 col-md-4">
                    <select class="form-control input-sm select2 required" name="{{ $pengaturan->key }}[]" multiple="multiple">
                        @foreach ($pengaturan->option as $val)
                            <option value="{{ $val }}" {{ in_array($val, json_decode($pengaturan->value) ?? []) ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($pengaturan->jenis == 'multiple-option-array')
                <div class="col-sm-12 col-md-4">
                    <input type="hidden" name="{{ $pengaturan->key }}" value="[]">
                    <select class="form-control input-sm select2" name="{{ $pengaturan->key }}[]" multiple="multiple">
                        @foreach ($pengaturan->option as $key => $val)
                            <option value="{{ $val['id'] }}" {{ in_array($val['id'], json_decode($pengaturan->value) ?? []) ? 'selected' : '' }}>{{ SebutanDesa($val['nama']) }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($pengaturan->jenis == 'datetime')
                <div class="col-sm-12 col-md-4">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm pull-right tgl_1 ', $pengaturan->attribute) : 'class="form-control input-sm pull-right tgl_1"' !!} id="{{ $pengaturan->key }}" name="{{ $pengaturan->key }}" type="text" value="{{ $pengaturan->value }}">
                    </div>
                </div>
            @elseif ($pengaturan->jenis == 'unggah')
                <div class="col-sm-12 col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" id="file_path"
                            name="{{ $pengaturan->key }}">
                        <input type="file" class="hidden" id="file" name="{{ $pengaturan->key }}">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-sm" id="file_browser"><i
                                    class="fa fa-search"></i>&nbsp;</button>
                            <a href="{{ ci_route('kehadiran.latar-kehadiran') }}" class="btn btn-danger btn-sm"
                                title="Lihat Gambar" target="_blank"><i class="fa fa-eye"></i>&nbsp;</a>
                        </span>
                    </div>
                </div>
            @elseif ($pengaturan->jenis == 'referensi')
                <div class="col-sm-12 col-md-4">
                    {{-- prettier-ignore-start --}}
                    <select class="form-control input-sm select2 required" name="{{ $pengaturan->key }}[]" multiple="multiple">
                        @php
                            $modelData   = $pengaturan->option;
                            $whereClause = $attrData['where'] ?? [];
                            $groupConfig = $modelData['group'] ?? null;

                            $query = (new $modelData['model']())
                                ->select([$modelData['value'], $modelData['label']]);

                            foreach ($whereClause as $column => $val) {
                                $query->where($column, $val);
                            }

                            if ($groupConfig) {
                                $query->addSelect($groupConfig['column'])->orderBy($groupConfig['column']);
                            }

                                $referensiData = $query->get()->toArray();
                                $selectedValue = json_decode($pengaturan->value, 1);
                        @endphp
                        <option value="-" @selected(empty($selectedValue))>Tanpa Referensi (kosong)</option>
                        @if ($groupConfig)
                            @foreach ($groupConfig['labels'] as $groupValue => $groupLabel)
                                @php
                                    $groupItems = array_filter($referensiData, fn ($v) => (string) $v[$groupConfig['column']] === (string) $groupValue);
                                @endphp
                                @if (count($groupItems))
                                    <optgroup label="{{ SebutanDesa($groupLabel) }}">
                                        @foreach ($groupItems as $val)
                                            <option value="{{ $val[$modelData['value']] }}" @selected(in_array($val[$modelData['value']], $selectedValue ?? []))>{{ $val[$modelData['label']] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        @else
                            @foreach ($referensiData as $val)
                                <option value="{{ $val[$modelData['value']] }}" @selected(in_array($val[$modelData['value']], $selectedValue ?? []))>{{ $val[$modelData['label']] }}</option>
                            @endforeach
                        @endif
                </select>
                {{-- prettier-ignore-end --}}
                </div>
            @elseif (in_array($pengaturan->jenis, [
                    'input-text',
                    'input-password',
                    'input-number',
                    'input-url',
                    'select-simbol',
                    'select-boolean',
                    'select-array',
                    'select-multiple-array',
                    'textarea',
                ]))
                <div class="col-sm-12 col-md-4">
                    {{-- Rebuild structur setting --}}
                    @php
                        $value = [];
                        $attributes = json_decode($pengaturan->attribute, true);
                        $attributes = is_array($attributes) ? $attributes : [];
                        if (isset($attributes['class'])) {
                            $value['class'] = $attributes['class'];

                            unset($attributes['class']);
                        }

                        // Exclude required_if dari HTML attributes
                        unset($attributes['required_if']);

                        $value['type'] = $pengaturan->jenis;
                        $value['default'] = $pengaturan->value;
                        $value['readonly'] = strpos($pengaturan->attribute, 'readonly') ? 'readonly' : '';
                        $value['disabled'] = strpos($pengaturan->attribute, 'disabled') ? 'disabled' : '';
                        $value['attributes'] = implode(
                            ' ',
                            array_map(
                                function ($key, $val) {
                                    return $key . '="' . $val . '"';
                                },
                                array_keys($attributes),
                                $attributes,
                            ),
                        );
                        $value['key'] = $pengaturan->key;
                        $value['option'] = $pengaturan->option;
                    @endphp
                    @includeIf("admin.pengaturan.components.{$value['type']}", ['value' => $value])
                    {{-- End New --}}
                </div>
            @elseif ($pengaturan->jenis == 'textarea')
                <div class="col-sm-12 col-md-4">
                    <textarea {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm ', $pengaturan->attribute) : 'class="form-control input-sm"' !!} name="{{ $pengaturan->key }}" placeholder="{{ SebutanDesa($pengaturan->keterangan) }}" rows="7">{{ $pengaturan->value }}</textarea>
                </div>
            @elseif($pengaturan->key == 'apbdes_tahun')
                <div class="col-sm-12 col-md-4">
                    <select class="form-control input-sm select2" id="{{ $pengaturan->key }}" name="{{ $pengaturan->key }}">
                        <option value="">Pilih Tahun</option>
                        @foreach ($list_tahun_apbd as $key => $value)
                            <option value="{{ $value->tahun }}" @selected($pengaturan->value == $value->tahun)>{{ $value->tahun }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="col-sm-12 col-md-4">
                    <input {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm ', $pengaturan->attribute) : 'class="form-control input-sm"' !!} id="{{ $pengaturan->key }}" name="{{ $pengaturan->key }}" {{ strpos($pengaturan->attribute, 'type=') ? '' : 'type="text"' }} value="{{ $pengaturan->value }}" />
                </div>
            @endif
            <label class="col-sm-12 col-md-5 pull-left" for="nama">{!! SebutanDesa($pengaturan->keterangan) !!}</label>
        </div>
    @endif
@endforeach

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Generic handler untuk required_if
            $('[data-required-if]').each(function() {
                const $formGroup = $(this);
                const config = $formGroup.data('required-if');

                if (!config || !config.field || config.value === undefined) {
                    return;
                }

                // Coba selector langsung (#key), fallback ke #input_key untuk jenis input-text/number/url
                let $triggerField = $(`#${config.field}`);

                if ($triggerField.length === 0) {
                    $triggerField = $(`#input_${config.field}`);
                }

                if ($triggerField.length === 0) {
                    return;
                }

                const $targetInput = $formGroup.find('input, select, textarea').first();

                function toggleRequired() {
                    const currentValue = $triggerField.val();
                    // Jika form group trigger sedang hidden (karena required_if chain), ikut hidden
                    const triggerGroupHidden = $triggerField.closest('.form-group').is(':hidden');
                    const matched = !triggerGroupHidden && String(config.value) == String(currentValue);

                    if (matched) {
                        $formGroup.show();
                        if (!config.optional) {
                            $targetInput.addClass('required');
                        } else {
                            $targetInput.removeClass('required');
                        }
                    } else {
                        $formGroup.hide();
                        $targetInput.removeClass('required');
                    }

                    // Propagasi ke field yang mungkin bergantung pada field ini (chaining)
                    $targetInput.trigger('change');
                }

                // Initial state
                toggleRequired();

                // Listen for changes
                $triggerField.on('select2:select change', function() {
                    toggleRequired();
                });
            });
        });
    </script>
@endpush
