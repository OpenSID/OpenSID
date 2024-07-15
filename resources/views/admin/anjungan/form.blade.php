@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Anjungan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('anjungan') }}">Anjungan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('anjungan') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Anjungan
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">IP Address</label>
                    <div class="col-sm-7">
                        <input
                            id="ip_address"
                            class="form-control input-sm ip_address"
                            type="text"
                            placeholder="IP address statis untuk anjungan"
                            onkeyup="wajib()"
                            name="ip_address"
                            value="{{ $anjungan->ip_address ?? null }}"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">Mac Address</label>
                    <div class="col-sm-7">
                        <input
                            id="mac_address"
                            class="form-control input-sm mac_address"
                            type="text"
                            placeholder="00:1B:44:11:3A:B7"
                            onkeyup="wajib()"
                            name="mac_address"
                            value="{{ $anjungan->mac_address ?? null }}"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="id_pengunjung">ID Pengunjung</label>
                    <div class="col-sm-7">
                        <input
                            id="id_pengunjung"
                            class="form-control input-sm alfanumerik"
                            type="text"
                            onkeyup="wajib()"
                            placeholder="ad02c373c2a8745d108aff863712fe92"
                            name="id_pengunjung"
                            value="{{ $anjungan->id_pengunjung ?? null }}"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">IP Address Printer</label>
                    <div class="col-sm-7">
                        <input class="form-control input-sm ip_address" type="text" placeholder="IP address statis untuk printer anjungan" name="printer_ip" value="{{ $anjungan->printer_ip }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">Port Address Printer</label>
                    <div class="col-sm-7">
                        <input class="form-control input-sm" type="text" placeholder="Port address statis untuk printer anjungan" name="printer_port" value="{{ $anjungan->printer_port }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                    <div class="col-sm-7">
                        <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;">{{ $anjungan->keterangan }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="keyboard">Keyboard Virtual</label>
                    <div class="btn-group col-sm-7" data-toggle="buttons">
                        <label id="sx1" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($anjungan->keyboard, '1', 'active') }}">
                            <input type="radio" name="keyboard" class="form-check-input" type="radio" value="1" {{ jecho($anjungan->keyboard, '1', 'checked') }}> Aktif
                        </label>
                        <label id="sx2" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($anjungan->keyboard != '1', true, 'active') }}">
                            <input type="radio" name="keyboard" class="form-check-input" type="radio" value="0" {{ jecho($anjungan->keyboard != '1', true, 'checked') }}> Tidak Aktif
                        </label>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            wajib();
        });

        function reset_form() {
            var keyboard = "{{ $anjungan->keyboard }}";
            var status = "{{ $anjungan->status }}";

            if (keyboard == 1) {
                $("#sx1").addClass('active');
                $("#sx2").removeClass('active');
            } else {
                $("#sx1").removeClass('active');
                $("#sx2").addClass('active');
            }

            if (status == 1) {
                $("#sx3").addClass('active');
                $("#sx4").removeClass('active');
            } else {
                $("#sx3").removeClass('active');
                $("#sx4").addClass('active');
            }
        };

        function wajib() {
            if ($("#ip_address").val().length > 0) {
                // $("#ip_address").addClass('required');
                $("#mac_address").removeClass('required');
                $("#id_pengunjung").removeClass('required');
            } else if ($("#mac_address").val().length > 0) {
                // $("#mac_address").addClass('required');
                $("#ip_address").removeClass('required');
                $("#id_pengunjung").removeClass('required');
            } else if ($("#id_pengunjung").val().length > 0) {
                // $("#id_pengunjung").addClass('required');
                $("#ip_address").removeClass('required');
                $("#mac_address").removeClass('required');
            } else {
                $("#ip_address").addClass('required');
            }
        }
    </script>
@endpush
