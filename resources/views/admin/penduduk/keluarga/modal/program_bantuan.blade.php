@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label for="program_bantuan">Program Bantuan</label>
            <select class="form-control input-sm select2" name="program_bantuan">
                <option value="{{ JUMLAH }}">Penduduk Penerima Bantuan</option>
                @foreach ($bantuan as $data)
                    <option value="{{ $data->id }}">{{ $data->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="button" onclick="searchBantuan(this)" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<script>
    function searchBantuan(elm) {
        $('#tabeldata').data('kumpulanKK', [])
        $('#tabeldata').data('bantuan', $('select[name=program_bantuan]').val())
        $('#tabeldata').DataTable().draw()
        $(elm).closest('.modal-dialog').find('.modal-header>button.close').click()
        let labelBantuan = '<b>PENERIMA BANTUAN (KELUARGA) : ' + $('select[name=program_bantuan]').find('option:selected').text() + '</b>'
        if ($('#judul-statistik').length) {
            $('#judul-statistik').html(labelBantuan)
        } else {
            $('<h5 id="judul-statistik" class="box-title text-center">' + labelBantuan + '</h5>').insertBefore($('#tabeldata').closest('.table-responsive'))
        }

    }
</script>
