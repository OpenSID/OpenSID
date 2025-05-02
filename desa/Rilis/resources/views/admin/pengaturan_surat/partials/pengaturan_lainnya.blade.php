<div class="tab-pane" id="lainnya">
    <div class="box-body">
        <div class="form-group">
            <label>Jenis Font Bawaan </label>
            <div class="row">
                <div class="col-lg-4 col-md-7 col-sm-12">
                    <select class="select2 form-control" name="font_surat">
                        @foreach ($font_option as $font)
                            <option value="{{ $font }}" @selected($font == setting('font_surat'))>
                                {{ $font }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Upload Font Custom</label>
            <input type="file" name="font_custom" class="form-control input-sm" accept=".ttf">
        </div>
        <div class="form-group">
            <label>Format Penomoran Surat </label>
            <input type="text" name="format_nomor_surat" class="form-control input-sm" value="{{ setting('format_nomor_surat') }}">
        </div>
        <div class="form-group">
            <label>{{ $penomoran_surat->judul }} </label>
            <select {!! $penomoran_surat->attribute ? str_replace('class="', 'class="form-control input-sm select2 required ', $penomoran_surat->attribute) : 'class="form-control input-sm select2 required"' !!} id="{{ $penomoran_surat->key }}" name="{{ $penomoran_surat->key }}">
                @foreach ($penomoran_surat->option as $key => $value)
                    <option value="{{ $key }}" @selected($penomoran_surat->value == $key)>{{ $value }}</option>
                @endforeach
            </select>
            <span class="help-block small text-red">{!! $penomoran_surat->keterangan !!}</span>
        </div>
        @php
            $panjang_no_surat = $list_setting->where('key', 'panjang_nomor_surat')->first();
        @endphp
        <div class="form-group">
            <label>{{ $panjang_no_surat->judul }} </label>
            <input type="text" name="{{ $panjang_no_surat->key }}" class="form-control input-sm" value="{{ $panjang_no_surat->value }}">
            <span class="help-block small text-red">{!! $panjang_no_surat->keterangan !!}</span>
        </div>
        <div class="form-group">
            <label>Kode Isian Data Kosong </label>
            <input type="text" name="ganti_data_kosong" class="form-control input-sm" value="{{ setting('ganti_data_kosong') }}">
        </div>
        <div class="form-group">
            <label>Format Tanggal Surat </label>
            <a href="{{ ci_route('surat_master.form') }}" title="Lihat Informasi Format Tanggal" class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#format-tanggal">
                <i class="fa fa-book"></i> Informasi</a>
            <input type="text" name="format_tanggal_surat" class="form-control input-sm" value="{{ setting('format_tanggal_surat') }}">
            <span class="help-block small text-red">{!! $list_setting->where('key', 'format_tanggal_surat')->first()->keterangan !!}</span>
        </div>
        <div class="form-group">
            <label>Margin</label>
            <div class="row">
                @foreach ($margins as $key => $value)
                    <div class="col-sm-6">
                        <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                            <span class="input-group-addon input-sm">{{ ucwords($key) }}</span>
                            <input
                                type="number"
                                class="form-control input-sm required"
                                min="0"
                                name="surat_margin[{{ $key }}]"
                                min="0"
                                max="10"
                                step="0.01"
                                style="text-align:right;"
                                value="{{ $value }}"
                            >
                            <span class="input-group-addon input-sm">cm</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label>Data Pelaku Digunakan Berulang</label>
            <div class="row">
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="margin: 0 0 5px 0">
                    <label class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(setting('sumber_penduduk_berulang_surat') ?? 0)">
                        <input type="radio" name="sumber_penduduk_berulang_surat" class="form-check-input" value="1" @checked(setting('sumber_penduduk_berulang_surat') ?? 0) autocomplete="off">Ya
                    </label>
                    <label class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!(setting('sumber_penduduk_berulang_surat') ?? 0))">
                        <input type="radio" name="sumber_penduduk_berulang_surat" class="form-check-input" value="0" @checked(!(setting('sumber_penduduk_berulang_surat') ?? 0)) autocomplete="off">Tidak
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.pengaturan_surat.partials.format_tanggal')
