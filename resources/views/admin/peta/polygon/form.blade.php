@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Tipe Area
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('polygon.index') }}"> Tipe Area</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ site_url('polygon') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Tipe Area
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama</label>
                        <div class="col-sm-9">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="{{ $polygon['nama'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Warna</label>
                        <div class="col-sm-5">
                            <div class="input-group my-colorpicker2">
                                <input type="text" id="color" name="color" class="form-control input-sm color required" placeholder="#FFFFFF" value="{{ $polygon['color'] }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <div>
                        <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                            Batal</button>
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.my-colorpicker2').colorpicker();
        })
    </script>
@endpush
