<div class="form-group subtitle_head">
    <label class="col-sm-12 control-label" for="lampiran">LAMPIRAN</label>
</div>

<div class="form-group">
    @foreach ($lampiran as $item)
        <div class="col-sm-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" checked="checked" name="lampiran[]" value="{{ $item }}">
                <label class="form-check-label">
                    {{ strtoupper($item) }}
                </label>
            </div>
        </div>
    @endforeach
</div>
