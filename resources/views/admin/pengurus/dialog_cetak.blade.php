<script>
    $(function() {
        let _objParams = $('#tabeldata').DataTable().ajax.params()
        delete(_objParams.draw)
        delete(_objParams.search)
        $('input[name=params]').val(JSON.stringify(_objParams))
        $('input[name=status]').val($('#status').val())
        $('input[name=kehadiran]').val($('#kehadiran').val())
        // copy id_rb terpilih ke form ini
        let _clone = $('#tabeldata').find('input[name="id_cb[]"]:checked').clone()
        $('#checkbox_div').append(_clone)
    })
</script>

<form id="validasi" action="{{ $formAction }}" method="post" target="_blank">
    <div class="modal-body">
        <input type="hidden" name="params" value="">
        <input type="hidden" name="status" value="">
        <input type="hidden" name="kehadiran" value="">
        <div class="form-group">
            <label for="pamong">Penandatangan</label>
            <select class="form-control input-sm select2 required" name="pamong">
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($pamong_ketahui['pamong_id'] == $data['pamong_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group hide" id="checkbox_div">
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
    $('#validasi').on('submit', function() {
        $('#modalBox').modal('hide')
    })
</script>
