@if ($suplemen->form_isian)
    @include('admin.layouts.components.datetime_picker')

    @foreach ($formData as $field)
        @php
            $class = $field['atribut'] ? buat_class($field['atribut'], '', $field['required']) : '';
            $widthClass = $field['kolom'] ? 'col-sm-' . $field['kolom'] : 'col-sm-12';
        @endphp

        <div class="form-group">
            @if ($field['tipe'] == 'date')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control input-sm pull-right {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                                    value="{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($field['tipe'] == 'text')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <input type="text" class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                                value="{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}"
                            >
                        </div>
                    </div>
                </div>
            @elseif($field['tipe'] == 'number')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <input type="number" class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                                value="{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}"
                            >
                        </div>
                    </div>
                </div>
            @elseif($field['tipe'] == 'time')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <div class="input-group input-group-sm ">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <input class="form-control input-sm {{ $class }}" type="text" maxlength="50" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                                    value="{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($field['tipe'] == 'textarea')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <textarea class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}" placeholder="{{ $field['deskripsi_kode'] }}">{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}</textarea>
                        </div>
                    </div>
                </div>
            @elseif($field['tipe'] == 'select-manual')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <select class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}">
                                <option value="">-- {{ $field['deskripsi_kode'] }} --</option>
                                @foreach ($field['pilihan_kode'] as $pilih)
                                    <option value="{{ $pilih }}" @selected(old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') == $pilih)>
                                        {{ $pilih }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @elseif($field['tipe'] == 'select-otomatis')
                <label class="col-sm-3 control-label" for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="{{ $widthClass }}">
                            <select class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}">
                                <option value="">-- {{ $field['deskripsi_kode'] }} --</option>
                                @foreach ($field['referensi_kode'] as $pilih)
                                    <option value="{{ $pilih }}" @selected(old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') == $pilih)>
                                        {{ $pilih }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @push('scripts')
            @if ($field['tipe'] == 'date')
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#{{ $field['nama_kode'] }}').datetimepicker({
                            format: 'DD-MM-YYYY',
                            locale: 'id',
                            maxDate: 'now',
                        });
                    });
                </script>
            @endif
            @if ($field['tipe'] == 'time')
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#{{ $field['nama_kode'] }}').datetimepicker({
                            format: 'HH:mm',
                            locale: 'id'
                        });
                    });
                </script>
            @endif
        @endpush
    @endforeach

@endif
