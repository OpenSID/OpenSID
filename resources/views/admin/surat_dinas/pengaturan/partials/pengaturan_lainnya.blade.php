<div class="tab-pane" id="lainnya">
    <div class="box-body">
        <div class="form-group">
            <label>Jenis Font Bawaan </label>
            <div class="row">
                <div class="col-lg-4 col-md-7 col-sm-12">
                    <select class="select2 form-control" name="font_surat_dinas">
                        @foreach ($font_option as $font)
                            <option value="{{ $font }}" @selected($font == setting('font_surat_dinas'))>
                                {{ $font }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Fomat penomoran surat </label>
            <input type="text" name="format_nomor_surat_dinas" class="form-control input-sm" value="{{ setting('format_nomor_surat_dinas') }}">
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Format tanggal surat </label>
            <a href="{{ ci_route('surat_dinas.form') }}" title="Lihat Informasi Format Tanggal" class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#format-tanggal">
                <i class="fa fa-book"></i> Informasi</a>
            <input type="text" name="format_tanggal_surat_dinas" class="form-control input-sm" value="{{ setting('format_tanggal_surat_dinas') }}">
            <span class="help-block small text-red">{!! $list_setting->where('key', 'format_tanggal_surat_dinas')->first()->keterangan !!}</span>
        </div>
    </div>
    <div class="box-body">
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
                                name="surat_dinas_margin[{{ $key }}]"
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
    </div>
</div>

@include('admin.surat_dinas.pengaturan.partials.format_tanggal')
