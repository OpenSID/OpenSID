<form action="{{ $form_action }}" method="post" target="_blank" id="validasi">
    <div class='modal-body'>
        <p>
            Ekspor data dan dokumen informasi publik untuk diimpor ke aplikasi di tingkat supra-desa, seperti PPID kabupaten atau ke aplikasi OpenDK
        </p>
        @if ($log_semua)
            <p>
                Ekspor lengkap terakhir pada {{ tgl_indo_out($log_semua->tgl_ekspor) }} dengan total data {{ $log_semua->total }}.
            </p>
        @endif
        @if ($log_perubahan)
            <p>
                Ekspor perubahan terakhir pada {{ tgl_indo_out($log_perubahan->tgl_ekspor) }} dengan total data {{ $log_perubahan->total }}.
            </p>
        @endif
        <div class="form-group">
            <label class="control-label">Data untuk diekspor</label>
            <select class="form-control input-sm select2 required" name="data_ekspor" id="data_ekspor">>
                <option value="">Pilih data untuk diekspor</option>
                <option value="1">Semua</option>
                @if ($log_semua)
                    <option value="2">Perubahan saja</option>
                @endif
            </select>
        </div>
        <div class="form-group" id="tanggal_dari" style="display: none;">
            <label class="control-label">Perubahan sejak tanggal</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl" name="tgl_dari" type="text" value="">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-ok">
            <i class='fa fa-download'></i> Unduh
        </button>
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
<script type="text/javascript">
    $('document').ready(function() {
        $("#data_ekspor").change(function(e) {
            e.preventDefault();
            var tgl_dari = $("input[name='tgl_dari']");
            if ($(this).val() == '2') {
                $('#tanggal_dari').show();
                tgl_dari.addClass('required');
            } else {
                $('#tanggal_dari').hide();
                tgl_dari.removeClass('required');
            }
        });

        $('#validasi').submit(function(e) {
            if ($('#validasi').valid())
                $('#modalBox').modal('hide');
        });
        $('.tgl').data('DateTimePicker').date('{{ date('d/m/Y H:i:s', strtotime($log_perubahan ? $log_perubahan->tgl_ekspor : $log_semua->tgl_ekspor)) }}');
    });
</script>
