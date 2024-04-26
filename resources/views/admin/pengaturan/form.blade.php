@foreach ($list_setting as $key => $pengaturan)
    @if ($pengaturan->jenis != 'upload' && in_array($pengaturan->kategori, $pengaturan_kategori ?? []))
        <div class="form-group" id="form_{{ $pengaturan->key }}">
            <label class="col-sm-12 col-md-3" for="nama">{{ SebutanDesa($pengaturan->judul) }}</label>
            @if ($pengaturan->jenis == 'option' || $pengaturan->jenis == 'boolean')
                <div class="col-sm-12 col-md-4">
                    <select {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm select2 required ', $pengaturan->attribute) : 'class="form-control input-sm select2 required"' !!} id="{{ $pengaturan->key }}" name="{{ $pengaturan->key }}">
                        @foreach ($pengaturan->option as $key => $value)
                            <option value="{{ $key }}" @selected($pengaturan->value == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($pengaturan->jenis == 'multiple-option')
                <div class="col-sm-12 col-md-4">
                    <select class="form-control input-sm select2 required" name="{{ $pengaturan->key }}[]" multiple="multiple">
                        @foreach ($pengaturan->option as $val)
                            <option value="{{ $val }}" {{ in_array($val, json_decode($pengaturan->value) ?? []) ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($pengaturan->jenis == 'multiple-option-array')
                <div class="col-sm-12 col-md-4">
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
            @elseif ($pengaturan->jenis == 'textarea')
                <div class="col-sm-12 col-md-4">
                    <textarea {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm ', $pengaturan->attribute) : 'class="form-control input-sm"' !!} name="{{ $pengaturan->key }}" placeholder="{{ SebutanDesa($pengaturan->keterangan) }}" rows="7">{{ $pengaturan->value }} </textarea>
                </div>
            @elseif ($pengaturan->jenis == 'password')
                <div class="col-sm-12 col-md-4">
                    <div class="input-group">
                        <input {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm ', $pengaturan->attribute) : 'class="form-control input-sm"' !!} id="{{ $pengaturan->key }}" name="{{ $pengaturan->key }}" type="password" data-password="{{ $pengaturan->value ? 1 : 0 }}" value="" />
                        <span class="input-group-addon input-sm show-hide-password"><i class="fa fa-eye-slash"></i></span>
                    </div>
                    @if ($pengaturan->value)
                        <p class="help-block small text-red">Kosongkan jika tidak ingin mengubah Password.</p>
                    @endif
                </div>
            @else
                <div class="col-sm-12 col-md-4">
                    <input {!! $pengaturan->attribute ? str_replace('class="', 'class="form-control input-sm ', $pengaturan->attribute) : 'class="form-control input-sm"' !!} id="{{ $pengaturan->key }}" name="{{ $pengaturan->key }}" type="text" value="{{ $pengaturan->value }}" />
                </div>
            @endif
            <label class="col-sm-12 col-md-5 pull-left" for="nama">{!! SebutanDesa($pengaturan->keterangan) !!}</label>
        </div>
    @endif
@endforeach
