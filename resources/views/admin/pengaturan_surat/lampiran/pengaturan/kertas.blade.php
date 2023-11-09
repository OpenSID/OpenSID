<div class="tab-pane active" id="kertas">
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
                                name="lampiran_margin[{{ $key }}]"
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
