<div class="tab-pane" id="lainnya">
    <div class="box-body">
        <div class="form-group">
            <label>Jenis Font Bawaan </label>
            <div class="row">
                <div class="col-lg-4 col-md-7 col-sm-12">
                    <select class="select2 form-control" name="font_surat">
                        @foreach ($font_option as $font)
                        <option value="{{ $font }}" @selected($font==setting('font_surat'))>
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
            <label>Upload Font Custom</label>
            <input type="file" name="font_custom" class="form-control input-sm">
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Fomat penomoran surat </label>
            <input type="text" name="format_nomor_surat" class="form-control input-sm"
                value="{{ setting('format_nomor_surat') }}">
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Kode Isian data kosong </label>
            <input type="text" name="ganti_data_kosong" class="form-control input-sm"
                value="{{ setting('ganti_data_kosong') }}">
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
                        <input type="number" class="form-control input-sm required" min="0"
                            name="surat_margin[{{ $key }}]" min="0" max="10" step="0.01"
                            style="text-align:right;" value="{{ $value }}">
                        <span class="input-group-addon input-sm">cm</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>