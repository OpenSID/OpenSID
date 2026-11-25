@extends('admin.layouts.components.ajax-cetak-bersama')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('fields')
    <div class="col-sm-12">
        @if (isset($rekap) && $rekap == true)
            <div class="form-group">
                <label for="tgl_cetak">Tampilkan Jumlah</label>
                <select class="form-control input-sm select2 required" id="tampil_jumlah" name="tampil_jumlah">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        @endif
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
@endsection

@section('script')
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
@endsection
