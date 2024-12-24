<form id="validasi" action="{{ $formAction }}" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Tahun</label>
            <select class="form-control input-sm select2 " name="tahun">
                <option value="">Pilih Tahun</option>
                @foreach ($list_tahun as $thn)
                    <option value="{{ $thn['tahun'] }}" @selected($tahun == $thn['tahun'])>
                        {{ $thn['tahun'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-ok">
            @if ($aksi == 'cetak')
                <i class='fa fa-print'></i> Cetak
            @else
                <i class='fa fa-download'></i> Unduh
            @endif
        </button>
    </div>
</form>
