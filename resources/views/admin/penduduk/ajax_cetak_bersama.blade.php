@include('admin.layouts.components.form_modal_validasi')
<script>
    $(function() {
        $('input[name=judul]').val($('#judul-statistik').text());
        // copy id_rb terpilih ke form ini
        let _clone = $('#tabeldata').find('input[name="id_cb[]"]:checked').clone();
        $('#checkbox_div').append(_clone)
    })

    function cetak() {
        // Retrieve DataTable parameters
        let params = $('#tabeldata').DataTable().ajax.params();

        // Convert params object to query string
        let queryString = $.param(params);

        // Get checkbox value
        const privasi_nik = $('#privasi_nik').is(':checked') ? '1' : '0';

        // Set form action with query parameters
        $("#form-cetak").attr("action", `{{ $action }}/${privasi_nik}?${queryString}`);

        // Hide modal
        $('#modalBox').modal('hide');
    }
</script>
<form target="_blank" action="" method="post" id="form-cetak">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <label for="nama">Centang kotak berikut apabila NIK/No. KK ingin disensor</label>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" name="judul" value="">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="privasi_nik">
                        <label class="form-check-label" for="cetak_privasi_nik">Sensor NIK/No. KK</label>
                    </div>
                </div>
            </div>
            <div class="form-group hide" id="checkbox_div">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" onclick="cetak()" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> {{ ucwords($aksi) }}</button>
    </div>
</form>
