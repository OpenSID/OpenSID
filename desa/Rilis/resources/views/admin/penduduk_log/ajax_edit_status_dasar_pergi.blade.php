@include('admin.layouts.components.form_modal_validasi')
@php
    $sekarang = $log_status_dasar['tgl_peristiwa'] != '' ? $log_status_dasar['tgl_peristiwa'] : date('d-m-Y');
@endphp
<form action="{{ $form_action }}" method="post" id="validasi" class="tgl_lapor_peristiwa">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="tgl_peristiwa">Tanggal Peristiwa</label>
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right required tgl_minimal" id="tgl_1" name="tgl_peristiwa" type="text" data-tgl-lebih-besar="#tgl_lapor" value="{{ $sekarang }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_lapor">Tanggal Lapor</label>
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_indo required" id="tgl_lapor" name="tgl_lapor" type="text" value="{{ $sekarang }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="catatan">Maksud dan Tujuan Kedatangan</label>
                            <textarea id="maksud_tujuan" name="maksud_tujuan" class="form-control input-sm" placeholder="Maksud dan Tujuan Kedatangan" style="height: 50px;">{{ $log_status_dasar['catatan'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
        </div>
    </div>
</form>
<script>
    $('#tgl_1').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });
    $('#tgl_lapor').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });
    $('document').ready(function() {
        setTimeout(function() {
            $("#tgl_lapor").rules('add', {
                tgl_lebih_besar: "input[name='tgl_peristiwa']",
                messages: {
                    tgl_lebih_besar: "Tanggal lapor harus sama atau lebih besar dari tanggal peristiwa."
                }
            })
        }, 500);
    });
</script>
