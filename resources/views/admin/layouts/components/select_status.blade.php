<div class="col-sm-2">
    <select name="status" id="status" class="form-control input-sm select2">
        <option value="">Semua</option>
        @foreach (\App\Enums\AktifEnum::all() as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>
