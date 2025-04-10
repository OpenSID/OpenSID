<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">

<form id="form-cetak" action="" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Tanggal Cetak</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right required" id="tgl_1" name="tgl_cetak" type="text" value="{{ date('d-m-Y') }}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" onclick="cetak()" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> {{ ucwords($aksi) }}</button>
    </div>
</form>
@include('admin.layouts.components.form_modal_validasi')
<!-- moment js -->
<script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
<!-- bootstrap Date time picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/id.js') }}"></script>
<!-- bootstrap Date picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.id.min.js') }}"></script>
<!-- Script-->
<script src="{{ asset('js/custom-datetimepicker.js') }}"></script>
<script>
    $(function() {
        let clone = $('#tabeldata').find('input[name="id_cb[]"]:checked').clone();

        $('#checkbox_div').append(clone)
    })

    function cetak() {
        // Retrieve DataTable parameters
        let params = $('#tabeldata').DataTable().ajax.params();

        // Convert params object to query string
        let queryString = $.param(params);

        // Set form action with query parameters
        $("#form-cetak").attr("action", `{{ $formAction }}?${queryString}`);

        // Hide modal
        $('#modalBox').modal('hide');
    }
</script>
