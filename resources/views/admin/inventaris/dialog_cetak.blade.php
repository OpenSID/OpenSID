<form id="validasi" action="{{ $formAction }}" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Tahun</label>
            <select class="form-control input-sm select2" name="tahun">>
                <option value="">Semua</option>
                @for ($i = date('Y'); $i >= date('Y') - 30; $i--)
                    <option value="<?= $i ?>">
                        <?= $i ?>
                    </option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <label for="pamong">Penandatangan</label>
            <select class="form-control input-sm select2 required" name="pamong">
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($pamong_ketahui['pamong_id'] == $data['pamong_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
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

@include('admin.layouts.components.validasi_form')
<script>
    $(document).ready(function() {
        $('.modal:visible').find('form').validate()
    })

    $('#validasi').submit(function() {
        $('#modalBox').modal('hide')
    })
</script>
